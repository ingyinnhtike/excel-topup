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
                        <h4 class="card-title changeTitle">Data Topup</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body byExcelShow">
                            <form class="form form-vertical" action="{{ route('data.request') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-group mb-4">
                                                <div class="input-group mb-3">
                                                    <label class="input-group-text" for="inputGroupFile01"><i
                                                                class="bi bi-upload"></i></label>
                                                    <input type="file" id="data" name="data-file" required class="form-control excelFile3">
                                                    @if($errors->has('bill-file'))
                                                        <p class="help-block" style="color: red;">{{ $errors->first('data-file') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-4">                                                                                    
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
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="" class="mb-1"> MPT </label>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="mpt" required>
                                                    <option value=""> Select Package </option>
                                                    @foreach ($mpt as $item)
                                                        <option value="{{$item->id}}"> {{ $item->package_name }} ({{ $item->volume }}) </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="" class="mb-1"> Ooredoo </label>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="ooredoo" required>
                                                    <option value=""> Select Package </option>
                                                    @foreach ($ooredoo as $item)
                                                        <option value="{{$item->id}}"> {{ $item->package_name }} ({{ $item->volume }}) </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                             <div class="col-md-3">
                                                <label for="" class="mb-1"> Telenor </label>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="telenor" required>
                                                    <option value=""> Select Package </option>
                                                    @foreach ($telenor as $item)
                                                        <option value="{{$item->id}}"> {{ $item->package_name }} ({{ $item->volume }}) </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                             <div class="col-md-3">
                                                <label for="" class="mb-1"> Mytel </label>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="mytel" required>
                                                    <option value=""> Select Package </option>
                                                    @foreach ($mytel as $item)
                                                        <option value="{{$item->id}}"> {{ $item->package_name }} ({{ $item->volume }}) </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body byPhoneShow">
                            <form class="form form-vertical" action="{{ route('data.request-phoneno') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-group mb-4">
                                                <div class="input-group mb-3">
                                                    <label class="input-group-text" for="inputGroupFile01">Phone Number</label>
                                                    <input type="number" id="data1" name="phoneNo" required class="form-control phoneNo">
                                                    @if($errors->has('bill-file'))
                                                        <p class="help-block" style="color: red;">{{ $errors->first('data-file') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-4">                                                                                    
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

                                        <div class="col-12 mb-4">                                                                                    
                                            <div class="form-group">  
                                                <label for="package">Choose Package</label>
                                                <select name="package" id="package" class="form-control choosePackage" required>
                                                    <option value="">--none--</option>
                                                    
                                                </select>
                                                @if($errors->has('customer'))
                                                    <p class="help-block" style="color: red;">{{ $errors->first('customer') }}</p>
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
<script>
    $('.phoneNo').change(function () {
        $(".choosePackage").empty();

        let phoneNo = $('.phoneNo').val();
        let prefix = phoneNo.substr(0, 2);
        
        let operator = "";
        if (prefix == "09") {
            let getOperator = phoneNo.substr(2, 2);
            
            if (getOperator >= '74' && getOperator <= '79') {
                operator = "Telenor";  //Telenor
            } else if (getOperator >= '90' && getOperator <= '99') {
                operator = "Ooredoo";  //Ooredoo
            } else if (getOperator >= '65' && getOperator <= '69') {
                operator = "MyTel"; //MyTel
            } else {
                operator = "MPT"; //MPT
            }
            
        } else if(prefix == "95"){

            let getOperator = phoneNo.substr(3, 2);
            
            if (getOperator >= '74' && getOperator <= '79') {
                operator = "Telenor";  //Telenor
            } else if (getOperator >= '90' && getOperator <= '99') {
                operator = "Ooredoo";  //Ooredoo
            } else if (getOperator >= '65' && getOperator <= '69') {
                operator = "MyTel"; //MyTel
            } else {
                operator = "MPT"; //MPT
            }
            
        }
        if (operator == "MPT") {
            let getmpt = {!! json_encode($mpt) !!}
            
            $.each(getmpt, (i, v) => { 
                $(".choosePackage").append(
                    '<option value="' +
                        v.id +
                        '">' +
                        v.package_name + '('+ v.volume +')' +
                        "</option>"
                );
            });
        }

        if (operator == "Ooredoo") {
            let getooredoo = {!! json_encode($ooredoo) !!}
            
            $.each(getooredoo, (i, v) => { 
                $(".choosePackage").append(
                    '<option value="' +
                        v.id +
                        '">' +
                        v.package_name + '('+ v.volume +')' +
                        "</option>"
                );
            });
        }
        
        if (operator == "Telenor") {
            let gettelenor = {!! json_encode($telenor) !!}
            
            $.each(gettelenor, (i, v) => { 
                $(".choosePackage").append(
                    '<option value="' +
                        v.id +
                        '">' +
                        v.package_name + '('+ v.volume +')' +
                        "</option>"
                );
            });
        }

        if (operator == "MyTel") {
            let getmytel = {!! json_encode($mytel) !!}
            
            $.each(getmytel, (i, v) => { 
                $(".choosePackage").append(
                    '<option value="' +
                        v.id +
                        '">' +
                        v.package_name + '('+ v.volume +')' +
                        "</option>"
                );
            });
        }
        
        
    });
</script>
@endsection
