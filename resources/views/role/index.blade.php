@extends('layout.main')

@section('title')
User | Hippam Kaligondo
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
                    <h4 class="page-title">User</h4>
                    <p>List Role / User Hippam Kaligondo</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambah">Tambah Role</button>
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- modal tambah -->
<div class="modal fade" id="tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="tambahLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tambahLabel">Tambah Role</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form action="{{ route('role-user.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="frist_name">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="nama" value="{{ old('nama') }}" autofocus required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Username</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required>
                            <div class="invalid-feedback"></div>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="last_name">No. Telepon</label>
                            <input type="number" class="form-control @error('tlp') is-invalid @enderror" value="{{ old('tlp') }}" name="tlp" required>
                            @error('tlp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">RT</label>
                            <input id="rt" type="number" class="form-control @error('rt') is-invalid @enderror" name="rt" value="{{ old('rt') }}" required>
                            <div class="invalid-feedback"></div>
                            @error('rt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="last_name">RW</label>
                            <input id="rw" type="number" class="form-control @error('rw') is-invalid @enderror" name="rw" value="{{ old('rw') }}" required>
                            @error('rw')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control @error('role') is-invalid @enderror" name="role_id" required>
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <h6 class="text-warning">Password otomstis diambil dari no. telepon</h6>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>

<!-- modal edit -->
<div class="modal fade" id="edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editLabel">Edit Role</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form id="form_edit" action="" method="post">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="nama" name="nama" value="" autofocus required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" required>
                            <div class="invalid-feedback"></div>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="">No. Telepon</label>
                            <input type="number" class="form-control @error('tlp') is-invalid @enderror" value="" id="tlp" name="tlp" required>
                            @error('tlp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">RT</label>
                            <input id="rt" type="number" class="form-control @error('rt') is-invalid @enderror" name="rt" value="" required>
                            @error('rt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="last_name">RW</label>
                            <input id="rw" type="number" class="form-control @error('rw') is-invalid @enderror" name="rw" value="" required>
                            @error('rw')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" required></textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="pass">
                        <label class="form-check-label" for="defaultCheck1">
                            <h6 class="text-warning ml-2">Ganti Password otomatis generate ulang diambil dari no. telepon</h6>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" name="role_id">
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>

                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
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

	$("#table-1").dataTable({
		processing: true,
		serverSide: true,
		ajax: {
			type: 'GET',
			url: "{{ route('role-user.datatable') }}",
		},
		columns: [
			{ data: 'nama', name: 'nama' },
			{ data: 'username', name: 'username' },
			{ data: 'role', name: 'role' },
			{ data: '', orderable: false },
		],
		order: [[0, 'desc']],
		columnDefs: [{
			targets: -1,
			orderable: false,
			render: (data, type, full, meta) => {
				return (
					`<div class="btn-group">
						<a class="btn dropdown-toggle hide-arrow" data-toggle="dropdown">Aksi</a>
						<div class="dropdown-menu dropdown-menu-right">
						<a href="javascript:;" class="dropdown-item" data-toggle="modal" data-target="#edit" onclick="edit({
							id: '${full.id}',
							nama: '${full.nama}',
							username: '${full.username}',
							tlp: '${full.tlp}',
							alamat: '${full.alamat}',
							rt: '${full.rt}',
							rw: '${full.rw}',
							role: '${full.role.id}',
						})">Edit</a>
						<a href="javascript:;" class="dropdown-item delete-record" onclick="hapus(${full.id})">Hapus</a>
						</div>
					</div>`
				);
			}
		}, {
            targets: -2,
            render: (data, type, full, meta) => full.role.name
        }],
	});

	const edit = (user) => {
		let url = `{{ route('role-user.update', ':id') }}`.replace(':id', user.id);

		$('#form_edit #nama').val(user.nama);
		$('#form_edit #username').val(user.username);
		$('#form_edit #tlp').val(user.tlp);
		$('#form_edit #alamat').val(user.alamat);
		$('#form_edit #rt').val(user.rt);
		$('#form_edit #rw').val(user.rw);
		$('#form_edit select[name="role_id"]').val(user.role);
		$('#form_edit').attr('action', url);
	}
</script>
@endsection
