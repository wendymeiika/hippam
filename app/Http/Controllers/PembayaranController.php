<?php

namespace App\Http\Controllers;

use App\Enums\Bulan;
use App\Http\Requests\StoreHippamRTRequest;
use App\Http\Requests\UpdatePembayaranValidationRequest;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class PembayaranController extends Controller
{
    public function index(Request $request): View
    {
        return view($request->user()->role->permissions()->where('name', 'Validasi')->exists()
        ? 'pembayaran.list-petugas'
        : 'pembayaran.list-pelanggan');
    }

    public function list(): JsonResponse
    {
        return DataTables::of(Pembayaran::orderBy('id', 'desc')->get())
            ->addIndexColumn()
            ->make(true);
    }

    public function bayar(Request $request): View
    {
        return match ($request->user()->role->name) {
            'pelanggan' => view('pembayaran.bayar'),
            'ketuart' => view('pembayaran.bayar-by-rt', [
                'pelanggan' => User::query()
                    ->whereHas('role', fn (Builder $query) => $query->where('name', 'pelanggan'))
                    ->where('rt', $request->user()->rt)
                    ->where('rw', $request->user()->rw)
                    ->get(),
                'bulan' => Bulan::cases(),
            ]),
        };
    }

    public function bayarHippam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulan' => 'required',
            'bukti' => 'required|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $bulan_sekarang = date('m');
        $tahun_sekarang = date('Y');

        if ($request->bulan > $bulan_sekarang) {
            return back()->with('error', 'Bulan yang dipilih melebihi bulan sekarang.');
        }

        $check_pembayaran = Pembayaran::query()
            ->where('id_pelanggan', $request->user()->id)
            ->where('tahun', $tahun_sekarang)
            ->where('bulan', $request->bulan)
            ->exists();

        if ($check_pembayaran) {
            return back()->with('error', 'Pembayaran sudah dilakukan, lihat di riwayat.');
        }

        $path = 'images/bukti/';
        $bukti = uploads($request->bukti, $path);

        Pembayaran::create([
            'id_pelanggan' => $request->user()->id,
            'nama' => $request->user()->nama,
            'tlp' => $request->user()->tlp,
            'alamat' => $request->user()->alamat,
            'bulan' => $request->bulan,
            'tahun' => $tahun_sekarang,
            'bukti' => $bukti,
            'status' => 'waiting',
        ]);

        Notifikasi::create([
            'id_pelanggan' => $request->user()->id,
            'nama' => $request->user()->nama,
            'tlp' => $request->user()->tlp,
            'type' => 'pembayaran',
            'pesan' => 'Pembayaran hippam bulan '.bulan_indo($request->bulan).' '.$tahun_sekarang,
            'petugas' => 1,
        ]);

        return back()->with('success', 'Pembayaran berhasil, tunggu validasi admin.');
    }

    // public function bayarHippamByRT(StoreHippamRTRequest $request)
    // {
    //     $request->pelanggan->pembayarans()->create([
    //         'bulan' => $request->bulan,
    //         'tahun' => date('Y'),
    //         'status' => 'waiting',
    //         'nama' => $request->pelanggan->nama,
    //         'tlp' => $request->pelanggan->tlp,
    //         'alamat' => $request->pelanggan->alamat,
    //     ]);

    //     $request->pelanggan->notifications()->create([
    //         'nama' => $request->pelanggan->nama,
    //         'tlp' => $request->pelanggan->tlp,
    //         'type' => 'pembayaran',
    //         'pesan' => 'Pembayaran hippam bulan '.Bulan::from($request->bulan)->name.' '.date('Y').' telah diproses oleh Ketua RT.',
    //         'petugas' => $request->user()->id, // ID Ketua RT yang masuk
    //     ]);

    //     return back()->with('success', 'Pembayaran berhasil diproses.');

    //     // menampilkan bulan user durung bayar
    //     // User::query()
    //     //     ->where('rt', Auth::user()->rt)
    //     //     ->where('rw', Auth::user()->rw)
    //     //     ->whereDoesntHave(
    //     //         'pembayarans',
    //     //         fn (Builder $query) => $query->where('bulan', date('m'))->where('tahun', date('Y'))
    //     //     )-get();
    // }

    public function bayarHippamByRT(StoreHippamRTRequest $request)
    {
        // Ambil pelanggan berdasarkan ID yang dikirim dari form
        $pelanggan = User::find($request->id_pelanggan);

        if (!$pelanggan) {
            return back()->with('error', 'Pelanggan tidak ditemukan.');
        }

        // Lakukan validasi tambahan jika diperlukan
        if ($pelanggan->rt != $request->user()->rt || $pelanggan->rw != $request->user()->rw) {
            return back()->with('error', 'Pelanggan tidak berada di RT/RW yang sama.');
        }

        // Cek apakah pembayaran sudah ada
        $tahun_sekarang = date('Y');
        $check_pembayaran = Pembayaran::query()
            ->where('id_pelanggan', $pelanggan->id)
            ->where('tahun', $tahun_sekarang)
            ->where('bulan', $request->bulan)
            ->exists();

        if ($check_pembayaran) {
            return back()->with('error', 'Pembayaran sudah dilakukan, lihat di riwayat.');
        }

        // Proses pembayaran
        $pelanggan->pembayarans()->create([
            'bulan' => $request->bulan,
            'tahun' => $tahun_sekarang,
            'status' => 'waiting',
            'nama' => $pelanggan->nama,
            'tlp' => $pelanggan->tlp,
            'alamat' => $pelanggan->alamat,
        ]);

        // Buat notifikasi
        $pelanggan->notifications()->create([
            'nama' => $pelanggan->nama,
            'tlp' => $pelanggan->tlp,
            'type' => 'pembayaran',
            'pesan' => 'Pembayaran hippam bulan ' . Bulan::from($request->bulan)->name . ' ' . date('Y') . ' telah diproses oleh Ketua RT.',
            'petugas' => $request->user()->id, // ID Ketua RT yang masuk
        ]);

        return back()->with('success', 'Pembayaran berhasil diproses.');
    }

    public function validating(UpdatePembayaranValidationRequest $request, Pembayaran $pembayaran)
    {
        $pembayaran->update([
            'status' => $request->status,
            'read' => true,
        ]);

        $message = 'Pembayaran hippam bulan '.Bulan::from($pembayaran->bulan)->name.' '.$pembayaran->tahun.' '
        .($request->status == 'success' ? 'diverifikasi.' : 'ditolak. Silahkan unggah ulang bukti yang benar.');

        Notifikasi::create([
            'id_pelanggan' => $pembayaran->id_pelanggan,
            'nama' => $pembayaran->nama,
            'tlp' => $pembayaran->tlp,
            'type' => 'pembayaran',
            'pesan' => $message,
            'petugas' => 0,
        ]);

        return response()->json('success', 200);
    }

    // public function riwayat(Request $request): View
    // {
    //     $riwayat = Pembayaran::where('id_pelanggan', $request->user()->id)->where('tahun', date('Y'))->get();

    //     return view('pembayaran.riwayat', compact(['riwayat']));
    // }

    public function riwayat(Request $request): View
    {
        return match ($request->user()->role->name) {
            'pelanggan' => $this->pelangganHistory($request->user()),
            'ketuart' => $this->rtHistory($request->user()),
            default => view('pembayaran.riwayat', ['riwayat' => collect([])]),
        };
    }

    protected function pelangganHistory(User $user): View
    {
        return view('pembayaran.riwayat', [
            'riwayat' => $user->pembayarans()->where('tahun', date('Y'))->get(),
        ]);
    }

    protected function rtHistory(User $user): View
    {
        return view('pembayaran.riwayat-rt', [
            'riwayat' => Pembayaran::query()
                ->whereHas(
                    'user',
                    fn (Builder $query) => $query->where('rt', $user->rt)->where('rw', $user->rw)
                )
                ->where('tahun', date('Y'))
                ->orderBy('id_pelanggan')
                ->orderBy('bulan')
                ->get(),
        ]);
    }

    public function uploadUlang(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'bukti' => 'required|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Unggah Bukti dengan benar.');
        }

        $pembayaran = Pembayaran::find($id);

        $path = 'images/bukti/';
        $bukti = uploads($request->bukti, $path);

        Storage::delete($path.'/'.$pembayaran->bukti);

        $pembayaran->update([
            'bukti' => $bukti,
            'status' => 'waiting',
            'read' => false,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Notifikasi::create([
            'id_pelanggan' => $pembayaran->id_pelanggan,
            'nama' => $pembayaran->nama,
            'tlp' => $pembayaran->tlp,
            'type' => 'pembayaran',
            'pesan' => 'Pembayaran ulang hippam bulan '.bulan_indo($pembayaran->bulan).' '.$pembayaran->tahun,
            'petugas' => 1,
        ]);

        return back()->with('success', 'Pembayaran berhasil, tunggu validasi admin.');
    }

    public function laporan(Request $request)
    {
        try {
            if ($request->isMethod('get')) {
                $dari = null;
                $sampai = null;

                return view('pembayaran.laporan', compact(['dari', 'sampai']));
            } else {
                $validator = Validator::make($request->all(), [
                    'dari' => 'required',
                    'sampai' => 'required',
                ]);

                if ($validator->fails()) {
                    return back()->with('error', 'Pastikan tanggal dipilih dengan benar!');
                }

                // $dari = Carbon::parse($request->dari)->translatedFormat('d F Y');
                // $sampai = Carbon::parse($request->sampai)->translatedFormat('d F Y');

                $dari = $request->dari;
                $sampai = $request->sampai;

                return view('pembayaran.laporan', compact(['dari', 'sampai']));
            }
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function listLaporan(Request $request)
    {
        $data = Pembayaran::query()
            ->latest()
            ->where('status', 'success')
            ->when(
                $request->dari && $request->sampai,
                fn (Builder $query) => $query->whereDate('updated_at', '>=', $request->dari)
                    ->whereDate('updated_at', '<=', $request->sampai)
            )->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function laporanBelumBayar(Request $request)
    {
        return view('pembayaran.laporan-belum-bayar', [
            'months' => Bulan::cases(),
        ]);
    }

    public function datatableBelumBayar(Request $request): JsonResponse
    {
        $date = (object) [
            'bulan' => $request->bulan ?? Str::padLeft(now()->month, 2, 0),
            'tahun' => $request->tahun ?? now()->year,
        ];

        return DataTables::of(
            User::query()
                ->latest()
                ->whereYear('created_at', '<=', $date->tahun)
                ->whereMonth('created_at', '<=', (int) $date->bulan)
                ->whereHas(
                    'role',
                    fn (Builder $query): Builder => $query->where('name', 'pelanggan')
                )
                ->whereDoesntHave(
                    'pembayarans',
                    fn (Builder $query): Builder => $query->where(
                        'bulan',
                        $date->bulan
                    )->where('tahun', $date->tahun)
                        ->where('status', 'success')
                )->get()
        )
            ->addIndexColumn()
            ->make(true);
    }
}
