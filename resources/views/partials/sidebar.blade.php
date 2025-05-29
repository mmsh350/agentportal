<nav class="sidebar sidebar-offcanvas mt-0" id="sidebar">
    <!-- User Profile Section -->
    <div class="sidebar-profile text-center p-3">
        @if (auth()->user()->profile_pic)
            <img src="{{ 'data:image/jpeg;base64,' . auth()->user()->profile_pic }}" alt="Profile Picture"
                class="rounded-circle shadow" style="width: 80px; height: 80px; object-fit: cover;">
        @else
            <i class="bi bi-person-circle" style="font-size: 3rem; color: #fff;"></i>
        @endif

        <div class="sidebar-profile-info mt-2">
            <span class="sidebar-profile-name truncate-text">{{ auth()->user()->name }}</span>
            <span class="sidebar-profile-email text-light truncate-text">
                <small>{{ auth()->user()->email }}</small>
            </span>
        </div>
        <div class="d-block d-sm-none mt-1">
            <small>Referral Code:</small>
            <p class="badge bg-danger">{{ ucwords(auth()->user()->referral_code) }}</p>
        </div>
    </div>

    <ul class="nav">

        <!-- Dashboard Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.dashboard') }}">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.wallet') }}">
                <i class="mdi mdi-wallet menu-icon"></i>
                <span class="menu-title">Fund Wallet</span>
            </a>
        </li>

        <!-- Verification Section -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.verify-nin') }}">
                <i class="mdi mdi-fingerprint menu-icon"></i>
                <span class="menu-title">Verify NIN</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.verify-nin-phone') }}">
                <i class="mdi mdi-phone menu-icon"></i>
                <span class="menu-title">Verify NIN PHONE</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.ipe') }}">
                <i class="mdi mdi-magnify menu-icon"></i>
                <span class="menu-title">IPE</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.verify-bvn') }}">
                <i class="mdi mdi-fingerprint menu-icon"></i>
                <span class="menu-title">Verify BVN</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.personalize-nin') }}">
                <i class="mdi mdi-magnify menu-icon"></i>
                <span class="menu-title">Personalize</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.bvn-enrollment') }}">
                <i class="mdi mdi-account-plus menu-icon"></i>

                <span class="menu-title">BVN User</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.support') }}">
                <i class="mdi mdi-lifebuoy menu-icon"></i>
                <span class="menu-title">Support</span>
            </a>
        </li>
        <!-- Admin Section -->
        @if (auth()->user()->role == 'admin')
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="toggleSubmenu(event, 'adminSubmenu')">
                    <i class="mdi mdi-cog-outline menu-icon"></i>
                    <span class="menu-title">Manage</span>
                    <i class="mdi mdi-chevron-down ms-auto"></i>
                </a>
                <ul class="sub-menu nav flex-column ps-4" id="adminSubmenu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.services.index') }}">
                            <i class="mdi mdi-pencil menu-icon"></i> Services
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        <!-- Logout Section -->
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <a class="nav-link d-flex align-items-center" style="margin-left:14px;" href="#"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="mdi mdi-logout menu-icon"></i>
                    <span class="menu-title">Logout</span>
                </a>
            </form>
        </li>
    </ul>
</nav>
