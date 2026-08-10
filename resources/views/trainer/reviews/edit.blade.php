<x-app-layout>
    <x-slot name="title">Edit Logbook {{ $logbook->logbook_number }}</x-slot>

    @php($isTrainerEditing = true)
    @include('ojt.logbooks.partials.create-form')
</x-app-layout>
