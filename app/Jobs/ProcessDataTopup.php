<?php

namespace App\Jobs;

use App\Batch;
use App\DataRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class
ProcessDataTopup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // protected $phone_number;
    // protected $reference_id;
    protected $batch_id;
    protected $processDatas;
    // protected $keyword;
    // protected $token;
    // protected $package_name;
    // protected $package_code;
    // protected $service_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($processDatas, $batch_id)
    {
        // $this->phone_number = $phone_number;
        // $this->reference_id = $reference_id;
        $this->batch_id = $batch_id;
        $this->processDatas = $processDatas;
        // $this->token = $token;
        // $this->keyword = $keyword;
        // $this->package_name = $package_name;
        // $this->package_code = $package_code;
        // $this->service_name = $service_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        foreach ($this->processDatas as $datas) {
            $response = Http::withOptions(['allow_redirects' => false])->timeout(30)
                ->withToken($datas->token)
                ->post(
                    config('settings.bp_gate.data_url'),
                    [
                        'keyword' => $datas->keyword,
                        'service_name' => $datas->service_name,
                        'to' => $datas->phone_number,
                        'id' => $datas->reference_id,
                        'package_name' => $datas->package_name,
                        'package_code' => $datas->package_code
                    ]
                );

            $data = json_decode($response->body());

            $batch = Batch::find($this->batch_id);

            if ($data->status == 'success') {
                if ($batch->failed != 0) {
                    $batch->failed = $batch->failed - 1;
                }
                $batch->succeeded = $batch->succeeded + 1;

                $status = "success";
            } else {
                $batch->failed = $batch->failed + 1;
                $status = "fail";
            }
            $batch->processed = $batch->processed + 1;

            DataRequest::where('reference_id', $datas->reference_id)->update(['status' => $status, 'description' => $data->description]);
            $batch->save();
        }
    }
}
