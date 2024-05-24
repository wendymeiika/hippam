@extends('layout.main')

@section('title')
Keluhan | Hippam Kaligondo
@endsection

@section('css')
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
                    <a href="#" type="button" class="btn btn-primary mb-3">Buat Pengaduan Baru</a>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Judul Pengaduan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                            @endphp
                            @foreach ($data as $item)
                            @if($item->user_id == Auth::user()->id)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ $item->created_at->format('l, d F Y') }}</td>
                                    @if($item->status =='Belum di Proses')
                                    <td>
                                      <span
                                        class="badge rounded-pill bg-danger">
                                        {{ $item->status }}
                                      </span>
                                    </td>
                                    @elseif ($item->status =='Sedang di Proses')
                                    <td>
                                      <span
                                        class="badge rounded-pill bg-warning text-dark">
                                        {{ $item->status }}
                                      </span>
                                    </td>
                                    @else
                                    <td>
                                      <span
                                        class="badge rounded-pill bg-success">
                                        {{ $item->status }}
                                      </span>
                                    </td>
                                    @endif
                                    <td>
                                      <form onsubmit="return confirm('Apakah Anda Yakin ?');"
                                          action="#" method="POST"
                                          id="delete">

                                          {{-- @if ($item->status != 'Terkirim') --}}
                                              <a href="#"
                                                  class="btn btn-sm btn-warning m-1" type="button"
                                                  title="Lihat detail Pengaduan" style="width: 75px">
                                                  Lihat
                                              </a>
                                          @csrf
                                          @method('delete')
                                          <button type="submit" class="btn btn-sm btn-danger m-1"
                                              title="Hapus data Pengaduan" style="width: 75px">
                                              Hapus
                                          </button>
                                      </form>
                                  </td>
                                </tr>
                              @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

        <script>
          $('#example').DataTable();
        </script>
    {{-- </x-app-layout> --}}

    </html>
@endsection
