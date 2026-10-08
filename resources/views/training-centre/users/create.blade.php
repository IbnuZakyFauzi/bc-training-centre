<x-app-layout>
    <x-slot name="title">Tambah Pengguna</x-slot>

    <div class="mb-6">
        <div class="flex items-center space-x-2 text-xs font-semibold text-[#2563eb] mb-1">
            <a href="{{ route('training-centre.users.index') }}" class="hover:underline">Manajemen Pengguna</a>
            <span>/</span>
            <span class="text-slate-500">Tambah Pengguna</span>
        </div>
        <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Tambah Pengguna Baru</h1>
            <p class="text-xs text-slate-500 mt-1">Daftarkan trainee, trainer, atau admin baru ke sistem.</p>
    </div>

    <form method="POST" action="{{ route('training-centre.users.store') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        @csrf
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">SID <span class="text-rose-500">*</span></label>
                    <input type="text" name="sid" value="{{ old('sid') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition" placeholder="Contoh: XXXXX">
                    @error('sid')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition" placeholder="Contoh: Ahmad Rian">
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email (opsional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition" placeholder="contoh@beraucoal.co.id">
                    @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Role <span class="text-rose-500">*</span></label>
                    <select name="role" required id="role-select" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                        <option value="">Pilih Role</option>
                        <option value="trainee" {{ old('role') === 'trainee' ? 'selected' : '' }}>Trainee</option>
                        <option value="trainer" {{ old('role') === 'trainer' ? 'selected' : '' }}>Trainer</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin Training Centre</option>
                <option value="pjo" {{ old('role') === 'pjo' ? 'selected' : '' }}>PJO (Penanggung Jawab Operasional)</option>
                <option value="hse_ct" {{ old('role') === 'hse_ct' ? 'selected' : '' }}>HSE CT (HSE Training Section)</option>
                    </select>
                    @error('role')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div id="trainer-type-field" class="hidden">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe Trainer <span class="text-rose-500">*</span></label>
                    <select name="trainer_type" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                        <option value="">Pilih Tipe Trainer</option>
                        <option value="instruktur" {{ old('trainer_type') === 'instruktur' ? 'selected' : '' }}>Trainer - Instruktur</option>
                        <option value="pengawas" {{ old('trainer_type') === 'pengawas' ? 'selected' : '' }}>Trainer - Pengawas</option>
                        <option value="operator_pendamping" {{ old('trainer_type') === 'operator_pendamping' ? 'selected' : '' }}>Trainer - Operator Pendamping</option>
                    </select>
                    @error('trainer_type')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div id="trainer-assignments" class="hidden md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Assign Trainer untuk Trainee</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach(['instruktur' => 'Instruktur', 'pengawas' => 'Pengawas', 'operator_pendamping' => 'Operator Pendamping'] as $type => $label)
                            <div class="border border-slate-200 rounded-lg flex flex-col h-full">
                                <div class="px-3 py-2 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wide">{{ $label }}</span>
                                    <span class="text-[10px] text-slate-400 trainer-count" data-target="{{ $type }}-options">0 terpilih</span>
                                </div>
                                <div class="p-2 flex-1 flex flex-col">
                                    <input type="text" placeholder="Cari {{ strtolower($label) }}..." class="trainer-search mb-2 w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs focus:ring-2 focus:ring-brand-500 focus:border-transparent" data-target="{{ $type }}-options">
                                    <div class="flex-1 overflow-y-auto space-y-0.5" id="{{ $type }}-options">
                                        @foreach($trainers->get($type, []) as $tr)
                                            <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-slate-50 cursor-pointer transition">
                                                <input type="checkbox" name="assigned_trainers[{{ $type }}][]" value="{{ $tr->id }}" class="h-3.5 w-3.5 rounded border-slate-300 text-[#1e3a8a] focus:ring-brand-500">
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs text-slate-700 truncate">{{ $tr->name }}</p>
                                                    <p class="text-[10px] text-slate-400">{{ $tr->sid }}</p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div id="trainee-identity-fields" class="hidden md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Identitas Trainee (Opsional)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">Sertifikasi</label>
                            <select name="certification" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                                <option value="">Pilih Sertifikasi</option>
                <option value="Green" {{ old('certification') === 'Green' ? 'selected' : '' }}>Green</option>
                <option value="Skill-up" {{ old('certification') === 'Skill-up' ? 'selected' : '' }}>Skill-up</option>
                <option value="Experience_internal" {{ old('certification') === 'Experience_internal' ? 'selected' : '' }}>Experience Internal</option>
                <option value="Experience_external" {{ old('certification') === 'Experience_external' ? 'selected' : '' }}>Experience External</option>
                            </select>
                            @error('certification')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">Perusahaan</label>
                            <input type="text" name="company" value="{{ old('company') }}" placeholder="Contoh: PT Mutiara Tanjung Lestari" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                            @error('company')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">Departemen</label>
                            <select name="department" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                                <option value="">Pilih Departemen</option>
                                <option value="CHCPP" {{ old('department') === 'CHCPP' ? 'selected' : '' }}>CHCPP</option>
                                <option value="RIM" {{ old('department') === 'RIM' ? 'selected' : '' }}>RIM</option>
                                <option value="HRGS" {{ old('department') === 'HRGS' ? 'selected' : '' }}>HRGS</option>
                            </select>
                            @error('department')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">Tipe Alat</label>
                            <select name="equipment_category_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                                <option value="">Pilih tipe alat</option>
                                @php
                                    $groupedOptions = [
                                        ['code' => 'EXC', 'label' => 'Excavator (EX)'],
                                        ['code' => 'DZ', 'label' => 'Bulldozer (DZ) / Motor Grader (GR)'],
                                        ['code' => 'HDT', 'label' => 'Heavy Dump Truck (HDT) / Light Dump Truck (LDT)'],
                                        ['code' => 'SDT', 'label' => 'Semi Dump Trailler (SDT) / Articulated Dump Truck (ADT)'],
                                        ['code' => 'WL', 'label' => 'Wheel Loader (WL)'],
                                    ];
                                @endphp
                                @foreach($groupedOptions as $group)
                                    @php $cat = $categories->firstWhere('code', $group['code']) @endphp
                                    @if($cat)
                                        <option value="{{ $cat->id }}" {{ old('equipment_category_id') == $cat->id ? 'selected' : '' }}>{{ $group['label'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('equipment_category_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">HM Siang Awal</label>
                            <input type="number" name="initial_hm_day" value="{{ old('initial_hm_day', 0) }}" step="0.1" min="0" placeholder="Contoh: 16.0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                            <p class="text-[10px] text-slate-400 mt-1">Jam kerja shift siang sebelum didaftarkan (default: 0)</p>
                            @error('initial_hm_day')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">HM Malam Awal</label>
                            <input type="number" name="initial_hm_night" value="{{ old('initial_hm_night', 0) }}" step="0.1" min="0" placeholder="Contoh: 8.0" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                            <p class="text-[10px] text-slate-400 mt-1">Jam kerja shift malam sebelum didaftarkan (default: 0)</p>
                            @error('initial_hm_night')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4">
                <p class="text-xs text-amber-900 font-bold">Password default akan di-set menjadi <span class="font-black">password</span></p>
                <p class="text-[11px] text-amber-700 mt-1">Pengguna wajib mengganti password sendiri di menu My Profile setelah login pertama kali.</p>
            </div>
        </div>
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3">
            <a href="{{ route('training-centre.users.index') }}" class="px-4 sm:px-5 py-2.5 sm:py-3 bg-white hover:bg-slate-100 text-slate-600 rounded-xl text-xs font-bold transition border border-slate-200 text-center min-h-[44px] inline-flex items-center justify-center">Batal</a>
            <button type="submit" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-[#2563eb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow-md transition min-h-[44px]">Simpan Pengguna</button>
        </div>
    </form>

    <script>
        const roleSelect = document.getElementById('role-select');
        const trainerTypeField = document.getElementById('trainer-type-field');
        const trainerAssignments = document.getElementById('trainer-assignments');
        const traineeIdentityFields = document.getElementById('trainee-identity-fields');

        function toggleFields() {
            if (roleSelect.value === 'trainer') {
                trainerTypeField.classList.remove('hidden');
                trainerAssignments.classList.add('hidden');
                traineeIdentityFields.classList.add('hidden');
            } else if (roleSelect.value === 'trainee') {
                trainerTypeField.classList.add('hidden');
                trainerAssignments.classList.remove('hidden');
                traineeIdentityFields.classList.remove('hidden');
            } else {
                trainerTypeField.classList.add('hidden');
                trainerAssignments.classList.add('hidden');
                traineeIdentityFields.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields();

        function updateTrainerCount(targetId) {
            const container = document.getElementById(targetId);
            const checkboxes = container.querySelectorAll('input[type="checkbox"]');
            const checked = container.querySelectorAll('input[type="checkbox"]:checked');
            const countLabel = document.querySelector(`.trainer-count[data-target="${targetId}"]`);
            if (countLabel) {
                countLabel.textContent = checked.length + ' terpilih';
            }
        }

        document.querySelectorAll('.trainer-search').forEach(function(input) {
            input.addEventListener('input', function() {
                const targetId = this.dataset.target;
                const container = document.getElementById(targetId);
                const filter = this.value.toLowerCase();
                const items = container.querySelectorAll('label');
                items.forEach(function(item) {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });

        document.querySelectorAll('#trainer-assignments input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const container = this.closest('[id$="-options"]') || this.closest('.space-y-1');
                if (container) {
                    updateTrainerCount(container.id);
                }
            });
        });

        document.querySelectorAll('[id$="-options"]').forEach(function(container) {
            updateTrainerCount(container.id);
        });
    </script>
</x-app-layout>


