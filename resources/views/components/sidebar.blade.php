<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}"> <img src="{{ asset('img/IDTV.png') }}" alt="logo" width="90"></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}"> <img src="{{ asset('img/IDTV.png') }}" alt="logo" width="50"></a>
        </div>
        <ul class="sidebar-menu">

            @can('read-dashboard')
            <li class="menu-header">Dashboard</li>
            <li class="{{ $type_menu === 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link"><i
                        class="fas fa-desktop"></i><span>Dashboard</span></a>
            </li>
            @endcan

            @canany(['read-stb', 'read-region','read-paket','read-bank'])
            <li class="menu-header">DATA MASTER</li>
            <li class="nav-item dropdown {{ $type_menu === 'master' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Data Master</span></a>
                <ul class="dropdown-menu">
                    @can('read-stb')
                    <li class="{{ Request::is('admin/master/stb') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('stb') }}">Stb</a>
                    </li>
                    @endcan
                    @can('read-region')
                    <li class="{{ Request::is('admin/master/region') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('region') }}">Area</a>
                    </li>
                    @endcan
                    @can('read-bank')
                    <li class="{{ Request::is('admin/master/bank') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('bank') }}">Data Bank</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @can('read-paket')
            <li class="{{ Request::is('admin/master/paket') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('paket') }}"><i class="fas fa-box">
                    </i> <span>Management Paket</span>
                </a>
            </li>
            @endcan
            @endcanany

            @canany(['read-chanel', 'read-categori', 'read-company', 'read-owner', 'read-customer','read-genre',
            'read-movie'])
            <li class="menu-header">MANAGEMENT</li>
            @canany(['read-chanel', 'read-categori'])
            <li class="nav-item dropdown {{ $type_menu === 'layout' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Chanel Management</span></a>
                <ul class="dropdown-menu">
                    @can('read-chanel')
                    <li class="{{ Request::is('admin/chanel-management/chanel') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('chanel') }}">Chanel</a>
                    </li>
                    @endcan
                    @can('read-categori')
                    <li class="{{ Request::is('admin/chanel-management/categori') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('categori-chanel') }}">Kategori</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany
            @canany(['read-genre', 'read-movie'])
            <li class="nav-item dropdown {{ $type_menu === 'movie' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-film"></i>
                    <span>Movie Management</span></a>
                <ul class="dropdown-menu">
                    @can('read-movie')
                    <li class="{{ Request::is('admin/movie') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('movie') }}">Movie</a>
                    </li>
                    @endcan
                    @can('read-genre')
                    <li class="{{ Request::is('admin/movie/genre') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('genre') }}">Genre</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany
            @can('read-company')
            <li class="{{ Request::is('admin/company') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('company') }}"><i class="fas fa-building">
                    </i> <span>Perusahaan</span>
                </a>
            </li>
            @endcan
            @canany(['read-reseller', 'read-owner'])
            <li class="nav-item dropdown {{ $type_menu === 'reseller' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-building"></i>
                    <span>Reseller Management</span></a>
                <ul class="dropdown-menu">
                    @can('read-reseller')
                    <li class="{{ Request::is('admin/reseller-management/reseller') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('resellerdata') }}">Reseller</a>
                    </li>
                    @endcan
                    @can('read-company')
                    <li class="{{ Request::is('admin/company') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('company') }}">Paket Reseller</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany
            
            @can('read-customer')
            <li class="{{ Request::is('admin/customer') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('customer') }}"><i class="fas fa-people-group">
                    </i> <span>Customers</span>
                </a>
            </li>
            @endcan
            @can('read-curentstream')
            <li class="{{ Request::is('admin/curentstream') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('curentstream') }}"><i class="fas fa-video">
                    </i> <span>Curent Stream Customer</span>
                </a>
            </li>
            @endcan
            @if (Auth::user()->hasRole('Reseller'))
            <li class="{{ Request::is('admin/pendapatan') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pendapatan.reseller') }}"><i class="fas fa-hourglass">
                    </i> <span>Pendapatan</span>
                </a>
            </li>
            @endif
            @endcanany


            @canany(['read-company',
            'read-owner','read-statistik','read-income-harian','read-income-periode','read-feeclaim'])
            <li class="menu-header">KEUNGAN</li>
            <li class="nav-item dropdown {{ $type_menu === 'Keuangan' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-wallet"></i>
                    <span>Data Keuangan</span></a>
                <ul class="dropdown-menu">
                    @can('read-tagihan')
                    <li class="{{ Request::is('admin/keuangan/tagihan') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('tagihan') }}">Tagihan Pelanggan</a>
                    </li>
                    @endcan
                    @can('read-income-harian')
                    <li class="{{ Request::is('admin/keuangan/income-harian') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('dailyincome') }}">Income Harian</a>
                    </li>
                    @endcan
                    @can('read-income-periode')
                    <li class="{{ Request::is('admin/keuangan/income-periode') ? 'active' : '' }}">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#showmodalkeu">Income
                            Periode</a>
                    </li>
                    @endcan
                    @can('read-statistik')
                    <li class="{{ Request::is('admin/keuangan/statistik') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('statistik') }}">Statistik</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @can('read-feeclaim')
            <li class="{{ Request::is('admin/keuangan/fee-claim') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('feeclaim') }}"><i class="fas fa-hourglass">
                    </i> <span>Fee Reseller</span>
                </a>
            </li>
            @endcan
            @endcanany

            @canany(['read-users', 'read-role','read-log','read-version_control'])
            <li class="menu-header">SETTINGS</li>
            <li class="nav-item dropdown {{ $type_menu === 'setting' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-gear"></i>
                    <span>Settings</span></a>
                <ul class="dropdown-menu">
                    @can('read-role')
                    <li class="{{ Request::is('admin/settings/role') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('role') }}">Role</a>
                    </li>
                    @endcan
                    @can('read-users')
                    <li class="{{ Request::is('admin/settings/user') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user') }}">User</a>
                    </li>
                    @endcan
                    @can('read-log')
                    <li class="{{ Request::is('admin/settings/log-activity') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('log') }}">Log Activity</a>
                    </li>
                    @endcan
                    @can('read-version_control')
                    <li class="{{ Request::is('admin/settings/version-control') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('versioncontrol') }}">Control Versi Aplikasi</a>
                    </li>
                    @endcan

                </ul>
            </li>
            @endcanany
        </ul>

    </aside>
</div>