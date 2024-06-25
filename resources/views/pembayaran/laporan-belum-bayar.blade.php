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
                    <p>Laporan Pembayaran Pelanggan Belum Bayar</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    @foreach($months as $month)
                                        <option value="{{ $month->value }}" @selected(old('bulan', Str::padLeft(now()->month, 2, 0)) == $month->value)>{{ $month->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="form-control" value="{{ old('tahun', now()->year) }}" required max="{{ now()->year }}">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="button" class="btn btn-primary d-inline" onclick="show()">Tampilkan</button>
                        </div>
                    </div>
                    <h5 id="report-title" class="mt-3">Laporan Belum Bayar</h5>
                    <div id="export" class="mt-3 mb-3"></div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>RT</th>
                                    <th>RW</th>
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

    let _token = $('meta[name="csrf-token"]').attr('content'),
        table = null;

    const show = () => {
        if (table) {
            $('#table-1').DataTable().destroy();
        }

        const bulanText = $('#bulan option:selected').text();
        const tahun = $('#tahun').val();

        // Update judul laporan
        $('#report-title').text(`Laporan belum bayar pada bulan ${bulanText} ${tahun}`);

        table = $("#table-1").dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                type: 'GET',
                url: "{{ route('laporan.belum-bayar.datatable') }}",
                data: {
                    bulan: $('#bulan').val(),
                    tahun: tahun,
                    _token: _token
                }
            },
            columns: [
                { data: 'nama', name: 'nama' },
                { data: 'tlp', name: 'tlp' },
                { data: 'alamat', name: 'alamat' },
                { data: 'rt', name: 'rt' },
                { data: 'rw', name: 'rw' },
            ],
            order: [[0, 'desc']],
            columnDefs: [],
        });

        // Inisialisasi ulang tombol print setelah tabel dimuat
        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: [
                {
                    extend: 'print',
                    orientation: 'portrait',
                    title: `Laporan belum bayar pada bulan ${bulanText} ${tahun}`
                }
            ]
        }).container().appendTo($('#export'));
    }

    // Panggil fungsi show() untuk pertama kali memuat data
    show();
</script>
@endsection
