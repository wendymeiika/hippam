<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PembayaranRecource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            
                'id' => $this -> id,
                'bulan' => $this -> bulan,
                'tgl bayar' => $this -> created_at,
                'bukti' => $this -> bukti,

        ];
    }
}
