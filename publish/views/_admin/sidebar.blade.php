<aside class="main-sidebar sidebar-dark-primary elevation-4"> 
    <a href="{{ url('magic/home') }}" class="brand-link">
        <img src="/img/logo.png" alt="Laravel" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Laravel</span>
    </a> 
    <div class="sidebar"> 
        <div class="user-panel mt-2 pb-2 mb-3 d-flex">
            <div class="image">
                <img src="/img/avatar.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"> {{auth()->user()->name!=null ? auth()->user()->name : "Administrator"}} </a>
            </div>
        </div> 
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{!! url('admin/home') !!}" class="nav-link ">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li> 
                <li class="nav-header">ADMIN NAVIGATION</li>
                <li class="nav-item">
                    <a href="{{ url('admin/users') }}" class="nav-link ">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            User Management
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Settings
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('site.settings') }}" class="nav-link ">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>General</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('site/website') }}" class="nav-link ">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Website</p>
                            </a>
                        </li>  
                    </ul>
                </li>
            </ul>
        </nav> 
    </div> 
</aside>