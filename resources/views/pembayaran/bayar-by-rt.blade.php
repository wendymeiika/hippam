@extends('layout.main')

@section('title')
Bayar | Hippam Kaligondo
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Bayar</h4>
                    <p>Form Pembayaran oleh Ketua RT</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <div class="card">
                        <h5 class="card-title mx-auto mt-5">
                            Pembayaran Hippam oleh Ketua RT
                        </h5>
                        <div class="card-body">
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ url('/pembayaran/bayar-by-rt') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="id_pelanggan">Nama Pelanggan</label>
                                    <select name="id_pelanggan" id="id_pelanggan" class="form-control" required>
                                        <option value="" disabled selected hidden>Pilih Pelanggan</option>
                                        @foreach($pelanggan as $user)
                                            <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_pelanggan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="bulan">Pilih Bulan</label>
                                    <select name="bulan" id="bulan" class="form-control" required>
                                        <option value="" disabled selected hidden>Pilih Bulan</option>
                                        @foreach ($bulan as $month)
                                            <option value="{{ $month->value }}">{{ $month->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('bulan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-4">
                                    <button type="submit" class="form-control btn btn-primary">BAYAR</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </div>
</div>
@endsection
