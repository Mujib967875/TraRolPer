@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Ubah Role</h1>
                <div class="ml-auto">
                    <a href="{{ route('roles.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label">Nama Role *</label>
                                        <input type="text" class="form-control" name="name" value="{{ $role->name }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Pilih Permission *</label>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="checkAllPermissions">
                                                <label class="form-check-label" for="checkAllPermissions">Pilih Semua</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @foreach($permissions as $permission)
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input type="checkbox" name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            class="form-check-input permission-checkbox"
                                                            id="perm_{{ $permission->id }}"
                                                            {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

<script>
    document.getElementById('checkAllPermissions').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });
</script>

@endsection
