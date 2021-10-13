@extends('layouts.master')
@section('title', 'edit-service')
@section('content')
<section id="custom-file-input">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Services</h4>
                    @if(session('edit-service-status'))
                    <div class="bs-example">
                        <div class="alert alert-danger">
                            <strong>{{ session('edit-service-status') }}</strong>
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <fieldset>
                                    <form class="role" action="{{ route('edit') }}" method="post">
                                        @csrf
                                        <div class="box-body">
                                            @foreach ($datas as $data)
                                            <div class="form-group">
                                                <input type="hidden" name="customer_id" value="{{ $data->customer->id }}">
                                                <label for="service">Service Name</label>
                                                <input type="text" class="form-control" name="service_name" id="service" value="{{ $data->name }}">
                                                @if($errors->has('name'))
                                                <p class="help-block" style="color: red;">{{ $errors->first('name') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="amount">Amount</label>
                                                <input type="text" class="form-control" name="amount" id="amount" value="{{ $data->amount }}">
                                                @if($errors->has('amount'))
                                                <p class="help-block" style="color: red;">{{ $errors->first('amount') }}</p>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
