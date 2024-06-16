@extends('layout.main')

@section('title')
Home | Hippam Kaligondo
@endsection

@section('content')
<style>
    .carousel-caption {
        color: #000000;
    }
    .card-group {
        display: flex;
        justify-content: center;
    }
    .card-equal {
        flex: 1;
        min-width: 15rem;
        max-width: 15rem;
        margin: 0 10px;
    }
    .card-header h6 {
        font-size: 1rem;
    }
    .card-body h5 {
        font-size: 1.5rem;
    }
</style>
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Home</h4>
                    <p>Selamat Datang di Aplikasi HIPPAM Kaligondo</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @if (Auth::user()->role->name == 'admin')
                            <div class="card-group">
                                @php
                                    function getBulanDalamBahasaIndonesia($bulanInggris) {
                                        $bulanInggrisKeIndonesia = [
                                            'January' => 'Januari',
                                            'February' => 'Februari',
                                            'March' => 'Maret',
                                            'April' => 'April',
                                            'May' => 'Mei',
                                            'June' => 'Juni',
                                            'July' => 'Juli',
                                            'August' => 'Agustus',
                                            'September' => 'September',
                                            'October' => 'Oktober',
                                            'November' => 'November',
                                            'December' => 'Desember'
                                        ];

                                        return $bulanInggrisKeIndonesia[$bulanInggris];
                                    }

                                    $bulanSaatIni = getBulanDalamBahasaIndonesia(date('F'));
                                @endphp
                                <div class="card text-white bg-success mb-3 card-equal">
                                    <div class="card-header">
                                        <h5 class="card-title text-center">Pelanggan Sudah Bayar Bulan {{ $bulanSaatIni }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-text text-center">{{ $sudah }} orang</h5>
                                    </div>
                                </div>
                                <div class="card text-white bg-primary mb-3 card-equal">
                                    <div class="card-header">
                                        <h5 class="card-title text-center">Pelanggan Belum Bayar Bulan {{ $bulanSaatIni }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-text text-center">{{ $belum }} orang</h5>
                                    </div>
                                </div>
                                <div class="card text-white bg-info mb-3 card-equal">
                                    <div class="card-header">
                                        <h5 class="card-title text-center">Total <br>Pelanggan</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-text text-center">{{ $total }} orang</h5>
                                    </div>
                                </div>
                                <div class="card text-white bg-warning mb-3 card-equal">
                                    <div class="card-header">
                                        <h5 class="card-title text-center">Jumlah Petugas</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-text text-center">{{ $jumlahPetugas }} orang</h5>
                                    </div>
                                </div>
                                <div class="card text-white bg-secondary mb-3 card-equal">
                                    <div class="card-header">
                                        <h5 class="card-title text-center">Jumlah Ketua RT</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-text text-center">{{ $jumlahKetuaRT }} orang</h5>
                                    </div>
                                </div>
                                <div class="card text-white bg-danger mb-3 card-equal">
                                    <div class="card-header">
                                        <h5 class="card-title text-center">Jumlah Admin</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-text text-center">{{ $jumlahAdmin }} orang</h5>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                            <h5 class="text-capitalize">Info {{ Auth::user()->role?->name }}</h5>
                            <hr>
                            <div class="card directory-card m-b-20">
                                <div class="card-body directory-card-bg">
                                    <div class="clearfix">
                                        <p class="text-muted">Nama</p>
                                        <h6 class="font-16 mt-0">{{ Auth::user()->nama }}</h6>
                                    </div>
                                    <div class="clearfix mt-4">
                                        <p class="text-muted">Username</p>
                                        <h6 class="font-16 mt-0">{{ Auth::user()->username }}</h6>
                                    </div>
                                    <div class="clearfix mt-4">
                                        <p class="text-muted">No. Telepon</p>
                                        <h6 class="font-16 mt-0">{{ Auth::user()->tlp }}</h6>
                                    </div>
                                    <div class="clearfix mt-4">
                                        <p class="text-muted">RT/RW</p>
                                        <h6 class="font-16 mt-0">{{ Auth::user()->rt }}/{{ Auth::user()->rw }}</h6>
                                    </div>
                                    <div class="clearfix mt-4">
                                        <p class="text-muted">Alamat</p>
                                        <h6 class="font-16 mt-0">{{ Auth::user()->alamat }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 mt-3">
                            <div class="alert alert-primary" role="alert">
                                <h5><i class="mdi mdi-bullhorn"></i> Pengumuman</h5>
                              </div>
                            @if(count($info))
                            <div id="info-poster" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner" role="listbox">
                                    @foreach($info as $i)
                                    <div class="carousel-item {{ ($loop->first) ? 'active' : '' }}">
                                        <div class="text-center mb-4">
                                            {{-- <h5>Deskripsi</h5> --}}
                                            <h6>{{ $i->deskripsi }}</h6>
                                        </div>
                                        <img src="{{ url('/storage/images/info/'.$i->poster) }}" class="d-block w-100" alt="Pengumuman">
                                    </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#info-poster" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#info-poster" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            @else
                                <div class="mt-5">
                                    <h3>Tidak ada pengumuman.</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
