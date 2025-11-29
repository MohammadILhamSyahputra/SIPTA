<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #e9ecef;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 25px;
            text-align: right;
        }
        .footer table {
            width: 450px;
            margin-left: auto;
            border: none;
            font-size: 11px;
        }
        .footer table th, .footer table td {
            border: none;
            padding: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PENJUALAN TOKO ARDIYANA</h1>
        <p>Periode: {{ $startDateFormatted }} - {{ $endDateFormatted }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Kode Barang</th>
                <th style="width: 25%;">Nama Barang</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 5%;" class="text-center">QTY</th>
                <th style="width: 10%;" class="text-right">Harga Satuan</th>
                <th style="width: 15%;" class="text-right">Total Penjualan</th>
                <th style="width: 10%;" class="text-right">Untung</th>
                <th style="width: 10%;" class="text-right">Margin (%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tableData as $index => $data)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                
                <td>{{ $data['kode_barang'] }}</td>
                <td>{{ $data['nama_barang'] }}</td>
                <td>{{ $data['kategori'] }}</td>
                <td class="text-center">{{ $data['jumlah'] }}</td> 
                
                <td class="text-right">{{ 'Rp ' . number_format($data['harga_satuan'], 0, ',', '.') }}</td>
                
                <td class="text-right">{{ 'Rp ' . number_format($data['total_penjualan'], 0, ',', '.') }}</td>
                <td class="text-right">{{ 'Rp ' . number_format($data['untung'], 0, ',', '.') }}</td>
                
                <td class="text-right">{{ number_format($data['margin_persentase'], 2, ',', '.') . ' %' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data penjualan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <th style="text-align: right; width: 150px; background-color: #fff;">TOTAL OMSET (Penjualan)</th>
                <td style="text-align: right; font-weight: bold; width: 100px;">{{ 'Rp ' . number_format($totalOmset, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th style="text-align: right; width: 150px; background-color: #fff;">TOTAL UNTUNG (Keuntungan)</th>
                <td style="text-align: right; font-weight: bold; width: 100px;">{{ 'Rp ' . number_format($totalUntung, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th style="text-align: right; width: 150px; background-color: #fff;">MARGIN KEUNTUNGAN</th>
                <td style="text-align: right; font-weight: bold; width: 100px;">{{ number_format($totalMarginPersentase, 2, ',', '.') . ' %' }}</td>
            </tr>
        </table>
    </div>

</body>
</html>