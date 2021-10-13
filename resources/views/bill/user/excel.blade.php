@extends('layouts.master')
@section('title', 'excel-topup')
@section('content')
    <section id="custom-file-input">
        <div class="row">
            <div class="col-8">
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
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <form role="form" action="{{ route('my.bill.request') }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" value="{{ $customer_id }}" name="customer_id">
                                            <div class="input-group mb-3">
                                                <div class="input-group mb-3">
                                                    <label class="input-group-text" for="inputGroupFile01"><i
                                                                class="bi bi-upload"></i></label>
                                                    <input type="file" id="bill" name="file" class="form-control excelFile2" required>
                                                    @if($errors->has('file'))
                                                        <p class="help-block" style="color: red;">{{ $errors->first('file') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="service">Choose Service</label>
                                                <select name="service" id="service" class="form-control" required>
                                                    <option value="">--none--</option>
                                                    @foreach($services as  $service)
                                                        @if ($service->name != "Data")
                                                            <option value="{{ $service->id }}" name='service'>{{ $service->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @if($errors->has('service'))
                                                    <p class="help-block" style="color: red;">{{ $errors->first('service') }}</p>
                                                @endif
                                            </div>

                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>

                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="card-body byPhoneShow">
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <form role="form" action="{{ route('my.bill.request-phoneno') }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" value="{{ $customer_id }}" name="customer_id">
                                            <div class="input-group mb-3">
                                                <div class="input-group mb-3">
                                                    <label class="input-group-text" for="">Phone Number</label>
                                                    <input type="number" id="bill" name="phoneNo" class="form-control" placeholder="959*********" required>
                                                    @if($errors->has('file'))
                                                        <p class="help-block" style="color: red;">{{ $errors->first('file') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="service">Choose Service</label>
                                                <select name="service" id="service" class="form-control" required>
                                                    <option value="">--none--</option>
                                                    @foreach($services as  $service)
                                                      <option value="{{ $service->id }}" name='service'>{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('service'))
                                                    <p class="help-block" style="color: red;">{{ $errors->first('service') }}</p>
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

@section('script')
    <script src="{{ asset('js/checkSamePhone.js') }}"></script>
@endsection