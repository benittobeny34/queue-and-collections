<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Deploy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        Redis::funnel('deployments')
//            ->limit(5)
//            ->block(10)
//            ->then(function(){
//                info('start deploying');
//                sleep(3);
//                info('deployment is going');
//                sleep(5);
//                info('deployment is completed');
//
//            });
        Cache::lock('deployments')->block(10, function () {
            info('start deploying');
            sleep(3);
            info('deployment is going');
            sleep(5);
            info('deployment is completed');
        });
    }

}
