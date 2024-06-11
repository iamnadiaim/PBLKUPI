<!DOCTYPE html>
<html>
<head>
    <title>Laporan Laba Rugi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<div class="row d-flex justify-content-center mb-5">
    <div class="col-md-9">
        <div class="card-header text-center border">
    <div class="mx-auto" style="width: fit-content;">
        <h2>TOKO {{ $namaUsaha }}</h2>
        <h2>Laporan Laba Rugi</h2>
    </div>


    <div class="card-body mx-auto px-5">
    <table class="mx-auto">
        <tr>
            <th>Pendapatan</th>
        </tr>
        <tr>
            <td>Total Pendapatan</td>
            <td>{{ $totalPendapatan }}</td>
        </tr>
    </table>

    <table class="mx-auto">
        <tr>
            <th>Pengeluaran</th>
        </tr>
        @foreach ($kategoris as $kategori)
            <tr>
                <td>{{ $kategori->nama }}</td>
                <td>{{ $kategori->bebans->sum('harga') }}</td>
            </tr>
        @endforeach
        <tr>
            <td>Total Beban</td>
            <td>{{ $totalBeban }}</td>
        </tr>
    </table>

    <table class="mx-auto">
        <tr>
            <td>
                @if ($labaRugi >= 0)
                    Laba
                @else
                    Rugi
                @endif
            </td>
            <td>{{ $labaRugi }}</td>
        </tr>
    </table>
</div>

        </div>
    </div>
</div>

</body>
</html>
