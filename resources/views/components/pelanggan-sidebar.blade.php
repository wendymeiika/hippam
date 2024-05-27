@if ($permissions?->contains('name', 'Tambah Keluhan'))
<li class="has-submenu {{ (request()->is('keluhan*')) ? 'active' : '' }}">
    <a href="{{ url('/keluhan') }}"><i class="mdi mdi-book-open-page-variant"></i>Keluhan</a>
</li>
@endif


@if ($permissions?->contains('name', 'Tambah Pembayaran'))
<li class="has-submenu {{ (request()->is('pembayaran*')) ? 'active' : '' }}">
    <a href="{{ route('pembayaran.index') }}"><i class="mdi mdi-clipboard"></i>Pembayaran</a>
</li>
@endif

@if ($permissions?->contains('name', 'Tambah Keluhan'))
<x-notification-navbar />
@endif
