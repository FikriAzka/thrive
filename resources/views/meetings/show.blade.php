@extends('adminlte::page')

@section('title', 'Show Rapat')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar Rapat</h1>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 mr-3">Status : @if ($meeting->status == 'scheduled')
                            <span class="badge rounded-pill bg-warning">Scheduled</span>
                        @elseif ($meeting->status == 'completed')
                            <span class="badge rounded-pill bg-success">Completed</span>
                        @elseif ($meeting->status == 'cancelled')
                            <span class="badge rounded-pill bg-danger">Cancelled</span>
                        @endif
                    </h5>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <a href="#" class="btn btn-info btn-sm">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! $meeting->notes !!} <!-- Content yang bisa diedit akan muncul di sini -->

            <div class="text-right mt-4">
                <form action="{{ route('meetings.complete', $meeting->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">
                        Done
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Notulensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('meetings.savenotes', $meeting->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <textarea id="editor" name="notes">{!! $meeting->notes !!}</textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@stop
