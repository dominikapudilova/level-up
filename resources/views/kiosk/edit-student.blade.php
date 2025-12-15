<x-kiosk-layout>

    <div class="max-w-full w-full absolute top-0 -z-0 min-h-dvh h-full" style="@if($student->background_image) background-image: url('{{ asset('assets/img/backgrounds/' . $student->background_image) }}') @endif">
        <span class="mask bg-gradient-{{ $student->theme }} opacity-80"></span>
    </div>

    @php
    $studentLvl = $student->getLevel();
    $expPerLevel = config('school.economy.exp_per_level');
    $studentLvlPercent = round((($expPerLevel - $student->getExpToNextLevel()) / $expPerLevel) * 100, 0);
    @endphp

    <main class="z-10 text-center sm:pt-12 pt-10 mb-10 w-full max-w-7xl flex flex-col relative" x-data="{ selectedPfp: '', selectedBg: '', selectedTheme: '' }">
        <a href="{{ route('kiosk.session', $kiosk) }}" class="m-auto inline-block">
            <img alt="{{ __('app logo') }}" src="{{ asset('assets/img/icon-fullsize.png') }}" class="w-28 ">
        </a>
        <h1 class="font-bold leading-none tracking-tighter text-white text-4xl">{{ __('Welcome, :name', ['name' => $student->nickname]) }}</h1>

        <div class="absolute top-2 right-auto sm:right-0 flex sm:flex-col flex-row gap-2 sm:ms-auto ms-2">
            <x-button-dark href="{{ route('kiosk.student.index', $kiosk) }}" class="w-full" :theme="$student->theme">
                {{ __('Log out') }}
            </x-button-dark>
        </div>

        {{--compact/desktop--}}
        <div class="hidden sm:flex h-28 max-w-xl w-full p-3 sm:p-4 bg-white bg-milky-glass shadow-lg rounded-xl space-x-4 mb-4 flex-row mt-6 self-center">
            <x-student-profile-pic :student="$student" class="h-full"/>

            <div class="flex-grow text-slate-600 text-start flex flex-col justify-center">
                <h3 class="text-lg font-semibold text-slate-800">{{ $student->nickname }}</h3>
                <h4 class="">{{ __('Student') }}</h4>
                <div class="flex items-center content-center w-full">
                    <span>{{ __('lvl') }}&nbsp;{{ $studentLvl }}</span>
                    <div class="overflow-visible h-0.5 bg-slate-400 w-full mx-2">
                        <div class="h-1.5 rounded-full bg-gradient-{{ $student->theme }} -mt-0.5" role="progressbar" aria-valuenow="{{ $studentLvlPercent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{$studentLvlPercent}}%;"></div>
                    </div>
                    <span class="me-2 text-xs font-weight-bold">{{ $studentLvlPercent }}%</span>
                </div>
            </div>
        </div>
        <div class="hidden sm:grid max-w-xl w-full self-center grid-cols-3 gap-4 text-slate-800">
            <div class="bg-white bg-milky-glass shadow-lg rounded-xl p-3 flex flex-nowrap items-center gap-2 text-start">
                <div class="grow">
                    <span class="text-sm text-slate-600 leading-none">{{ __('Brain Bucks') }}</span>
                    <div class="font-bold text-xl leading-none">{{ $student->bucks }}</div>
                </div>
                <div class="inline-flex bg-gradient-{{ $student->theme }} rounded p-1 aspect-square w-10 h-10 items-center justify-center">
                    <i class="fa-solid fa-money-bills text-white text-xs sm:text-base"></i>
                </div>
            </div>

            <div
                class="bg-white bg-milky-glass shadow-lg rounded-xl p-3 flex flex-nowrap items-center gap-2 text-start">
                <div class="grow">
                    <span class="text-sm text-slate-600 leading-none">{{ __('Experience') }}</span>
                    <div class="font-bold text-xl leading-none">{{ $student->exp }}</div>
                </div>
                <div class="inline-flex bg-gradient-{{ $student->theme }} rounded p-1 aspect-square w-10 h-10 items-center justify-center">
                    <i class="fa-solid fa-star-half-stroke text-white text-xs sm:text-base"></i>
                </div>
            </div>

            <div class="bg-white bg-milky-glass shadow-lg rounded-xl p-3 flex flex-nowrap items-center gap-2 text-start">
                <div class="grow">
                    <span class="text-sm text-slate-600 leading-none">{{ __('Knowledge units') }}</span>
                    <div class="font-bold text-xl leading-none">{{ $student->knowledge->count() }}</div>
                </div>
                <div
                    class="inline-flex bg-gradient-{{ $student->theme }} rounded p-1 aspect-square w-10 h-10 items-center justify-center">
                    <i class="fa-solid fa-graduation-cap text-white text-xs sm:text-base"></i>
                </div>
            </div>
        </div>

        {{--wide/mobile--}}
        <div class="flex sm:hidden min-h-28 max-w-xl w-full p-3 sm:p-4 bg-white bg-milky-glass shadow-lg rounded-xl gap-4 mb-4 flex-col mt-6 self-center text-start text-slate-600">
            <x-student-profile-pic :student="$student" class="max-w-none sm:max-w-none w-auto "/>

            <div class="text-slate-600">
                <h3 class="text-slate-800 text-lg font-semibold leading-tight">{{ $student->nickname }}</h3>
                <h4 class="leading-tight">{{ __('Student') }} <span>{{ __('lvl') }}&nbsp;{{ $studentLvl }}</span></h4>
                <div class="flex flex-nowrap items-center mt-2">
                    <div class="overflow-visible h-0.5 bg-slate-400 w-full">
                        <div class="h-1.5 rounded-full bg-gradient-{{ $student->theme }} -mt-0.5" role="progressbar" aria-valuenow="{{ $studentLvlPercent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{$studentLvlPercent}}%;"></div>
                    </div>
                    <span class="ms-2 text-xs font-semibold">{{ $studentLvlPercent }}%</span>
                </div>
            </div>


            <div class="grid grid-cols-3 gap-1">
                <div>
                    <div class="flex items-center gap-1 sm:gap-2 flex-nowrap mb-2">
                        <div class="inline-flex bg-gradient-{{ $student->theme }} rounded p-0.5 aspect-square w-6 sm:w-8 h-6 sm:h-8 items-center justify-center">
                            <i class="fa-solid fa-money-bills text-white text-xs sm:text-base"></i>
                        </div>
                        <span class="text-sm text-slate-600 tracking-tight grow leading-none">{{ __('Brain Bucks') }}</span>
                    </div>
                    <span class="font-bold text-slate-800 text-xl">{{ $student->bucks }}</span>
                </div>

                <div>
                    <div class="flex items-center gap-1 sm:gap-2 flex-nowrap mb-2">
                        <div class="inline-flex bg-gradient-{{ $student->theme }} rounded p-0.5 aspect-square w-6 sm:w-8 h-6 sm:h-8 items-center justify-center">
                            <i class="fa-solid fa-star-half-stroke text-white text-xs sm:text-base"></i>
                        </div>
                        <span class="text-sm text-slate-600 tracking-tight grow leading-none">{{ __('Experience') }}</span>
                    </div>
                    <span class="font-bold text-slate-800 text-xl">{{ $student->exp }}</span>
                </div>

                <div>
                    <div class="flex items-center gap-1 sm:gap-2 flex-nowrap mb-2">
                        <div class="inline-flex bg-gradient-{{ $student->theme }} rounded p-0.5 aspect-square w-6 sm:w-8 h-6 sm:h-8 items-center justify-center">
                            <i class="fa-solid fa-graduation-cap text-white text-xs sm:text-base"></i>
                        </div>
                        <span class="text-sm text-slate-600 tracking-tight grow leading-3">{{ __('Knowledge units') }}</span>
                    </div>
                    <span class="font-bold text-slate-800 text-xl">{{ $student->knowledge->count() }}</span>
                </div>
            </div>
        </div>

        {{--profile pictures--}}
        <x-kiosk-collapsable-item :title="__('Profile pictures')" :subtitle="__('Get a new profile picture for :cost :buck',  ['cost' => config('school.economy.prices.profile_picture'), 'buck' => '<i class=\'fa-solid fa-money-bill text-base\'></i>'])">
            <div class="grid sm:grid-cols-12 grid-cols-4 gap-2 sm:gap-3 mt-4">
                @foreach(config('school.cosmetics.avatars') as $name)
                    <x-student-profile-pic :student="$student" :img="$name" :interactive="true"
                                           x-on:click.prevent="$dispatch('open-modal', 'confirm-student-pin-pfp'); selectedPfp = '{{$name}}'"/>
                @endforeach
            </div>
        </x-kiosk-collapsable-item>

        {{--profile picture random--}}
        <x-kiosk-collapsable-item :title="__('Random profile picture')" :subtitle="__('Get a new random profile picture for :cost :buck',  ['cost' => config('school.economy.prices.profile_picture_random'), 'buck' => '<i class=\'fa-solid fa-money-bill text-base\'></i>'])">
            <x-button-dark x-on:click.prevent="$dispatch('open-modal', 'confirm-student-pin-pfp'); selectedPfp = 'random'" class="w-full sm:w-auto py-4 mt-4" :theme="$student->theme">
                <i class="fa-solid fa-dice fa-2x"></i>
                <div class="ms-2">{{ __('Get random profile picture') }}</div>
            </x-button-dark>
        </x-kiosk-collapsable-item>

        {{--backgrounds--}}
        <x-kiosk-collapsable-item :title="__('Backgrounds')" :subtitle="__('Get a background for your avatar for :cost :buck',  ['cost' => config('school.economy.prices.background'), 'buck' => '<i class=\'fa-solid fa-money-bill text-base\'></i>'])">
            <div class="grid sm:grid-cols-6 grid-cols-2 gap-2 sm:gap-3 mt-4">
                @foreach(config('school.cosmetics.backgrounds') as $name)
                    <x-student-profile-pic :student="$student" :bg="$name" :interactive="true"
                                           x-on:click.prevent="$dispatch('open-modal', 'confirm-student-pin-bg'); selectedBg = '{{$name}}'"/>
                @endforeach
            </div>
        </x-kiosk-collapsable-item>

        {{--backgrounds random--}}
        <x-kiosk-collapsable-item :title="__('Random background')" :subtitle="__('Get a new random background for :cost :buck',  ['cost' => config('school.economy.prices.background_random'), 'buck' => '<i class=\'fa-solid fa-money-bill text-base\'></i>'])">
            <x-button-dark x-on:click.prevent="$dispatch('open-modal', 'confirm-student-pin-bg'); selectedBg = 'random'" class="w-full sm:w-auto py-4 mt-4" :theme="$student->theme">
                <i class="fa-solid fa-dice fa-2x"></i>
                <div class="ms-2">{{ __('Get random background') }}</div>
            </x-button-dark>
        </x-kiosk-collapsable-item>

        {{--theme--}}
        <x-kiosk-collapsable-item :title="__('Theme color')" :subtitle="__('Get a new theme color for :cost :buck',  ['cost' => config('school.economy.prices.theme'), 'buck' => '<i class=\'fa-solid fa-money-bill text-base\'></i>'])">
            <div class="grid sm:grid-cols-6 grid-cols-2 gap-2 sm:gap-3 mt-4">
                @foreach(config('school.cosmetics.themes') as $theme)
                    <x-button-dark x-on:click.prevent="$dispatch('open-modal', 'confirm-student-pin-theme'); selectedTheme = '{{$theme}}'" class="w-full sm:w-auto py-4" :theme="$theme">
                        <i class="fa-solid fa-palette fa-2x"></i>
                        <div class="ms-2">{{ __('Get this theme') }}</div>
                    </x-button-dark>
                @endforeach
            </div>
        </x-kiosk-collapsable-item>

        {{--change username--}}
        <x-kiosk-collapsable-item :title="__('Rename')" :subtitle="__('Change your nickname for :cost :buck',  ['cost' => config('school.economy.prices.rename'), 'buck' => '<i class=\'fa-solid fa-money-bill text-base\'></i>'])">
            <x-button-dark x-on:click.prevent="$dispatch('open-modal', 'confirm-student-pin-rename')" class="w-full sm:w-auto py-4 mt-4" :theme="$student->theme">
                <i class="fa-solid fa-user-tag fa-2x"></i>
                <div class="ms-2">{{ __('Rename') }}</div>
            </x-button-dark>
        </x-kiosk-collapsable-item>

        {{--change PIN--}}
        <x-kiosk-collapsable-item :title="__('Change PIN')" :subtitle="__('Change your PIN for free')">
            <x-button-dark x-on:click.prevent="$dispatch('open-modal', 'confirm-student-pin-pin')" class="w-full sm:w-auto py-4 mt-4" :theme="$student->theme">
                <i class="fa-solid fa-user-lock fa-2x"></i>
                <div class="ms-2">{{ __('Change PIN') }}</div>
            </x-button-dark>
        </x-kiosk-collapsable-item>

        {{--MODAL - profile pictures + random--}}
        <x-modal name="confirm-student-pin-pfp" focusable>
            <form action="{{ route('kiosk.student.purchase-pfp', [$kiosk, $student]) }}" method="POST" class="w-full text-center sm:text-start p-6 text-slate-800">
                @csrf
                <h2 class="text-lg">{{ __('Confirm purchase') }}</h2>
                <template x-if="selectedPfp !== 'random'">
                    <p class="text-slate-600">{!! __('Do you really want to purchase this profile picture for :cost :buck?', ['cost' => config('school.economy.prices.profile_picture'), 'buck' => '<i class="fa-solid fa-money-bill text-base"></i>'])  !!}</p>
                </template>
                <template x-if="selectedPfp === 'random'">
                    <p class="text-slate-600">{!! __('Do you really want to purchase a random profile picture for :cost :buck?', ['cost' => config('school.economy.prices.profile_picture_random'), 'buck' => '<i class="fa-solid fa-money-bill text-base"></i>'])  !!}</p>
                </template>

                <div class="flex flex-row flex-nowrap items-center gap-4 my-4 justify-center">
                    <x-student-profile-pic :student="$student" :img="$student->avatar" class="h-24"/>
                    <div><i class="fa-solid fa-angles-right"></i></div>
                    <template x-if="selectedPfp !== '' && selectedPfp !== 'random'">
                        <x-student-profile-pic :student="$student" class="h-24">
                            <img x-bind:src="'{{ asset('assets/img/avatars/') }}' + '/' + selectedPfp" alt="{{ __(':name\'s profile picture', ['name' => $student->nickname]) }}">
                        </x-student-profile-pic>
                    </template>
                    <template x-if="selectedPfp === 'random'">
                        <x-student-profile-pic :student="$student" class="h-24 aspect-square items-center">
                            <i class="fa-solid fa-question text-2xl text-white"></i>
                        </x-student-profile-pic>
                    </template>
                </div>

                <input type="hidden" name="pfp" id="pfp" :value="selectedPfp">
                <div>
                    <x-input-label for="confirm-student-pin-pfp">{{ __('Student\'s PIN (:name)', ['name' => $student->nickname]) }}</x-input-label>
                    <x-input-text id="confirm-student-pin-pfp" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20"></x-input-text>
                </div>

                <div class="w-full flex justify-end mt-4">
                    <x-button-dark class="sm:w-auto w-full" :theme="$student->theme">
                        {{ __('Confirm') }}
                    </x-button-dark>
                </div>
            </form>
        </x-modal>

        {{--MODAL - backgrounds + random--}}
        <x-modal name="confirm-student-pin-bg" focusable>
            <form action="{{ route('kiosk.student.purchase-bg', [$kiosk, $student]) }}" method="POST" class="w-full text-center sm:text-start p-6 text-slate-800">
                @csrf
                <h2 class="text-lg">{{ __('Confirm purchase') }}</h2>
                <template x-if="selectedBg !== 'random'">
                    <p class="text-slate-600">{!! __('Do you really want to purchase this background for :cost :buck?', ['cost' => config('school.economy.prices.background'), 'buck' => '<i class="fa-solid fa-money-bill text-base"></i>'])  !!}</p>
                </template>
                <template x-if="selectedBg === 'random'">
                    <p class="text-slate-600">{!! __('Do you really want to purchase a random background for :cost :buck?', ['cost' => config('school.economy.prices.background_random'), 'buck' => '<i class="fa-solid fa-money-bill text-base"></i>'])  !!}</p>
                </template>

                <div class="flex flex-row flex-nowrap items-center gap-4 my-4 justify-center">
                    <x-student-profile-pic :student="$student" :img="$student->avatar" class="h-24 grow"/>
                    <div><i class="fa-solid fa-angles-right"></i></div>

                    <template x-if="selectedBg !== '' && selectedBg !== 'random'">
                        <x-student-profile-pic :student="$student" class="h-24 grow"
                           x-bind:style="`
                               background-image: url('{{asset('assets/img/backgrounds')}}/${selectedBg}');
                               background-size: 100%;
                               background-repeat: repeat
                           `"/>
                    </template>
                    <template x-if="selectedBg === 'random'">
                        <x-student-profile-pic :student="$student" :bg="'bg-random.png'" class="h-24 grow"/>
                    </template>

                </div>

                <input type="hidden" name="bg" id="bg" :value="selectedBg">
                <div>
                    <x-input-label for="confirm-student-pin-bg">{{ __('Student\'s PIN (:name)', ['name' => $student->nickname]) }}</x-input-label>
                    <x-input-text id="confirm-student-pin-bg" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20"></x-input-text>
                </div>

                <div class="w-full flex justify-end mt-4">
                    <x-button-dark class="sm:w-auto w-full" :theme="$student->theme">
                        {{ __('Confirm') }}
                    </x-button-dark>
                </div>
            </form>
        </x-modal>

        {{--MODAL - theme--}}
        <x-modal name="confirm-student-pin-theme" focusable>
            <form action="{{ route('kiosk.student.purchase-theme', [$kiosk, $student]) }}" method="POST" class="w-full text-center sm:text-start p-6 text-slate-800">
                @csrf
                <h2 class="text-lg">{{ __('Confirm purchase') }}</h2>
                <p class="text-slate-600">{!! __('Do you really want to purchase a new theme color for :cost :buck?', ['cost' => config('school.economy.prices.rename'), 'buck' => '<i class="fa-solid fa-money-bill text-base"></i>'])  !!}</p>

                <input type="hidden" name="theme" id="theme" :value="selectedTheme">

                <div>
                    <x-input-label for="confirm-student-pin-theme">{{ __('Student\'s PIN (:name)', ['name' => $student->nickname]) }}</x-input-label>
                    <x-input-text id="confirm-student-pin-theme" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20"></x-input-text>
                </div>

                <div class="w-full flex justify-end mt-4">
                    <x-button-dark class="sm:w-auto w-full" :theme="$student->theme">
                        {{ __('Confirm') }}
                    </x-button-dark>
                </div>
            </form>
        </x-modal>

        {{--MODAL - rename--}}
        <x-modal name="confirm-student-pin-rename" focusable>
            <form x-data="{nickname: ''}" action="{{ route('kiosk.student.purchase-rename', [$kiosk, $student]) }}" method="POST" class="w-full text-center sm:text-start p-6 text-slate-800">
                @csrf
                <h2 class="text-lg">{{ __('Confirm purchase') }}</h2>
                <p class="text-slate-600">{!! __('Do you really want to purchase a new nickname for :cost :buck?', ['cost' => config('school.economy.prices.rename'), 'buck' => '<i class="fa-solid fa-money-bill text-base"></i>'])  !!}</p>

                <div class="flex flex-row flex-nowrap items-center gap-4 my-4 justify-center">
                    <div class="grow text-center">
                        <span class="font-semibold sm:text-lg">{{ $student->nickname }}</span>
                        <x-student-profile-pic :student="$student" class="h-24"/>
                    </div>
                    <div><i class="fa-solid fa-angles-right"></i></div>
                    <div class="grow text-center">
                        <span class="font-semibold sm:text-lg" x-text="nickname">{{ $student->nickname }}</span>
                        <x-student-profile-pic :student="$student" class="h-24"/>
                    </div>
                </div>

                <div class="mb-2">
                    <x-input-label for="nickname">{{ __('Your new nickname') }}</x-input-label>
                    <x-input-text id="nickname" class="w-full mt-1" name="nickname" type="text" minlength="4" maxlength="100" x-model="nickname"></x-input-text>
                </div>

                <div>
                    <x-input-label for="confirm-student-pin-rename">{{ __('Student\'s PIN (:name)', ['name' => $student->nickname]) }}</x-input-label>
                    <x-input-text id="confirm-student-pin-rename" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20"></x-input-text>
                </div>

                <div class="w-full flex justify-end mt-4">
                    <x-button-dark class="sm:w-auto w-full" :theme="$student->theme">
                        {{ __('Confirm') }}
                    </x-button-dark>
                </div>
            </form>
        </x-modal>

        {{--MODAL - change PIN--}}
        <x-modal name="confirm-student-pin-pin" focusable>
            <form action="{{ route('kiosk.student.change-pin', [$kiosk, $student]) }}" method="POST" class="w-full text-center sm:text-start p-6 text-slate-800">
                @csrf
                <h2 class="text-lg">{{ __('Change your PIN') }}</h2>
                <p class="text-slate-600">{{ __('PIN is used to confirm purchases and other changes to your profile. Make sure to remember it!') }}</p>

                <div class="my-2">
                    <x-input-label for="pin">{{ __('New PIN') }}</x-input-label>
                    <x-input-text id="pin" class="w-full mt-1" name="pin" type="password" minlength="4" maxlength="20" ></x-input-text>
                </div>

                <div class="mb-2">
                    <x-input-label for="pin_confirmation">{{ __('New PIN confirmation') }}</x-input-label>
                    <x-input-text id="pin_confirmation" class="w-full mt-1" name="pin_confirmation" type="password" minlength="4" maxlength="20" ></x-input-text>
                </div>

                <div>
                    <x-input-label for="pin_old">{{ __('Old PIN (:name)', ['name' => $student->nickname]) }}</x-input-label>
                    <x-input-text id="pin_old" class="w-full mt-1" name="pin_old" type="password" minlength="4" maxlength="20"></x-input-text>
                </div>

                <div class="w-full flex justify-end mt-4">
                    <x-button-dark class="sm:w-auto w-full" :theme="$student->theme">
                        {{ __('Confirm') }}
                    </x-button-dark>
                </div>
            </form>
        </x-modal>

    </main>



</x-kiosk-layout>
