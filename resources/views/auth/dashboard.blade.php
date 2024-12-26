<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible"
		content="IE=edge">
	<meta name="viewport"
		content="width=device-width, 
				initial-scale=1.0">
	<title>Aplikasi KUPI</title>
	
	<link rel="stylesheet"
		href="dashboard.css">
	<link rel="stylesheet"
		href="responsive.css">
	
		<script>
			function redirectToProduk() {
				window.location.href = "{{ route('produks.index') }}";
			}
		</script>
</head>

<body>

	<!-- for header part -->
	<header>
		<div class="logosec">
		   <div class="logo">Keuangan Pintar</div>
		   <i class="fas fa-bars icn menuicn" id="menuicn" ></i>
		   <img src="images/imageslogokupi.png" class="img" alt="" >
		</div>
	 
		<div class="message">
		   <a href="{{route('profile')}}">
			  <div class="circle"></div>
			  <img src="images/profile.png" class="icn" alt="">
		   </a>
		   <div class="dp">
			  <img src="images/notifikasi.png" class="dpicn" alt="dp">
		   </div>
		</div>
	 </header>	 

	<div class="main-container">
		<div class="navcontainer">
			<nav class="nav">
				<div class="nav-upper-options">
					<a href="/dashboard">
						<div class="nav-option option1">
							
							<h3> Dashboard</h3>
						</div>
					
					</a>

					<div class="nav-option option2" onclick="redirectToProduk()">
						<h3> Produk</h3>
					</div>

					<a href="/pendapatans">
						<div class="nav-option option3">
							<h3> Pendapatan </h3>
						</div>	
					</a>
					
					
					@if (auth()->user()->role->nama_role == 'admin')
					<a href="/beban">
					<div class="nav-option option4">
						<h3> Beban </h3>
					</div>
					</a>

					<a href="/labarugi">
					<div class="nav-option option5">
						<h3> Laba Rugi</h3>
					</div>
					</a>

					<div class="nav-option option6">
						
						<h3> Arus Kas</h3>
					</div>
				<a href="/riwayat">
					<div class="nav-option option7">
						
						<h3> Riwayat</h3>
					</div>
				</a>
					<a href="{{route('tambah-pegawai')}}" class="tambah">
						<div class="nav-option option8">
							<h3> Tambah Pegawai</h3>
						</div>
					</a>
					@endif
					
					<div class="nav-option logout">
						<form action="{{route('logout')}}" method="POST">
							@csrf
						<button type="submit">Logout</button>
					</form>  
					</div>

				</div>
			</nav>
		</div>
		<div class="main">

			<div class="tanggal">
				<p id="tanggal"></p>
			</div>
			<div class="b">
				<h6>Selamat Datang {{ strtoupper(auth()->user()->role->nama_role) }}
				</h6>
			</div>
			
		</div>

	<script src="script.js"></script>
</body>
</html>