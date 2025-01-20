<!DOCTYPE html>
<html>
<head>
    <title>Undangan Rapat</title>
</head>
<body>
    <h1>Undangan Rapat: {{ $meeting['nama_rapat'] }}</h1>
    <p><strong>Agenda:</strong> {{  strip_tags($meeting->agenda_rapat) }}</p>
    <p><strong>Tanggal:</strong> {{ $meeting['tanggal_mulai'] }} - {{ $meeting['tanggal_berakhir'] }}</p>
    <p><strong>Waktu:</strong> {{ $meeting['jam_mulai'] }} - {{ $meeting['jam_berakhir'] }}</p>
    <p><strong>Link Google Meet:</strong> 
        <a href="{{ $googleMeetLink }}" target="_blank">{{ $googleMeetLink }}</a>
    </p>
</body>
</html>
