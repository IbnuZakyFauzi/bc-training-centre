<x-app-layout>
    <x-slot name="title">Edit Logbook - {{ $logbook->logbook_number }}</x-slot>

    <!-- Trainer Revision Callout (If Status is Revision) -->
    @if($logbook->status === 'revision' && $logbook->revision_notes)
        <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-6 rounded-2xl shadow-sm">
            <div class="flex items-start space-x-3">
                <div class="p-2 bg-amber-100 rounded-xl text-amber-800 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900">Catatan Revisi dari Trainer Evaluator</h3>
                    <p class="text-xs text-amber-800 mt-1 leading-relaxed">{{ $logbook->revision_notes }}</p>
                </div>
            </div>
        </div>
    @endif

    @include('ojt.logbooks.partials.edit-form')

</x-app-layout>
