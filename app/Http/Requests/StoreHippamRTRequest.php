<?php

namespace App\Http\Requests;

use App\Enums\Bulan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHippamRTRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role->permissions()->where('name', 'Bayar lewat Ketua RT')->exists();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'pelanggan' => User::query()->whereHas(
                'role', fn (Builder $query) => $query->where('name', 'pelanggan')
            )->where('rt', $this->user()->rt)
                ->where('rw', $this->user()->rw)
                ->firstOrFail(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // pelanggan wajib ada di tabel user, role pelanggan, rt dan rw sama dengan ketuart
            'id_pelanggan' => [
                'required',
                Rule::unique(Pembayaran::class, 'id_pelanggan')
                    ->where(
                        fn ($query) => $query->where('bulan', (int) $this->bulan)
                            ->where('tahun', date('Y'))
                    ),
            ],
            // bulan: wajib, angka, minimal 1, maksimal bulan saat ini
            'bulan' => ['required', Rule::in(array_column(Bulan::cases(), 'value')), 'lte:'.date('m')],
        ];
    }

    public function messages(): array
    {
        return [
            'id_pelanggan.required' => 'Pelanggan wajib diisi',
            'id_pelanggan.exists' => 'Pelanggan tidak ditemukan',
            'id_pelanggan.unique' => 'Pembayaran untuk bulan ini sudah dilakukan',
            'bulan.required' => 'Bulan wajib diisi',
            'bulan.in' => 'Bulan tidak valid',
            'bulan.lte' => 'Bulan tidak boleh lebih dari bulan saat ini',
        ];
    }
}
