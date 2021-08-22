<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/routes');
});

Route::get('/send-mail', function () {
    \App\Jobs\SendMail::dispatch()->delay(3);
    return view('welcome');
});


//chain method runs jobs in same order if anything fails it will stop immediately never invoke the next jobs
Route::get('/job-chain', function () {
    $chain = [
        new   \App\Jobs\PullRepo,
        new  \App\Jobs\RunTests,
        new  \App\Jobs\DeployProject,
    ];

    \Illuminate\Support\Facades\Bus::chain($chain)->dispatch();

    return 'job chain dispatched';
});


//batch will run parallely
Route::get('/job-batch', function () {
    $batch = [
        new \App\Jobs\PullRepo('laracasts'),
        new \App\Jobs\PullRepo('snappy'),
        new \App\Jobs\PullRepo('dom-pdf')
    ];

    \Illuminate\Support\Facades\Bus::batch($batch)
        ->allowFailures(false)
        ->catch(function ($batch, $exception) {
            info($batch->progress());
            info('some of the jobs failed');
        })
        ->then(function ($batch) {
            info('all jobs executed successfully');
        })
        ->finally(function ($batch) {
            info('clear the resources whether the job successful or not');
        })
        ->onQueue('pull-repo')
        ->onConnection('database')
        ->dispatch();

    return 'job batch dispatched';
});

Route::get('chain-inside-batch', function () {
    $batch = [
        new \App\Jobs\PullRepo('laracasts'),
        [
            new \App\Jobs\PullRepo('snappy'),
            new \App\Jobs\RunTests('snappy'),
            new \App\Jobs\DeployProject('snappy'),
        ],
        new \App\Jobs\PullRepo('phpunit'),
        [
            new \App\Jobs\PullRepo('dom-pdf'),
            new \App\Jobs\RunTests('dom-pdf'),
            new \App\Jobs\DeployProject('dom-pdf'),
        ],
    ];

    \Illuminate\Support\Facades\Bus::batch($batch)
        ->allowFailures(true)
        ->catch(function ($batch, $exception) {
            info('some of the jobs failed');
        })
        ->then(function ($batch) {
            info('all jobs executed successfully');
        })
        ->finally(function ($batch) {
            info('total progress of the This Batch' . $batch->progress());
            info('clear the resources whether the job successful or not');
        })
        ->onQueue('pull-repo')
        ->onConnection('database')
        ->dispatch();


    return 'chain inside job is dispatched';
});

Route::get('/limiting-job', function() {
   \App\Jobs\Deploy::dispatch();
   return 'Deploy job dispatched';
});


//if you want a job to be dispatched after the successful db commit. instead using after commit method on the job
//use configuration queue file. update after_commit = true . by setting this value all the job is should triggered after
//successful commit


//if you want keep sensitive data on tje job use ShoudBeEncrypted interface.


Route::get('downloads',function() {
    return 'only allow three downloads per minutes';
})->middleware('throttle:downloads');