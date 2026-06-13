<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Notifikasi - JOBLYNX</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-[#eaf3f0] min-h-screen text-[15px] text-gray-800 flex">

<!-- OVERLAY (mobile) -->
<div id="sidebarOverlay" onclick="toggleSidebar()"
     class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

<!-- SIDEBAR -->
<aside id="sidebar"
       class="fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-100 shadow-lg z-50
              transform -translate-x-full transition-transform duration-300 flex flex-col">

    <!-- Logo -->
    <div class="px-6 py-5 border-b border-gray-100">
        <h1 class="text-xl font-extrabold text-[#1f4e5a] tracking-tight">
            <span class="text-[#2d7f6a]">
                <i class="fa-solid fa-shield-halved"></i> ADMIN
            </span>
            JOBLYNX
        </h1>
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-4 py-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border-l-4
                  {{ request()->is('admin/dashboard') ? 'bg-[#dcfce7] text-[#2d7f6a] border-[#2d7f6a] pl-3 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-transparent pl-3' }}">
            <i class="fa-solid fa-gauge-high w-4"></i> Dashboard
        </a>
        <a href="{{ url('admin/users') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border-l-4
                  {{ request()->is('admin/users') ? 'bg-[#dcfce7] text-[#2d7f6a] border-[#2d7f6a] pl-3 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-transparent pl-3' }}">
            <i class="fa-solid fa-users w-4"></i> Users
        </a>
        <a href="{{ route('admin.jobs') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border-l-4
                  {{ request()->is('admin/jobs') ? 'bg-[#dcfce7] text-[#2d7f6a] border-[#2d7f6a] pl-3 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-transparent pl-3' }}">
            <i class="fa-solid fa-briefcase w-4"></i> Job Postings
        </a>
        <a href="{{ url('admin/perusahaan') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border-l-4
                  {{ request()->is('admin/perusahaan') ? 'bg-[#dcfce7] text-[#2d7f6a] border-[#2d7f6a] pl-3 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-transparent pl-3' }}">
            <i class="fa-solid fa-building w-4"></i> Perusahaan
        </a>
        <a href="{{ route('admin.applications') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border-l-4
                  {{ request()->is('admin/applications') ? 'bg-[#dcfce7] text-[#2d7f6a] border-[#2d7f6a] pl-3 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-transparent pl-3' }}">
            <i class="fa-solid fa-file-signature w-4"></i> Lamaran
        </a>
        {{-- MENU BARU: Kelola Notifikasi --}}
        <a href="{{ route('admin.notifications.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border-l-4
                  {{ request()->is('admin/notifications*') ? 'bg-[#dcfce7] text-[#2d7f6a] border-[#2d7f6a] pl-3 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-[#2d7f6a] border-transparent pl-3' }}">
            <i class="fa-solid fa-bell w-4"></i> Notifikasi
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-gray-100">
        <a href="javascript:void(0)" onclick="konfirmasiLogout()"
           class="w-full block text-center bg-red-50 text-red-500 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-500 hover:text-white transition">
            <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
        </a>
    </div>
</aside>

<!-- MAIN WRAPPER -->
<div class="flex-1 flex flex-col min-h-screen">

    <!-- TOPBAR -->
    <header class="bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 z-30 px-6 py-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()" class="text-[#1f4e5a] hover:text-[#2d7f6a] transition text-xl">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up text-[#2d7f6a] text-xl"></i>
                <span class="font-extrabold text-[#1f4e5a] text-xl tracking-tight">JOB<span class="text-[#2d7f6a]">LYNX</span></span>
            </div>
        </div>

        <div class="flex items-center gap-3 relative">
            <!-- NOTIF BELL -->
            <div class="relative">
                <div class="relative">
                    <button onclick="toggleNotif()" class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-400 hover:text-[#2d7f6a] hover:bg-[#dcfce7] transition">
                        <i class="fa-solid fa-bell text-sm"></i>
                    </button>
                    @if(isset($unread_count) && $unread_count > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full flex items-center justify-center text-white text-[8px] font-black">
                            {{ $unread_count > 9 ? '9+' : $unread_count }}
                        </span>
                    @endif
                </div>
                <div id="notifDropdown" class="hidden absolute right-0 top-11 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 z-[999]">
                    <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center">
                        <span class="text-[13px] font-bold text-[#1a2e38]">Notifikasi</span>
                        @if(isset($unread_count) && $unread_count > 0)
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-[11px] bg-red-500 text-white px-2 py-0.5 rounded-full font-bold">{{ $unread_count }}</span>
                                <a href="{{ route('notifications.readAll') }}" class="text-[9px] text-blue-600 hover:underline font-bold italic">Tandai semua dibaca</a>
                            </div>
                        @endif
                    </div>
                    @forelse($notif_result ?? [] as $notif)
                        <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 text-sm text-gray-700">
                            {{ $notif->message ?? $notif->pesan ?? '-' }}
                            <div class="text-[10px] text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-6 text-center text-gray-400 text-sm">
                            <i class="fa-solid fa-bell-slash text-2xl mb-2 block opacity-30"></i>
                            Belum ada notifikasi.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- PROFILE -->
            <button id="pbtn" onclick="togglePD()" class="flex items-center gap-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl px-3 py-2 transition-all">
                <div class="w-7 h-7 rounded-lg bg-[#2d7f6a] flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'A', 0, 1)) }}
                </div>
                <span class="text-[13px] font-semibold text-[#1a2e38]">{{ explode(' ', Auth::user()->nama_lengkap)[0] }}</span>
                <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
            </button>
            <div id="pdd" class="hidden absolute right-0 top-14 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-[999]">
                <div class="px-4 py-2 border-b border-gray-50 mb-1">
                    <div class="text-[12px] font-bold text-[#1a2e38]">{{ Auth::user()->nama_lengkap }}</div>
                    <div class="text-[11px] text-gray-400">{{ Auth::user()->email }}</div>
                </div>
                <button onclick="closePD(); openChangePasswordModal()" class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] text-gray-600 hover:bg-gray-50 transition">
                    <i class="fa-solid fa-key text-[#2d7f6a] text-xs w-4"></i> Ubah Password
                </button>
                <div class="border-t border-gray-50 mt-1 pt-1">
                    <a href="javascript:void(0)" onclick="konfirmasiLogout()" class="flex items-center gap-3 px-4 py-2.5 text-[13px] text-red-500 hover:bg-red-50 transition">
                        <i class="fa-solid fa-right-from-bracket text-xs w-4"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- KONTEN -->
    <main class="flex-1 px-6 py-5">

        <!-- ALERT -->
        @if(session('success'))
            <div class="max-w-6xl mx-auto mb-4">
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="max-w-6xl mx-auto mb-4">
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-circle-info text-blue-500"></i>
                    {{ session('info') }}
                </div>
            </div>
        @endif

        <!-- PAGE HEADER -->
        <div class="max-w-6xl mx-auto px-6 pt-2">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm"
                     style="background: linear-gradient(135deg, #1a4450, #2d7f6a);">
                    <i class="fa-solid fa-bell text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-[#1a4450] tracking-tight leading-tight">Kelola Notifikasi</h2>
                    <p class="text-[12px] text-[#2d7f6a] font-semibold tracking-wide uppercase">Broadcast pengumuman kepada pengguna JOBLYNX</p>
                </div>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="max-w-6xl mx-auto px-6 mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total Notifikasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #1a4450, #2d7f6a);">
                    <i class="fa-solid fa-bell text-white"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-[#1a4450]">{{ $total_broadcast }}</div>
                    <div class="text-[11px] text-gray-400 font-semibold uppercase tracking-wide">Total Notifikasi</div>
                </div>
            </div>

            <!-- Draft -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gray-100">
        <i class="fas fa-file-alt text-gray-500"></i>
    </div>
    <div>
        <div class="text-2xl font-extrabold text-[#1a4450]">{{ $total_draft }}</div>
        <div class="text-[11px] text-gray-400 font-semibold uppercase tracking-wide">Draft</div>
    </div>
</div>

            <!-- Published -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-green-100">
                    <i class="fa-solid fa-paper-plane text-[#2d7f6a]"></i>
                </div>
                <div>
                    <div class="text-2xl font-extrabold text-[#1a4450]">{{ $total_published }}</div>
                    <div class="text-[11px] text-gray-400 font-semibold uppercase tracking-wide">Published</div>
                </div>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="max-w-6xl mx-auto px-6 mt-6">
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">

                <!-- CARD HEADER -->
                <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3"
                     style="background: linear-gradient(135deg, #1a4450 0%, #2d7f6a 100%);">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-bell text-white text-base"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-base">Daftar Notifikasi Broadcast</h3>
                            <p class="text-white/60 text-[11px]">Notifikasi pengumuman yang dibuat Admin</p>
                        </div>
                    </div>
                    <button onclick="openTambahModal()"
                        class="flex items-center gap-2 bg-white text-[#2d7f6a] font-bold text-sm px-4 py-2 rounded-xl hover:bg-[#dcfce7] transition shadow-sm">
                        <i class="fa-solid fa-plus text-xs"></i> Tambah Notifikasi
                    </button>
                </div>

                <!-- FILTER & SEARCH -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                    <form method="GET" action="{{ route('admin.notifications.index') }}" class="flex flex-col sm:flex-row gap-3">
                        <!-- Search -->
                        <div class="flex-1 relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Cari judul atau pesan..."
                                class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a] bg-white">
                        </div>
                        <!-- Filter Status -->
                        <select name="status" class="border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 bg-white text-gray-600">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $status == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        <button type="submit" class="bg-[#2d7f6a] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#1a4450] transition">
                            <i class="fa-solid fa-filter mr-1"></i> Filter
                        </button>
                        @if($search || $status)
                            <a href="{{ route('admin.notifications.index') }}" class="border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-100 transition text-center">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- TABLE -->
                <div class="overflow-x-auto">
                    <table class="w-full text-[13px]">
                        <thead>
                            <tr style="background-color: #f0faf7; border-bottom: 2px solid #c6ead9;">
                                <th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">No</th>
                                <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'judul', 'order' => ($sort == 'judul' && $order == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-[#1a4450]">
                                        Judul
                                        <i class="fa-solid fa-sort text-gray-300 text-[10px]"></i>
                                    </a>
                                </th>
                                <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">Target</th>
                                <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">Status</th>
                                <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => ($sort == 'created_at' && $order == 'asc') ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-[#1a4450]">
                                        Tanggal
                                        <i class="fa-solid fa-sort text-gray-300 text-[10px]"></i>
                                    </a>
                                </th>
                                <th class="px-4 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-[#2d7f6a]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($broadcasts as $i => $broadcast)
                            <tr class="hover:bg-[#f0faf7] transition-colors duration-150 group">

                                <!-- NO -->
                                <td class="px-5 py-4 text-gray-400 font-semibold">
                                    {{ $broadcasts->firstItem() + $i }}
                                </td>

                                <!-- JUDUL & PESAN -->
                                <td class="px-4 py-4">
                                    <div class="font-bold text-[#1a4450] text-sm group-hover:text-[#2d7f6a] transition-colors">
                                        {{ $broadcast->judul }}
                                    </div>
                                    <div class="text-[11px] text-gray-400 mt-0.5 line-clamp-1 max-w-xs">
                                        {{ Str::limit($broadcast->pesan, 60) }}
                                    </div>
                                </td>

                                <!-- TARGET -->
                                <td class="px-4 py-4">
                                    @if($broadcast->target_role === 'all')
                                        <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-blue-200">
                                            <i class="fa-solid fa-users text-[9px]"></i> Semua User
                                        </span>
                                    @elseif($broadcast->target_role === 'pelamar')
                                        <span class="inline-flex items-center gap-1.5 bg-purple-100 text-purple-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-purple-200">
                                            <i class="fa-solid fa-user text-[9px]"></i> Pelamar
                                        </span>
                                    @elseif($broadcast->target_role === 'perusahaan')
                                        <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-amber-200">
                                            <i class="fa-solid fa-building text-[9px]"></i> Perusahaan
                                        </span>
                                    @endif
                                </td>

                                <!-- STATUS -->
                                <td class="px-4 py-4">
                                    @if($broadcast->status === 'published')
                                        <span class="inline-flex items-center gap-1.5 bg-[#dcfce7] text-[#2d7f6a] px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-[#a7f3d0]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#2d7f6a] inline-block"></span> Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full text-[10px] font-extrabold border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span> Draft
                                        </span>
                                    @endif
                                </td>

                                <!-- TANGGAL -->
                                <td class="px-4 py-4 text-gray-500 text-[12px]">
                                    {{ $broadcast->created_at ? $broadcast->created_at->format('d/m/Y H:i') : '-' }}
                                </td>

                                <!-- AKSI -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <!-- Edit -->
                                        <button onclick='openEditModal({{ json_encode($broadcast) }})'
                                            class="inline-flex items-center gap-1.5 bg-[#2d7f6a] text-white px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-[#1a4450] transition shadow-sm">
                                            <i class="fa-solid fa-pen text-[10px]"></i> Edit
                                        </button>

                                        <!-- Publish (hanya jika masih draft) -->
                                        @if($broadcast->status === 'draft')
                                            <a href="{{ route('admin.notifications.publish', $broadcast->id) }}"
                                                onclick="return confirm('Publish notifikasi ini? Notifikasi akan dikirim ke semua target pengguna.')"
                                                class="inline-flex items-center gap-1.5 bg-emerald-500 text-white px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-emerald-600 transition shadow-sm">
                                                <i class="fa-solid fa-paper-plane text-[10px]"></i> Publish
                                            </a>
                                        @endif

                                        <!-- Hapus -->
                                        <button onclick='openDeleteModal("{{ route('admin.notifications.delete', $broadcast->id) }}", "{{ addslashes($broadcast->judul) }}")'
                                            class="inline-flex items-center gap-1.5 border border-red-300 text-red-500 px-3 py-1.5 rounded-lg text-[11px] font-bold hover:bg-red-500 hover:text-white hover:border-red-500 transition">
                                            <i class="fa-solid fa-trash text-[10px]"></i> Hapus
                                        </button>
                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-20">
                                    <div class="inline-flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center">
                                            <i class="fa-solid fa-bell-slash text-3xl text-gray-300"></i>
                                        </div>
                                        <span class="text-gray-400 text-sm font-medium">Belum ada notifikasi</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex items-center justify-between flex-wrap gap-2">
                    <span class="text-xs text-gray-400">
                        Menampilkan {{ $broadcasts->firstItem() ?? 0 }}–{{ $broadcasts->lastItem() ?? 0 }} dari {{ $broadcasts->total() }} notifikasi
                    </span>
                    <div class="text-sm">{{ $broadcasts->links() }}</div>
                </div>

            </div>
        </div>

    </main>
</div>

<!-- ===================================================================== -->
<!-- MODAL: Tambah Notifikasi                                               -->
<!-- ===================================================================== -->
<div id="tambahModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-6 relative mx-4">
        <button onclick="closeTambahModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #1a4450, #2d7f6a);">
                <i class="fa-solid fa-bell text-white text-sm"></i>
            </div>
            <h3 class="text-xl font-bold text-[#1a4450]">Tambah Notifikasi</h3>
        </div>
        <form action="{{ route('admin.notifications.store') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Judul -->
            <div>
                <label class="block text-[12px] font-bold text-gray-600 uppercase tracking-wide mb-1.5">Judul Notifikasi <span class="text-red-500">*</span></label>
                <input type="text" name="judul" required maxlength="255"
                    placeholder="Contoh: Sistem akan maintenance..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a]">
            </div>
            <!-- Pesan -->
            <div>
                <label class="block text-[12px] font-bold text-gray-600 uppercase tracking-wide mb-1.5">Isi Pesan <span class="text-red-500">*</span></label>
                <textarea name="pesan" required rows="4" placeholder="Tulis isi pesan pengumuman..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a] resize-none"></textarea>
            </div>
            <!-- Target -->
            <div>
                <label class="block text-[12px] font-bold text-gray-600 uppercase tracking-wide mb-1.5">Target Pengguna <span class="text-red-500">*</span></label>
                <select name="target_role" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a] bg-white text-gray-700">
                    <option value="">-- Pilih Target --</option>
                    <option value="all">Semua User</option>
                    <option value="pelamar">Pelamar</option>
                    <option value="perusahaan">Perusahaan</option>
                </select>
            </div>
            <!-- Status -->
            <div>
                <label class="block text-[12px] font-bold text-gray-600 uppercase tracking-wide mb-1.5">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a] bg-white text-gray-700">
                    <option value="">-- Pilih Status --</option>
                    <option value="draft">Draft (Simpan dulu)</option>
                    <option value="published">Publish (Langsung kirim)</option>
                </select>
            </div>
            <!-- Actions -->
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeTambahModal()" class="flex-1 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-sm transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-white font-bold text-sm transition" style="background: linear-gradient(135deg, #1a4450, #2d7f6a);">
                    <i class="fa-solid fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===================================================================== -->
<!-- MODAL: Edit Notifikasi                                                 -->
<!-- ===================================================================== -->
<div id="editModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-6 relative mx-4">
        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-amber-100">
                <i class="fa-solid fa-pen text-amber-500 text-sm"></i>
            </div>
            <h3 class="text-xl font-bold text-[#1a4450]">Edit Notifikasi</h3>
        </div>
        <form id="editForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <!-- Judul -->
            <div>
                <label class="block text-[12px] font-bold text-gray-600 uppercase tracking-wide mb-1.5">Judul Notifikasi <span class="text-red-500">*</span></label>
                <input type="text" name="judul" id="editJudul" required maxlength="255"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a]">
            </div>
            <!-- Pesan -->
            <div>
                <label class="block text-[12px] font-bold text-gray-600 uppercase tracking-wide mb-1.5">Isi Pesan <span class="text-red-500">*</span></label>
                <textarea name="pesan" id="editPesan" required rows="4"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a] resize-none"></textarea>
            </div>
            <!-- Target -->
            <div>
                <label class="block text-[12px] font-bold text-gray-600 uppercase tracking-wide mb-1.5">Target Pengguna <span class="text-red-500">*</span></label>
                <select name="target_role" id="editTarget" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a] bg-white text-gray-700">
                    <option value="all">Semua User</option>
                    <option value="pelamar">Pelamar</option>
                    <option value="perusahaan">Perusahaan</option>
                </select>
            </div>
            <!-- Status -->
            <div>
                <label class="block text-[12px] font-bold text-gray-600 uppercase tracking-wide mb-1.5">Status <span class="text-red-500">*</span></label>
                <select name="status" id="editStatus" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2d7f6a]/30 focus:border-[#2d7f6a] bg-white text-gray-700">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <!-- Actions -->
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="flex-1 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-sm transition">Batal</button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl text-white font-bold text-sm transition bg-amber-500 hover:bg-amber-600">
                    <i class="fa-solid fa-save mr-1"></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===================================================================== -->
<!-- MODAL: Hapus Notifikasi                                                -->
<!-- ===================================================================== -->
<div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
    <div class="bg-white w-[380px] rounded-2xl shadow-xl p-6 mx-4">
        <div class="text-center">
            <div class="w-16 h-16 rounded-2xl bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-trash text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-[#1a4450] mb-2">Hapus Notifikasi?</h3>
            <p class="text-sm text-gray-500 mb-1">Anda akan menghapus:</p>
            <p id="deleteJudul" class="text-sm font-bold text-[#1a4450] mb-6"></p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-sm transition">Batal</button>
                <a id="deleteLink" href="#" class="flex-1 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold text-sm text-center transition">Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================================== -->
<!-- MODAL: Konfirmasi Logout                                               -->
<!-- ===================================================================== -->
<!-- MODAL UBAH PASSWORD -->
<div id="changePasswordModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 relative mx-4">
        <button type="button" onclick="closeChangePasswordModal()" class="absolute top-4 right-4 text-gray-500 hover:text-[#1a4450]">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="text-center mb-5">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-yellow-100 flex items-center justify-center text-yellow-600 text-2xl mb-3">
                <i class="fa-solid fa-key"></i>
            </div>
            <h3 class="text-xl font-bold text-[#1a4450]">Ubah Password Admin</h3>
            <p class="text-sm text-gray-400 mt-1">{{ Auth::user()->nama_lengkap }}</p>
        </div>
        <form method="POST" action="{{ route('admin.users.update.password', Auth::user()->id) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="password" required autocomplete="new-password"
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:border-[#2d7f6a] focus:ring-1 focus:ring-[#2d7f6a] bg-gray-50"
                        placeholder="Masukkan password baru...">
                </div>
                <button type="submit" class="w-full bg-[#2d7f6a] hover:bg-[#1f5c4d] text-white px-5 py-3 rounded-2xl font-bold transition">
                    Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">
    <div class="bg-white w-[340px] rounded-2xl shadow-xl p-6 mx-4">
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-400 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-right-from-bracket text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi Logout</h3>
            <p class="text-sm text-gray-500 mb-5">Apakah Anda yakin ingin keluar?</p>
            <div class="flex gap-3">
                <button onclick="closeLogout()" class="flex-1 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-sm">Batal</button>
                <a href="{{ route('logout') }}" class="flex-1 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-sm text-center">Logout</a>
            </div>
        </div>
    </div>
</div>

<script>
    /* ------------------------------------------------------------------ */
    /* SIDEBAR                                                             */
    /* ------------------------------------------------------------------ */
    function toggleSidebar() {
        const sb = document.getElementById('sidebar');
        const ov = document.getElementById('sidebarOverlay');
        const hidden = sb.classList.toggle('-translate-x-full');
        ov.classList.toggle('hidden', hidden);
    }

    /* ------------------------------------------------------------------ */
    /* NOTIF DROPDOWN                                                      */
    /* ------------------------------------------------------------------ */
    function toggleNotif() {
        document.getElementById('notifDropdown').classList.toggle('hidden');
    }

    /* ------------------------------------------------------------------ */
    /* PROFILE DROPDOWN                                                    */
    /* ------------------------------------------------------------------ */
    function togglePD() {
        document.getElementById('pdd').classList.toggle('hidden');
    }
    function closePD() {
        document.getElementById('pdd').classList.add('hidden');
    }

    /* ------------------------------------------------------------------ */
    /* UBAH PASSWORD                                                       */
    /* ------------------------------------------------------------------ */
    function openChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('hidden', 'flex');
    }
    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.replace('flex', 'hidden');
    }

    /* ------------------------------------------------------------------ */
    /* LOGOUT MODAL                                                        */
    /* ------------------------------------------------------------------ */
    function konfirmasiLogout() {
        document.getElementById('logoutModal').classList.replace('hidden', 'flex');
    }
    function closeLogout() {
        document.getElementById('logoutModal').classList.replace('flex', 'hidden');
    }

    /* ------------------------------------------------------------------ */
    /* TAMBAH MODAL                                                        */
    /* ------------------------------------------------------------------ */
    function openTambahModal() {
        document.getElementById('tambahModal').classList.replace('hidden', 'flex');
    }
    function closeTambahModal() {
        document.getElementById('tambahModal').classList.replace('flex', 'hidden');
    }

    /* ------------------------------------------------------------------ */
    /* EDIT MODAL                                                          */
    /* ------------------------------------------------------------------ */
    function openEditModal(broadcast) {
        document.getElementById('editJudul').value   = broadcast.judul;
        document.getElementById('editPesan').value   = broadcast.pesan;
        document.getElementById('editTarget').value  = broadcast.target_role;
        document.getElementById('editStatus').value  = broadcast.status;
        document.getElementById('editForm').action   = '/admin/notifications/' + broadcast.id;
        document.getElementById('editModal').classList.replace('hidden', 'flex');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.replace('flex', 'hidden');
    }

    /* ------------------------------------------------------------------ */
    /* DELETE MODAL                                                        */
    /* ------------------------------------------------------------------ */
    function openDeleteModal(url, judul) {
        document.getElementById('deleteLink').href   = url;
        document.getElementById('deleteJudul').textContent = judul;
        document.getElementById('deleteModal').classList.replace('hidden', 'flex');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.replace('flex', 'hidden');
    }

    /* ------------------------------------------------------------------ */
    /* CLOSE ON OUTSIDE CLICK                                              */
    /* ------------------------------------------------------------------ */
    document.addEventListener('click', function (e) {
        const nd = document.getElementById('notifDropdown');
        const pdd = document.getElementById('pdd');

        if (!e.target.closest('#notifDropdown') && !e.target.closest('[onclick="toggleNotif()"]')) {
            nd.classList.add('hidden');
        }
        if (!e.target.closest('#pdd') && !e.target.closest('#pbtn')) {
            pdd.classList.add('hidden');
        }
    });
</script>

</body>
</html>
