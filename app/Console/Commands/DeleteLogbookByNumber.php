<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OjtLogbook;
use App\Models\LogbookHistory;
use App\Models\LogbookAssignment;
use App\Models\CompetencyEvaluation;
use Illuminate\Support\Facades\DB;

class DeleteLogbookByNumber extends Command
{
    protected $signature = 'logbook:delete {number}';
    protected $description = 'Delete logbook by logbook_number';

    public function handle(): void
    {
        $number = $this->argument('number');
        $logbook = OjtLogbook::where('logbook_number', $number)->first();

        if (! $logbook) {
            $this->error("Logbook {$number} not found.");
            return;
        }

        $id = $logbook->id;

        DB::transaction(function () use ($logbook) {
            CompetencyEvaluation::where('ojt_logbook_id', $logbook->id)->delete();
            LogbookHistory::where('ojt_logbook_id', $logbook->id)->delete();
            LogbookAssignment::where('ojt_logbook_id', $logbook->id)->delete();
            $logbook->delete();
        });

        $this->info("DELETED logbook {$number} (id={$id})");
    }
}
