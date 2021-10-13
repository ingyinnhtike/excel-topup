<div class="sidebar-wrapper active">
    <div class="sidebar-header">
        <div class="d-flex justify-content-between">
            @if(auth()->check())
            <div class="logo">
                <a href="{{url('/home')}}">Topup Gate</a>
            </div>
            @endif
            <div class="toggler">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
            <li class="sidebar-title">Menu</li>

            <li class="sidebar-item {{ request()->is('home') ? 'active' : '' }}">
                <a href="{{url('/home')}}" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @if(auth()->user()->hasRole('admin'))
            <!------------------------------------------------------------------------------------->
            <li class="sidebar-item  has-sub {{ request()->is('all-bill-request') ? 'active' : '' }} {{ request()->is('all-data-request') ? 'active' : '' }}">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-stack"></i>
                    <span>Top-up</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item {{ request()->is('all-bill-request') ? 'active' : '' }}">
                        <a href="{{ url('all-bill-request') }}">Bill Top-up</a>
                    </li>
                    <li class="submenu-item {{ request()->is('all-data-request') ? 'active' : '' }}">
                        <a href="{{url('all-data-request')}}">Data Top-up</a>
                    </li>
                </ul>
            </li> 

            <li class="sidebar-item  has-sub {{ request()->is('all-bill-processed') ? 'active' : '' }} {{ request()->is('all-data-processed') ? 'active' : '' }}">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Logs</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item {{ request()->is('all-bill-processed') ? 'active' : '' }}">
                        <a href="{{ url('all-bill-processed') }}">Bill Log</a>
                    </li>
                    <li class="submenu-item {{ request()->is('all-data-processed') ? 'active' : '' }}">
                        <a href="{{ url('all-data-processed') }}">Data Log</a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-title">Forms &amp; Tables</li>

            <li class="sidebar-item {{ request()->is('all-batches') ? 'active' : '' }}">
                <a href="{{ url('all-batches') }}" class='sidebar-link'>
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>All Batches</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('fetch-account') ? 'active' : '' }}">
                <a href="{{ url('fetch-account') }}" class='sidebar-link'>
                    <i class="bi bi-hexagon-fill"></i>
                    <span>Setting</span>
                </a>
            </li>
            <!------------------------------------------------------------------------------------->
            @else
            <li class="sidebar-item  has-sub {{ request()->is('my-bill-request') ? 'active' : '' }} {{ request()->is('my-data-request') ? 'active' : '' }}">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-stack"></i>
                    <span>Top-up</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item {{ request()->is('my-bill-request') ? 'active' : '' }}">
                        <a href="{{ url('my-bill-request') }}">Bill Top-up</a>
                    </li>
                    <li class="submenu-item {{ request()->is('my-data-request') ? 'active' : '' }}">
                        <a href="{{url('my-data-request')}}">Data Top-up</a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item  has-sub {{ request()->is('my-bill-processed') ? 'active' : '' }} {{ request()->is('my-data-processed') ? 'active' : '' }}">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Logs</span>
                </a>
                <ul class="submenu ">
                    <li class="submenu-item {{ request()->is('my-bill-processed') ? 'active' : '' }}">
                        <a href="{{ url('my-bill-processed') }}">Bill Log</a>
                    </li>
                    <li class="submenu-item {{ request()->is('my-data-processed') ? 'active' : '' }}">
                        <a href="{{ url('my-data-processed') }}">Data Log</a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item {{ request()->is('my-batches') ? 'active' : '' }}">
                <a href="{{ url('my-batches') }}" class='sidebar-link'>
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>My Batches</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
</div>
