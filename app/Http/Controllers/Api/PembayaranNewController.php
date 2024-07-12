<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Enums\Bulan;
use App\Models\User;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreHippamRTRequest;
use App\Http\Controllers\PembayaranController;
use App\Http\Requests\UpdatePembayaranValidationRequest;

class PembayaranNewController extends Controller
{
    protected $pembayaranController;

    public function __construct(PembayaranController $pembayaranController)
    {
        $this->pembayaranController = $pembayaranController;
    }
    
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'view' => $request->user()->role->permissions()->where('name', 'Validasi')->exists()
            ? 'pembayaran.list-petugas'
            : 'pembayaran.list-pelanggan'
        ]);
    }

    public function list(): JsonResponse
    {
        $pembayarans = Pembayaran::orderByDesc('id')
            ->with('user:id,nama,rt,rw,alamat,tlp')
            ->select('id', 'id_pelanggan', 'bulan', 'tahun', 'bukti', 'status')
            ->get()
            ->map(function ($pembayaran) {
                $pembayaran->nama = $pembayaran->user->nama;
                $pembayaran->rt = $pembayaran->user->rt;
                $pembayaran->rw = $pembayaran->user->rw;
                $pembayaran->alamat = $pembayaran->user->alamat;
                $pembayaran->tlp = $pembayaran->user->tlp;
                unset($pembayaran->user);
                return $pembayaran;
            });
    
        return response()->json($pembayarans);
    }

    public function bayar(Request $request): JsonResponse
    {
        $view = match ($request->user()->role->name) {
            'pelanggan' => 'pembayaran.bayar',
            'ketuart' => 'pembayaran.bayar-by-rt',
        };

        if ($request->user()->role->name === 'ketuart') {
            $pelanggan = User::query()
                ->whereHas('role', fn (Builder $query) => $query->where('name', 'pelanggan'))
                ->where('rt', $request->user()->rt)
                ->where('rw', $request->user()->rw)
                ->get();

            $bulan = Bulan::cases();

            return response()->json(compact('view', 'pelanggan', 'bulan'));
        }

        return response()->json(['view' => $view]);
    }

    public function bayarHippam(Request $request): JsonResponse
    {
       
        $validator = Validator::make($request->all(), [
            'bulan' => 'required',
            'bukti' => 'required|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $bulan_sekarang = date('m');
        $tahun_sekarang = date('Y');

        if ($request->bulan > $bulan_sekarang) {
            return response()->json(['error' => 'Bulan yang dipilih melebihi bulan sekarang.'], 400);
        }

        $check_pembayaran = Pembayaran::query()
            ->where('id_pelanggan', $request->user()->id)
            ->where('tahun', $tahun_sekarang)
            ->where('bulan', $request->bulan)
            ->exists();

        if ($check_pembayaran) {
            return response()->json(['error' => 'Pembayaran sudah dilakukan, lihat di riwayat.'], 400);
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

        return response()->json(['success' => 'Pembayaran berhasil, tunggu validasi petugas.'], 200);
    }

    public function bayarHippamByRT(StoreHippamRTRequest $request): JsonResponse
    {
        $pelanggan = User::find($request->id_pelanggan);
    
        if (!$pelanggan) {
            return response()->json(['error' => 'Pelanggan tidak ditemukan.'], 404);
        }
    
        // Lakukan validasi tambahan jika diperlukan
        if ($pelanggan->rt != $request->user()->rt || $pelanggan->rw != $request->user()->rw) {
            return response()->json(['error' => 'Pelanggan tidak berada di RT/RW yang sama.'], 403);
        }
    
        // Cek apakah pembayaran sudah ada
        $tahun_sekarang = date('Y');
        $check_pembayaran = Pembayaran::query()
            ->where('id_pelanggan', $pelanggan->id)
            ->where('tahun', $tahun_sekarang)
            ->where('bulan', $request->bulan)
            ->exists();
    
        if ($check_pembayaran) {
            return response()->json(['error' => 'Pembayaran sudah dilakukan, lihat di riwayat.'], 400);
        }
    
        // Proses pembayaran
        $pembayaran = $pelanggan->pembayarans()->create([
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
    
        return response()->json(['success' => 'Pembayaran berhasil, tunggu validasi petugas.', 'pembayaran' => $pembayaran], 200);
    }
    

    public function validating(UpdatePembayaranValidationRequest $request, Pembayaran $pembayaran): JsonResponse
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

    public function riwayat(Request $request): JsonResponse
    {
        $view = match ($request->user()->role->name) {
            'pelanggan' => $this->pelangganHistory($request->user()),
            'ketuart' => $this->rtHistory($request->user()),
            default => 'pembayaran.riwayat',
        };

        return response()->json(['view' => $view]);
    }

    protected function pelangganHistory(User $user): JsonResponse
    {
        $riwayat = $user->pembayarans()->where('tahun', date('Y'))->get();
        return response()->json(compact('riwayat'));
    }

    protected function rtHistory(User $user): JsonResponse
    {
        $riwayat = Pembayaran::query()
            ->whereHas(
                'user',
                fn (Builder $query) => $query->where('rt', $user->rt)->where('rw', $user->rw)
            )->where('tahun', date('Y'))->get();

        return response()->json(compact('riwayat'));
    }

    public function bukti(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'bukti' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($pembayaran->bukti && Storage::disk('local')->exists($pembayaran->bukti)) {
            Storage::delete($pembayaran->bukti);
        }

        $path = 'images/bukti/';
        $bukti = $request->file('bukti')->store($path);

        $pembayaran->update([
            'bukti' => $bukti,
            'status' => 'waiting',
        ]);

        return back()->with('success', 'Bukti berhasil diunggah ulang, tunggu validasi petugas.');
    }

    public function destroy(Pembayaran $pembayaran): RedirectResponse
    {
        if ($pembayaran->bukti && Storage::disk('local')->exists($pembayaran->bukti)) {
            Storage::delete($pembayaran->bukti);
        }

        $pembayaran->delete();

        return back()->with('success', 'Pembayaran berhasil dihapus.');
    }
}
