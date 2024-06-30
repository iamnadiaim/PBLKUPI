<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Hutang Piutang</title>
<style>
  /* Add your CSS styles here */
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
  }
  main {
    max-width: 800px;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  #data {
    margin-top: 20px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  thead {
    background-color: #4E73DF;
    color: #fff;
  }
  th, td {
    padding: 12px;
    text-align: left;
  }
  tbody tr:nth-child(even) {
    background-color: #f9f9f9;
  }
  .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
</style>
</head>
<body >
<main>
  <section id="data">
    <div style="width: 100%; text-align: center;">
        <h2 class="text-center">Laporan Hutang Piutang</h2>
        <p class="text-center">{{ $namaUsaha }}</p>
    </div>
    <br>
    <div class="container" style="display: flex; flex-direction: column; justify-content: start; align-items: flex-start">
      <div class="income-summary" style="display: flex; align-items: center;"> 
        <label for="month">Periode:</label>
        <p class="">{{ strtoupper(request('month', \Carbon\Carbon::now()->format('F'))) }} {{ \Carbon\Carbon::now()->format('Y') }}</p>
      </div>
        <div class="income-summary d-flex mt-2" style="display: flex; align-items: center;">
          <label for="month" class="mr-3">Tanggal dibuat :</label>
          <div class="income-value">{{ \Carbon\Carbon::now()->format('d F Y') }}</div>
      </div>
    </div>
    <div class="container mt-4">
      <div class="total-hutang">
        <p style="background-color: red; padding: 10px; color: #fff;" class="bg-danger text-center text-light p-2 col-5 mb-3">Total Hutang</p>
        <div class="col-5" style="height: 40px; border: 1px solid #9ca3af; background-color: #f8fafc; text-align: center; font-weight: bold;">
          <p style="padding: 0px 10px;" class="text-center text-dark">Rp. {{ $totalHutang }}</p>
        </div>
      </div>
      <div class="total-piutang">
        <p style="background-color: #4ade80; padding: 10px; color: #fff;" class="bg-success text-center text-light p-2 col-5 mb-3">Total Piutang</p>
        <div class="col-5" style="height: 40px; border: 1px solid #9ca3af; background-color: #f8fafc; text-align: center; font-weight: bold;">
          <p class="text-center text-dark">Rp. {{ $totalPiutang }}</p>
        </div>
      </div>
    </div>
            <table class="table table-bordered mt-4">
                <!-- Table content -->
            </table>
        </div>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th style="color: #ef4444;">Menerima</th>
                    <th style="color: #4ade80;">Memberi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hutangs as $hutang)
                    <tr>
                        <td>{{ $hutang->tanggal_pinjaman }}</td>
                        <td>{{ $hutang->nama }}</td>
                        <td>
                            @if ($hutang->jumlah_hutang > 0)
                                <span>{{ $hutang->jumlah_hutang }}</span>
                            @else
                                {{ $hutang->jumlah_hutang }}
                            @endif
                        </td>
                        <td>0</td>
                    </tr>
                @endforeach

                @foreach ($piutangs as $piutang)
                    <tr>
                        <td>{{ $piutang->tanggal_pinjaman }}</td>
                        <td>{{ $piutang->nama }}</td>
                        <td>0</td>
                        <td>
                            @if ($piutang->jumlah_piutang > 0)
                                <span class="text-success">{{ $piutang->jumlah_piutang }}</span>
                            @else
                                {{ $piutang->jumlah_piutang }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-primary text-light">
                    <td colspan="2" class="text-center font-weight-bold">Total</td>
                    <td style="color: #ef4444;">Rp. {{ $totalHutang }}</td>
                    <td style="color: #4ade80;">Rp. {{ $totalPiutang }}</td>
                </tr>
            </tfoot>
        </table>
    </section>
</main>
<script>
  window.onload = function() {
      window.print();
  }
</script>
</body>
</html>
