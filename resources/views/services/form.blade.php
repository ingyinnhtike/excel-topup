@extends('layouts.master')
@section('title', 'create-service')
@section('content')
<section id="custom-file-input">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Added Services</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if(session('service-name-status'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Alert!</h4>
                            {{ session('service-name-status') }}
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <fieldset>
                                    <form role="form" action="{{ route('service') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="customer">Choose Customer <span class="asterike">*</span></label>
                                            <select name="customer" id="customer" class="form-control" required>
                                                <option value="">--none--</option>
                                                @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('customer'))
                                            <p class="help-block" style="color: red;">{{ $errors->first('customer') }}</p>
                                            @endif
                                        </div>
                                        <label for="service">Service Name <span class="asterike">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="service_name" id="service">
                                            @if($errors->has('service_name'))
                                            <p class="" style="color: red;">{{ $errors->first('service_name') }}</p>
                                            @endif
                                        </div>
                                        <label for="amount">Amount <span class="asterike">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="amount" id="amount">
                                            @if($errors->has('amount'))
                                            <p class="" style="color: red;">{{ $errors->first('amount') }}</p>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
