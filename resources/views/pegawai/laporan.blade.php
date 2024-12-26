<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Title Here</title>

  <!-- Include your CSS styles if needed -->
  {{-- <link rel="stylesheet" href="path/to/your/styles.css"> --}}
</head>
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
  background-color: #343a40;
  color: #fff;
}

th, td {
  padding: 12px;
  text-align: left;
}

tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

img.rounded-circle {
  border-radius: 50%;
}


    </style>
<body>

  <main>
    <section id="data">
      <table class="table table-striped">
        <thead class="thead-dark">
          <tr>
            <th>No.</th>
            <th>Foto</th>
            <th>Nama Pegawai</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>No Telepon</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($daftarPegawai as $pegawai)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
              @if ($pegawai->img_profile)
              <img src="{{ asset('storage/' . $pegawai->img_profile) }}" alt="Admin" class="rounded-circle" width="50">
              @else
              <img src="{{ asset('images/polosan.png') }}" alt="Admin" class="rounded-circle" width="50">
              @endif 
            </td>
            <td>{{ $pegawai->nama }}</td>
            <td>{{ $pegawai->email }}</td>
            <td>{{ $pegawai->alamat }}</td>
            <td>{{ $pegawai->no_telepon }}</td>
          </tr>
          @endforeach
        </tbody>
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
