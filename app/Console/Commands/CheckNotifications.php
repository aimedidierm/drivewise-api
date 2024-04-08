<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;

class CheckNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $found = "";
        // if ($found) {
        //     $notification = new Notification();
        //     $notification->title = $maintenance->title;
        //     $notification->notification = $maintenance->notification;
        //     $notification->notification = $maintenance->notification;
        //     $notification->vehicle_id = $maintenance->notification;
        //     $notification->user_id = $maintenance->notification;
        //     $notification->save();
        // }
    }
}
