@extends('layout.main')

@section('title')
Riwayat | Hippam Kaligondo
@endsection

@section('css')
<style>

    #printable { visibility: hidden; }

    @media print
    {
        .non-printable { visibility: hidden; }
        #printable { 
            visibility: visible;
        }
    }

    @page {
        size: 100mm 120mm;
    }
</style>
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Riwayat</h4>
                    <p>Riwayat Pembayaran</p>
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
                        <h5 class="card-title mx-auto mt-5 non-printable">
                            Riwayat Pembayaran Hippam
                        </h5>
                        <div class="ml-4 mt-3">
                            <div class="row">
                                <div class="col-2">
                                    <span class="badge badge-warning badge-pill text-uppercase">waiting</span>
                                </div>
                                <div class="col-10">
                                    Menunggu validasi dari Admin.
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <span class="badge badge-primary badge-pill text-uppercase">reject</span>
                                </div>
                                <div class="col-10">
                                    Bukti pembayaran ditolak oleh Admin. Silahkan upload ulang bukti yang benar.
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2">
                                    <span class="badge badge-success badge-pill text-uppercase">success</span>
                                </div>
                                <div class="col-10">
                                    Sudah divalidasi oleh Admin. Pembayaran valid.
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group non-printable">
                                @foreach($riwayat as $r)
                                <div class="row">
                                    <div class="col-12 col-sm-10 col-md-10 col-lg-10">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ bulan_indo($r->bulan) }} {{ $r->tahun }}
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
                                        </li>
                                    </div>
                                    <div class="col-12 col-sm-2 col-md-2 col-lg-2 d-flex justify-content-center align-items-center">
                                        @switch($r->status)
                                            @case('waiting')
                                            <button class="btn btn-secondary btn-rounded" disabled>Cetak</button>
                                            @break
                                            @case('reject')
                                            <button class="btn btn-primary btn-rounded" onclick="upload({{ $r->id }})">Upload</button>
                                            @break
                                            @case('success')
                                            <button onclick="printDiv( {{$r->id}}, '{{$r->nama}}', '{{$r->tlp}}', '{{$r->alamat}}', '{{bulan_indo($r->bulan)}}', '{{$r->tahun}}', '{{tanggal_indo($r->updated_at)}}' )" class="btn btn-success btn-rounded">Cetak</button>
                                            @break
                                        @endswitch
                                    </div>
                                </div>
                                @endforeach
                            </ul>
                            
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

<!-- Modal Form Upload ulang Bukti -->
<div class="modal fade" id="buktiModal" tabindex="-1" aria-labelledby="buktiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="buktiModalLabel">Unggah Ulang Bukti</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-upload" action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="">Bukti Pembayaran</label>
                <input type="file" accept="image/*" class="form-control filestyle" name="bukti" data-buttonname="btn-secondary" required>
                @error('bukti')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Unggah</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}"></script>
<script>
    function upload(id) {
        $("#buktiModal").modal('show');
        $("#form-upload").attr('action', '/pembayaran/riwayat/upload-ulang/'+id);
    }

    function printDiv(id, nama, tlp, alamat, bulan, tahun, tgl_bayar) {
        $("#id").html('#'+id);
        $("#nama").html(nama);
        $("#tlp").html(tlp);
        $("#alamat").html(alamat);
        $("#pembayaran").html(bulan+' '+tahun);
        $("#tgl_bayar").html(tgl_bayar);

        var printContents = document.getElementById("printable").innerHTML;
        console.log(printContents);
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>

@endsection