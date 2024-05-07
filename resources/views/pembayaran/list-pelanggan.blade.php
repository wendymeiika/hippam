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
                    <p>Menu Pembayaran</p>
                </div>
            </div>
        </div>
    
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <a href="{{ url('/pembayaran/bayar') }}">
                        <div class="card bg-primary mini-stat position-relative">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">Pembayaran</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">Pemabayaran</h6>
                                        <h3 class="mb-3 mt-0">Bayar</h3>
                                        <div class="">
                                            <span class="">Lakukan pembayaran disini.</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-tag-text-outline display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <a href="{{ url('/pembayaran/riwayat') }}">
                        <div class="card bg-primary mini-stat position-relative">
                            <div class="card-body">
                                <div class="mini-stat-desc">
                                    <h6 class="text-uppercase verti-label text-white-50">Riwayat</h6>
                                    <div class="text-white">
                                        <h6 class="text-uppercase mt-0 text-white-50">Riwayat</h6>
                                        <h3 class="mb-3 mt-0">Riwayat</h3>
                                        <div class="">
                                            <span class="">Riwayat pemabayaran.</span>
                                        </div>
                                    </div>
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-cube-outline display-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>


</script>
@endsection