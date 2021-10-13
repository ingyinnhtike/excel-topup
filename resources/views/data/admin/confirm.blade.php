@extends('layouts.master')
@section('title', 'confirm-dashboard')
@section('content')
    <section class="content">
        <!-- Profile Image -->
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-body box-profile">
                    <br>
                    <p class="text-muted text-center"></p>
                    <form action="{{ url('all-data-process') }}" method="post" >
                        @csrf
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Batch Name</b> - <a class="float-right">{{ $batch_name }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Package Name</b> - 
                                @foreach ($packages as $package)
                                    <input type="hidden" name="package_name" value="{{ $package->package_name }}">
                                    <a class="float-right">{{ $package->package_name }} ({{ $package->volume }})</a>,
                                @endforeach                            
                            </li>
                            <li class="list-group-item">
                                <b>Total Count</b> - <a class="float-right">{{ $data_count }}</a>
                            </li>              
                            <li class="list-group-item">
                                <b>Total Amount</b> - <a class="float-right">{{ $sum * $data_count }} Ks</a>
                            </li>          
                            {{-- <li class="list-group-item">
                                <b>First 5 numbers</b> -
                                @foreach($first_five_numbers as $first_number)
                                    <a class="float-right">{{ $first_number.', ' }}</a>
                                @endforeach
                            </li>
                            <li class="list-group-item">
                                <b>Last 5 numbers</b> -
                                @foreach($last_five_numbers as $last_number)
                                    <a class="float-right">{{$last_number.', '}}</a>
                                @endforeach
                            </li> --}}
                        </ul>
                        <br>                                                
                        <input type="hidden" name="batch_id" value="{{$batch_id}}" class="form-control" id="exampleFormControlFile1">
                        <button type="submit" class="btn btn-primary"><b>Confirm topup</b></button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </section>
@endsection

