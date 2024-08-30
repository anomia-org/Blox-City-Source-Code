<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDuplicateGroupMembers extends Command
{
    protected $signature = 'groups:duplicates';
    protected $description = 'Check for duplicate group members and remove them';

    public function handle()
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Query to find duplicates based on user_id and group_id
            $duplicates = DB::table('guilds_members')
                ->select('user_id', 'guild_id', DB::raw('COUNT(*) as count'))
                ->groupBy('user_id', 'guild_id')
                ->having('count', '>', 1)
                ->get();

            if ($duplicates->isEmpty()) {
                $this->info('No duplicate entries found.');
            } else {
                $this->info('Duplicate entries found and removed:');
                foreach ($duplicates as $duplicate) {
                    // Find all duplicate records
                    $duplicateRecords = DB::table('guilds_members')
                        ->where('user_id', $duplicate->user_id)
                        ->where('guild_id', $duplicate->guild_id)
                        ->get();

                    // Keep the first record and delete the others
                    $keepRecord = $duplicateRecords->shift();
                    $deleteCount = 0;
                    foreach ($duplicateRecords as $record) {
                        DB::table('guilds_members')->where('id', $record->id)->delete();
                        $deleteCount++;
                    }

                    $this->line("User ID: {$duplicate->user_id}, Group ID: {$duplicate->guild_id}, Removed duplicates: {$deleteCount}");
                }
            }

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();
            $this->error('An error occurred while removing duplicates: ' . $e->getMessage());
        }
    }
}
