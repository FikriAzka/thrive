@extends('adminlte::page')

@section('title', 'Kalendar Rapat')

@section('content_header')
    <h1>Kalendar Rapat</h1>
@stop

@section('content')

    <div id='calendar'></div>

@stop

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">

    <style>
        #calendar {
            background-color: white;
            /* Mengatur background menjadi putih */
            padding: 10px;
            /* Opsional: Menambahkan padding untuk estetika */
            border-radius: 5px;
            /* Opsional: Membuat sudut kalender lebih halus */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Opsional: Menambahkan efek bayangan */
        }

        .fc-event {
            background-color: #007bff !important;
            /* Warna background event */
            color: white !important;
            /* Warna teks */
            border: none !important;
            /* Hilangkan border */
            padding: 5px;
            /* Tambahkan padding untuk event */
            border-radius: 5px;
            /* Membuat event lebih bulat */
            font-size: 14px;
            /* Ukuran teks */
        }

        .fc-daygrid-event-dot {
            display: none;
            /* Hilangkan dot jika tidak diperlukan */
        }

        .fc-timegrid-event {
            white-space: nowrap;
            /* Mencegah teks terpotong */
        }
    </style>
@stop

@section('js')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/google-calendar@6.1.5/index.global.min.js"></script>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                googleCalendarApiKey: 'AIzaSyBmz-V3YrbLuNglISLd41yaQs1zh-96sss',
                events: {
                    googleCalendarId: 'fikriazkaa4@gmail.com'
                },
                displayEventTime: false, // Sembunyikan waktu
                // Tambahkan eventMouseEnter untuk tooltip
                eventMouseEnter: function(info) {
                    // Buat tooltip menggunakan Tippy.js
                    tippy(info.el, {
                        content: `Nama Rapat: ${info.event.title}<br>
                          Waktu: ${info.event.start.toLocaleTimeString()}<br>
                          Deskripsi: ${info.event.extendedProps.description || 'Tidak ada deskripsi'}`,
                        allowHTML: true, // Izinkan HTML di tooltip
                        placement: 'top', // Posisi tooltip
                        theme: 'light', // Tema tooltip
                    });
                }


            });
            calendar.render();
        });
    </script>
@stop
