@extends('layouts.master')
@section('title', 'excel-topup')
@section('content')
<div class="page-heading">
    <!-- layout section start -->
    <section id="basic-vertical-layouts">
        <div class="row match-height">
            <div class="col-md-6 col-lg-8">
                <div class="card">
                    <div class="col-md-4 offset-md-8">
                        <div class="row mx-0 px-0">
                            <div class="col-md-4 px-2 py-1" style="background-color: #ffddc6; font-size: 12px; cursor: pointer">
                                <a href="{{ url('sample-excel-download') }}">Sample Excel</a>
                            </div>
                            <div class="col-md-4 px-2 py-1 byPhone" style="background-color: #c4d6ff; font-size: 12px; cursor: pointer">
                                By Phone
                            </div>
                            <div class="col-md-4 px-2 py-1 byExcel" style="background-color: #84ff87; font-size: 12px; cursor: pointer">
                                By Excel
                            </div>
                        </div>
                    </div>
                    <div class="card-header">
                        <h4 class="card-title changeTitle">Bill Topup By Excel File</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body byExcelShow">
                            <form class="form form-vertical" action="{{ route('bill.request') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <div class="input-group mb-3">
                                                    <label class="input-group-text" for="inputGroupFile01"><i
                                                                class="bi bi-upload"></i></label>
                                                    <input type="file" id="bill" name="bill-file" required class="form-control excelFile1">
                                                    @if($errors->has('bill-file'))
                                                        <p class="help-block" style="color: red;">{{ $errors->first('bill-file') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">                                                                                    
                                            <div class="form-group">  
                                                <label for="customer">Choose Company Name</label>
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
                                        </div>  
                                        <div class="col-12">                                                                                    
                                            <div class="form-group">  
                                                <label for="amount">Choose Service</label>
                                            <select name="service" id="amount" class="form-control" required>
                                                <option value="">--none--</option>
                                               
                                            </select>
                                            @if($errors->has('service'))
                                                <p class="help-block" style="color: red;">{{ $errors->first('service') }}</p>
                                            @endif
                                            </div>
                                        </div>   
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary me-1 mb-1 confirm1">Submit</button>
                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body byPhoneShow">
                            <form class="form form-vertical" action="{{ route('bill.request-phoneno') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-group mb-3">
                                                <div class="input-group mb-3">
                                                    <label class="input-group-text" for="">Phone Number</label>
                                                    <input type="number" id="bill1" name="phoneNo" class="form-control"placeholder="959*********" required>
                                                    @if($errors->has('bill-file'))
                                                        <p class="help-block" style="color: red;">{{ $errors->first('bill-file') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">                                                                                    
                                            <div class="form-group">  
                                                <label for="customer">Choose Company Name</label>
                                                <select name="customer" id="customerbyphone" class="form-control" required>
                                                    <option value="">--none--</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('customer'))
                                                    <p class="help-block" style="color: red;">{{ $errors->first('customer') }}</p>
                                                @endif
                                            </div>
                                        </div>  
                                        <div class="col-12">                                                                                    
                                            <div class="form-group">  
                                                <label for="amount">Choose Service</label>
                                            <select name="service" id="amountbyphone" class="form-control" required>
                                                <option value="">--none--</option>
                                               
                                            </select>
                                            @if($errors->has('service'))
                                                <p class="help-block" style="color: red;">{{ $errors->first('service') }}</p>
                                            @endif
                                            </div>
                                        </div>   
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/checkSamePhone.js') }}"></script>    
@endsection
