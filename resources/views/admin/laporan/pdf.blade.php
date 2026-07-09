<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan SAMIS</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1f2937; }
        h1 { font-size: 16px; margin-bottom: 2px; }
        .subtitle { color: #6b7280; font-size: 10px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { border: 1px solid #e5e7eb; padding: 5px 8px; text-align: left; }
        th { background: #f3f4f6; font-size: 9px; text-transform: uppercase; color: #6b7280; }
        h2 { font-size: 13px; margin-top: 20px; margin-bottom: 8px; border-bottom: 2px solid #4338ca; padding-bottom: 4px; }
    </style>
</head>
<body>
    <h1>Laporan Sistem SAMIS</h1>
    <p class="subtitle">Dicetak pada {{ now()->format('d F Y, H:i') }} WIB</p>

    <h2>Rekap Kelas</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Kelas</th>
                <th>Kode</th>
                <th>Mata Kuliah</th>
                <th>Dosen</th>
                <th>Jml Mahasiswa</th>
                <th>Jml Tugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $k)
                <tr>
                    <td>{{ $k->nama_kelas }}</td>
                    <td>{{ $k->kode_kelas }}</td>
                    <td>{{ $k->mata_kuliah }}</td>
                    <td>{{ $k->dosen->name }}</td>
                    <td>{{ $k->mahasiswa_count }}</td>
                    <td>{{ $k->tugas_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Rekap Tugas</h2>
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kelas</th>
                <th>Pembuat</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Dikumpulkan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tugas as $t)
                <tr>
                    <td>{{ $t->judul }}</td>
                    <td>{{ $t->kelas?->nama_kelas ?? '-' }}</td>
                    <td>{{ $t->pembuat->name }}</td>
                    <td>{{ $t->deadline->format('d M Y') }}</td>
                    <td>{{ $t->labelStatus() }}</td>
                    <td>{{ $t->pengumpulan_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Rekap Mahasiswa</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIM</th>
                <th>Jml Kelas</th>
                <th>Tugas Dikumpulkan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa as $m)
                <tr>
                    <td>{{ $m->name }}</td>
                    <td>{{ $m->nim_nip }}</td>
                    <td>{{ $m->kelas_count }}</td>
                    <td>{{ $m->pengumpulan_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
