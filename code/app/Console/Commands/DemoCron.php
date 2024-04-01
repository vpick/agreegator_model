<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $count = Order::where('awb_no', '')->where('order_status','!=','Cancelled')->count();
        if($count > 0){
            $data = Order::where('awb_no', '')->where('order_status','!=','Cancelled')->update(['order_status' => 'Cancelled']);
            Log::info('Records updated successfully');
        }
        else{
            Log::info("No records for updation!");
        }   

      
    }
}
