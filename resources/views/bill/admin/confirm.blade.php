@extends('layouts.master')
@section('title','confirm-dashboard')
@section('content')

    <section class="content">
        <!-- Profile Image -->
        <div class="col-12">
            <div class="box box-info">
                <div class="box-body box-profile">
                    <br>
                    <p class="text-muted text-center"></p>

                    <ul class="list-group list-group-unbordered" >
                        <li class="list-group-item" >
                            <b>Batch Name</b> - <a class="float-right">{{ $batch_name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Total Count</b> - <a class="float-right">{{ $data_count }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Total Amount</b> - <a class="float-right">{{ $sum }}</a>
                        </li>
                        <li class="list-group-item">
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
                        </li>
                    </ul>

                    <br>

                    <form action="{{ url('all-bill-process') }}" method="post">
                        @csrf
                        <input type="hidden" name="batch_id" value="{{$batch_id}}" class="form-control" id="exampleFormControlFile1">
                       <button type="submit" class="btn btn-primary"><b>Confirm topup</b></button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </section>
    @endsection