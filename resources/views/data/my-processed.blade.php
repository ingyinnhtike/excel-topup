@extends('layouts.master')
@section('title', 'data-processed')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">My Processed Data</h4>
    </div>
    <div class="card-body">
        <form role="form" action="{{ url('data-filter') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-4">
                    <h6>Start Date</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="date" class="form-control" placeholder="Input with icon left" name="start_date" value="{{ isset($from) ? $from : '' }}">
                        <div class="form-control-icon">
                            <i class="bi bi-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h6>End Date</h6>
                    <div class="form-group position-relative has-icon-left">
                        <input type="date" class="form-control" placeholder="Input with icon left" name="end_date" value="{{ isset($to) ? $to : '' }}">
                        <div class="form-control-icon">
                            <i class="bi bi-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 mt-4">
                    <button type="submit" class="btn btn-primary" style="font-weight: bold;">Search</button>
                    <a type="submit" class="btn btn-success" href="{{ route('my_processed_data.export') }}" style="font-weight: bold;margin-left: 10px;">Export</a> 
                </div>                
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Phone Number</th>
                    <th>Merchant</th>
                    <th>Package Name</th>
                    <th>Package Volume</th>
                    <th>Batch</th>
                    <th>Status</th>
                    <th>Response Range</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @isset($filters)

                @foreach($filters as $data)
                <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->phone_number }}</td>
                    <td>{{ $data->customer_name }}</td>
                    <td>{{ $data->package_name }}</td>
                    <td>{{ $data->volume }}</td>
                    <td>{{ $data->name }}</td>
                    @if($data->status == 'pending')
                    <td><span class="label label-primary">{{ $data->status }}</span></td>
                    <td>internet connection error. pls try again</td>
                    @elseif($data->status == 'success')
                    <td><span class="label label-success">{{ $data->status }}</span></td>
                    <td>success</td>
                    @elseif($data->status == 'credit fail')
                    <td><span class="label label-warning">{{ $data->status }}</span></td>
                    <td>service not available.pls try again</td>
                    @elseif($data->status == 'error')
                    <td><span class="label label-danger">{{ $data->status }}</span></td>
                    <td>internal server error</td>
                    @else
                    <td><span class="label label-info">{{ $data->status }}</span></td>
                    <td>unprocessable entity response</td>
                    @endif
                    <td>{{ $data->created_at }}</td>
                    <td>{{ $data->updated_at }}</td>
                </tr>
                @endforeach
                @endisset

                @isset($processedDatas)
                @foreach($processedDatas as $data)
                <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->phone_number }}</td>
                    <td>{{ $data->customer_name }}</td>
                    <td>{{ $data->service_name }}</td>
                    <td>{{ $data->name }}</td>
                    @if($data->status == 'pending')
                    <td><span class="label label-primary">{{ $data->status }}</span></td>
                    <td>pending</td>
                    @elseif($data->status == 'success')
                    <td><span class="label label-success">{{ $data->status }}</span></td>
                    <td>success</td>
                    @elseif($data->status == 'credit fail')
                    <td><span class="label label-warning">{{ $data->status }}</span></td>
                    <td>service not available.pls try again</td>
                    @elseif($data->status == 'error')
                    <td><span class="label label-danger">{{ $data->status }}</span></td>
                    <td>internal server error because of internet connection or contact IT team</td>
                    @else
                    <td><span class="label label-info">{{ $data->status }}</span></td>
                    <td>other</td>
                    @endif
                    <td>{{ $data->created_at }}</td>
                    <td>{{ $data->updated_at }}</td>
                    @endforeach
                    @endisset
            </tbody>
        </table>
        </div>
    </div>
</div>
<script src="{{asset('js/simple-datatables.js')}}"></script>

<script>
    // Simple Datatable
    var table1 = document.querySelector('#table1');

    var dataTable = new simpleDatatables.DataTable(table1);

</script>
@endsection
