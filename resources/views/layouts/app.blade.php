<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- jQuery (Required by jQuery UI) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI CSS (for styling the datepicker) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- jQuery UI JS (includes datepicker) -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (already included if you're using datepicker) -->
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Bottle Green Gradient Theme */
        :root {
            --sidebar-bg: linear-gradient(to bottom, #006A4E 0%, #23977c 100%);
            --navbar-bg: linear-gradient(to bottom, #007D5C 0%, #30a187 100%);
            --content-bg: #E8F6EF;
            --text-color: white;
            --burger-bg: transparent;
        }

        /* Navbar (Top bar) */
        .navbar {
            height: 56px;
            background: var(--navbar-bg) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999;
            transition: all 0.3s;
            width: calc(100% - 250px);
            margin-left: 250px;
            display: flex;
            align-items: center;
            padding: 0 15px;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.2);
        }

        .navbar.collapsed {
            width: calc(100% - 80px);
            margin-left: 80px;
        }

        /* Sidebar */
        #sidebar {
            width: 250px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--text-color);
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            padding-bottom: 20px;
            transition: all 0.3s;
            overflow-y: auto;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        #sidebar.collapsed {
            width: 80px;
        }

        #sidebar .nav-link {
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border-radius: 5px;
            transition: all 0.3s;
            text-decoration: none;
        }

        #sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            /* Slightly stronger highlight */
            font-weight: bold;
            border-radius: 5px;
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }

        /* Ensure active submenu item is distinguishable */
        #sidebar .submenu .nav-link.active {
            background: rgba(255, 255, 255, 0.3);
            /* Different highlight for submenus */
            font-weight: bold;
            padding-left: 25px;
            border-left: 4px solid #fff;
        }

        /* Ensure active parent menu is also highlighted when submenu is active */
        #sidebar .nav-item.has-submenu>.nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }

        /* Fix collapsing issue: hide text when collapsed, only show icons */
        #sidebar.collapsed .nav-text {
            display: none;
        }

        /* Fix submenu text showing in mobile view */
        #sidebar.collapsed .submenu {
            display: none !important;
        }

        /* Submenu */
        .submenu {
            display: none;
            padding-left: 20px;
        }

        .submenu.open {
            display: block;
        }

        /* Main Content */
        #content {
            margin-left: 250px;
            transition: all 0.3s;
            background-color: var(--content-bg);
            min-height: 100vh;
            padding: 70px 20px 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #content.collapsed {
            margin-left: 80px;
        }

        /* Burger Menu */
        #toggleSidebar {
            background-color: var(--burger-bg);
            border: none;
            color: var(--text-color);
            font-size: 24px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar {
                width: 80px;
                left: -80px;
            }

            #sidebar.collapsed {
                left: 0;
            }

            #content {
                margin-left: 0;
                width: 100%;
            }

            #content.collapsed {
                margin-left: 80px;
                width: calc(100% - 80px);
            }

            .navbar {
                width: 100%;
                margin-left: 0;
            }

            .navbar.collapsed {
                width: 100%;
            }
        }
    </style>

</head>

<body>
    <!-- Admin Sidebar -->
    @include('layouts.sidebar')


    <!-- Admin Navigation -->
    @include('layouts.navigation')



    <!-- Page Content -->
    <main id="content">
        {{ $slot }}
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let currentPath = window.location.pathname;

            // Highlight Active Menu Item Based on URL
            document.querySelectorAll("#sidebar .nav-link").forEach(link => {
                let linkPath = link.getAttribute("href");

                if (linkPath && currentPath.includes(linkPath)) {
                    link.classList.add("active");

                    // If the active link is inside a submenu, also activate the parent menu
                    let parentMenu = link.closest(".submenu");
                    if (parentMenu) {
                        parentMenu.classList.add("open"); // Keep submenu expanded
                        parentMenu.previousElementSibling.classList.add("active"); // Highlight parent menu
                    }
                }
            });

            // Toggle Sidebar Collapse
            document.getElementById("toggleSidebar").addEventListener("click", function () {
                document.getElementById("sidebar").classList.toggle("collapsed");
                document.getElementById("content").classList.toggle("collapsed");
                document.querySelector(".navbar").classList.toggle("collapsed");
            });

            // Submenu Toggle on Click
            document.querySelectorAll("#sidebar .has-submenu > .nav-link").forEach(menu => {
                menu.addEventListener("click", function (e) {
                    e.preventDefault();
                    let submenu = this.nextElementSibling;

                    if (submenu) {
                        submenu.classList.toggle("open");

                        // Close other submenus (optional behavior)
                        document.querySelectorAll("#sidebar .submenu").forEach(sub => {
                            if (sub !== submenu) {
                                sub.classList.remove("open");
                            }
                        });
                    }
                });
            });
        });



    </script>


</body>

</html>