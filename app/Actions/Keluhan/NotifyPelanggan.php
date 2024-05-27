<?php

namespace App\Actions\Keluhan;

use App\Http\Requests\StoreBalasanRequest;
use Closure;

final class NotifyPelanggan
{
    public function handle(StoreBalasanRequest $request, Closure $next): StoreBalasanRequest
    {
        $request->keluhan->user->notifications()->create([
            'nama' => $request->keluhan->user->nama,
            'tlp' => $request->keluhan->user->tlp,
            'type' => 'keluhan',
            'pesan' => 'Balasan atas keluhan Anda telah diterima. Silakan cek balasan pada menu keluhan.',
        ]);

        return $next($request);
    }
}
