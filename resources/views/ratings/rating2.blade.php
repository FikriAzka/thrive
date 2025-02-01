@extends('adminlte::page')

@section('title', 'Specialist Feedback Page 2')

@section('content_header')
    <h1>Rating</h1>
@stop

@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container py-4">
        <div class="card">
            {{-- <div class="card-header">
                <h3 class="card-title mb-0 text-center w-100">
                    Pendapat Anda sangat berarti untuk perkembangan produk dan servis kami
                </h3>
            </div> --}}

            <div class="card-body">
                <form id="feedbackForm" action="{{ route('ratings.store.final', $meeting) }}" method="POST">
                    @csrf
                    <input type="hidden" name="meeting_id" value="{{ $meeting->id }}">

                    <h4 class="section-title">Produk</h4>

                    @foreach (range(6, 8) as $questionNumber)
                        @if ($questionNumber == 6)
                            <hr>
                            <h5><span style="color: red">* </span><strong>Berikan Penilaian Anda secara keseluruhan terhadap Produk</strong></h5>
                        @endif
                        <div class="rating-container">
                            <div class="question-text">
                                @switch($questionNumber)
                                    @case(6)
                                        Penilaian Produk
                                    @break

                                    @case(7)
                                        7. Bagaimana penilaian Anda terhadap ketepatan waktu yang dijanjikan Specialist kami dalam
                                        menyelesaikan proses onboarding?
                                    @break

                                    @case(8)
                                        8. Bagaimana penilaian Anda terhadap keseluruhan proses onboarding yang telah dilakukan?
                                    @break
                                @endswitch
                            </div>
                            <div class="rating-buttons">
                                <span class="rating-label">Sangat Tidak Puas</span>
                                <div class="d-flex gap-2">
                                    <input type="hidden" name="pertanyaan{{ $questionNumber }}" value="">
                                    @foreach (range(1, 5) as $rating)
                                        <button type="button" class="rating-btn" data-question="{{ $questionNumber }}"
                                            data-value="{{ $rating }}">
                                            {{ $rating }}
                                        </button>
                                    @endforeach
                                </div>
                                <span class="rating-label">Sangat Puas</span>
                            </div>
                            @error('pertanyaan' . $questionNumber)
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($questionNumber == 6)
                            <hr>
                            <h5><span style="color: red">* </span><strong>Mohon berikan penilaian detail atas penilaian
                                    tersebut :</strong></h5>
                            <br>
                        @endif
                    @endforeach

                    <div class="form-group mt-4">
                        <label class="question-text">Apa harapan Anda untuk Specialist kami kedepannya?</label>
                        <textarea class="form-control" name="suggestions2" rows="4" maxlength="2000">{{ old('suggestions2') }}</textarea>
                        <div class="char-count">0 / 2000</div>
                        @error('suggestions2')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex mt-4">
                        <button type="submit" class="btn btn-primary btn-navigation ms-auto">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include your existing CSS styles here -->
    <style>
        .rating-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 30px;
        }

        .question-text {
            color: #555;
            font-size: 14px;
            line-height: 1.5;
        }

        .rating-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            width: 100%;
        }

        .rating-label {
            color: #666;
            font-size: 12px;
        }

        .rating-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .d-flex.gap-2 {
            display: flex;
            flex-wrap: wrap;
            gap: 8px !important;
            justify-content: center;
        }

        /* Responsive adjustments */
        @media (min-width: 768px) {
            .rating-container {
                flex-direction: row;
                align-items: center;
            }

            .question-text {
                flex: 1;
            }

            .rating-buttons {
                width: auto;
                min-width: 280px;
                justify-content: flex-end;
            }
        }

        @media (max-width: 767px) {
            .rating-buttons {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .rating-label {
                margin: 5px 0;
            }

            .d-flex.gap-2 {
                margin: 10px 0;
            }

            .rating-btn {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
        }

        /* Keep existing button active state */
        .rating-btn.active {
            background-color: #0d6efd !important;
            color: white !important;
            border-color: #0d6efd !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle rating button clicks
            $('.rating-btn').click(function() {
                const questionNumber = $(this).data('question');
                const value = $(this).data('value');

                // Remove active class from all buttons in this question group
                $(this).closest('.rating-buttons').find('.rating-btn').removeClass('active');
                // Add active class to clicked button
                $(this).addClass('active');
                // Set the hidden input value
                $(`input[name="pertanyaan${questionNumber}"]`).val(value);
            });

            // Handle character count for textarea
            $('textarea[name="suggestions2"]').on('input', function() {
                const count = $(this).val().length;
                $(this).siblings('.char-count').text(`${count} / 2000`);
            });

            // Handle form submission
            $('#feedbackForm').on('submit', function(e) {
                e.preventDefault();

                // Check if all questions are answered
                let unansweredQuestions = [];
                for (let i = 6; i <= 8; i++) {
                    const value = $(`input[name="pertanyaan${i}"]`).val();
                    if (!value) {
                        unansweredQuestions.push(i);
                    }
                }

                if (unansweredQuestions.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Mohon berikan penilaian untuk pertanyaan nomor ${unansweredQuestions.join(', ')}!`
                    });
                    return;
                }

                // If all validations pass, submit the form
                this.submit();
            });
        });
    </script>
@stop
