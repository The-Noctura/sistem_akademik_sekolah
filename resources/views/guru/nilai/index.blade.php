<h1>Daftar Nilai</h1>
@foreach($nilai as $n)
    <p>{{ $n->siswa->name }} - {{ $n->mataPelajaran->nama_mapel }} - {{ $n->nilai }}</p>
@endforeach