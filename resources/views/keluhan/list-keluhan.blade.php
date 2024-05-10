@extends('layout.main')

@section('title')
Keluhan | Hippam Kaligondo
@endsection

@section('css')
<style>
    input[type=checkbox], input[type=radio] {
        width: 20px;
        height: 20px;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Keluhan</h4>
                    <p>List Keluhan</p>
                </div>
            </div>
        </div>

    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @if ($insert = Auth::user()->role->permissions->contains('name', 'Tambah Keluhan'))
                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambah">Tambah Keluhan</button>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>keluhan</th>
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

@if($insert)
<!-- modal tambah -->
<div class="modal fade" id="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="tambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahLabel">Tambah Keluhan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form action="{{ url('/keluhan/tambah') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Keluhan</label>
                    <textarea name="keluhan" class="form-control @error('keluhan') is-invalid @enderror" required>{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Submit</button>
             </form>
      </div>
    </div>
  </div>
</div>
@endif

<!-- modal edit -->
<div class="modal fade" id="edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLabel">Edit Keluhan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form id="form_edit" action="" method="post">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="">Keluhan</label>
                    <textarea name="keluhan" class="form-control @error('keluhan') is-invalid @enderror" id="keluhan_edit" required></textarea>
                    @error('keluhan')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Perbarui Keluhan</button>
             </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
    let role = `{{ $insert ? 'pelanggan' : 'petugas' }}`
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
            url: "keluhan/list",
        },
        columns: [
            { data: 'nama', name: 'nama' },
            { data: 'tlp', name: 'tlp' },
            { data: 'alamat', name: 'alamat' },
            { data: 'keluhan', name: 'keluhan' },
            { data: '', orderable: false },
        ],
        order: [[0, 'desc']],
        columnDefs: [
        {
          targets: -1,
          orderable: false,
          render: function (data, type, full, meta) {
            var id_keluhan = full['id'];
            if(role == 'petugas') {
              var aksi = 'Tidak ada aksi';
            } else {
              var aksi = (
                '<div class="btn-group">' +
                  '<a class="btn dropdown-toggle hide-arrow" data-toggle="dropdown">Aksi</a>' +
                  '<div class="dropdown-menu dropdown-menu-right">' +
                  '<a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#edit" onclick="edit(this, '+ id_keluhan +')">Edit</a>' +
                  '<a href="javascript:;" class="dropdown-item delete-record" onclick="hapus('+ id_keluhan +')">Hapus</a>' +
                  '</div>' +
                '</div>'
              );
            }

            return aksi;
          }
        }
      ],
    });

    function edit(this_el, id_user) {
        var url = '/keluhan/update/'+id_user;
        var tr_el = this_el.closest('tr');
        var row = $("#table-1").DataTable().row(tr_el);
        var row_data = row.data();
        console.log(row_data.email);
        $('#keluhan_edit').val(row_data.keluhan);
        $('#form_edit').attr('action', url);
    }

    function hapus(e) {
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
                url    : `{{ route('keluhan.destroy', ':id') }}`.replace(':id', e),
                type   : "delete",
                success: function(data) {
                    $('#table-1').DataTable().ajax.reload();
                    swal({
                        type: 'success',
                        title: 'Data Keluhan berhasil dihapus.',
                        showConfirmButton: true,
                        confirmButtonClass: 'btn btn-success',
                    });
                }
            })
        })
    }

</script>
@endsection
