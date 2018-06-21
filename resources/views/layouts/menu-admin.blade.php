<li class="{{ isActiveRoute('admin.dashboard') }}">
    <a href="{{ route('admin.dashboard') }}">
        <i class="material-icons">dashboard</i>
        <span>Dashboard</span>
    </a>
</li>
<li class="{{ isActiveRoute('admin.managers.index') }}">
    <a href="{{ route('admin.managers.index') }}">
        <i class="material-icons">group</i>
        <span>Managers</span>
    </a>
</li>
<li class="{{ isActiveRoute('admin.restaurants.index') }}">
    <a href="{{ route('admin.restaurants.index') }}">
        <i class="material-icons">restaurant</i>
        <span>Restaurants</span>
    </a>
</li>