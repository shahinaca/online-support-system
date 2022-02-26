<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enquiry;

class EnquiryStatusUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Status:Update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Enquiry::whereIn('status', ['Not Answered', 'In Progress'])->whereHas('lastAnswer', function($q){
            $q->where('updated_at','<=', now()->subHours(Config('const.hours_change')));
        })->get()->each(function($enquiry){
            $enquiry->status = 'Answered';
            $enquiry->closed_by = $enquiry->lastAnswer->created_by;
            $enquiry->closed_at = now();
            $enquiry->save();
        });
    }
}
