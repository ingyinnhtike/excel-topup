<?php

namespace App\Jobs;

use App\Batch;
use App\Retry;
use App\BillRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RetryProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $phone_number;
    protected $reference_id;
    protected $batch_id;
    protected  $keyword;
    protected  $token;
    protected  $service_name;
    protected $service_id;

    public function __construct($phone_number, $reference_id, $batch_id,$keyword,$token,$service_name,$user_id, $service_id)
    {
        $this->phone_number = $phone_number;
        $this->reference_id = $reference_id;
        $this->batch_id = $batch_id;
        $this->keyword = $keyword;
        $this->token = $token;
        $this->service_name = $service_name;
        $this->user_id = $user_id;
        $this->service_id = $service_id;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::withOptions(['allow_redirects' => false])->timeout(30)
            ->withToken($this->token)
            ->post(config('settings.bp_gate.url'),
                [
                    'keyword' =>$this->keyword ,
                    'service_name' => $this->service_name,
                    'to' => $this->phone_number,
                    'id' => $this->reference_id,
                ]
            );           
            
        $batch = Batch::find($this->batch_id);
        $retry = new Retry();

        if(BillRequest::where('batch_id',$this->batch_id)->where('status','!=','success')->exists())
        {
            if($response->status() === 200)
            {
                $retry->succeeded = $retry->succeeded + 1;               
                $status = "success";
            }
            elseif($response->status() === 402){
                $retry->failed =  $retry->failed + 1;
                $status = "credit fail";
            }
            elseif($response->status() === 500){
                $retry->failed =  $retry->failed + 1;
                $status = "error";
            }
            else{
                $retry->failed = $retry->failed + 1;
                $status = $response->status();
            }
            
            // dd($batch->failed);
            $retry->processed = $retry->processed + 1;
            $retry->batch_id = $this->batch_id;
            $retry->service_id =  $this->service_id;
            $retry->user_id = $this->user_id;
            
            BillRequest::where('reference_id', $this->reference_id)->update(['status' => $status]);
            $retry->save();
        }
    }
}
