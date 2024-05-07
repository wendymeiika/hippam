@extends('layout.main')

@section('title')
Laporan | Hippam Kaligondo
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Laporan</h4>
                    <p>Laporan Pembayaran</p>
                </div>
            </div>
        </div>
    
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="">Dari Tanggal</label>
                                  <input type="date" name="dari" class="form-control" value="{{ $dari }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="">Sampai Tanggal</label>
                                  <input type="date" name="sampai" class="form-control" value="{{ $sampai }}" required>
                                </div>
                            </div>
                        
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="submit" class="btn btn-primary d-inline">Tampilkan</button>
                            </div>
                        </div>
                    </form>
                    <div id="export" class="mt-3 mb-3"></div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>Pembayaran</th>
                                    <th>Status</th>
                                    <th>Tanggal Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
@if(isset($dari) && isset($sampai))
    <script>
        var dari = "<?= $dari; ?>"; 
        var sampai = "<?= $sampai; ?>"
        console.log(dari);
        console.log(sampai);
    </script>
@else
    <script>
        var dari = null; 
        var sampai = null;
    </script>
@endif
<script>
  $(document).ready(function() {

      $.ajaxSetup({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      let _token   = $('meta[name="csrf-token"]').attr('content');
    
      $("#table-1").dataTable({
          processing: true,
          serverSide: true,
          ajax: {
              type: 'POST',
              url: "laporan/list",
              data: {
                dari: dari,
                sampai: sampai,
                _token: _token
              }
          },
          columns: [
              { data: 'nama', name: 'nama' },
              { data: 'tlp', name: 'tlp' },
              { data: 'alamat', name: 'alamat' },
              { data: 'bulan', name: 'bulan' },
              { data: 'status', name: 'status' },
              { data: 'updated_at', name: 'updated_at' },
          ],
          order: [[0, 'desc']],
          columnDefs: [
          {
            targets: 3,
              render: function (data, type, full, meta) {
                var bulan = toMonthName(parseInt(full['bulan']));
                var tahun = parseInt(full['tahun']);
    
                var output = `<h6 class='w-100 mx-auto font-weight-bold'>${bulan} ${tahun}</h6>`;
    
                return output;
              }
          },
          {
            targets: 4,
              render: function (data, type, full, meta) {
                var status = full['status'];
    
                var output = `<h6 class='text-uppercase'>${status}</h6>`;
    
                return output;
              }
          },
          {
            targets: 5,
              render: function (data, type, full, meta) {
                var tgl_bayar = full['updated_at'];
                tgl_bayar = new Date(tgl_bayar);
                tgl_bayar = moment(tgl_bayar).format('DD-MM-YYYY HH:mm');
    
                // var tgl = tgl_bayar.getDay();
                // var bulan = tgl_bayar.getMonth();
                // bulan = toMonthName(bulan);
                // var tahun = tgl_bayar.getFullYear();
    
                // var tanggal_bayar = tgl + ' ' + bulan + ' ' + tahun;
    
                var output = `<h6 class='text-uppercase'>${tgl_bayar}</h6>`;
    
                return output;
              }
          }
        ],
      });

      var buttons = new $.fn.dataTable.Buttons($("#table-1").dataTable(), {
          buttons: [
            {
                extend: 'print',
                orientation: 'portrait',
                title: 'Laporan Hippam Kaligondo',
            }
          ]
      }).container().appendTo($('#export'));
      
    });

</script>
@endsection