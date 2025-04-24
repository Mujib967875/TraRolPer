@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')

    <style>
        .card {
            /* border-radius: 12px; */
            border: 1px solid #ddd;
        }

        .card-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
    </style>


    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Tambah Role</h1>
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
                                <form action="{{ route('roles.store') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Nama Role *</label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Contoh: admin, editor, dll" required>
                                    </div>

                                    <div class="row">
                                        @foreach ($groupedPermissions as $modul => $perms)
                                            @if ($modul === 'lainnya' && !(auth()->check() && auth()->user()->hasRole('super_admin')))
                                                @continue
                                            @endif

                                            <div class="col-md-6 col-lg-4 mb-2">
                                                <div class="card h-100 shadow-sm">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h5 class="fw-bold text-dark text-capitalize m-0">
                                                                {{ str_replace('_', ' ', $modul) }}</h5>
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    class="form-check-input check-all-per-modul"
                                                                    data-target="{{ $modul }}"
                                                                    id="check-{{ $modul }}">
                                                                <label class="form-check-label"
                                                                    for="check-{{ $modul }}">Pilih Semua</label>
                                                            </div>
                                                        </div>



                                                        <div class="row">
                                                            @foreach($perms as $permission)
                                                                <div class="col-12">
                                                                    <label>
                                                                        <input type="checkbox" name="permissions[]" class="check-permission-item mb-3 check-{{ $modul }}" value="{{ $permission->name }}">
                                                                        {{ $permission->name }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>



                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Simpan Role
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
        document.querySelectorAll('.check-all-per-modul').forEach(modulCheckbox => {
            modulCheckbox.addEventListener('change', function() {
                const target = this.getAttribute('data-target');
                const checkboxes = document.querySelectorAll(`.check-${target}`);
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        });
    </script>
@endsection
