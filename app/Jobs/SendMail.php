<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1; // the maximum time should be taken job taken. if it exceeds marked as failed

    public $tries = 10;  // number of times the job is retried if it fails  before finally marked as failure

//    public $backoff = [2,10];

    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        info('new mail coming');

        throw new \Exception('failed');

        return $this->release(1); // this will override the backoff and release back to the queue

    }

    public function failed($e)
    {
       info('job is completely failed and give it up');
    }
}
