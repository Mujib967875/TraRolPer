@extends('admin.layout.master')

@section('main_content')
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>Log Aktivitas Admin</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="example1">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pengguna</th>
                                                <th>Aksi</th>
                                                <th>Tanggal & Jam</th>
                                                <th>Deskripsi</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($adminlogs as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        @if ($item->causer)
                                                            {{ $item->causer->name }}
                                                        @else
                                                            Nama tidak ditemukan
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->event }}</td>
                                                    <td>{{ $item->created_at->format('d-m-Y H:i:s') }}</td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>
                                                        <a href="" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal{{ $loop->iteration }}">Detail</a>
                                                    </td>
                                                </tr>

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal{{ $loop->iteration }}"
                                                    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail
                                                                    Data</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3 row modal-seperator">
                                                                    <div class="col-md-5">

                                                                        <h6>Data Lama</h6>
                                                                        <p>
                                                                            @if (isset($item->properties['old']) && is_array($item->properties['old']))
                                                                                @foreach ($item->properties['old'] as $key => $itemChange)
                                                                                    @if ($key !== 'created_at' && $key !== 'updated_at' && $key !== 'password' && $key !== 'token')
                                                                                        <!-- Mengecualikan created_at dan updated_at -->
                                                                                        {{ $key }} :
                                                                                        {{ $itemChange }} <br>
                                                                                    @endif
                                                                                @endforeach
                                                                            @else
                                                                                <p>No old data available.</p>
                                                                            @endif
                                                                        </p>
                                                                        <br>

                                                                        <h6>Data Baru</h6>
                                                                        <p>
                                                                            @if (isset($item->properties['attributes']) && is_array($item->properties['attributes']))
                                                                                @foreach ($item->properties['attributes'] as $key => $itemChange)
                                                                                    @if ($key !== 'created_at' && $key !== 'updated_at' && $key !== 'password' && $key !== 'token')
                                                                                        <!-- Mengecualikan created_at dan updated_at -->
                                                                                        {{ $key }} :
                                                                                        {{ $itemChange }} <br>
                                                                                    @endif
                                                                                @endforeach
                                                                            @else
                                                                                <p>No new data available.</p>
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- // Modal -->

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
