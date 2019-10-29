<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU UTAMA</li>
            <!-- Optionally, you can add icons to the links -->
            <li><a href="#" class="nav-link">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="{{ (request()->is('admin/consultation')) ? 'active' : '' }}"><a href="{{URL::to('admin/consultation')}}" class="nav-link">
                <i class="fa fa-book"></i> <span>Data Konsultasi</span></a></li>
            <li class="{{ (request()->is('admin/pelaporan')) ? 'active' : '' }}"><a href="{{URL::to('admin/pelaporan')}}" class="nav-link">
                <i class="fa fa-book"></i> <span>Data Pelaporan</span></a></li>        
            <li class="treeview {{ (request()->is('admin/import')) ? 'active' : '' }} {{ (request()->is('admin/apoteker')) ? 'active' : '' }} {{ (request()->is('admin/konseli')) ? 'active' : '' }}">
                <a href="#"><i class="fa fa-users"></i> <span>Manajemen User</span>
                  <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                  <li class="{{ (request()->is('admin/import')) ? 'active' : '' }}"><a href="{{URL::to('admin/import')}}">Import Apoteker</a></li>
                  <li class="{{ (request()->is('admin/apoteker')) ? 'active' : '' }}"><a href="{{URL::to('admin/apoteker')}}">Apoteker</a></li>
                  <li class="{{ (request()->is('admin/konseli')) ? 'active' : '' }}"><a href="{{URL::to('admin/konseli')}}">Konseli</a></li>
                </ul>
              </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>