<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Alat</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
        .print-btn { margin-bottom: 20px; }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="print-btn">
        <button onclick="window.print()">Cetak</button>
        <button onclick="window.location.href='{{ route('alat.index') }}'">Kembali</button>
    </div>

    <h2>Laporan Data Alat</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Denda (Per Hari)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alats as $alat)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $alat->nama_alat }}</td>
                <td>{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $alat->stok }}</td>
                <td>Rp {{ number_format($alat->denda_per_hari, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
