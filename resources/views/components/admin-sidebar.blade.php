@if ($permissions->contains('name', 'Atur izin akses'))
<li class="has-submenu {{ (request()->routeIs('role-user.*')) ? 'active' : '' }}">
    <a href="{{ route('role-user.index') }}"><i class="mdi mdi-account-plus"></i>Role</a>
</li>

<li class="has-submenu {{ (request()->routeIs('role.*')) ? 'active' : '' }}">
    <a href="{{ route('role.index') }}"><i class="mdi mdi-file-chart"></i>Hak Izin</a>
</li>
@endif
