@extends('layouts.master')

@section('title','confirm-dashboard')

@section('content')
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
    </section>
    <section class="content">
        <!-- Profile Image -->
        <div class="col-12">
            <div class="box box-info">
                <div class="box-body box-profile">
                    <br>
                    <p class="text-muted text-center"></p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Batch Name</b> - <a class="float-right">{{ $batch_name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Total Count</b> - <a class="float-right">{{ $data_count }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Total Amount</b> - <a class="float-right">{{ $amount }}</a>
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

                    <form action="{{ url('my-bill-process') }}" method="post" id="billSubmit">
                        @csrf
                        <input type="hidden" name="batch_id" value="{{$batch_id}}" class="form-control" id="exampleFormControlFile1">
                        <button type="submit" class="btn btn-primary" id="confirm"><b>Confirm topup</b></button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </section>
@endsection


@section('script')
    <script>
        document.getElementById("confirm").onclick = function() {
            //disable
            document.getElementById("billSubmit").submit();
            this.disabled = true;

            //do some validation stuff
        }
    </script>
@endsection

