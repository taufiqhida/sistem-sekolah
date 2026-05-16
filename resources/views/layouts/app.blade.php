<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMK At Kausar') | Sistem Informasi Akademik</title>
    <meta name="description" content="Sistem Informasi Akademik dan Absensi Siswa SMK At Kausar">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- SB Admin 2 -->
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --warning: #f6c23e;
            --danger:  #e74a3b;
            --info:    #36b9cc;
        }

        body { font-family: 'Inter', sans-serif; }

        #accordionSidebar {
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
        }

        .sidebar-brand-text { font-weight: 800; letter-spacing: 0.05em; }

        .nav-item .nav-link:hover,
        .nav-item .nav-link.active {
            background: rgba(255,255,255,0.12) !important;
            border-radius: 8px;
            margin: 0 0.5rem;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }

        .border-left-primary  { border-left: 4px solid var(--primary) !important; }
        .border-left-success  { border-left: 4px solid var(--success) !important; }
        .border-left-warning  { border-left: 4px solid var(--warning) !important; }
        .border-left-danger   { border-left: 4px solid var(--danger)  !important; }
        .border-left-info     { border-left: 4px solid var(--info)    !important; }

        .table thead th {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
        }

        .table-hover tbody tr:hover { background-color: rgba(78,115,223,0.05); }

        .badge { font-size: 0.75rem; font-weight: 600; border-radius: 6px; }

        .btn { border-radius: 8px; font-weight: 600; font-size: 0.875rem; transition: all 0.2s; }
        .btn:hover { transform: translateY(-1px); }

        .badge-counter {
            position: absolute;
            transform: scale(0.7);
            transform-origin: top right;
            right: 0.25rem;
            top: 0.25rem;
        }

        .avatar-circle {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status-hadir { background: #d4edda; color: #155724; border-radius: 6px; padding: 2px 8px; }
        .status-izin  { background: #d1ecf1; color: #0c5460; border-radius: 6px; padding: 2px 8px; }
        .status-sakit { background: #fff3cd; color: #856404; border-radius: 6px; padding: 2px 8px; }
        .status-alfa  { background: #f8d7da; color: #721c24; border-radius: 6px; padding: 2px 8px; }

        .progress { border-radius: 10px; height: 8px; }
        .progress-bar { border-radius: 10px; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeup { animation: fadeInUp 0.4s ease-out forwards; }
    </style>

    @stack('styles')
</head>
<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-graduation-cap fa-lg" style="color:#f6c23e;"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SMK<br><small style="font-size:0.65rem;opacity:0.8;font-weight:400;">At Kausar</small></div>
            </a>

            <hr class="sidebar-divider my-0">

            @yield('sidebar-menu')

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"
                    style="background:rgba(255,255,255,0.1);width:36px;height:36px;color:white;">
                    <i class="fas fa-angle-left"></i>
                </button>
            </div>
        </ul>
        <!-- End Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand topbar mb-4 static-top bg-white shadow-sm">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars text-gray-600"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">

                        @auth
                        @if(auth()->user()->hasRole('siswa'))
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown"
                               role="button" data-toggle="dropdown">
                                <i class="fas fa-bell fa-fw text-gray-400"></i>
                                @php $unread = auth()->user()->notifikasisBelumDibaca()->count(); @endphp
                                @if($unread > 0)
                                <span class="badge badge-danger badge-counter">{{ $unread > 9 ? '9+' : $unread }}</span>
                                @endif
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <h6 class="dropdown-header bg-primary text-white font-weight-bold py-2">
                                    <i class="fas fa-bell mr-2"></i>Notifikasi
                                </h6>
                                @foreach(auth()->user()->notifikasisBelumDibaca()->latest()->take(5)->get() as $notif)
                                <a class="dropdown-item d-flex align-items-center py-3"
                                   href="{{ $notif->url ?? '#' }}">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary" style="width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-bell text-white small"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">{{ $notif->created_at->diffForHumans() }}</div>
                                        <div class="font-weight-bold small">{{ $notif->judul }}</div>
                                        <div class="small text-gray-600">{{ Str::limit($notif->pesan, 60) }}</div>
                                    </div>
                                </a>
                                @endforeach
                                @if($unread === 0)
                                <div class="dropdown-item text-center small text-gray-500 py-3">
                                    <i class="fas fa-check-circle text-success mr-1"></i>Tidak ada notifikasi baru
                                </div>
                                @endif
                                <a class="dropdown-item text-center small text-gray-500 py-2 border-top"
                                   href="{{ route('siswa.notifikasi.index') }}">
                                    Lihat semua notifikasi
                                </a>
                            </div>
                        </li>
                        @endif
                        @endauth

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                               id="userDropdown" role="button" data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small font-weight-bold">
                                    {{ auth()->user()->name ?? 'Guest' }}
                                </span>
                                @auth
                                <div class="avatar-circle bg-primary d-flex align-items-center justify-content-center"
                                     style="width:36px;height:36px;border-radius:50%;">
                                    <i class="fas fa-user text-white small"></i>
                                </div>
                                @endauth
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 style="border-radius:12px;border:none;min-width:200px;">
                                <div class="px-4 py-3">
                                    <div class="font-weight-bold text-dark small">{{ auth()->user()->name ?? '' }}</div>
                                    <div class="small text-muted">
                                        @auth
                                            @if(auth()->user()->hasRole('admin')) <span class="badge badge-primary">Administrator</span>
                                            @elseif(auth()->user()->hasRole('guru')) <span class="badge badge-success">Guru</span>
                                            @elseif(auth()->user()->hasRole('siswa')) <span class="badge badge-info">Siswa</span>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user-circle fa-sm mr-2 text-gray-400"></i>Profil Saya
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="fas fa-sign-out-alt fa-sm mr-2"></i>Keluar
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End Topbar -->

                <!-- Page Content -->
                <div class="container-fluid">

                    {{-- Flash Messages --}}
                    @if(session('success'))
                    <div id="flash-success" data-msg="{{ session('success') }}"></div>
                    @endif
                    @if(session('error'))
                    <div id="flash-error" data-msg="{{ session('error') }}"></div>
                    @endif

                    {{-- Validation Errors --}}
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px;border:none;">
                        <strong><i class="fas fa-exclamation-triangle mr-2"></i>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    @endif

                    @yield('content')
                </div>
                <!-- End Page Content -->
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-4">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span class="text-gray-600 small">
                            &copy; {{ date('Y') }} <strong>SMK At Kausar</strong>
                            &mdash; Sistem Informasi Akademik &amp; Absensi
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successEl = document.getElementById('flash-success');
            const errorEl   = document.getElementById('flash-error');

            if (successEl) {
                Swal.fire({
                    icon: 'success', title: 'Berhasil!',
                    text: successEl.dataset.msg,
                    timer: 3000, showConfirmButton: false,
                    toast: true, position: 'top-end',
                });
            }
            if (errorEl) {
                Swal.fire({
                    icon: 'error', title: 'Gagal!',
                    text: errorEl.dataset.msg,
                    timer: 4000, showConfirmButton: false,
                    toast: true, position: 'top-end',
                });
            }
        });

        function confirmDelete(formId, itemName) {
            Swal.fire({
                title: 'Hapus ' + (itemName || 'data') + '?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
