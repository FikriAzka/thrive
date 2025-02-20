<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Rating</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>


    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title">Data Diri</h3>
            </div>

            <div class="card-body">
                <form action="{{ route('ratings.store-data', $meeting) }}" method="POST">
                    @csrf
                    @auth
                        <div class="d-flex justify-content-end align-items-center mb-3">
                            <label class="form-label me-2">Menerima Jawaban</label>
                            <label class="switch">
                                <input type="checkbox" id="toggleAccess"
                                    {{ Cache::get("allow_ratings_{$meeting->id}", true) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    @endauth

                    <script>
                        document.getElementById('toggleAccess').addEventListener('change', function() {
                            fetch("{{ route('ratings.toggle-access', $meeting->id) }}", {

                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        allow_ratings: this.checked
                                    })
                                }).then(response => response.json())
                                .then(data => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Akses halaman diperbarui!',
                                        text: data.message,
                                    });
                                });
                        });
                    </script>



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

    <!-- Script diletakkan di akhir body -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
    <script>
        document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, ''); // Hapus semua karakter non-angka
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if ($errors->any())
                let errorMessage = "";
                @foreach ($errors->all() as $error)
                    errorMessage += "{{ $error }}\n";
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: errorMessage.trim(),
                });
            @endif
        });
    </script>


</body>

</html>
