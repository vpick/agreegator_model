<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CronJobController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            $logContents = File::get($logPath);
             return view('common-app.cron_job', compact('logContents'));
        } else {
            return abort(404, 'Log file not found');
        }
       
       
    }
}
