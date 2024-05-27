
@if ($permissions->contains('name', 'Atur izin akses'))
<li class="has-submenu {{ (request()->routeIs('role-user.*')) ? 'active' : '' }}">
    <a href="{{ route('role-user.index') }}"><i class="mdi mdi-account-plus"></i>Role</a>
</li>
@endif

@if ($permissions->contains('name', 'Atur izin akses'))
<li class="has-submenu {{ (request()->routeIs('role.*')) ? 'active' : '' }}">
    <a href="{{ route('role.index') }}"><i class="mdi mdi-file-chart"></i>Hak Izin</a>
</li>
@endif

{{-- @if ($permissions?->contains('name', 'Baca Seluruh Riwayat'))
<li class="has-submenu {{ (request()->is('laporan*')) ? 'active' : '' }}">
    <a href="{{ url('/laporan') }}"><i class="mdi mdi-file-chart"></i>Laporan</a>
</li>
@endif --}}

{{-- @if ($permissions?->contains('name', 'Baca Seluruh Riwayat'))
<li class="has-submenu {{ (request()->is('laporan*')) ? 'active' : '' }}">
    <a href="#"><i class="mdi mdi-file-chart"></i>Laporan</a>
    <ul class="submenu megamenu">
        <li>
            <ul>
                <li><a href="{{ url('/laporan') }}">Laporan Sudah Bayar</a></li>
                <li><a href="{{ url('/laporan/list-belum') }}">Laporan Belum Bayar</a></li>
            </ul>
        </li>
    </ul>
</li>
@endif --}}


