<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-800 shadow-lg">
    @php
        $role = Auth::user()->role ?? null;
        $isAdmin = $role === 'admin';
        $dashboardRouteName = $isAdmin ? 'dashboard' : 'wali.dashboard';
        $dashboardPattern = $isAdmin ? 'dashboard' : 'wali.dashboard';
        $laporanRouteName = $isAdmin ? 'admin.laporan.index' : 'wali.laporan.index';
        $laporanPattern = $isAdmin ? 'admin.laporan.*' : 'wali.laporan.*';
    @endphp
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center mr-4 sm:mr-6">
                    <a href="{{ route($dashboardRouteName) }}" class="flex items-center gap-2 group">
                        <div class="px-1.5 py-1 rounded-2xl bg-gradient-to-br from-sky-600 via-slate-900 to-sky-500 shadow-md shadow-sky-900/40 group-hover:shadow-sky-500/30 transition flex items-center">
                            <img src="{{ asset('logo.png') }}" alt="Logo PKBM RIDHO" class="h-8 w-auto rounded-lg shadow-sm shadow-sky-500/30" />
                        </div>
                        <div class="hidden md:flex flex-col leading-tight">
                            <span class="text-sm font-bold tracking-normal text-slate-100 uppercase">{{ config('app.name', 'PKBM RIDHO') }}</span>
                            <span class="text-[10px] font-medium tracking-normal text-slate-400 uppercase">Sistem Absensi</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-2 sm:flex">
                    <x-nav-link :href="route($dashboardRouteName)" :active="request()->routeIs($dashboardPattern)" class="px-3 py-2 text-sm rounded-lg transition-all duration-200 hover:bg-slate-800/50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if ($isAdmin)
                        <x-nav-link :href="route('admin.tahun-ajaran.index')" :active="request()->routeIs('admin.tahun-ajaran.*')" class="px-3 py-2 text-sm rounded-lg transition-all duration-200 hover:bg-slate-800/50 whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('Tahun Ajaran') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.wali-kelas.index')" :active="request()->routeIs('admin.wali-kelas.*')" class="px-3 py-2 text-sm rounded-lg transition-all duration-200 hover:bg-slate-800/50 whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Wali Kelas') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.kelas.index')" :active="request()->routeIs('admin.kelas.*')" class="px-3 py-2 text-sm rounded-lg transition-all duration-200 hover:bg-slate-800/50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            {{ __('Kelas') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.siswa.index')" :active="request()->routeIs('admin.siswa.*')" class="px-3 py-2 text-sm rounded-lg transition-all duration-200 hover:bg-slate-800/50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ __('Siswa') }}
                        </x-nav-link>
                    @endif

                    @if (! $isAdmin)
                        <x-nav-link :href="route('admin.absensi.scan')" :active="request()->routeIs('admin.absensi.scan')" class="px-3 py-2 text-sm rounded-lg transition-all duration-200 hover:bg-slate-800/50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7V5a2 2 0 012-2h2M4 17v2a2 2 0 002 2h2m8 0h2a2 2 0 002-2v-2m0-8V5a2 2 0 00-2-2h-2M9 12h6m-3-3v6"></path>
                            </svg>
                            {{ __('Scanner') }}
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('admin.absensi.manual.index')" :active="request()->routeIs('admin.absensi.manual.*')" class="px-3 py-2 text-sm rounded-lg transition-all duration-200 hover:bg-slate-800/50 whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('Input Manual') }}
                    </x-nav-link>

                    <x-nav-link :href="route($laporanRouteName)" :active="request()->routeIs($laporanPattern)" class="px-3 py-2 text-sm rounded-lg transition-all duration-200 hover:bg-slate-800/50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m2 4H7m5 8v-4m4 4v-6m-8 6v-2m4-8h.01M5 5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z" />
                        </svg>
                        {{ __('Laporan') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-slate-50 bg-slate-900 hover:bg-slate-800 border border-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-neutral-950 transition-all duration-200">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-500 to-sky-400 flex items-center justify-center text-white font-semibold text-sm shadow-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:block text-slate-50">{{ Auth::user()->name }}</span>
                            </div>
                            <svg class="ms-2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-2 text-sm text-slate-50 hover:bg-slate-900 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <a href="{{ route('logout.confirm') }}" 
                           class="flex items-center px-4 py-2 text-sm text-red-400 hover:bg-red-900/30 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('Log Out') }}
                        </a>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-slate-200 hover:bg-slate-800/50 focus:outline-none focus:bg-slate-800/50 focus:text-slate-200 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-800 bg-slate-900/95 backdrop-blur-md">
        <div class="px-4 py-4 border-b border-slate-800 flex items-center gap-3">
            <div class="p-1.5 rounded-2xl bg-gradient-to-br from-sky-600 via-slate-900 to-sky-500 shadow-lg shadow-sky-900/40">
                <img src="{{ asset('logo.png') }}" alt="Logo PKBM RIDHO" class="h-12 w-auto rounded-xl shadow-md shadow-sky-500/20" />
            </div>
            <div>
                <p class="text-base font-bold tracking-[0.25em] text-slate-100 uppercase">PKBM RIDHO</p>
                <p class="text-xs text-slate-400">Pusat Kegiatan Belajar Mengajar</p>
            </div>
        </div>

        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route($dashboardRouteName)" :active="request()->routeIs($dashboardPattern)" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800/50 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if ($isAdmin)
                <x-responsive-nav-link :href="route('admin.tahun-ajaran.index')" :active="request()->routeIs('admin.tahun-ajaran.*')" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800/50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Tahun Ajaran') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.wali-kelas.index')" :active="request()->routeIs('admin.wali-kelas.*')" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800/50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ __('Wali Kelas') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.kelas.index')" :active="request()->routeIs('admin.kelas.*')" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800/50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    {{ __('Kelas') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.siswa.index')" :active="request()->routeIs('admin.siswa.*')" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800/50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    {{ __('Siswa') }}
                </x-responsive-nav-link>
            @endif

            @if (! $isAdmin)
                <x-responsive-nav-link :href="route('admin.absensi.scan')" :active="request()->routeIs('admin.absensi.scan')" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800/50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7V5a2 2 0 012-2h2M4 17v2a2 2 0 002 2h2m8 0h2a2 2 0 002-2v-2m0-8V5a2 2 0 00-2-2h-2M9 12h6m-3-3v6"></path>
                    </svg>
                    {{ __('Scanner') }}
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('admin.absensi.manual.index')" :active="request()->routeIs('admin.absensi.manual.*')" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800/50 transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                {{ __('Input Manual') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route($laporanRouteName)" :active="request()->routeIs($laporanPattern)" class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m2 4H7m5 8v-4m4 4v-6m-8 6v-2m4-8h.01M5 5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z" />
                </svg>
                {{ __('Laporan') }}
            </x-responsive-nav-link>
            
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-800 bg-slate-900/50">
            <div class="px-4 mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-sky-400 flex items-center justify-center text-white font-semibold shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-medium text-base text-slate-50">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-4">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800/50 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <a href="{{ route('logout.confirm') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800/50 text-red-400 transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    {{ __('Log Out') }}
                </a>
            </div>
        </div>
    </div>
</nav>
