@extends('adminlte::page')

@section('title', 'Project Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Project Management</h1>
        <div class="card-header text-right">
            <a href="{{ route('projectmanagement.create') }}" class="btn btn-primary">Tambah Proyek</a>
        </div>
    </div>

    <form id="searchForm" class="mt-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="search" name="search" class="form-control" placeholder="Cari proyek..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select id="status" name="status" class="form-control">
                    <option value="">-- Filter Status --</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Tempatkan tabel di dalam div agar bisa di-update dengan AJAX -->
    <div id="projectTable">
        @include('projectmanagement.table', ['projects' => $projects])
    </div>

@stop


@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            function fetchProjects() {
                let search = $('#search').val();
                let status = $('#status').val();

                $.ajax({
                    url: "{{ route('projectmanagement.index') }}",
                    method: "GET",
                    data: {
                        search: search,
                        status: status
                    },
                    success: function(response) {
                        $('#projectTable').html(response);
                    }
                });
            }

            $('#search, #status').on('keyup change', function() {
                fetchProjects();
            });
        });
    </script>
@stop
