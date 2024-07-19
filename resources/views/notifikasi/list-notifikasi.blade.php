@extends('layout.main')

@section('title')
Notifikasi | Hippam Kaligondo
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
                    <h4 class="page-title">Notifikasi</h4>
                    <p>List Notifikasi</p>
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
                                    <th>Tipe</th>
                                    <th>Pesan</th>
                                    <th>Action</th> <!-- Tambahkan kolom Action -->
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

    var table = $("#table-1").dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            url: "notifikasi/list",
        },
        columns: [
            { data: 'nama', name: 'nama' },
            { data: 'tlp', name: 'tlp' },
            { data: 'type', name: 'type' },
            { data: 'pesan', name: 'pesan' },
            { // Tambahkan kolom Action
                data: 'id',
                name: 'id',
                render: function (data, type, full, meta) {
                    return '<button class="btn btn-primary btn-sm delete-btn" data-id="'+data+'">Hapus</button>';
                },
                orderable: false,
                searchable: false
            }
        ],
        order: [[0, 'desc']],
        columnDefs: [
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    var type = full['type'];
                    var output = '<p class="text-uppercase">'+type+'</p>';

                    return output;
                }
            }
        ],
    });

    // Tambahkan fungsi untuk menghapus notifikasi
    $('#table-1').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        if(confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
            $.ajax({
                url: 'notifikasi/delete/' + id,
                type: 'DELETE',
                success: function(response) {
                    alert('Notifikasi berhasil dihapus.');
                    table.api().ajax.reload(); // Reload DataTables
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat menghapus notifikasi.');
                }
            });
        }
    });

</script>
@endsection
