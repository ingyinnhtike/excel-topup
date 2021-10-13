@extends('layouts.master')
@section('title','detail-batches')
@section('title','Batch | Detail')

@section('content')
<section class="content-header">
    <h1>
        Batch Details
    </h1>
</section>
<section class="content">
    <!-- Profile Image -->
    <div class="col-12">
        <div class="box box-info">
            <div class="box-body box-profile">
                <br>
                <p class="text-muted text-center"></p>
                @if(App\BillRequest::where('batch_id',$batch_id)->exists())
                <form action="{{ url('retry-bill-process') }}" method="post">
                    @csrf
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <input type="hidden" value="{{ $batch->id }}" name="batch_id">
                            <input type="hidden" value="{{ $batch->name }}" name="batch_name">
                            <b>Batch Name</b> - <a class="pull-right">{{ $batch->name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Failed Count</b> - <a class="pull-right">{{ $batch->failed }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Phone Number for Billing</b> -
                            @foreach ($bill_data as $data)
                            <input type="hidden" name='reference[]' value="{{ $data->reference_id }}">
                            <input type="hidden" name="phone[]" value="{{ $data->phone_number }}">
                            <a class="pull-right">{{ $data->phone_number.', '}}</a>
                            @endforeach
                        </li>                                               
                        <li class="list-group-item">
                            <b>Service Name</b> -
                            @foreach ($services as $service)
                            <input type="hidden" name="service" value="{{ $service->id }}">
                            <a class="pull-right">{{ $service->name }}</a>
                            @endforeach
                        </li>
                        <li class="list-group-item">
                            <b>Created At</b> - <a class="pull-right">{{ $batch->created_at }}</a>
                        </li>
                    </ul>
                    <br>
                    <input type="hidden" name="batch_id" value="{{ $batch_id }}" class="form-control" id="exampleFormControlFile1">
                    @if(App\BillRequest::where('status','!=','success')->where('batch_id',$batch_id)->exists())
                    <button type="submit" class="btn btn-primary"><b>Retry</b></button>
                    @endif
                </form>
                @endif
    <!-------------------------------------------------------------------------------------------------->
                @if(App\DataRequest::where('batch_id',$batch_id)->exists())
                <form action="{{ url('retry-data-process') }}" method="post">
                    @csrf
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <input type="hidden" value="{{ $batch->id }}" name="batch_id">
                            <input type="hidden" value="{{ $batch->name }}" name="batch_name">
                            <b>Batch Name</b> - <a class="pull-right">{{ $batch->name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Failed Count</b> - <a class="pull-right">{{ $batch->failed }}</a>
                        </li>
                        @isset($data)
                        <li class="list-group-item">
                            <b>Phone Number for Data</b> -
                            @foreach ($data as $phone_number)
                            <input type="hidden" name="phone[]" value="{{ $phone_number }}">
                            <a class="pull-right">{{ $phone_number.', '}}</a>
                            @endforeach
                        </li>
                        @endisset
                        <li class="list-group-item">
                            <b>Package Name</b> -
                            @foreach ($packages as $package)
                            <input type="hidden" name="package" value="{{ $package->id }}">
                            <a class="pull-right">{{ $package->package_name }}({{$package->volume}}) | </a>
                            @endforeach
                        </li>
                        <li class="list-group-item">
                            <b>Created At</b> - <a class="pull-right">{{ $batch->created_at }}</a>
                        </li>
                    </ul>
                    <br>
                    <input type="hidden" name="batch_id" value="{{ $batch_id }}" class="form-control" id="exampleFormControlFile1">
                    @if(App\DataRequest::where('status','!=','success')->where('batch_id',$batch_id)->exists())
                    <button type="submit" class="btn btn-primary"><b>Retry</b></button>
                    @endif
                </form>
                @endif
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</section>
@endsection
