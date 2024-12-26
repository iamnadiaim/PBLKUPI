<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kupi2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM produks";
$result = $conn->query($sql);

$conn->close();
?>

@extends('layouts.app')

@section('title', 'Laporan Produk')

@section('contents')

<div class="container">
    <div class="box-container">
        <div style="margin-top: 50px"></div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the retrieved product data and display it in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["nama_produk"] . "</td>";
                    echo "<td>" . $row["harga"] . "</td>";
                    echo "<td>" . $row["stok"] . "</td>";
                    echo "</tr>";
                }
                ?>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
        <a href="{{ route('produks.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

@endsection
