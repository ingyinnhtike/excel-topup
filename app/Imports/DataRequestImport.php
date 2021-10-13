<?php

namespace App\Imports;

use App\DataRequest;
use App\Helpers\PhoneParse;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataRequestImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return ImportedData
     */
    protected $batch_id;
    protected $customer;
    protected $operator;
    protected $mpt;
    protected $ooredoo;
    protected $telenor;
    protected $mytel;
    private $rows = 0;

    public function __construct($batch_id, $mpt, $ooredoo, $telenor, $mytel)
    {
        $this->batch_id = $batch_id;
        // $this->package_id = $package_id;
        // $this->operator = $operator;
        $this->mpt = $mpt;
        $this->ooredoo = $ooredoo;
        $this->telenor = $telenor;
        $this->mytel = $mytel;
    }

    public function model(array $row)
    {
        ++$this->rows;
        if (PhoneParse::getOperator($row['phone_number']) == "MPT") {
            return new DataRequest(
                [
                    'reference_id' => Str::uuid(),
                    'phone_number' => $row['phone_number'],
                    'provider' => 'BP_Gate',
                    'operator' => PhoneParse::getOperator($row['phone_number']),
                    'status' => 'pending',
                    'batch_id' => $this->batch_id,
                    'user_id' => auth()->id(),
                    'package_id' => $this->mpt
                ]
            );
        } elseif (PhoneParse::getOperator($row['phone_number']) == "Telenor") {
            return new DataRequest(
                [
                    'reference_id' => Str::uuid(),
                    'phone_number' => $row['phone_number'],
                    'provider' => 'BP_Gate',
                    'operator' => PhoneParse::getOperator($row['phone_number']),
                    'status' => 'pending',
                    'batch_id' => $this->batch_id,
                    'user_id' => auth()->id(),
                    'package_id' => $this->telenor
                ]
            );
        } elseif (PhoneParse::getOperator($row['phone_number']) == "Ooredoo") {
            return new DataRequest(
                [
                    'reference_id' => Str::uuid(),
                    'phone_number' => $row['phone_number'],
                    'provider' => 'BP_Gate',
                    'operator' => PhoneParse::getOperator($row['phone_number']),
                    'status' => 'pending',
                    'batch_id' => $this->batch_id,
                    'user_id' => auth()->id(),
                    'package_id' => $this->ooredoo
                ]
            );
        } else {
            return new DataRequest(
                [
                    'reference_id' => Str::uuid(),
                    'phone_number' => $row['phone_number'],
                    'provider' => 'BP_Gate',
                    'operator' => PhoneParse::getOperator($row['phone_number']),
                    'status' => 'pending',
                    'batch_id' => $this->batch_id,
                    'user_id' => auth()->id(),
                    'package_id' => $this->mytel
                ]
            );
        }
    }
    public function getRowCount(): int
    {
        return $this->rows;
    }
}
