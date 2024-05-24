<?php

namespace App\View\Components;

use App\Models\Notifikasi;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationNavbar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notification-navbar', [
            'total' => Notifikasi::query()
                ->when(
                    Auth::user()->role->name === 'petugas',
                    fn (Builder $query) => $query->where('petugas', 1),
                    fn (Builder $query) => $query->where('id_pelanggan', Auth::user()->id)
                        ->where('petugas', false)
                )
                ->where('read', 0)
                ->count(),
        ]);
    }
}
