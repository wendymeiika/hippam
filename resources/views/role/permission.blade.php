@extends('layout.main')

@section('title')
Role Permission | Hippam Kaligondo
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">User</h4>
                    <p>Role Permission / User Hippam Kaligondo</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambah">Tambah Hak Akses</button>
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Nama Role</th>
                                    <th>Hak Akses</th>
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
				<h5 class="modal-title" id="tambahLabel">Tambah Hak Akses</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form action="{{ route('role.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="frist_name">Nama Role</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autofocus required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <h2 for="permissions">Hak Akses</h2>
                    </div>

                    @foreach ($groups as $group)
                    <div class="form-group">
                        <h5 class="text-lg">{{ $group->name }}</h5>

                        <div class="row">
                            @foreach ($group->permissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input me-1" type="checkbox" value="{{ $permission->id }}" id="check-{{ $permission->id }}" name="permissions[]">
                                <label class="form-check-label" for="check-{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

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
				<h5 class="modal-title" id="editLabel">Edit Hak Akses</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form id="form_edit" action="" method="post">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="">Nama Role</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="" autofocus required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <h2 for="permissions">Hak Akses</h2>
                    </div>

                    @foreach ($groups as $group)
                    <div class="form-group">
                        <h5 class="text-lg">{{ $group->name }}</h5>

                        <div class="row">
                            @foreach ($group->permissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input me-1" type="checkbox" value="{{ $permission->id }}" id="edit-{{ $permission->id }}" name="permissions[]">
                                <label class="form-check-label" for="edit-{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

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
			url: "{{ route('role.datatable') }}",
		},
		columns: [
			{ data: 'name', name: 'name' },
            { data: 'permissions', name: 'permissions' },
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
                            name: '${full.name}',
							permissions: '${JSON.stringify(full.permissions.map((permission) => permission.id))}',
						})">Edit</a>
						${full.deletable
                        ? `<a href="javascript:;" class="dropdown-item delete-record" onclick="hapus(${full.id})">Hapus</a>`
                        : ''
                        }
						</div>
					</div>`
				);
			}
		}, {
            targets: -2,
            render: (data, type, full, meta) => {
                return `<ol class="list-disc">
                    ${full.permissions.map((permission) => `<li>${permission.name}</li>`).join('')}
                </ol>`
            }
        }],
	});

	const edit = (role) => {
		let url = `{{ route('role.update', ':id') }}`.replace(':id', role.id),
            permissions = JSON.parse(role.permissions)

		$('#form_edit input[name="name"]').val(role.name)

        // trigger false dulu, baru true di belakang
        $('#form_edit input[name="permissions[]"]').prop('checked', false)

        permissions.forEach(permission => {
            $(`#form_edit input[name="permissions[]"][value="${permission}"]`).prop('checked', true)
        })

		$('#form_edit').attr('action', url);
	}

    const hapus = id => {
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
                url    : `{{ route('role.destroy', ':id') }}`.replace(':id', id),
                type   : "delete",
                success: function(data) {
                    $('#table-1').DataTable().ajax.reload();
                    swal({
                        type: 'success',
                        title: 'Data Role Akses berhasil dihapus.',
                        showConfirmButton: true,
                        confirmButtonClass: 'btn btn-success',
                    });
                }
            })
        }).catch((failed) => {
            console.error(failed)
        })
    }
</script>
@endsection
