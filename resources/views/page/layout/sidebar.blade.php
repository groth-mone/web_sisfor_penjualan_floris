<?php
$currentRoute = optional(request()->route())->getName();
$isDataMaster = false;
$isMasterUser = false;
$isLaporan = false;

if (in_array($currentRoute, ['index.kategori','index.barang','index.produk','index.supplier'])) {
  $isDataMaster = true;
}
if (in_array($currentRoute, ['index.pengelola','index.pelanggan'])) {
  $isMasterUser = true;
}
if (in_array($currentRoute, ['laporan.pembelian','laporan.penjualan','laporan.barang'])) {
  $isLaporan = true;
}
?>
<div class="app-brand demo">
  <a href="javascript:void(0)" class="app-brand-link mb-2" style="display: flex; align-items: center;">
    <div class="app-brand-logo demo" style="margin-left: -15px;">
     @foreach($userprofil as $usp)
     @if($usp->foto == NULL)
     <img src="{{asset('thumbnail.png')}}" alt class="rounded-circle" style="width: 50px;height: 50px;" />
     @else
     <img src="{{asset($path_foto)}}/{{$usp->foto}}" alt class="rounded-circle" style="width: 50px;height: 50px;" />
     @endif
     @endforeach
   </div>
   <div class="app-brand-text menu-text">
    <span class="user-name">{{implode(" ", array_slice(explode(" ",Auth::user()->name),0,2))}}</span>
    <span class="user-email mt-2">{{ Auth::user()->level }}</span>
  </div>
</a>

<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
  <i class="icon-base bx bx-chevron-left"></i>
</a>

<!-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
  <i class="bx bx-chevron-left bx-sm align-middle"></i>
</a> -->
</div>


<div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1">
 <li class="menu-header small text-uppercase">
  <span class="menu-header-text">Dashboard</span>
</li>
<li class="menu-item {{ (route('index.dashboard') == url()->current()) ? ' active' : '' }}">
  <a
  href=" {{route('index.dashboard')}} "
  class="menu-link">
  <i class="menu-icon tf-icons bx bx-home"></i>
  <div data-i18n="">Dashboard</div>
</a>
</li>
<!--  -->
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Data Master</span>
</li>
<li class="menu-item{{ $isDataMaster ? ' active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-package"></i>
    <div data-i18n="Dashboards">Master Inventaris</div>
    <div class="badge bg-label-primary rounded-pill ms-auto">4</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item {{ (route('index.kategori') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.kategori')}}" class="menu-link">
        <div data-i18n="">Kategori</div>
      </a>
    </li>
    <li class="menu-item {{ (route('index.barang') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.barang')}}" class="menu-link">
        <div data-i18n="">Bahan</div>
      </a>
    </li>
    <li class="menu-item {{ (route('index.produk') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.produk')}}" class="menu-link">
        <div data-i18n="">Produk</div>
      </a>
    </li>
    <li class="menu-item {{ (route('index.supplier') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.supplier')}}" class="menu-link">
        <div data-i18n="">Supplier</div>
      </a>
    </li>
  </ul>
</li>
<li class="menu-item{{ $isMasterUser ? ' active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-user"></i>
    <div data-i18n="Dashboards">Master User</div>
    <div class="badge bg-label-primary rounded-pill ms-auto">{{ Auth::user()->level == 'Owner' ? 1 : 1 }}</div>
  </a>
  <ul class="menu-sub">
    @if(Auth::user()->level == 'Owner')
    <li class="menu-item {{ (route('index.pengelola') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.pengelola')}}" class="menu-link">
        <div data-i18n="">Pengelola</div>
      </a>
    </li>
    @endif
  </ul>
</li>
<!--  -->
<!--  -->
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Transaksi</span>
</li>
<li class="menu-item {{ (route('index.pembelian') == url()->current()) ? ' active' : '' }}">
  <a
  href=" {{route('index.pembelian')}} "
  class="menu-link">
  <i class="menu-icon tf-icons bx bx-receipt"></i>
  <div data-i18n="">Pembelian</div>
</a>
</li>
<li class="menu-item {{ (route('index.penjualan') == url()->current()) ? ' active' : '' }}">
  <a
  href=" {{route('index.penjualan')}} "
  class="menu-link">
  <i class="menu-icon tf-icons bx bx-cart"></i>
  <div data-i18n="">Penjualan</div>
</a>
</li>
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Laporan</span>
</li>
<li class="menu-item{{ $isLaporan ? ' active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-file"></i>
    <div data-i18n="Account Settings">Laporan</div>
    <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">3</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item {{ (route('laporan.pembelian') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('laporan.pembelian')}}" class="menu-link">
        <div data-i18n="Account">Pembelian</div>
      </a>
    </li>
    <li class="menu-item {{ (route('laporan.penjualan') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('laporan.penjualan')}}" class="menu-link">
        <div data-i18n="Notifications">Penjualan</div>
      </a>
    </li>
    <li class="menu-item {{ (route('laporan.barang') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('laporan.barang')}}" class="menu-link">
        <div data-i18n="Connections">Bahan</div>
      </a>
    </li>
  </ul>
</li>
</ul>
