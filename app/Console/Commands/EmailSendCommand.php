<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EnquiryResponse;
use App\Models\Email;
use App\Mail\NotificationEmail;

class EmailSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Sending!!';

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
        Email::whereIn('status',['Pending'])->get()->each(function($oneemail){
            $oneemail->status = 'In Progress';
            $oneemail->save();
            $this->sendEmail($oneemail);
        });
        return true;
    }

    public function sendEmail($email){
        try{
            \Mail::to($email->to)->send(new NotificationEmail($email->toArray()));
            $email->status = 'Success';
            $email->completed_at = now();
            $email->save();
            if($email->source_table == EnquiryResponse::class){
                $enquiryResponse = EnquiryResponse::find($email->source_table_id);
                $enquiryResponse->notification_email = 1;
                $enquiryResponse->save();
            }

        }catch(\Exception $e){
            dd($e->getMessage());
            return 'error';
        }
    }
}
