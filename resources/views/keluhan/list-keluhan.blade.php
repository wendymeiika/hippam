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
    #img-gambar img {
        max-width: 100%; /* Memastikan gambar tidak melebihi lebar kontainer */
        height: auto; /* Mempertahankan aspek rasio gambar */
        max-height: 200px; /* Batasi tinggi maksimum gambar */
        display: block;
        margin: 0 auto; /* Center gambar */
    }
    .mw-img-info {
        max-width: 100%; /* Memastikan gambar tidak melebihi lebar kontainer */
        height: auto; /* Mempertahankan aspek rasio gambar */
        max-height: 200px; /* Batasi tinggi maksimum gambar */
        display: block;
        margin: 0 auto; /* Center gambar */
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
                                    <th style="width: 15%">Gambar</th>
                                    {{-- <th>No. Telepon</th>
                                    <th>Alamat</th> --}}
                                    <th>Nama</th>
                                    <th>Keluhan</th>
                                    <th>Balasan Petugas</th>
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
            <form action="{{ url('/keluhan/tambah') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="">Keluhan</label>
                    <textarea name="keluhan" class="form-control @error('keluhan') is-invalid @enderror" required>{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="gambar">Gambar (<small>Maksimal 2 MB</small>)</label>
                    <input name="gambar" type="file" accept="images/*" class="form-control @error('gambar') is-invalid @enderror" value="{{ old('gambar') }}" required>
                    @error('gambar')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
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
            <form id="form_edit" action="" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group" id="img-gambar"></div>
                <div class="form-group">
                    <label for="gambar">Gambar (<small>Maksimal 2 MB</small>)</label>
                    <input name="gambar" type="file" accept="images/*" class="form-control @error('gambar') is-invalid @enderror">
                    @error('gambar')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
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

<!--balasan petugas-->
<div class="modal fade" id="balasan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="balasanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="balasanLabel">Balas Keluhan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_balasan" action="" method="post">
                @csrf
                <div class="form-group">
                    <label for="balasan">Balasan</label>
                    <textarea name="balasan" class="form-control @error('balasan') is-invalid @enderror" required>{{ old('balasan') }}</textarea>
                    @error('balasan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
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
            { data: 'gambar', name: 'gambar' },
            { data: 'nama', name: 'nama' },
            // { data: 'tlp', name: 'tlp' },
            // { data: 'alamat', name: 'alamat' },
            { data: 'keluhan', name: 'keluhan' },
            { data: 'balasan_petugas', name: 'balasan_petugas', orderable: false },

            { data: '', orderable: false },
        ],
        order: [[0, 'desc']],
        columnDefs: [
        {
        targets: 0,
        render: function (data, type, full, meta) {
            var pathGambar = "{{ url('/storage/images/info') }}";
            var gambar = pathGambar + '/' + full['gambar'];

            var output = '<a href="'+ gambar +'" target="_blank"><img src="'+ gambar +'" class="img-fluid" /></a>';

            return output;
            }
        },
        {
          targets: -1,
          orderable: false,
          render: function (data, type, full, meta) {
            var id_keluhan = full['id'];
            var role = '{{ $insert ? 'pelanggan' : 'petugas' }}';
            if(role == 'petugas') {
              var aksi = (
                '<div class="btn-group">' +
                  '<a class="btn dropdown-toggle hide-arrow" data-toggle="dropdown">Aksi</a>' +
                  '<div class="dropdown-menu dropdown-menu-right">' +
                  '<a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#balasan" onclick="balas(this, '+ id_keluhan +')">Balas</a>' +
                  '<a href="javascript:;" class="dropdown-item delete-record" onclick="hapus('+ id_keluhan +')">Hapus</a>' +
                  '</div>' +
                '</div>'
              );
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
        },
        // Tambah logika untuk menampilkan balasan
        {
                targets: 3, // Index kolom balasan
                render: function (data, type, full, meta) {
                    if (full['balasan'].length) {
                        return `<ol>
                            ${full['balasan'].map((reply) => `<li>${reply.balasan} - ${ setDate(reply.created_at) }</li>`).join('')}
                        </ol>`
                    }

                    // Jika tidak ada balasan, tampilkan pesan ini
                     return `<span class="badge rounded-pill bg-primary text-white">Belum ada Balasan</span>`;
                }
            }
      ],
    })

    const formatter = new Intl.DateTimeFormat('id-ID', {
        dateStyle: 'long',
        timeStyle: 'short',
        timeZone: 'Asia/Jakarta'
    })

    const setDate = date => {
        return formatter.format(new Date(date));
    }

    function edit(this_el, id_user) {
        var url = '/keluhan/update/'+id_user;
        var tr_el = this_el.closest('tr');
        var row = $("#table-1").DataTable().row(tr_el);
        var row_data = row.data();
        console.log(row_data.email);

        var pathGambar = "{{ url('/storage/images/info') }}";
        var gambar = pathGambar + '/' + row_data.gambar;

        var imgGambar = '<a href="'+ gambar +'" target="_blank"><img src="'+ gambar +'" class="mw-img-info" /></a>';

        $('#img-gambar').html(imgGambar);
        $('#keluhan_edit').val(row_data.keluhan);
        $('#form_edit').attr('action', url);
    }


    function balas(this_el, id_keluhan) {
        $('#form_balasan').attr('action', '{{ route('keluhan.balas', ':id') }}'.replace(':id', id_keluhan));
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
