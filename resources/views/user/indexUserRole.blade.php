@extends('layout.sidebar')

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row py-3 align-items-center">
                    <div class="col d-flex justify-content-between align-items-center">
                        <!-- Left navbar links -->
                        <div class="d-flex justify-center align-items-center gap-12">
                            <button class="btn-pushmenu" data-widget="pushmenu" role="button">
                                <i class="fas fa-bars"></i></button>
                            <h1 class="headline-large-32 mb-0">Kelola User</h1>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Add Data Button -->
                            <button class="btn-purple" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fa fa-plus me-1"></i> Add Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mx-4" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div class="flex-grow-1 ml-3">
                        <div class="alert-heading h5 mb-2">Berhasil!</div>
                        <div class="small">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mx-4" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-danger"></i>
                    </div>
                    <div class="flex-grow-1 ml-3">
                        <div class="alert-heading h5 mb-2">Error!</div>
                        <div class="small">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mx-4" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-danger"></i>
                    </div>
                    <div class="flex-grow-1 ml-3">
                        <div class="alert-heading h5 mb-2">Periksa kembali Data yang dimasukkan</div>
                        <div class="small">
                            @foreach ($errors->all() as $error)
                                <div class="mb-1">• {{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        <section class="content">
            <div class="container-fluid">
                <div class="card mt-3">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="Table">
                            <thead>
                                <tr>
                                    <th class="text-center align-top">No</th>
                                    <th class="align-top">Username</th>
                                    <th class="align-top">Nama</th>
                                    <th class="text-center align-top">Role</th>
                                    <th class="text-center align-top">Status</th>
                                    <th class="align-top">Keterangan</th>
                                    <th class="text-center align-top">Last Login</th>
                                    <th class="text-center align-top">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->nama }}</td>
                                        <td class="text-center">
                                            @if ($user->role == 'super_admin')
                                                <span class="badge badge-danger">Super Admin</span>
                                            @elseif($user->role == 'teknisi')
                                                <span class="badge badge-info">Teknisi</span>
                                            @else
                                                <span class="badge badge-success">Marketing</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($user->status == 'aktif')
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->keterangan ?? '-' }}</td>
                                        <td class="text-center">
                                            {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{-- Edit Button --}}
                                            <button data-toggle="modal" data-target="#editModal{{ $user->id }}"
                                                title="Edit">
                                                <ion-icon name="pencil-outline"></ion-icon>
                                            </button>

                                            {{-- Delete Button --}}
                                            <button data-toggle="modal" data-target="#deleteModal{{ $user->id }}"
                                                title="Hapus">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </td>
                                    </tr>


                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1"
                                        aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('UserRole.user-role.update', $user->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="username">Username <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="username"
                                                                name="username" value="{{ $user->username }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nama">Nama Lengkap <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="nama"
                                                                name="nama" value="{{ $user->nama }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="password">Password <small
                                                                    class="text-muted">(Kosongkan jika tidak ingin
                                                                    mengubah)</small></label>
                                                            <input type="password" class="form-control" id="password"
                                                                name="password" minlength="8">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="role">Role <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-control" id="role" name="role"
                                                                required>
                                                                <option value="super_admin"
                                                                    {{ $user->role == 'super_admin' ? 'selected' : '' }}>
                                                                    Super Admin</option>
                                                                <option value="teknisi"
                                                                    {{ $user->role == 'teknisi' ? 'selected' : '' }}>
                                                                    Teknisi</option>
                                                                <option value="marketing"
                                                                    {{ $user->role == 'marketing' ? 'selected' : '' }}>
                                                                    Marketing</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">Status <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-control" id="status" name="status"
                                                                required>
                                                                <option value="aktif"
                                                                    {{ $user->status == 'aktif' ? 'selected' : '' }}>
                                                                    Aktif</option>
                                                                <option value="tidak_aktif"
                                                                    {{ $user->status == 'tidak_aktif' ? 'selected' : '' }}>
                                                                    Tidak Aktif</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="keterangan">Keterangan</label>
                                                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ $user->keterangan }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn-cancel-modal"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn-purple">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                                <p>Belum ada data user</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>

    {{-- Delete Modal --}}
    @foreach ($users as $user)
        <div class="modal fade" id="deleteModal{{ $user->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center align-items-center">
                        <ion-icon name="alert-circle-outline" class="text-9xl text-[var(--yellow-04)]"></ion-icon>
                        <p class="headline-large-32">Anda Yakin?</p>
                        <label>Apakah user <b>{{ $user->username }}</b> ingin dihapus?</label>
                    </div>
                    <div class="modal-footer flex-row-reverse justify-content-between">
                        <button type="button" class="btn-cancel-modal" data-dismiss="modal">Tutup</button>
                        <form action="{{ route('UserRole.user-role.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Add User Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('UserRole.user-role.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah User Baru</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" value="{{ old('username') }}" required>
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" minlength="6" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="role">Role <span class="text-danger">*</span></label>
                            <select class="form-control @error('role') is-invalid @enderror" id="role"
                                name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super
                                    Admin</option>
                                <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                                <option value="marketing" {{ old('role') == 'marketing' ? 'selected' : '' }}>Marketing
                                </option>
                            </select>
                            @error('role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status"
                                name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak
                                    Aktif</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-purple">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
