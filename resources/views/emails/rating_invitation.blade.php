<h1>Halo,</h1>
<p>Terima kasih telah mengikuti meeting <strong>{{ $meeting->title }}</strong> pada {{ $meeting->date }}.</p>
<p>Mohon berikan feedback dengan klik link di bawah ini sebelum
    <strong>{{ now()->addDay()->format('d-m-Y H:i') }}</strong>:</p>

<a href="{{ route('ratings.create', ['meeting' => $meeting->id]) }}">
    Klik di sini untuk mengisi rating
</a>
<hr>
<code>{{ route('ratings.create', ['meeting' => $meeting->id]) }}</code>


<p>Terima kasih!</p>
