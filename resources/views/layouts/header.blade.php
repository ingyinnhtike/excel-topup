<header class='mb-3'>
    <nav class="navbar navbar-expand navbar-light ">
        <div class="container-fluid">            
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="dropdown">
                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-menu d-flex">
                        @if(auth()->check())
                        <div class="user-name text-end me-3">
                            <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                            <p class="mb-0 text-sm text-gray-600">
                                @if (auth()->user()->role_id == 1)
                                    Customer
                                @else
                                    Admin
                                @endif
                            </p>
                        </div>
                        @endif
                        <div class="user-img d-flex align-items-center">
                            <div class="avatar avatar-md">
                                @if (auth()->user()->hasRole('admin'))
                                    <img src="{{ asset('images/logo/user_profile.png') }}">                                    
                                @else    
                                    @if (\App\Customer::where(['added_user_id' => auth()->user()->id])->first()->logo)
                                        <img src="{{ asset(\App\Customer::where(['added_user_id' => auth()->user()->id])->first()->logo) }}">
                                    @else
                                        <img src="{{ asset('images/logo/user_profile.png') }}">
                                        
                                    @endif                                
                                    
                                @endif
                               
                                
                            </div>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li>
                        <h6 class="dropdown-header">Hello, {{ auth()->user()->name }}!</h6>
                    </li>

                    <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
