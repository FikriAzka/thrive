@extends('adminlte::page')

@section('title', 'Specialist Feedback')

@section('content_header')
    {{-- <h1>Specialist Feedback</h1> --}}
@stop

@section('content')
    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title">Data Diri</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('ratings.store-data', $meeting) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label required">Nama</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Email </label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nomor HP</label>
                        <input type="tel" class="form-control" name="phone" required pattern="[0-9]+">

                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Posisi/Jabatan</label>
                        <input type="text" class="form-control" name="position" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Project</label>
                        <input type="text" class="form-control" name="project_or_product" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nama PIC</label>
                        <input type="text" class="form-control" name="pic" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
    <script>
        document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, ''); // Hapus semua karakter non-angka
        });
    </script>
@stop
