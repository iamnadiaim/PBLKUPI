<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('landingPage')}}">
    <div class="sidebar-brand-icon rotate-n-30">
      {{-- <i class="fas fa-laugh-wink"></i> --}}
      <img src="{{ asset('images/kupi.png') }}" style="width: 50px; height: 50px;" />
    </div>
    <div class="sidebar-brand-text mx-3">Keuangan Pintar</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('produks.index') }}">
      <i class="fab fa-product-hunt"></i>
      <span>Produk</span></a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('pendapatans') }}">
      <i class="fas fa-arrow-up "></i>
      <span>Pendapatan</span></a>
</li>

@if (auth()->user()->role->nama_role == 'admin')
<li class="nav-item">
    <a class="nav-link" href="{{ route('beban.index') }}">
      <i class="fas fa-arrow-down"></i>
      <span>Pengeluaran</span></a>
</li>
@endif
<li class="nav-item">
  <a class="nav-link" href="{{ route('riwayat.index') }}">
    <i class="fas fa-book"></i>
    <span>Riwayat Transaksi</span></a>
  </li>
  @if (auth()->user()->role->nama_role == 'admin')
<li class="nav-item">
    <a class="nav-link" href="{{ route('labarugi.index') }}">
      <i class="fas fa-money-bill-wave"></i>
      <span>Laba Rugi</span></a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('aruskas.index') }}">
      <i class="fas fa-exchange-alt"></i>
      <span>Arus Kas</span></a>
</li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('hutang.index') }}">
      <i class="fas fa-arrow-down"></i>
      <span>Hutang Piutang</span></a>
</li>


<li class="nav-item">
  <a class="nav-link" href="{{ route('daftarPegawai') }}">
    <i class="fas fa-user"></i>
    <span>  Pegawai</span></a>
  </li>
  @endif

  <!-- Sidebar Toggler (Sidebar) -->
  <hr class="sidebar-divider d-none d-md-block">
  <!-- Divider -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>


</ul>
