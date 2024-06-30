<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arus Kas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }
        .header {
            background-color: #4472C4;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .section-header {
            background-color: #d9e1f2;
            font-weight: bold;
        }
        .sub-item {
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ Auth::user()->usaha->nama_usaha }}</h1>
            <p>Laporan Arus Kas</p>
            <p>Periode Berakhir {{ strtoupper(request('month', \Carbon\Carbon::now()->format('F'))) }} {{ \Carbon\Carbon::now()->format('Y') }}</p>
        </div>
        <table>
            <tr>
                <th>AKUN</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>Saldo Awal Kas :</td>
                <td>{{ number_format($cekSaldo->saldo, 0, ',', '.') }}</td>
            </tr>
            <tr class="section-header">
                <td>A. Kas yang dikeluarkan :</td>
                <td></td>
            </tr>
            @foreach($kategoris as $kategori)
                <tr>
                    <td class="sub-item">{{ $kategori->nama }}</td>
                    <td>{{ number_format($kategori->bebans->sum('harga'), 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="section-header">
                <td>B. Kas yang diterima :</td>
                <td></td>
            </tr>
            <tr>
                <td class="sub-item">Pendapatan</td>
                <td>{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
            <tr class="section-header">
                <td>Pergerakan Bersih Atas Kas (A + B)</td>
                <td>{{ number_format($totalPendapatan - $totalBeban, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Saldo Akhir Kas :</td>
                <td>{{ number_format($saldoAkhir, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>