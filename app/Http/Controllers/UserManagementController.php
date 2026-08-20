<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();
        abort_unless($admin && $admin->isTrainingCentre(), 403);

        $query = User::query();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")
                ->orWhere('sid', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%"));
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $counts = [
            'trainee' => User::where('role', 'trainee')->count(),
            'trainer' => User::where('role', 'trainer')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'total' => User::count(),
        ];

        return view('training-centre.users.index', compact('admin', 'users', 'counts'));
    }

    public function create()
    {
        $admin = Auth::user();
        abort_unless($admin && $admin->isTrainingCentre(), 403);

        $trainers = User::where('role', 'trainer')->get()->groupBy('trainer_type');
        $categories = \App\Models\EquipmentCategory::all();

        return view('training-centre.users.create', compact('admin', 'trainers', 'categories'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        abort_unless($admin && $admin->isTrainingCentre(), 403);

        $data = $request->validate([
            'sid' => ['required', 'string', 'max:50', 'unique:users,sid'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['trainee', 'trainer', 'admin', 'pjo', 'hse_ct'])],
            'trainer_type' => ['nullable', Rule::in(['instruktur', 'pengawas', 'operator_pendamping'])],
            'assigned_trainers' => ['nullable', 'array'],
            'assigned_trainers.instruktur' => ['nullable', 'array'],
            'assigned_trainers.pengawas' => ['nullable', 'array'],
            'assigned_trainers.operator_pendamping' => ['nullable', 'array'],
            'assigned_trainers.instruktur.*' => ['exists:users,id'],
            'assigned_trainers.pengawas.*' => ['exists:users,id'],
            'assigned_trainers.operator_pendamping.*' => ['exists:users,id'],
            'certification' => ['nullable', Rule::in(['Green', 'Skill-up', 'Experience_internal', 'Experience_external'])],
            'company' => ['nullable', 'string', 'max:255'],
            'equipment_category_id' => ['nullable', 'exists:equipment_categories,id'],
            'sticker_expired_at' => ['nullable', 'date'],
        ]);

        if ($data['role'] !== 'trainer') {
            $data['trainer_type'] = null;
        }

        $data['password'] = bcrypt('password');
        $data['must_change_password'] = true;

        $user = User::create($data);

        if ($data['role'] === 'trainee' && !empty($data['assigned_trainers'])) {
            $this->saveTrainerAssignments($user, $data['assigned_trainers']);
        }

        return redirect()->route('training-centre.users.index')->with('success', 'Pengguna berhasil didaftarkan. Password default: password');
    }

    public function edit($id)
    {
        $admin = Auth::user();
        abort_unless($admin && $admin->isTrainingCentre(), 403);

        $user = User::findOrFail($id);

        if (!$admin->isSuperAdmin() && $user->isSuperAdmin()) {
            abort(403, 'Tidak memiliki akses untuk mengedit Super Admin.');
        }

        $trainers = User::where('role', 'trainer')->get()->groupBy('trainer_type');
        $assignedTrainers = [
            'instruktur' => $user->assignedInstruktur->pluck('id')->toArray(),
            'pengawas' => $user->assignedPengawas->pluck('id')->toArray(),
            'operator_pendamping' => $user->assignedOperatorPendamping->pluck('id')->toArray(),
        ];
        $categories = \App\Models\EquipmentCategory::all();

        return view('training-centre.users.edit', compact('admin', 'user', 'trainers', 'assignedTrainers', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $admin = Auth::user();
        abort_unless($admin && $admin->isTrainingCentre(), 403);

        $user = User::findOrFail($id);

        if (!$admin->isSuperAdmin() && $user->isSuperAdmin()) {
            abort(403, 'Tidak memiliki akses untuk mengedit Super Admin.');
        }

        $data = $request->validate([
            'sid' => ['required', 'string', 'max:50', Rule::unique('users', 'sid')->ignore($user->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['trainee', 'trainer', 'admin', 'pjo', 'hse_ct'])],
            'trainer_type' => ['nullable', Rule::in(['instruktur', 'pengawas', 'operator_pendamping'])],
            'assigned_trainers' => ['nullable', 'array'],
            'assigned_trainers.instruktur' => ['nullable', 'array'],
            'assigned_trainers.pengawas' => ['nullable', 'array'],
            'assigned_trainers.operator_pendamping' => ['nullable', 'array'],
            'assigned_trainers.instruktur.*' => ['exists:users,id'],
            'assigned_trainers.pengawas.*' => ['exists:users,id'],
            'assigned_trainers.operator_pendamping.*' => ['exists:users,id'],
            'certification' => ['nullable', Rule::in(['Green', 'Skill-up', 'Experience_internal', 'Experience_external'])],
            'company' => ['nullable', 'string', 'max:255'],
            'equipment_category_id' => ['nullable', 'exists:equipment_categories,id'],
            'sticker_expired_at' => ['nullable', 'date'],
        ]);

        if ($data['role'] !== 'trainer') {
            $data['trainer_type'] = null;
        }

        $user->update($data);

        if ($data['role'] === 'trainee') {
            $this->saveTrainerAssignments($user, $data['assigned_trainers'] ?? []);
        } else {
            $user->assignedTrainers()->detach();
        }

        return redirect()->route('training-centre.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    private function saveTrainerAssignments(User $trainee, array $assignments): void
    {
        $trainee->assignedTrainers()->detach();

        foreach ($assignments as $type => $trainerIds) {
            if (!is_array($trainerIds)) {
                continue;
            }

            foreach ($trainerIds as $trainerId) {
                $trainee->assignedTrainers()->attach($trainerId, [
                    'trainer_type' => $type,
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $admin = Auth::user();
        abort_unless($admin && $admin->isTrainingCentre(), 403);

        $user = User::findOrFail($id);

        if ($user->id === $admin->id) {
            return redirect()->route('training-centre.users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if (!$admin->isSuperAdmin() && $user->isSuperAdmin()) {
            abort(403, 'Tidak memiliki akses untuk menghapus Super Admin.');
        }

        $user->delete();

        return redirect()->route('training-centre.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function resetPassword($id)
    {
        $admin = Auth::user();
        abort_unless($admin && $admin->isTrainingCentre(), 403);

        $user = User::findOrFail($id);

        if (!$admin->isSuperAdmin() && $user->isSuperAdmin()) {
            abort(403, 'Tidak memiliki akses untuk mereset password Super Admin.');
        }

        $user->update([
            'password' => bcrypt('password'),
            'must_change_password' => true,
        ]);

        return redirect()->route('training-centre.users.index')->with('success', "Password {$user->name} ({$user->sid}) telah direset ke 'password'.");
    }
}
