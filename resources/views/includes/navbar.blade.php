<nav class="pcoded-navbar menupos-fixed menu-light brand-blue">
    <div class="navbar-wrapper  ">
        <div class="navbar-content scroll-div ">

            <div class="">
                <div class="main-menu-header">
                    <img class="img-radius" src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="User-Profile-Image">
                    <div class="user-details">
                        <div id="more-details">{{ Auth::user()->username }} <i class="fa fa-caret-down"></i></div>
                    </div>
                </div>
                <div class="collapse" id="nav-user-link">
                    <ul class="list-inline">
                        {{-- <li class="list-inline-item"><a href="user-profile.html" data-toggle="tooltip"
                                title="View Profile"><i class="feather icon-user"></i></a></li>
                        <li class="list-inline-item"><a href="email_inbox.html"><i class="feather icon-mail"
                                    data-toggle="tooltip" title="Messages"></i><small
                                    class="badge badge-pill badge-primary">5</small></a></li> --}}
                        <li class="list-inline-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                data-toggle="tooltip" title="Logout" class="text-danger">
                                <i class="feather icon-power"></i>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <ul class="nav pcoded-inner-navbar ">
                <li class="nav-item pcoded-menu-caption">
                    <label>Navigasi</label>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Dashboard</span>
                    </a>
                </li>
                @if(Auth::check() && Auth::user()->role === 'owner')
                <li class="nav-item">
                    <a href="{{ route('perusahaan.index') }}" class="nav-link ">
                        <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                        <span class="pcoded-mtext">Tentang Perusahaan</span>
                    </a>    
                </li>
                @endif

                @if(Auth::check() && Auth::user()->role === 'owner')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-book"></i></span><span class="pcoded-mtext">Chart Of
                            Account</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{ route('coa-kelompok.index') }}">Kelompok COA</a></li>
                        <li><a href="{{ route('coa.index') }}">COA</a></li>
                    </ul>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-github"></i></span><span class="pcoded-mtext">Pegawai</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{ route('jabatan.index') }}">Jabatan</a></li>
                        <li><a href="{{ route('pegawai.index') }}">Pegawai</a></li>
                        <li><a href="{{ route('presensi.index') }}">Presensi</a></li>
                        {{-- <li><a href="pegawai/penggajian">Penggajian</a></li> --}}
                    </ul>
                </li>
                @endif

                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-server"></i></span><span class="pcoded-mtext">Masterdata</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
                        <li><a href="{{ route('aset.index') }}">Asset</a></li>
                        <li><a href="{{ route('barang.index') }}">Barang</a></li>
                        <li><a href="{{ route('diskon.index') }}">Diskon</a></li>
                        {{-- <li><a href="{{ route('stok-produk.index') }}">Stok Produk</a></li> --}}
                    </ul>
                </li>

                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-package"></i></span><span class="pcoded-mtext">Produk</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{ route('kategori-produk.index') }}">Kategori Produk</a></li>
                        <li><a href="{{ route('produk.index') }}">Produk</a></li>
                        <li><a href="{{ route('supplier.index') }}">Supplier</a></li>
                        <li><a href="{{ route('produk.kartustok') }}">Kartu Stok</a></li>
                        {{-- <li><a href="{{ route('stok-produk.index') }}">Stok Produk</a></li> --}}
                    </ul>
                </li>

                 <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-credit-card"></i></span><span
                            class="pcoded-mtext">Transaksi</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{ route ('pembelian.index')}}">Pembelian Barang Dagang</a></li>
                        <li><a href="{{ route ('penjualan.index')}}">Penjualan</a></li>
                        @if(Auth::check() && Auth::user()->role === 'owner')
                        <li><a href="{{ route ('penggajian.index') }}">Penggajian</a></li>
                        @endif
                        <li><a href="{{ route ('beban.index') }}">Beban dan Pengeluaran Lainnya</a></li>

                    </ul>
                </li>
                <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i
                                class="feather icon-clipboard"></i></span><span class="pcoded-mtext">Laporan</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{ route ('jurnal-umum.index')}}">Jurnal Umum</a></li>
                        <li><a href="{{ route ('buku-besar')}}">Buku Besar</a></li>
                        <li><a href="{{ route ('neraca-saldo')}}">Neraca Saldo</a></li>
                        <li><a href="{{ route ('laba-rugi.index')}}">Laporan Laba Rugi</a></li>
                        <li><a href="{{ route ('perubahan-modal.index')}}">Laporan Perubahan Modal</a></li>
                        <li><a href="{{ route ('neraca.index')}}">Laporan Neraca</a></li>
                        <li><a href="{{ route ('cashflow')}}">Laporan Cash Flow</a></li>
                        <li><a href="{{ route('rekap_hutang') }}">Rekapitulasi Hutang</span></a></li>

                        {{-- <li><a href="laporan/neraca">Neraca Saldo</a></li>
                        <li><a href="laporan/laba_rugi">Laporan Laba Rugi</a></li>
                        <li><a href="laporan/perubahan_modal">Laporan Perubahan Modal</a></li>
                        <li><a href="laporan/laporan_neraca">Laporan Neraca</a></li>
 --}}
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


