<!-- Top Navbar -->
<nav class="navbar navbar-dark px-3">
    <button class="btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
    <h5 class="text-white m-0">@yield('page-title', 'Dashboard')</h5>
    <div class="ms-auto">
        <div class="dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i>
                        {{ __('Profile') }}</a></li>
                <li><!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            <i class="bi bi-box-arrow-right"></i> {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</nav>