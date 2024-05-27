<!-- Because you are alive, everything is possible. - Thich Nhat Hanh -->
<li class="has-submenu {{ (request()->is('notifikasi*')) ? 'active' : '' }}">
    <a href="{{ url('/notifikasi') }}">
        <i class="mdi mdi-bell"></i>
        Notifikasi
        @if ($total)
            <span class="badge badge-pill badge-primary">{{ $total }}</span>
        @endif
    </a>
</li>
