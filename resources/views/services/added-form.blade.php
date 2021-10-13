@extends('layouts.master')
@section('title','added-service')
@section('content')   
<section id="custom-file-input">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Added Services</h4>  
                    @if(session('service-status'))
                        <div class="bs-example">
                            <div class="alert alert-danger">
                                <strong>{{ session('service-status') }}</strong>
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
                                    <form class="role" action="{{ route('added.service') }}" method="post">
                                        @csrf
                                        <div class="box-body">                       
                                            <div class="form-group">
                                              <label for="service">Service Name</label>
                                              <input type="text" class="form-control" name="name" id="service">
                                                @if($errors->has('name'))
                                                <p class="help-block" style="color: red;">{{ $errors->first('name') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="amount">Amount</label>
                                                <input type="text" class="form-control" name="amount" id="amount">
                                                  @if($errors->has('amount'))
                                                  <p class="help-block" style="color: red;">{{ $errors->first('amount') }}</p>
                                                  @endif
                                              </div>                         
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