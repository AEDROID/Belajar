<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; font-size: 12px; }
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
        <button onclick="window.location.href='{{ route('peminjaman.index') }}'">Kembali</button>
    </div>

    <h2>Laporan Peminjaman</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Jml</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Status Denda</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $peminjaman)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $peminjaman->user->name ?? 'User Deleted' }}</td>
                <td>{{ $peminjaman->alat->nama_alat ?? 'Alat Deleted' }}</td>
                <td>{{ $peminjaman->jumlah }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian_rencana)->format('d-m-Y') }}</td>
                <td>{{ $peminjaman->tanggal_pengembalian_aktual ? \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian_aktual)->format('d-m-Y') : '-' }}</td>
                <td>{{ ucfirst($peminjaman->status) }}</td>
                <td>{{ ucfirst($peminjaman->status_denda) }}</td>
                <td>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
