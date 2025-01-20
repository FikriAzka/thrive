@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>

@stop

@section('content')
    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-6">
            <!-- Number of tasks box -->
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Tugas Bulan Ini</span>
                    <span class="info-box-number">19</span>
                </div>
                <div class="info-box-footer">
                    <a href="#" class="text-muted">LIHAT RAPAT</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Number of notes box -->
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-edit"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Jumlah Notulensi Rapat Bulan Ini</span>
                    <span class="info-box-number">3</span>
                </div>
                <div class="info-box-footer">
                    <a href="#" class="text-muted">LIHAT NOTULENSI</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rapat Bulanan list box -->
    <div class="card">
        <div class="card-body p-0">
            <!-- Container with left icon -->
            <div class="d-flex">
                <!-- Left icon section -->
                <div class="bg-danger p-4 d-flex align-items-center justify-content-center"
                    style="min-height: 100%; margin: 10px; border-radius: 5px;">
                    <i class="fas fa-users text-white fa-2x"></i>
                </div>

                <!-- Content section -->
                <div class="p-3">
                    <h5 class="font-weight-bold mb-3">Rapat Bulan Ini</h5>

                    <div>
                        <div class="mb-2 text-success">
                            <span>Rapat Sarana Prasarana </span>
                            <span>(Sudah Lewat)</span>
                            <span class="text-muted">18-Jun-2020</span>
                        </div>

                        <div class="text-success">
                            <span>Rapat RTM </span>
                            <span>(Sudah Lewat)</span>
                            <span>04-Jun-2020</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Tugas Rapat list box -->
    <div class="card">
        <div class="card-body p-0">
            <!-- Container with left icon -->
            <div class="d-flex">
                <!-- Left icon section -->
                <div class="p-4 d-flex align-items-center justify-content-center"
                    style="min-height: 100%; margin: 10px; border-radius: 5px; background-color: #ff7e1a;">
                    <i class="fas fa-edit text-white fa-2x"></i>
                </div>

                <!-- Content section -->
                <div class="p-3">
                    <h5 class="font-weight-bold mb-3">Tugas Rapat </h5>

                    <div>
                        <div class="mb-2 text-success">
                            <span>Tugas Rapat Abdimas SI (Status: Selesai, Deadline 1 Hari Lagi) 25-Jun-2020</span>
                        </div>

                        <div class="text-muted">
                            <span>Tugas Rapat Sarana Prasarana (Tidak Selesai & Sudah Lewat Deadline) 23-Jun-2020</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button for "Lihat Lebih Banyak" -->
            <div class="d-flex justify-content-end mt-1">
                <button class="btn btn-link text-primary">Lihat Lebih Banyak</button>
            </div>
        </div>
    </div>



    <!-- Progress Rapat box -->
    <div class="card">
        <div class="card-body p-0">
            <!-- Container with left icon -->
            <div class="d-flex">
                <!-- Left icon section -->
                <div class="p-4 d-flex align-items-center justify-content-center"
                    style="min-height: 100%; margin: 10px; border-radius: 5px; background-color: #ff7e1a;">
                    <i class="fas fa-edit text-white fa-2x"></i>
                </div>

                <!-- Content section -->
                <div class="p-3">
                    <h5 class="font-weight-bold mb-3">Progress Tugas Rapat</h5>

                    <div>
                        <div class="mb-2">
                            <span>Rapat Sarana Prasarana </span>
                            <span class="text-success">(Sudah Lewat)</span>
                            <span class="text-muted">18-Jun-2020</span>
                        </div>

                        <div>
                            <span>Rapat RTM </span>
                            <span class="text-success">(Sudah Lewat)</span>
                            <span class="text-muted">04-Jun-2020</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">


@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
