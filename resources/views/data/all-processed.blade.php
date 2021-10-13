@extends('layouts.master')
@section('title','data-processed')
@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">All Processed Data</h4>
        </div>
        <div class="card-body">
            <form role="form" action="{{ url('all-data-filter') }}" method="post" class="mb-3">
                @csrf
                <div class="row">
    
                    <div class="col-sm-3">
                            <h6>Start Date</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input type="date" class="form-control"
                                       placeholder="Input with icon left" name="start_date" value="{{ isset($from) ? $from : '' }}">
                                <div class="form-control-icon">
                                    <i class="bi bi-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <h6>End Date</h6>
                            <div class="form-group position-relative has-icon-left">
                                <input type="date" class="form-control"
                                       placeholder="Input with icon left" name="end_date" value="{{ isset($to) ? $to : '' }}">
                                <div class="form-control-icon">
                                    <i class="bi bi-calendar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <h6>Batch Name</h6>
                            <div class="form-group position-relative">
                                <input type="text" class="form-control"
                                       placeholder="eg: username_2021-01-01 ..." name="batchName" value="{{ isset($batch_name) ? $batch_name : '' }}">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <h6>Status</h6>
                            @if (isset($status))
                                <div class="form-group">
                                    <select class="custom-select form-control" name="status">
                                        <option value="null" {{($status == "null") ? "selected" : ''}}>Select</option>
                                        <option value="success" {{($status == "success") ? "selected" : ''}}>Success</option>
                                        <option value="fail" {{($status == "fail") ? "selected" : ''}}>Fail</option>
                                    </select>
                                </div>
                            @else
                            <div class="form-group">
                                <select class="custom-select form-control" name="status">
                                    <option value="null" selected>Select</option>
                                    <option value="success">Success</option>
                                    <option value="fail">Fail</option>
                                </select>
                            </div>
                            @endif
                        </div>

                        <div class="col-sm-4">
                            {{-- <button type="submit" class="btn btn-primary" style="font-weight: bold;">Search</button>
                            <a type="submit" class="btn btn-success" href="{{ route('all_processed_bill.export') }}" style="font-weight: bold;margin-left: 10px;">Export</a> --}}
                            <input type="submit" name="action" value="Search" class="btn btn-primary" style="font-weight: bold;" />
                            <input type="submit" name="action" value="Export" class="btn btn-primary" style="font-weight: bold;" />

                        </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table" style="width:100%" id="alldataProcessed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Phone Number</th>
                        <th>Merchant</th>
                        <th>Package Name</th>
                        <th>Volume</th>
                        <th>Batch</th>
                        <th>Status</th>
                        <th>Response Range</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($filters)
    
                    @foreach($filters as $data)
                    <tr>
                        <td> {{$loop->iteration}} </td>
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
                        @elseif($data->status == 'fail')
                        <td><span class="label label-warning">{{ $data->status }}</span></td>
                        <td>service not available.pls try again</td>
                        @elseif($data->status == 'error')
                        <td><span class="label label-danger">{{ $data->status }}</span></td>
                        <td>internal server error</td>
                        @else
                        <td><span class="label label-info">{{ $data->status }}</span></td>
                        <td>unprocessable entity response</td>
                        @endif
                        <td> {{ $data->description}} </td>
                        <td>{{ $data->created_at }}</td>
                        <td>{{ $data->updated_at }}</td>
                    </tr>
                    @endforeach
                    @endisset
    
                    @isset($all)
                    @foreach($all as $data)
                    <tr>
                        <td> {{$loop->iteration}} </td>
                        <td>{{ $data->phone_number }}</td>
                        <td>{{ $data->customer_name }}</td>
                        <td>{{ $data->package_name }}</td>
                        <td>{{ $data->volume }}</td>
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
                        <td> {{ $data->description}} </td>
                        <td>{{ $data->created_at }}</td>
                        <td>{{ $data->updated_at }}</td>
                    </tr>
                    @endforeach
                    @endisset
                </tbody>
            </table>
            </div>
        </div>
    </div>
</section>   
@endsection

@section('script')
   <script>
        $('#alldataProcessed').DataTable();
   </script>
@endsection