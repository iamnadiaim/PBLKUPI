<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi KUPI</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .text-red {
            color: crimson;
            text-align: start;
        }

        .wrapper {
            margin-top: 3rem;
            margin-bottom: 3rem;
        }
    </style>
    <!-- Add Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container">

        <div class="wrapper">
            <form action="{{ route('register') }}" class="signup-form" method="POST">
                @csrf
                <h2>Sign Up</h2>
                <h6>Selamat Datang Di Aplikasi KUPI</h6>
                <div class="input-box">
                    <input type="text" placeholder="Nama" required name="nama" value="{{ old('nama') }}">
                    @error('nama')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="text" placeholder="Alamat" required name="alamat" value="{{ old('alamat') }}">
                    @error('alamat')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="text" placeholder="Nama Usaha" required name="nama_usaha"
                        value="{{ old('nama_usaha') }}">
                    @error('nama_usaha')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="text" placeholder="No Telepon" required name="no_telepon"
                        value="{{ old('no_telepon') }}">
                    @error('no_telepon')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="text" placeholder="Email" required name="email" value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="password" placeholder="Password" required name="password">
                    @error('password')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-box button">
                    <input type="submit" value="Create an Account">
                </div>
                <div class="text">
                    <h5>Sudah Punya Akun? <a href="/login">Login Sekarang</a></h5>
                </div>
            </form>
        </div>
        <p>
            <img class="image-kupi" src="images/kupi.png"style="width:300px;height:300px;" />
        </p>
    </div>
</body>

</html>
