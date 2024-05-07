<?php

namespace App\Http\Controllers;

use App\Enums\Bulan;
use App\Http\Requests\StoreHippamRTRequest;
use App\Models\Notifikasi;
use App\Models\Pembayaran;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Storage;
use Validator;

class PembayaranController extends Controller
{
    public function index(): View
    {
        return Auth::user()->role == 'petugas'
            ? view('pembayaran.list-petugas')
            : view('pembayaran.list-pelanggan');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    // public function bayar()
    // {
    //     try {
    //         return view('pembayaran.bayar');
    //     } catch (Exception $e) {
    //         return view('error');
    //         dd($e->getMessage());
    //     }
    // }

    public function bayar(Request $request): View
    {
        return match ($request->user()->role) {
            'pelanggan' => view('pembayaran.bayar'),
            'ketuart' => view('pembayaran.bayar-by-rt', [
                'pelanggan' => User::query()->role('pelanggan')
                    ->where('rt', $request->user()->rt)
                    ->where('rw', $request->user()->rw)
                    ->get(),
                'bulan' => Bulan::cases(),
            ]),
        };
    }

    public function bayarHippam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bulan' => 'required',
                'bukti' => 'required|max:2048',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $bulan_sekarang = date('m');
            $tahun_sekarang = date('Y');
            $id_pelanggan = Auth::user()->id;

            if ($request->bulan > $bulan_sekarang) {
                return back()->with('error', 'Bulan yang dipilih melebihi bulan sekarang.');
            }

            $check_pembayaran = Pembayaran::where('id_pelanggan', $id_pelanggan)->where('tahun', $tahun_sekarang)->where('bulan', $request->bulan)->count();
            if ($check_pembayaran) {
                return back()->with('error', 'Pembayaran sudah dilakukan, lihat di riwayat.');
            }

            $path = 'images/bukti/';
            $bukti = uploads($request->bukti, $path);

            Pembayaran::create([
                'id_pelanggan' => $id_pelanggan,
                'nama' => Auth::user()->nama,
                'tlp' => Auth::user()->tlp,
                'alamat' => Auth::user()->alamat,
                'bulan' => $request->bulan,
                'tahun' => $tahun_sekarang,
                'bukti' => $bukti,
                'status' => 'waiting',
            ]);

            Notifikasi::create([
                'id_pelanggan' => $id_pelanggan,
                'nama' => Auth::user()->nama,
                'tlp' => Auth::user()->tlp,
                'type' => 'pembayaran',
                'pesan' => 'Pembayaran hippam bulan '.bulan_indo($request->bulan).' '.$tahun_sekarang,
                'petugas' => 1,
            ]);

            return back()->with('success', 'Pembayaran berhasil, tunggu validasi admin.');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function bayarHippamByRT(StoreHippamRTRequest $request)
    {
        $pelanggan = User::find($request->id_pelanggan);

        $pelanggan->pembayarans()->create([
            'bulan' => $request->bulan,
            'tahun' => date('Y'),
            'status' => 'waiting',
            'nama' => $pelanggan->nama,
            'tlp' => $pelanggan->tlp,
            'alamat' => $pelanggan->alamat,
        ]);

        $pelanggan->notifications()->create([
            'nama' => $pelanggan->nama,
            'tlp' => $pelanggan->tlp,
            'type' => 'pembayaran',
            'pesan' => 'Pembayaran hippam bulan '.Bulan::from($request->bulan)->name.' '.date('Y').' telah diproses oleh Ketua RT.',
            'petugas' => Auth::id(), // ID Ketua RT yang masuk
        ]);

        return back()->with('success', 'Pembayaran berhasil diproses.');

        // menampilkan bulan user durung bayar
        // User::query()
        //     ->where('rt', Auth::user()->rt)
        //     ->where('rw', Auth::user()->rw)
        //     ->whereDoesntHave(
        //         'pembayarans',
        //         fn (Builder $query) => $query->where('bulan', date('m'))->where('tahun', date('Y'))
        //     )-get();
    }

    public function tolak($id)
    {
        try {
            $pembayaran = Pembayaran::find($id);
            $pembayaran->update([
                'status' => 'reject',
                'read' => true,
            ]);

            Notifikasi::create([
                'id_pelanggan' => $pembayaran->id_pelanggan,
                'nama' => $pembayaran->nama,
                'tlp' => $pembayaran->tlp,
                'type' => 'pembayaran',
                'pesan' => 'Pembayaran hippam bulan '.bulan_indo($pembayaran->bulan).' '.$pembayaran->tahun.' ditolak. Silahkan unggah ulang bukti yang benar.',
                'petugas' => 0,
            ]);

            return response()->json('success', 200);
        } catch (Exception $e) {
            return response()->json('error', 500);
            dd($e->getMessage());
        }
    }

    public function riwayat()
    {
        try {
            $id_pelanggan = Auth::user()->id;
            $tahun_sekarang = date('Y');
            $riwayat = Pembayaran::where('id_pelanggan', $id_pelanggan)->where('tahun', $tahun_sekarang)->get();

            return view('pembayaran.riwayat', compact(['riwayat']));
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function uploadUlang(Request $request, $id)
    {
        try {
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
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
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
        if ($request->ajax()) {
            if ($request->dari && $request->sampai) {
                $data = Pembayaran::orderBy('id', 'desc')
                    ->where('status', 'success')
                    ->whereDate('updated_at', '>=', $request->dari)
                    ->whereDate('updated_at', '<=', $request->sampai)
                    ->get();
            } else {
                $data = Pembayaran::orderBy('id', 'desc')
                    ->where('status', 'success')
                    ->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
