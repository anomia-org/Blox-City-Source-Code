<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckTransactions extends Command
{
    protected $signature = 'transactions:check';
    protected $description = 'Check for transactions with released=0 and release_at in the past';

    public function handle()
    {
        $now = Carbon::now();

        $transactions = Transaction::where('released', 0)
            ->where('release_at', '<', $now)
            ->get();

        foreach ($transactions as $transaction) {
            // Perform actions for transactions that need to be released
            // For example, update the released status
            if($transaction->cash > 0)
                $transaction->owner->increment('cash', $transaction->cash);
            elseif($transaction->coins > 0)
                $transaction->owner->increment('coins', $transaction->coins);
            $transaction->released = 1;
            $transaction->save();
        }

        $this->info('Transactions checked successfully.');
    }
}
