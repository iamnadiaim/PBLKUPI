<style>
  .scrollable-content {
    height: 300px; /* Set fixed height */
    overflow-y: auto; /* Enable vertical scrolling */
    scrollbar-gutter: stable both-edges; /* Ensures space for scrollbar */
  }

  /* Optional: Custom styles for the scrollbar */
  .scrollable-content::-webkit-scrollbar {
    width: 8px; /* Width of the scrollbar */
  }
  .scrollable-content::-webkit-scrollbar-thumb {
    background-color: #ffff; /* Color of the scrollbar */
    border-radius: 4px; /* Rounded corners */
  }
  .scrollable-content::-webkit-scrollbar-thumb:hover {
    background-color: #888; /* Color on hover */
  }
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">

    <!-- Nav Item - Notifications -->
    <li class="nav-item dropdown no-arrow mx-1">
      <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        @if (auth()->user()->usaha->unreadNotifications->count() > 0)
          <span class="badge badge-danger badge-counter">{{ auth()->user()->usaha->unreadNotifications->count() }}</span>
        @endif
      </a>

      <!-- Dropdown - Alerts -->
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
      <h6 class="dropdown-header">Notifikasi</h6>

        <!-- Scrollable content for notifications -->
        <div class="scrollable-content" style="padding: 0;">
          @if(auth()->user()->usaha->unreadNotifications->isEmpty())
            <div class="dropdown-item d-flex align-items-center justify-content-center" style="height: 100%;">
              <span class="font-small text-gray">Tidak ada notifikasi</span>
            </div>
            @else
            @foreach(auth()->user()->usaha->unreadNotifications as $notif)
              <a class="dropdown-item d-flex align-items-center">
                <div class="mr-3">
                  <div class="icon-circle bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                  </div>
                </div>
                <div>
                  <div class="small text-gray-500">{{ $notif->created_at->format('d M Y H:i') }}</div>
                  <span class="font-weight-bold">{{ $notif->data['message'] ?? 'No message available' }}</span>
                </div>
              </a>
            @endforeach
          @endif
        </div>

        <!-- Button to delete all notifications -->
        <form method="POST" action="{{ route('notifications.deleteAll') }}" class="px-3 py-2">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm w-100">Hapus Semua Notifikasi</button>
        </form>
      </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
          {{ auth()->user()->nama }}
          <br>
          <small>{{ auth()->user()->level }}</small>
        </span>
        @if (auth()->user()->img_profile)
          <img class="img-profile rounded-circle" src="{{ asset('storage/' . auth()->user()->img_profile) }}">
        @else
          <img class="img-profile rounded-circle" src="{{ asset('images/polosan.png') }}">
        @endif
      </a>
      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="{{ route('profile') }}">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
          Profile
        </a>
          
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('logout') }}">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Logout
        </a>
      </div>
    </li>

  </ul>

</nav>
