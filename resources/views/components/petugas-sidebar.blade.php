@if ($permissions?->contains('name', 'Tambah Pelanggan'))
<li class="has-submenu {{ (request()->is('user*')) ? 'active' : '' }}">
    <a href="{{ url('/user') }}"><i class="mdi mdi-account-plus"></i>User</a>
</li>
@endif

@if ($permissions?->contains('name', 'Validasi'))
<li class="has-submenu {{ (request()->is('pembayaran*')) ? 'active' : '' }}">
    <a href="{{ url('/pembayaran') }}"><i class="mdi mdi-clipboard"></i>Pembayaran</a>
</li>
@endif

@if ($permissions?->contains('name', 'Baca Notifikasi'))
<li class="has-submenu {{ (request()->is('notifikasi*')) ? 'active' : '' }}">
    <a href="{{ url('/notifikasi') }}"><i class="mdi mdi-bell"></i>Notifikasi</a>
</li>
@endif

@if ($permissions?->contains('name', 'Tambah Pengumuman'))
<li class="has-submenu {{ (request()->is('pengumuman*')) ? 'active' : '' }}">
    <a href="{{ url('/pengumuman') }}"><i class="mdi mdi-bullhorn"></i>Pengumuman</a>
</li>
@endif

@if ($permissions?->contains('name', 'Baca Seluruh Riwayat'))
<li class="has-submenu {{ (request()->is('laporan*')) ? 'active' : '' }}">
    <a href="{{ url('/laporan') }}"><i class="mdi mdi-file-chart"></i>Laporan</a>
</li>
@endif

@if ($permissions?->contains('name', 'Balas Keluhan'))
<li class="has-submenu {{ (request()->routeIs('keluhan.*')) ? 'active' : '' }}">
    <a href="{{ route('keluhan.index') }}"><i class="mdi mdi-file-chart"></i>Keluhan</a>
</li>
@endif
