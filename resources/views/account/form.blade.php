@extends('layouts.master')
@section('title','create-customer')
@section('content')
<section id="custom-file-input">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Added Customer</h4>
                   
                    
                </div>
                <div class="card-content">
                    @if(session('api-response'))
                        <div class="bs-example">
                            <div class="alert alert-danger">                    
                                <strong>{{ session('api-response') }}</strong>                          
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <form autocomplete="off" action="{{ route('create') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="">
                                        <label for="name">Name <span class="asterike">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="contact_name" value="{{ old('contact_name') }}" id="name" required>
                                            @if($errors->has('contact_name'))
                                                <p class="" style="color: red;">{{ $errors->first('contact_name') }}</p>
                                            @endif
                                        </div>
                                        <label for="account">Company Name <span class="asterike">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="account" required>
                                            @if($errors->has('name'))
                                                <p class="" style="color: red;">Company Name has already been taken.</p>
                                            @endif
                                        </div>                                    
                                        <label for="email">Email <span class="asterike">*</span></label>
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                                            @if($errors->has('email'))
                                                <p class="" style="color: red;">{{ $errors->first('email') }}</p>
                                            @endif
                                        </div>
                                        <label for="password">Password <span class="asterike">*</span></label>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password" id="password" autocomplete="new-password" required>
                                            @if($errors->has('password'))
                                                <p class="" style="color: red;">{{ $errors->first('password') }}</p>
                                            @endif
                                        </div>
                                        <label for="number">Phone Number <span class="asterike">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="phone_number" id="number" value="{{ old('phone_number') }}" required>
                                            @if($errors->has('phone_number'))
                                                <p class="" style="color: red;">{{ $errors->first('phone_number') }}</p>
                                            @endif
                                        </div>
                                        <label for="address">Address <span class="asterike">*</span></label>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}" required>
                                            @if($errors->has('address'))
                                                <p class="" style="color: red;">{{ $errors->first('address') }}</p>
                                            @endif
                                        </div>
                                        <label for="logo">Logo</label>
                                        <div class="form-group">
                                            <input type="file" class="form-control" accept="image/png, image/jpg, image/jpeg" name="logo" id="logo" value="{{ old('logo') }}">
                                            @if($errors->has('logo'))
                                                <p class="" style="color: red;">{{ $errors->first('logo') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ url('fetch-account') }}" class="btn btn-secondary">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span  class="d-none d-sm-block">
                                                Back
                                            </span>
                                        </a>
                                        <button type="submit" class="btn btn-success">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
