<?php
namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json($request->user()->role->permissions()->where('name', 'Validasi')->exists()
        ? Pembayaran::orderBy('id', 'desc')->get(['id_pelanggan', 'bulan', 'tahun', 'bukti', 'status','created_at'])
        : $request->user()->pembayarans()->orderBy('id', 'desc')->get(['id_pelanggan', 'bulan', 'tahun', 'bukti', 'status','created_at']));
    }

    public function list(): JsonResponse
    {
        return response()->json(Pembayaran::orderBy('id', 'desc')->get(['id_pelanggan', 'bulan', 'tahun', 'bukti', 'status','created_at']));
    }

    public function bayar(Request $request): JsonResponse
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

    public function uploadUlang(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bukti' => 'required|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
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

        return response()->json(['success' => 'Pembayaran berhasil, tunggu validasi admin.'], 200);
    }
}
