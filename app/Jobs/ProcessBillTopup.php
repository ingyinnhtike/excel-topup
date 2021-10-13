<?php

namespace App\Jobs;

use App\Batch;
use App\BillRequest;
use App\Helpers\PhoneParse;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class ProcessBillTopup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 10;

    // protected $phone_number;
    // protected $reference_id;
    protected $batch_id;
    protected $processDatas;
    // protected $keyword;
    // protected $token;
    // protected $service_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public function __construct($phone_number, $reference_id, $batch_id, $keyword, $token, $service_name)
    // {
    //     $this->phone_number = $phone_number;
    //     $this->reference_id = $reference_id;
    //     $this->batch_id = $batch_id;
    //     $this->keyword = $keyword;
    //     $this->token = $token;
    //     $this->service_name = $service_name;
    // }

    public function __construct($processDatas, $batch_id)
    {
        $this->processDatas = $processDatas;
        $this->batch_id = $batch_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle(PhoneParse $parse)
    {
        foreach ($this->processDatas as $datas) {

            $response = Http::withOptions(['allow_redirects' => false])->timeout(30)
                ->withToken($datas->token)
                ->post(
                    config('settings.bp_gate.url'),
                    [
                        'keyword' => $datas->keyword,
                        'service_name' => $datas->service_name,
                        'to' => $datas->phone_number,
                        'id' => $datas->reference_id,
                    ]
                );

            $data = json_decode($response->body());
            $batch = Batch::find($this->batch_id);

            if ($data->status === 'success') {
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

            BillRequest::where('reference_id', $datas->reference_id)->update(['status' => $status, 'description' => $data->description]);
            $batch->save();
        }
    }
}
