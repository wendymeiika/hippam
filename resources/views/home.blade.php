@extends('layout.main')

@section('title')
Home | Hippam Kaligondo
@endsection

@section('content')
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
                        <div class="col-12 col-md-6 col-lg-6">
                            <h5 class="text-capitalize">Info {{ Auth::user()->role }}</h5>
                            <hr>
                            <div class="card directory-card m-b-20">
                                <div class="card-body directory-card-bg">
                                    <div class="clearfix">
                                        <p class="text-muted">Nama</p>
                                        <h5 class="font-16 mt-0">{{ Auth::user()->nama }}</h5>
                                    </div>
                                    <div class="clearfix mt-4">
                                        <p class="text-muted">Username</p>
                                        <h5 class="font-16 mt-0">{{ Auth::user()->username }}</h5>
                                    </div>
                                    <div class="clearfix mt-4">
                                        <p class="text-muted">No. Telepon</p>
                                        <h5 class="font-16 mt-0">{{ Auth::user()->tlp }}</h5>
                                    </div>
                                    <div class="clearfix mt-4">
                                        <p class="text-muted">Alamat</p>
                                        <h5 class="font-16 mt-0">{{ Auth::user()->alamat }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <h5>Pengumuman</h5>
                            @if(count($info))
                            <div id="info-poster" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner" role="listbox">
                                    @foreach($info as $i)
                                    <div class="carousel-item {{ ($loop->first) ? 'active' : '' }}">
                                        <img src="{{ url('/storage/images/info/'.$i->poster) }}" class="d-block w-100" alt="Pengumuman">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Deskripsi</h5>
                                            <p>{{ $i->deskripsi }}</p>
                                        </div>
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