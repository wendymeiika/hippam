@extends('layout.main')

@section('title')
Pengumuman | Hippam Kaligondo
@endsection

@section('css')
<style>
    .mw-img-info {
      max-width: 180px;
    }
</style>
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Pengumuman</h4>
                    <p>List Pengumuman</p>
                </div>
            </div>
        </div>
    
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambah">Tambah Pengumuman</button>
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th style="width: 15%">Poster</th>
                                    <th>Deskripsi</th>
                                    <th></th>
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

<!-- modal tambah -->
<div class="modal fade" id="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="tambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahLabel">Tambah Pengumuman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form action="{{ url('/pengumuman/tambah') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="poster">Poster (<small>Maksimal 2 MB</small>)</label>
                    <input name="poster" type="file" accept="images/*" class="form-control @error('poster') is-invalid @enderror" value="{{ old('poster') }}" required>
                    @error('poster')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Tambah Pengumuman</button>
             </form>
      </div>
    </div>
  </div>
</div>

<!-- modal edit -->
<div class="modal fade" id="edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLabel">Edit Pengumuman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="form_edit" action="" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group" id="img-poster"></div>
                <div class="form-group">
                    <label for="poster">Poster (<small>Maksimal 2 MB</small>)</label>
                    <input name="poster" type="file" accept="images/*" class="form-control @error('poster') is-invalid @enderror">
                    @error('poster')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi_edit" required></textarea>
                    @error('deskripsi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Perbarui Pengumuman</button>
             </form>
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
            url: "pengumuman/list",
        },
        columns: [
            { data: 'poster', name: 'poster' },
            { data: 'deskripsi', name: 'deskripsi' },
            { data: '', orderable: false },
        ],
        order: [[0, 'desc']],
        columnDefs: [
          {
            targets: 0,
            render: function (data, type, full, meta) {
              var pathPoster = "{{ url('/storage/images/info') }}";
              var poster = pathPoster + '/' + full['poster'];

              var output = '<a href="'+ poster +'" target="_blank"><img src="'+ poster +'" class="img-fluid" /></a>';

              return output;
            }
          },
          {
            targets: -1,
            orderable: false,
            render: function (data, type, full, meta) {
              var id_pengumuman = full['id'];
              
              var aksi = (
                '<div class="btn-group">' +
                  '<a class="btn dropdown-toggle hide-arrow" data-toggle="dropdown">Aksi</a>' +
                  '<div class="dropdown-menu dropdown-menu-right">' +
                  '<a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#edit" onclick="edit(this, '+ id_pengumuman +')">Edit</a>' +
                  '<a href="javascript:;" class="dropdown-item delete-record" onclick="hapus('+ id_pengumuman +')">Hapus</a>' +
                  '</div>' +
                '</div>'
              );

              return aksi;
            }
          }
        ],
    });

    function edit(this_el, id_pengumuman) {
        var url = '/pengumuman/update/'+id_pengumuman;
        var tr_el = this_el.closest('tr');
        var row = $("#table-1").DataTable().row(tr_el);
        var row_data = row.data();

        var pathPoster = "{{ url('/storage/images/info') }}";
        var poster = pathPoster + '/' + row_data.poster;

        var imgPoster = '<a href="'+ poster +'" target="_blank"><img src="'+ poster +'" class="mw-img-info" /></a>';

        $('#img-poster').html(imgPoster);
        $('#deskripsi_edit').val(row_data.deskripsi);
        $('#form_edit').attr('action', url);
    }

    function hapus(e) {
        var url = 'pengumuman/delete/'+e;

        swal({
            title             : "Apakah Anda Yakin ?",
            text              : "Data Yang Sudah Dihapus Tidak Bisa Dikembalikan!",
            type              : "warning",
            showCancelButton  : true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor : "#d33",
            confirmButtonText : "Ya, Tetap Hapus!"
        }).then((result) => {
            $.ajax({
                url    : url,
                type   : "delete",
                success: function(data) {
                    $('#table-1').DataTable().ajax.reload();
                    swal({
                        type: 'success',
                        title: 'Data Pengumuman berhasil dihapus.',
                        showConfirmButton: true,
                        confirmButtonClass: 'btn btn-success',
                    });
                }
            })
        })
    }

</script>
@endsection