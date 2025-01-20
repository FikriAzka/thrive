@extends('adminlte::page')

@section('title', 'Specialist Feedback')

@section('content_header')
    <h1>Specialist Feedback</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-light">
            <h3 class="card-title">
                Pendapat Anda sangat berarti untuk perkembangan produk dan servis kami
            </h3>
        </div>

        <div class="card-body">
            <form id="feedbackForm">
                <div class="form-section mb-4">
                    <h4 class="mb-3">Specialist</h4>

                    <!-- Overall Rating -->
                    <div class="form-group">
                        <label>Berikan penilaian Anda secara keseluruhan terhadap Specialist yang membantu proses onboarding</label>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-sm">Sangat Tidak Puas</span>
                            <div class="btn-group" role="group">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-outline-secondary rounded-circle mx-1" style="width: 40px; height: 40px;">
                                        {{ $i }}
                                    </button>
                                @endfor
                            </div>
                            <span class="text-sm">Sangat Puas</span>
                        </div>
                    </div>

                    <!-- Hospitality Rating -->
                    <div class="form-group">
                        <label>1. Bagaimana penilaian Anda terhadap keramahan, kedisiplinan dan aspek santun yang ditunjukkan oleh tim Specialist kami dalam memberikan pelayanan?</label>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-sm">Sangat Tidak Puas</span>
                            <div class="btn-group" role="group">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-outline-secondary rounded-circle mx-1" style="width: 40px; height: 40px;">
                                        {{ $i }}
                                    </button>
                                @endfor
                            </div>
                            <span class="text-sm">Sangat Puas</span>
                        </div>
                    </div>

                    <!-- Product Knowledge Rating -->
                    <div class="form-group">
                        <label>2. Bagaimana penilaian Anda terhadap product knowledge yang dimiliki oleh specialist kami dalam membantu Anda selama proses onboarding?</label>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-sm">Sangat Tidak Puas</span>
                            <div class="btn-group" role="group">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-outline-secondary rounded-circle mx-1" style="width: 40px; height: 40px;">
                                        {{ $i }}
                                    </button>
                                @endfor
                            </div>
                            <span class="text-sm">Sangat Puas</span>
                        </div>
                    </div>

                    <!-- Problem Solving Rating -->
                    <div class="form-group">
                        <label>3. Bagaimana penilaian Anda terhadap penyelesaian masalah dan solusi yang diberikan specialist kami dalam membantu kesuksesan proses onboarding?</label>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-sm">Sangat Tidak Puas</span>
                            <div class="btn-group" role="group">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-outline-secondary rounded-circle mx-1" style="width: 40px; height: 40px;">
                                        {{ $i }}
                                    </button>
                                @endfor
                            </div>
                            <span class="text-sm">Sangat Puas</span>
                        </div>
                    </div>

                    <!-- Communication Rating -->
                    <div class="form-group">
                        <label>4. Bagaimana penilaian Anda terhadap cara komunikasi dari specialist kami baik verbal maupun non verbal?</label>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-sm">Sangat Tidak Puas</span>
                            <div class="btn-group" role="group">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="btn btn-outline-secondary rounded-circle mx-1" style="width: 40px; height: 40px;">
                                        {{ $i }}
                                    </button>
                                @endfor
                            </div>
                            <span class="text-sm">Sangat Puas</span>
                        </div>
                    </div>

                    <!-- Suggestions -->
                    <div class="form-group">
                        <label>Apa saran dan masukan Anda yang dapat diberikan kepada specialist kami?</label>
                        <textarea class="form-control" rows="4" maxlength="2000"></textarea>
                        <small class="form-text text-muted text-right">0 / 2000</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-default">Previous</button>
                    <button type="button" class="btn btn-primary">Next</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-group .btn.rounded-circle {
            padding: 0;
            line-height: 38px;
        }
        .form-group label {
            font-weight: normal;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle rating button clicks
            $('.btn-group .btn').click(function() {
                $(this).siblings().removeClass('active btn-secondary').addClass('btn-outline-secondary');
                $(this).removeClass('btn-outline-secondary').addClass('active btn-secondary');
            });

            // Handle character count for textarea
            $('textarea').on('input', function() {
                let count = $(this).val().length;
                $(this).next().text(count + ' / 2000');
            });

            // Handle form submission
            $('#feedbackForm').on('submit', function(e) {
                e.preventDefault();
                // Add your form submission logic here
            });
        });
    </script>
@stop