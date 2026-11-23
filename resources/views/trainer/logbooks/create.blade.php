<x-app-layout>
    <x-slot name="title">Input Logbook OJT Trainee</x-slot>

    @php
        $isTrainerCreating = true;
        $formAction = route('trainer.logbooks.store');
        $user = $trainer;
        $trainee = $trainees->firstWhere('id', $selectedTraineeId) ?? $trainees->first() ?? new \App\Models\User();
    @endphp

    @include('ojt.logbooks.partials.create-form', [
        'isTrainerCreating' => true,
        'formAction' => route('trainer.logbooks.store'),
        'user' => $trainer,
        'trainee' => $trainee,
        'trainees' => $trainees,
        'traineesMap' => $traineesMap,
        'selectedTraineeId' => $selectedTraineeId,
    ])
</x-app-layout>
