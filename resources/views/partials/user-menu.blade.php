<div class="user-dropdown-container">
    <button type="button" class="user-dropdown-toggle" onclick="toggleUserMenu(this, event)">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
    <div class="user-dropdown-menu">
        <div class="user-menu-header">
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ auth()->user()->role === 'worker' ? 'Staff' : ucfirst(auth()->user()->role) }}</div>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('dashboard') }}" class="user-menu-item">Dashboard</a>
            <a href="{{ route('admin.users') }}" class="user-menu-item">Admin Panel</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="user-menu-item text-danger">Logout</button>
        </form>
    </div>
</div>
