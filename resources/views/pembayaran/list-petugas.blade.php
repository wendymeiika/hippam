@extends('layout.main')

@section('title')
Pembayaran | Hippam Kaligondo
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Pembayaran</h4>
                    <p>List Pembayaran</p>
                </div>
            </div>
        </div>
    
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th style="width: 10%;">Bukti</th>
                                    <th style="width: 10%;">Pembayaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
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
<script>
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#table-1").dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            url: "pembayaran/list",
        },
        columns: [
            { data: 'nama', name: 'nama' },
            { data: 'tlp', name: 'tlp' },
            { data: 'alamat', name: 'alamat' },
            { data: 'bukti', name: 'bukti' },
            { data: 'bulan', name: 'bulan' },
            { data: 'status', name: 'status' },
            { data: '', orderable: false },
        ],
        order: [[0, 'desc']],
        columnDefs: [
        {
          targets: 3,
            render: function (data, type, full, meta) {
              var path = "{{ url('/storage/images/bukti') }}";
              var bukti = path + '/' + full['bukti'];

              var output = '<a href="'+ bukti +'" target="_blank"><img src="'+ bukti +'" class="img-fluid" /></a>';

              return output;
            }
        },
        {
          targets: 4,
            render: function (data, type, full, meta) {
              var bulan = toMonthName(parseInt(full['bulan']));
              var tahun = parseInt(full['tahun']);

              var output = `<h6 class='w-100 mx-auto font-weight-bold'>${bulan} ${tahun}</h6>`;

              return output;
            }
        },
        {
          targets: 5,
            render: function (data, type, full, meta) {
              var status = full['status'];

              var output = `<h6 class='text-uppercase'>${status}</h6>`;

              return output;
            }
        },
        {
          targets: -1,
          orderable: false,
          render: function (data, type, full, meta) {
            var id = full['id'];
            var status = full['status'];

            if(status == 'waiting') {
              return (
                '<div class="btn-group bg-secondary text-white">' +
                  '<a class="btn dropdown-toggle hide-arrow" data-toggle="dropdown">Aksi</a>' +
                  '<div class="dropdown-menu dropdown-menu-right">' +
                  '<a href="javascript:;" class="dropdown-item bg-success text-white" onclick="valid('+ id +')">Valid</a>' +
                  '<a href="javascript:;" class="dropdown-item bg-danger text-white" onclick="tolak('+ id +')">Tolak</a>' +
                  '</div>' +
                '</div>'
              );
            } else {
              return 'Tidak ada aksi.';
            }
          }
        }
      ],
    });

    function valid(id) {
      var url = 'pembayaran/valid/'+id;

      swal({
          title             : "Apakah Anda Yakin ?",
          text              : "Validasi Pembayaran",
          type              : "warning",
          showCancelButton  : true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor : "#d33",
          confirmButtonText : "Ya, validasi"
      }).then((result) => {
          $.ajax({
              url    : url,
              type   : "post",
              success: function(data) {
                  $('#table-1').DataTable().ajax.reload();
                  swal({
                      type: 'success',
                      title: 'Data Pembayaran berhasil DIVALIDASI.',
                      showConfirmButton: true,
                      confirmButtonClass: 'btn btn-success',
                  });
              }
          })
      });
    }

    function tolak(id) {
      var url = 'pembayaran/tolak/'+id;

      swal({
          title             : "Apakah Anda Yakin ?",
          text              : "Tolak Pembayaran",
          type              : "warning",
          showCancelButton  : true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor : "#d33",
          confirmButtonText : "Ya, Tolak"
      }).then((result) => {
          $.ajax({
              url    : url,
              type   : "post",
              success: function(data) {
                  $('#table-1').DataTable().ajax.reload();
                  swal({
                      type: 'success',
                      title: 'Data Pembayaran berhasil DITOLAK.',
                      showConfirmButton: true,
                      confirmButtonClass: 'btn btn-success',
                  });
              }
          })
      });
    }

</script>
@endsection