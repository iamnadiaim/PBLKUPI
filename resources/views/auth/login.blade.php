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
        }

        p {
            color: #0f172a;
            /* text-align: start; */
        }
    </style>
    <!-- Add Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="wrapper">
            {{-- <div class="d-flex justify-content-end"> --}}

            {{-- </div> --}}
            {{-- @if (session()->has('success')) --}}
            {{-- @endif --}}
            <form action="{{ route('login') }}" class="signup-form" method="POST">
                @csrf
                <h2>Login</h2>
                @if (session()->has('errorLogin'))
                    <p class="text-red">Email Atau Password Anda Salah</p>
                @endif
                <div class="input-box">
                    <input type="text" placeholder="Email" required name="email">
                    @error('email')
                        <p class="text-red">{{ $message }}</p>
                    @enderror
                </div>
                <div class="input-box">
                    <input type="password" placeholder="Password" required name="password">
                </div>
                <div class="input-box button">
                    <input type="submit" value="LOGIN">
                </div>
            </form>
            <p>Belum Punya Akun ? <a href="{{ route('register.index') }}">Daftar Disini </a></p>
        </div>
        <p>
            <img class="image-kupi" src="images/kupi.png"style="width:300px;height:300px;" />
        </p>
    </div>


</body>

</html>
