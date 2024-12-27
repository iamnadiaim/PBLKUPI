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
    background-color: #888; /* Color of the scrollbar */
    border-radius: 4px; /* Rounded corners */
  }
  .scrollable-content::-webkit-scrollbar-thumb:hover {
    background-color: #555; /* Color on hover */
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
    @if (auth()->user()->role->nama_role == 'admin')
      <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="markNotificationsAsRead()">
          <i class="fas fa-bell fa-fw"></i>
          @if (auth()->user()->usaha->unreadNotifications->count() > 0)
            <span class="badge badge-danger badge-counter">{{ auth()->user()->usaha->unreadNotifications->count() }}</span>
          @endif
        </a>

        <!-- Dropdown - Alerts -->
        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
          <h6 class="dropdown-header">Notifikasi</h6>

          <div class="scrollable-content" style="padding: 0;">
            @php
              $todayNotifications = auth()->user()->usaha->notifications->filter(function($notif) {
                  return $notif->created_at->isToday() || $notif->unread();
              });
            @endphp

            @if($todayNotifications->isEmpty())
              <div class="dropdown-item d-flex align-items-center justify-content-center" style="height: 100%;">
                <span class="font-small text-gray">Tidak ada notifikasi hari ini</span>
              </div>
            @else
              @foreach($todayNotifications as $notif)
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

          <!-- Button to toggle all notifications -->
          <form id="toggleForm" class="px-3 py-2">
            <button type="button" id="toggleNotifications" class="btn btn-secondary btn-sm w-100">Notifikasi Lainnya</button>
          </form>
        </div>
      </li>
    @endif

    <script>
      // JavaScript to toggle between today's notifications and all notifications
      document.getElementById('toggleNotifications').addEventListener('click', function() {
          const toggleButton = document.getElementById('toggleNotifications');
          const content = document.querySelector('.scrollable-content');
          if (toggleButton.textContent === 'Notifikasi Lainnya') {
              toggleButton.textContent = 'Sembunyikan Semua Notifikasi';
              content.innerHTML = `
                  @if(auth()->user()->role->nama_role == 'admin')
                      @foreach(auth()->user()->usaha->notifications as $notif)
                          <a class="dropdown-item d-flex align-items-center">
                              <div class="mr-3">
                                  <div class="icon-circle bg-primary">
                                      <i class="fas fa-file-alt text-white"></i>
                                  </div>
                              </div>
                              <div>
                                  <div class="small text-gray-500">{{ $notif->created_at->format('d M Y H:i') }}</div>
                                  <span>{{ $notif->data['message'] ?? 'No message available' }}</span>
                              </div>
                          </a>
                      @endforeach
                  @endif
              `;
          } else {
              toggleButton.textContent = 'Notifikasi Lainnya';
              content.innerHTML = `
                  @if(auth()->user()->role->nama_role == 'admin')
                      @foreach($todayNotifications as $notif)
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
              `;
          }
      });
    
      // Function to mark notifications as read when the bell icon is clicked
      function markNotificationsAsRead() {
          fetch('/notifications/read', { 
              method: 'POST', 
              headers: { 
                  'X-CSRF-TOKEN': '{{ csrf_token() }}' 
              } 
          })
          .then(response => {
              if (response.ok) {
                  const badgeCounter = document.querySelector('.badge-counter');
                  if (badgeCounter) {
                      badgeCounter.remove(); // Remove the badge
                  }
              }
          });
      }
    </script>

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
          <img class="img-profile rounded-circle" src="{{ asset('storage/public/' . auth()->user()->img_profile) }}">
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
