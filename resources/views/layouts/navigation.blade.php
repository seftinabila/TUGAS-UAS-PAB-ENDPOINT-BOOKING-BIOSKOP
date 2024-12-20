<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2>Admin</h2>
    </div>
    <ul class="sidebar-nav">
        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        <li class="{{ request()->routeIs('films.index','films.create') ? 'active' : '' }}">
            <a href="{{ route('films.index') }}">Films</a>
        </li>
        <li class="{{ request()->routeIs('screenings.index') ? 'active' : '' }}">
            <a href="{{ route('screenings.index') }}">Screening</a>
        </li>
        <li class="{{ request()->routeIs('bookings.index','bookings.create','bookings.edit') ? 'active' : '' }}">
            <a href="{{ route('bookings.index') }}">Booking</a>
        </li>
        <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}">Users</a>
        </li>
    </ul>
</div>
