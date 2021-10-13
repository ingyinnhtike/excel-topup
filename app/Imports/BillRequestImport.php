<?php


namespace App\Imports;

use App\BillRequest;
use App\Helpers\PhoneParse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Maatwebsite\Excel\HeadingRowImport;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BillRequestImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return ImportedData
     */

    protected $batch_id;
    protected $phone_number;
    private $rows = 0;

    public function __construct($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    public function model(array $row)
    {
        ++$this->rows;

        // $result = BillRequest::where('status','!=','success')->where('created_at','<=',Carbon::now()->subMinutes(2)->toDateTimeString())->exists();

        return new BillRequest(
            [
                'reference_id' => Str::uuid(),
                'phone_number' => $row['phone_number'],
                'provider' => 'BP_Gate',
                'operator' => PhoneParse::getOperator($row['phone_number']),
                'status' => 'pending',
                'batch_id' => $this->batch_id,
                'user_id' => auth()->id()
            ]
        );
    }


    public function rules(): array
    {
        return [

            // 'phone_number' => 'required|unique:bill_requests',

            // 'phone_number' => Rule::unique('bill_requests', 'phone_number')

            //    'phone_number' => Rule::in('bill_requests', '*.phone_number')

        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.phone_number' => 'Duplicate',
        ];
    }

    // public function import()
    // {
    //     $headings = (new HeadingRowImport)->toArray('users.xlsx');
    // }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
