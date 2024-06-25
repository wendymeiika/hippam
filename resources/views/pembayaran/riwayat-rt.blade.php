@extends('layout.main')

@section('title')
Riwayat | Hippam Kaligondo
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<style>
    #printable { visibility: hidden; }
    @media print {
        .non-printable { visibility: hidden; }
        #printable { visibility: visible; }
    }
    @page { size: 100mm 120mm; }
</style>
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Riwayat</h4>
                    <p>Riwayat Pembayaran Ketua RT</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <div class="card">
                        <h5 class="card-title mx-auto mt-5 non-printable">Riwayat Pembayaran Hippam Melalui Ketua RT</h5>
                        <div class="ml-4 mt-3">
                            <div class="row">
                                <div class="col-2">
                                    <span class="badge badge-warning badge-pill text-uppercase">waiting</span>
                                </div>
                                <div class="col-10">Menunggu validasi dari Petugas.</div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <span class="badge badge-primary badge-pill text-uppercase">reject</span>
                                </div>
                                <div class="col-10">Bukti pembayaran ditolak oleh Petugas. Silahkan upload ulang bukti yang benar.</div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <span class="badge badge-success badge-pill text-uppercase">success</span>
                                </div>
                                <div class="col-10">Sudah divalidasi oleh Petugas. Pembayaran valid.</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="riwayatTable" class="display">
                                <thead>
                                    <tr>
                                        <th>Nama Pelanggan</th>
                                        <th>Alamat</th>
                                        <th>RT/RW</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayat as $r)
                                    <tr>
                                        <td>{{ $r->user->nama }}</td>
                                        <td>{{ $r->user->alamat }}</td>
                                        <td>{{ $r->user->rt }}/{{ $r->user->rw }}</td>
                                        <td>{{ bulan_indo($r->bulan) }}</td>
                                        <td>{{ $r->tahun }}</td>
                                        <td>
                                            @switch($r->status)
                                                @case('waiting')
                                                <span class="badge badge-warning badge-pill text-uppercase">{{ $r->status }}</span>
                                                @break
                                                @case('reject')
                                                <span class="badge badge-primary badge-pill text-uppercase">{{ $r->status }}</span>
                                                @break
                                                @case('success')
                                                <span class="badge badge-success badge-pill text-uppercase">{{ $r->status }}</span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($r->status)
                                                @case('waiting')
                                                <button class="btn btn-secondary btn-rounded" disabled>Cetak</button>
                                                @break
                                                {{-- @case('reject')
                                                <button class="btn btn-primary btn-rounded" onclick="upload({{ $r->id }})">Upload</button>
                                                @break --}}
                                                @case('success')
                                                <button onclick="printDiv({{ $r->id }}, '{{ $r->user->nama }}', '{{ $r->user->tlp }}', '{{ $r->user->alamat }}', '{{ bulan_indo($r->bulan) }}', '{{ $r->tahun }}', '{{ tanggal_indo($r->updated_at) }}' )" class="btn btn-success btn-rounded">Cetak</button>
                                                @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </div>
</div>

<div id="printable">
    <div class="">
        <h5>Hippam Kaligondo</h5>
        <h5 id="id"></h5>
    </div>
    <hr>
    <div class="">
        <h6 class="font-weight-bold">Pelanggan :</h6>
        <div id="nama"></div>
        <div id="tlp"></div>
        <div id="alamat"></div>

        <h6 class="font-weight-bold mt-3">Pembayaran :</h6>
        <div id="pembayaran"></div>

        <h6 class="font-weight-bold mt-3">Tanggal Bayar :</h6>
        <div id="tgl_bayar"></div>

        <h6 class="font-weight-bold mt-3">Status : <span class="text-success">LUNAS</span></h6>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#riwayatTable').DataTable();
    });

    // function upload(id) {
    //     $("#buktiModal").modal('show');
    //     $("#form-upload").attr('action', '/pembayaran/riwayat/upload-ulang/' + id);
    // }

    function printDiv(id, nama, tlp, alamat, bulan, tahun, tgl_bayar) {
        $("#id").html('#' + id);
        $("#nama").html(nama);
        $("#tlp").html(tlp);
        $("#alamat").html(alamat);
        $("#pembayaran").html(bulan + ' ' + tahun);
        $("#tgl_bayar").html(tgl_bayar);

        var printContents = document.getElementById("printable").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
@endsection
