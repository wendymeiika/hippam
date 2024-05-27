<?php

namespace App\Actions\Keluhan;

use App\Http\Requests\StoreBalasanRequest;
use Closure;

final class ReplyKeluhan
{
    public function handle(StoreBalasanRequest $request, Closure $next): StoreBalasanRequest
    {
        $request->user()->replies()->create([
            'id_keluhan' => $request->keluhan->id,
            'balasan' => $request->balasan,
        ]);

        return $next($request);
    }
}
