<!-- Sidebar -->
<div id="sidebar">
    <div class="logo text-center">
        <img src="{{ asset('images/WhiteLogo.png') }}" alt="S4B Logo" class="img-fluid" style="max-width: 100%;">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                <i class="bi bi-house-door"></i> <span class="nav-text">Dashboard</span>
            </a>
        </li>

        <!-- Booking Events -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle @if(request()->routeIs('booking-events.*')) active @endif"
                data-bs-toggle="collapse" href="#eventMenu" role="button">
                <i class="bi bi-journal-check"></i> <span class="nav-text">Eventi Prenotazione</span>
            </a>
            <div class="collapse @if(request()->routeIs('booking-events.*')) show @endif" id="eventMenu"
                data-bs-parent="#sidebar">
                <ul class="nav flex-column ps-3">
                    <li class="nav-item">
                        <a href="{{ route('booking-events.calendar') }}"
                            class="nav-link @if(request()->routeIs('booking-events.calendar')) active @endif">
                            <i class="bi bi-calendar"></i> Calendario Eventi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('booking-events.index') }}"
                            class="nav-link @if(request()->routeIs('booking-events.index')) active @endif">
                            <i class="bi bi-list"></i> Elenco Eventi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('booking-events.create') }}"
                            class="nav-link @if(request()->routeIs('booking-events.create')) active @endif">
                            <i class="bi bi-ticket"></i> Aggiungi Evento
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Customers -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle @if(request()->routeIs('customers.*')) active @endif"
                data-bs-toggle="collapse" href="#customerMenu" role="button">
                <i class="bi bi-person"></i> <span class="nav-text">Clienti</span>
            </a>
            <div class="collapse @if(request()->routeIs('customers.*')) show @endif" id="customerMenu"
                data-bs-parent="#sidebar">
                <ul class="nav flex-column ps-3">
                    <li class="nav-item">
                        <a href="{{ route('customers.index') }}"
                            class="nav-link @if(request()->routeIs('customers.index')) active @endif">
                            <i class="bi bi-list"></i> Visualizza Clienti
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customers.create') }}"
                            class="nav-link @if(request()->routeIs('customers.create')) active @endif">
                            <i class="bi bi-person-plus"></i> Aggiungi Cliente
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Assets -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle @if(request()->routeIs('assets.*')) active @endif"
                data-bs-toggle="collapse" href="#assetMenu" role="button">
                <i class="bi bi-box"></i> <span class="nav-text">Risorse</span>
            </a>
            <div class="collapse @if(request()->routeIs('assets.*')) show @endif" id="assetMenu"
                data-bs-parent="#sidebar">
                <ul class="nav flex-column ps-3">
                    <li class="nav-item">
                        <a href="{{ route('assets.index') }}"
                            class="nav-link @if(request()->routeIs('assets.index')) active @endif">
                            <i class="bi bi-list"></i> Visualizza Risorse
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('assets.create') }}"
                            class="nav-link @if(request()->routeIs('assets.create')) active @endif">
                            <i class="bi bi-bank"></i> Aggiungi Risorsa
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Reporting -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle @if(request()->routeIs('income.*')) active @endif"
                data-bs-toggle="collapse" href="#reportMenu" role="button">
                <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Reportistica</span>
            </a>
            <div class="collapse @if(request()->routeIs('income.*')) show @endif" id="reportMenu"
                data-bs-parent="#sidebar">
                <ul class="nav flex-column ps-3">
                    <a href="{{ route('income.report') }}"
                        class="nav-link @if(request()->routeIs('income.report')) active @endif">
                        <i class="bi bi-clipboard-data"></i> Rapporto Entrate
                    </a>
                </ul>
            </div>
        </li>

        <!-- Users -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle @if(request()->routeIs('users.*')) active @endif"
                data-bs-toggle="collapse" href="#userMenu" role="button">
                <i class="bi bi-people"></i> <span class="nav-text">Utenti</span>
            </a>
            <div class="collapse @if(request()->routeIs('users.*')) show @endif" id="userMenu"
                data-bs-parent="#sidebar">
                <ul class="nav flex-column ps-3">
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                            class="nav-link @if(request()->routeIs('users.index')) active @endif">
                            <i class="bi bi-list"></i> Visualizza Utenti
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.create') }}"
                            class="nav-link @if(request()->routeIs('users.create')) active @endif">
                            <i class="bi bi-person-plus"></i> Aggiungi Utente
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>