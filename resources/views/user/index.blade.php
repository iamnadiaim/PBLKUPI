@extends('layouts.app')

@section('title', 'Profile')

@section('contents')

<body>
    @if (session()->has('success'))
    <div class="d-flex justify-content-end">
      <div class="toast my-4 bg-primary" id="myToast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="15000">
        <div class="toast-header bg-primary text-light justify-content-between">
          <div class="toast-body text-ligth">
            {{ session('success') }}
          </div>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    </div>
    @endif
    <div class="container">
        <div class="main-body">  
             
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                              @if (auth()->user()->img_profile)
                             <img src="{{ asset('storage/public/' . $user->img_profile) }}" alt="Admin" class="rounded-circle" width="150">
                              @else
                              <img src="{{ asset('images/polosan.png') }}" alt="Admin" class="rounded-circle" width="150">
                              @endif
                                <div class="mt-3">
                                    <h4>{{ $nama }}</h4>
                                    <p class="text-secondary mb-1">{{ $role }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nama Usaha</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <p class="form-control-static">{{ $namaUsaha }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <p class="form-control-static">{{ $email }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">No Telepon</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <p class="form-control-static">{{ $noTelepon }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Alamat</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <p class="form-control-static">{{ $alamat }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-info" href="{{ route('profileedit') }}">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    var myToast = new bootstrap.Toast(document.getElementById('myToast'));
    myToast.show();
  });
</script>
</body>
@endsection

