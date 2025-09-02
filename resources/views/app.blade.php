<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <title>{{ $title }} | Laravel RS</title>

        <!-- Bootstrap CSS -->
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
            integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
            crossorigin="anonymous"
        />
        <!-- Datatable -->
        <link
            rel="stylesheet"
            href="https://cdn.datatables.net/2.3.3/css/dataTables.dataTables.css"
        />
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    </head>
    <body>
        <!-- Sidebar -->
        <aside id="sidebarMenu" class="sidebar px-2">
            <div class="brand">Laravel RS</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a
                        class="nav-link {{
                            $title === 'Rumah Sakit' ? 'active' : ''
                        }}"
                        href="{{ route('rumahsakit') }}"
                    >
                        Rumah Sakit
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link  {{
                            $title === 'Pasien' ? 'active' : ''
                        }}"
                        href="{{ route('pasien') }}"
                    >
                        Pasien
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link  {{
                            $title === 'User' ? 'active' : ''
                        }}"
                        href="{{ route('user') }}"
                    >
                        User
                    </a>
                </li>
            </ul>
        </aside>

        <div id="mainContent" class="main-content">
            <!-- Navbar -->
            <nav
                class="navbar navbar-expand-lg navbar-light bg-light shadow-sm p-3"
            >
                <button id="sidebarToggle" class="btn btn-outline-light mr-2">
                    ☰
                </button>

                <div class="ml-auto d-flex align-items-center dropdown">
                    <a
                        href="#"
                        class="d-flex align-items-center text-body text-decoration-none dropdown-toggle"
                        id="userMenu"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span class="mr-2">Halo, {{ Auth::user()->name }}</span>
                        <img
                            src="{{ asset('img/user.jpg') }}"
                            alt="Profile"
                            class="rounded-circle img-profile"
                        />
                    </a>

                    <div
                        class="dropdown-menu dropdown-menu-right"
                        aria-labelledby="userMenu"
                    >
                        <!-- Button di dropdown -->
                        <button
                            id="darkModeToggle"
                            class="dropdown-item text-dark"
                        >
                            Dark/Light
                        </button>

                        <hr />
                        <a
                            href="#"
                            class="dropdown-item text-danger"
                            data-toggle="modal"
                            data-target="#logoutModal"
                        >
                            Logout
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="container-fluid">@yield('content')</main>

            <!-- Footer -->
            <footer class="footer text-center py-3 shadow-sm mt-auto">
                <div class="container">
                    <small>
                        &copy; {{ date("Y") }} Laravel RS. All rights reserved.
                    </small>
                </div>
            </footer>
        </div>

        <!-- Logout Modal -->
        <div
            class="modal fade"
            id="logoutModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="logoutModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">
                            Konfirmasi Logout
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Tutup"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin logout,
                        <strong>{{ Auth::user()->name }}</strong
                        >?
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Batal
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- JQuery & Bootstrap -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
            integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
            crossorigin="anonymous"
        ></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
            integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+"
            crossorigin="anonymous"
        ></script>
        <!-- Datatable -->
        <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>

        <script>
            // Sidebar toggle
            document
                .getElementById("sidebarToggle")
                .addEventListener("click", function () {
                    document
                        .getElementById("sidebarMenu")
                        .classList.toggle("collapsed");
                    document
                        .getElementById("mainContent")
                        .classList.toggle("expanded");
                });

            // Saat halaman load, cek apakah dark mode pernah diaktifkan
            if (localStorage.getItem("dark-mode") === "enabled") {
                document.body.classList.add("dark-mode");
            }

            // Toggle dark mode
            document
                .getElementById("darkModeToggle")
                .addEventListener("click", function () {
                    document.body.classList.toggle("dark-mode");

                    // Simpan status ke localStorage
                    if (document.body.classList.contains("dark-mode")) {
                        localStorage.setItem("dark-mode", "enabled");
                    } else {
                        localStorage.setItem("dark-mode", "disabled");
                    }
                });

            function showToast(title, message) {
                $("#toastTitle").text(title);
                $("#toastBody").text(message);
                $("#toastMessage").toast("show"); // Bootstrap 4
            }
        </script>

        @yield('scripts')
    </body>
</html>
