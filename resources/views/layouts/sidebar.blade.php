<nav
    class="fixed flex flex-col sm:static z-40 sm:bg-gray-100 bg-white sm:rounded-none rounded-xl sm:shadow-none shadow-2xl bottom-4 top-4 max-w-xs w-64 h-auto sm:min-h-full transform sm:transform-none transition-transform duration-150 sm:block p-2"
    :class="openNav ? 'translate-x-0 left-4' : '-translate-x-full sm:translate-x-0'" x-cloak
>
    <header class="p-2">
        <a href="/">
            <img src="{{ asset('assets/img/icon-fullsize.png') }}" alt="{{ __('app logo') }}"
                 class="w-16 inline-block">
            <h1 class="inline-block text-lg font-semibold text-slate-600">{{ config('app.name') }}</h1>
        </a>
    </header>
    <hr>
    <ul class="p-4 sm:space-y-2 space-y-1">
        <li><x-button-dark class="w-full" :href="route('kiosk.create')">{{ __('Start lesson') }}</x-button-dark></li>
        <li><x-nav-link class="" :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link></li>
{{--        <li><x-nav-link class="" :href="route('dashboard')" :active="request()->routeIs('/')">{{ __('My students') }}</x-nav-link></li>--}}
        <li><x-nav-link class="" :href="route('student.index')" :active="request()->routeIs('student.index')">{{ __('Manage students') }}</x-nav-link></li>
        <li><x-nav-link class="" :href="route('edugroup.index')" :active="request()->routeIs('edugroup.index')">{{ __('Manage groups') }}</x-nav-link></li>
        <li><x-nav-link class="" :href="route('course.index')" :active="request()->routeis('course.index')">{{ __('Manage courses') }}</x-nav-link></li>
        <li><x-nav-link class="" :href="route('knowledge.index')" :active="request()->routeis('knowledge.index')">{{ __('Manage knowledge') }}</x-nav-link></li>
        @can('admin')
            <li>
                <h6 class="p-2 uppercase text-xs font-normal text-slate-400">{{ __('Administrace') }}</h6>
            </li>
            <li>
{{--                <x-nav-link class="" :href="route('dashboard')" :active="request()->routeIs('/')">{{ __('Configuration') }}</x-nav-link>--}}
                <x-nav-link class="" :href="route('user.index')" :active="request()->routeIs('user.index')">{{ __('Manage users') }}</x-nav-link>
            </li>
        @endcan
        <li>
            <h6 class="p-2 uppercase text-xs font-normal text-slate-400">{{ __('User') }}</h6>
        </li>
        <li><x-nav-link class="" :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">{{ __('Profile') }}</x-nav-link></li>
        <li>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="inline-block sm:block sm:w-full text-start sm:m-0 my-2 ms-2 sm:py-2 sm:px-2  sm:rounded hover:border-rose-300 border-b-2 sm:border-0 sm:hover:bg-rose-200 sm:hover:border-rose-300 border-transparent leading-5 text-slate-700 transition duration-150 ease-in-out">
                    {{ __('Logout') }}
                </button>
            </form>
        </li>
    </ul>
</nav>

<!-- Mobile Overlay -->
<div
    x-show="openNav"
    x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black bg-opacity-10 z-30 sm:hidden"
    @click="openNav = false"
></div>
{{--
<nav x-show="openNav || $screen('sm')" x-transition
     class="max-w-xs w-full min-h-screen sm:relative fixed sm:block hidden sm:rounded-none rounded-lg bg-green-400">
    <div class="  ">
sfdsfs
        :class="{'block': openNav, 'hidden': ! openNav}"
    </div>


    <div  class="hidden sm:hidden">
schovano
    </div>
</nav>
--}}
