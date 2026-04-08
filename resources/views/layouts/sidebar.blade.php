<aside class="main-sidebar sidebar-dark-primary elevation-4">


    <!-- Sidebar -->
    <div class="sidebar">

        <div class="user-panel mt-3 pb-2 mb-3 d-flex align-items-center">
            <div class="image">
                {{-- <img src="{{ asset('favicon.png') }}" class="img-circle elevation-2"
                    style="width:30px; height:30px; object-fit:cover;" alt="Logo"> --}}
            </div>
            <div class="info ml-2">
                <a href="{{ url('/') }}" class="d-block font-weight-bold text-light">Tally</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/*') ? 'active' : '' }} text-light">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Item -->
                <li class="nav-item">
                    <a href="{{ url('tally') }}"
                        class="nav-link {{ request()->is('tally*') || request()->is('tujuan*') || request()->is('timbangan*') ? 'active' : '' }} text-light">
                        <i class="nav-icon fas fa-balance-scale"></i>
                        <p>Tally</p>
                    </a>
                </li>

                <!-- Data -->
                <li
                    class="nav-item has-treeview {{ request()->is('produk*') || request()->is('user*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('produk*') || request()->is('user*') ? 'active' : '' }} text-light">
                        <i class="nav-icon fas fa-database"></i>
                        <p>Data <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('produk') }}"
                                class="nav-link {{ request()->is('produk*') ? 'active' : '' }} text-light">
                                <i class="fas fa-tags nav-icon"></i>
                                <p>Item</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('user') }}"
                                class="nav-link {{ request()->is('user*') ? 'active' : '' }} text-light">
                                <i class="fas fa-user nav-icon"></i>
                                <p>User</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
