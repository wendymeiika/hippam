@extends('layout.main')

@section('title')
Edit Profile | Hippam Kaligondo
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Edit Profile</h4>
                    <p>Edit dan perbarui profile anda disini</p>
                </div>
            </div>
        </div>

    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
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

                    <form method="POST" action="{{ url('/profile/edit-profile') }}">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="nama" value="{{ Auth::user()->nama }}" autofocus required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ Auth::user()->username }}" autofocus required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">No. Telepon</label>
                            <input type="number" class="form-control @error('tlp') is-invalid @enderror" value="{{ Auth::user()->tlp }}" name="tlp" required>
                            @error('tlp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" required>{{ Auth::user()->alamat }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                Ubah Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
