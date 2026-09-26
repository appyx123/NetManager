<nav x-data="{ open: false }" class="bg-slate-900/95 backdrop-blur-md border-b border-slate-800 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto text-amber-500" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    @if (!in_array(Auth::user()->role, ['marketing', 'technician', 'customer', 'super_admin']))
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard') || request()->routeIs('*.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'super_admin')
                        <x-nav-link href="{{ route('superadmin.dashboard') }}" :active="request()->routeIs('superadmin.dashboard')">
                            {{ __('Super Panel') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('superadmin.users.index') }}" :active="request()->routeIs('superadmin.users.*')">
                            {{ __('Kelola Pegawai') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard Admin') }}
                        </x-nav-link>
                    @endif

                    @if (in_array(Auth::user()->role, ['admin', 'super_admin']))
                        <x-nav-link href="{{ route('admin.customers.index') }}" :active="request()->routeIs('admin.customers.*')">
                            {{ __('Pelanggan') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.tickets.index') }}" :active="request()->routeIs('admin.tickets.*')">
                            {{ __('Tiket Teknisi') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.leads.index') }}" :active="request()->routeIs('admin.leads.*')">
                            {{ __('Lead Marketing') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.billing.index') }}" :active="request()->routeIs('admin.billing.*')">
                            {{ __('Keuangan') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.routers.index') }}" :active="request()->routeIs('admin.routers.*')">
                            {{ __('Jaringan') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'marketing')
                        <x-nav-link href="{{ route('marketing.dashboard') }}" :active="request()->routeIs('marketing.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('marketing.leads.index') }}" :active="request()->routeIs('marketing.leads.*')">
                            {{ __('Prospek') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('marketing.customers.index') }}" :active="request()->routeIs('marketing.customers.*')">
                            {{ __('Pelanggan') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('marketing.reports.index') }}" :active="request()->routeIs('marketing.reports.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'technician')
                        <x-nav-link href="{{ route('technician.dashboard') }}" :active="request()->routeIs('technician.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('technician.survey.index') }}" :active="request()->routeIs('technician.survey.*')">
                            {{ __('Survey') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('technician.installation.index') }}" :active="request()->routeIs('technician.installation.*')">
                            {{ __('Instalasi') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('technician.ticket.index') }}" :active="request()->routeIs('technician.ticket.*')">
                            {{ __('Gangguan') }}
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->role === 'customer')
                        <x-nav-link href="{{ route('client.billing.index') }}" :active="request()->routeIs('client.billing.*')">
                            {{ __('Pembayaran') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('client.complaints.index') }}" :active="request()->routeIs('client.complaints.*')">
                            {{ __('Pengajuan') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-slate-600 text-sm leading-4 font-medium rounded-md text-slate-300 bg-slate-800 hover:text-white hover:border-slate-500 focus:outline-none focus:bg-slate-700 active:bg-slate-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>
                            <x-slot name="content">
                                <div class="w-60">
                                    <div class="block px-4 py-2 text-xs text-slate-400">{{ __('Manage Team') }}</div>
                                    <x-dropdown-link
                                        href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">{{ __('Team Settings') }}</x-dropdown-link>
                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link
                                            href="{{ route('teams.create') }}">{{ __('Create New Team') }}</x-dropdown-link>
                                    @endcan
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-slate-700"></div>
                                        <div class="block px-4 py-2 text-xs text-slate-400">{{ __('Switch Teams') }}
                                        </div>
                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-slate-700 transition">
                                    <img class="size-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-slate-600 text-sm leading-4 font-medium rounded-md text-slate-300 bg-slate-800 hover:text-white hover:border-slate-500 focus:outline-none focus:bg-slate-700 active:bg-slate-700 transition ease-in-out duration-150">
                                        <span
                                            class="mr-2 px-2 py-0.5 rounded text-xs font-bold text-white uppercase 
                                            {{ Auth::user()->role == 'super_admin' ? 'bg-purple-600' : '' }}
                                            {{ Auth::user()->role == 'admin' ? 'bg-red-600' : '' }}
                                            {{ Auth::user()->role == 'marketing' ? 'bg-amber-600' : '' }}
                                            {{ Auth::user()->role == 'technician' ? 'bg-amber-600' : '' }}
                                            {{ Auth::user()->role == 'customer' ? 'bg-green-600' : '' }}
                                        ">
                                            {{ str_replace('_', ' ', Auth::user()->role) }}
                                        </span>
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-slate-400">
                                {{ __('Manage Account') }}
                            </div>

                            @if (Auth::user()->role !== 'customer')
                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile Setting') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif
                            @endif

                            <div class="border-t border-slate-700"></div>

                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();"
                                    class="text-red-500 font-semibold">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-300 hover:bg-slate-800 focus:outline-none focus:bg-slate-800 focus:text-slate-300 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-slate-800 border-t border-slate-700">
        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard') || request()->routeIs('*.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (Auth::user()->role === 'super_admin')
                <div class="block px-4 py-2 text-xs font-bold text-purple-400 bg-purple-900/40 uppercase">Super Admin Area
                </div>
                <x-responsive-nav-link href="{{ route('superadmin.dashboard') }}" :active="request()->routeIs('superadmin.dashboard')">
                    {{ __('Super Panel') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('superadmin.users.index') }}" :active="request()->routeIs('superadmin.users.*')">
                    {{ __('Kelola Pegawai & Role') }}
                </x-responsive-nav-link>
                <div class="border-t border-slate-700 my-2"></div>
            @endif

            @if (in_array(Auth::user()->role, ['admin', 'super_admin']))
                <div class="block px-4 py-2 text-xs font-bold text-red-400 bg-red-900/40 uppercase">Admin Operasional</div>
                <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard Admin') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.customers.index') }}" :active="request()->routeIs('admin.customers.*')">
                    {{ __('Kelola Pelanggan & Paket') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.billing.index') }}" :active="request()->routeIs('admin.billing.*')">
                    {{ __('Keuangan & Tagihan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.routers.index') }}" :active="request()->routeIs('admin.routers.*')">
                    {{ __('Manajemen Jaringan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports.*')">
                    {{ __('Laporan & Log') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->role === 'marketing')
                <x-responsive-nav-link href="{{ route('marketing.dashboard') }}" :active="request()->routeIs('marketing.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('marketing.leads.index') }}" :active="request()->routeIs('marketing.leads.*')">
                    {{ __('Prospek') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('marketing.customers.index') }}" :active="request()->routeIs('marketing.customers.*')">
                    {{ __('Pelanggan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('marketing.reports.index') }}" :active="request()->routeIs('marketing.reports.*')">
                    {{ __('Laporan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('marketing.profile.index') }}" :active="request()->routeIs('marketing.profile.*')">
                    {{ __('Profil Marketing') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->role === 'technician')
                <x-responsive-nav-link href="{{ route('technician.dashboard') }}" :active="request()->routeIs('technician.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('technician.survey.index') }}" :active="request()->routeIs('technician.survey.*')">
                    {{ __('Tugas Survey') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('technician.installation.index') }}" :active="request()->routeIs('technician.installation.*')">
                    {{ __('Tugas Instalasi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('technician.ticket.index') }}" :active="request()->routeIs('technician.ticket.*')">
                    {{ __('Tiket Gangguan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('technician.profile') }}" :active="request()->routeIs('technician.profile')">
                    {{ __('Profil Teknisi') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->role === 'customer')
                <x-responsive-nav-link href="{{ route('client.billing.index') }}" :active="request()->routeIs('client.billing.*')">
                    {{ __('💳 Pembayaran') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('client.complaints.index') }}" :active="request()->routeIs('client.complaints.*')">
                    {{ __('📝 Pengajuan') }}
                </x-responsive-nav-link>
            @endif

        </div>

        <div class="pt-4 pb-1 border-t border-slate-700 bg-slate-800">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover border border-slate-600"
                            src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif
                <div>
                    <div class="font-bold text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
                    <span
                        class="inline-block mt-1 px-2 py-0.5 text-xs font-bold text-white bg-amber-600 rounded uppercase tracking-widest">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                @if (Auth::user()->role !== 'customer')
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile Settings') }}
                    </x-responsive-nav-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                            {{ __('API Tokens') }}
                        </x-responsive-nav-link>
                    @endif
                @endif

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();"
                        class="text-red-500">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
