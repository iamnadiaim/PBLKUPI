<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            text-align: center;
            margin: 10px 0;
        }
        h1 {
            font-size: 18px;
            font-weight: normal;
        }
        h2 {
            font-size: 16px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .header {
            background-color: #4472C4;
            color: white;
            padding: 10px;
            font-weight: bold;
        }
        td {
            padding: 5px 10px;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>TOKO {{ $namaUsaha }}</h1>
        <h2>Laporan Laba Rugi</h2>
        <h2>{{ strtoupper(request('month', \Carbon\Carbon::now()->format('F'))) }} {{ \Carbon\Carbon::now()->format('Y') }}</h2>
        
        <table>
            <tr>
                <td class="header" colspan="2">Pendapatan</td>
            </tr>
            <tr>
                <td>Total Pendapatan</td>
                <td>{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="header" colspan="2">Pengeluaran</td>
            </tr>
            @foreach ($kategoris as $kategori)
            <tr>
                <td>{{ $kategori->nama }}</td>
                <td>{{ number_format($kategori->bebans->sum('harga'), 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td class="header" colspan="2">Total Beban</td>
            </tr>
            <tr>
                <td class="total">Total Beban</td>
                <td>{{ number_format($totalBeban, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="total">
                    @if ($labaRugi >= 0)
                        Laba Bersih
                    @else
                        Rugi Bersih
                    @endif
                </td>
                <td>{{ number_format(abs($labaRugi), 0, ',', '.') }}</td>
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