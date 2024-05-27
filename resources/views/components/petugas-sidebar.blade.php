@if ($permissions?->contains('name', 'Validasi'))
<li class="has-submenu {{ (request()->is('user*')) ? 'active' : '' }}">
    <a href="{{ url('/user') }}"><i class="mdi mdi-account-plus"></i>User</a>
</li>
@endif

@if ($permissions?->contains('name', 'Validasi'))
<li class="has-submenu {{ (request()->is('pembayaran*')) ? 'active' : '' }}">
    <a href="{{ url('/pembayaran') }}"><i class="mdi mdi-clipboard"></i>Pembayaran</a>
</li>
@endif

@if ($permissions?->contains('name', 'Validasi'))
<x-notification-navbar />
@endif

@if ($permissions?->contains('name', 'Tambah Pengumuman'))
<li class="has-submenu {{ (request()->is('pengumuman*')) ? 'active' : '' }}">
    <a href="{{ url('/pengumuman') }}"><i class="mdi mdi-bullhorn"></i>Pengumuman</a>
</li>
@endif

{{-- @if ($permissions?->contains('name', 'Baca Seluruh Riwayat'))
<li class="has-submenu {{ (request()->is('laporan*')) ? 'active' : '' }}">
    <a href="{{ url('/laporan') }}"><i class="mdi mdi-file-chart"></i>Laporan</a>
</li>
@endif --}}

@if ($permissions?->contains('name', 'Baca Seluruh Riwayat'))
<li class="has-submenu {{ (request()->is('laporan*')) ? 'active' : '' }}">
    <a href="#"><i class="mdi mdi-file-chart"></i>Laporan</a>
    <ul class="submenu megamenu">
        <li>
            <ul>
                <li><a href="{{ url('/laporan') }}">Laporan Sudah Bayar</a></li>
                <li><a href="{{ url('/laporan/list-belum-bayar') }}">Laporan Belum Bayar</a></li>
            </ul>
        </li>
    </ul>
</li>
@endif

@if ($permissions?->contains('name', 'Balas Keluhan'))
<li class="has-submenu {{ (request()->routeIs('keluhan.*')) ? 'active' : '' }}">
    <a href="{{ route('keluhan.index') }}"><i class="mdi mdi-file-chart"></i>Keluhan</a>
</li>
@endif
