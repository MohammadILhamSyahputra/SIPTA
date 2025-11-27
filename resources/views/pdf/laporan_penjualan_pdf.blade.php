<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            margin: 5px 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer table {
            width: auto;
            margin-left: auto;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>Periode: {{ $startDateFormatted }} - {{ $endDateFormatted }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Penjualan</th>
                <th>Untung</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penjualanDetails as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $detail->barang->kode_barang }}</td>
                <td>{{ $detail->barang->nama_barang }}</td>
                <td>{{ $detail->barang->kategori->nama_kategori }}</td>
                <td class="text-center">{{ $detail->jumlah }}</td>
                <td class="text-right">{{ 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">{{ 'Rp ' . number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">{{ 'Rp ' . number_format(($detail->harga_satuan - $detail->barang->harga_beli) * $detail->jumlah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data penjualan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <th style="padding-right: 20px;">TOTAL OMSET</th>
                <td style="padding-right: 20px;" class="text-right">{{ 'Rp ' . number_format($totalOmset, 0, ',', '.') }}</td>
                <th>TOTAL UNTUNG</th>
                <td class="text-right">{{ 'Rp ' . number_format($totalUntung, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

</body>
</html>