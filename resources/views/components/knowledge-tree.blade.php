@forelse($edufields as $edufield)
    <div x-data="{fieldExpanded: false}">
        <div @click="fieldExpanded = !fieldExpanded"
             class="flex items-center gap-2 cursor-pointer hover:bg-slate-100 p-2 rounded-md">
            <i class="fa-solid fa-book"></i>
            <div>
                <span class="text-xs uppercase text-slate-400">{{ __('Education field') }}</span>
                <h6 class="text-slate-600 text-lg sm:text-nowrap leading-none">{{ $edufield->name }}</h6>
            </div>
            <span class="text-slate-400 leading-none self-end">{{ $edufield->categories->count() }}</span>
            <div class="grow"></div>
            @if($mode !== 'kiosk')
                <x-button-outline :href="route('edufield.edit', [$edufield, 'course' => $course])" @click.stop>
                    <i class="fa-solid fa-wrench"></i>
                </x-button-outline>
                <x-button-outline :href="route('category.create', [$edufield, 'course' => $course])" @click.stop>
                    <i class="fa-solid fa-plus"></i>
                </x-button-outline>
            @endif
        </div>

        <div x-show="fieldExpanded" x-collapse>
            <div class="border border-slate-200 rounded-md p-1 ps-4">
                @forelse($edufield->categories as $category)
                    <div x-data="{categoryExpanded: false}">
                        <div @click="categoryExpanded = !categoryExpanded"
                             class="flex items-center gap-2 cursor-pointer hover:bg-slate-100 p-2 rounded-md">
                            <i class="fa-solid fa-caret-right self-end" x-show=!categoryExpanded></i>
                            <i class="fa-solid fa-caret-down self-end" x-show=categoryExpanded></i>
                            <div>
                                <span class="text-xs uppercase text-slate-400">{{ __('Category') }}</span>
                                <h6 class="text-slate-600 text-lg sm:text-nowrap leading-none">{{ $category->name }}</h6>
                            </div>
                            <span class="text-slate-400 leading-none self-end">{{ $category->subcategories->count() }}</span>
                            <div class="grow"></div>
                            @if($mode !== 'kiosk')
                                <x-button-outline :href="route('category.edit', [$category, 'course' => $course])" @click.stop>
                                    <i class="fa-solid fa-wrench"></i>
                                </x-button-outline>
                                <x-button-outline :href="route('subcategory.create', [$category, 'course' => $course])" @click.stop>
                                    <i class="fa-solid fa-plus"></i>
                                </x-button-outline>
                            @endif
                        </div>

                        <div x-show="categoryExpanded" x-collapse>
                            <div class="border border-slate-200 rounded-md p-1 ps-4">
                                @forelse($category->subcategories as $subcategory)
                                    <div x-data="{subcategoryExpanded: false}">
                                        <div @click="subcategoryExpanded = !subcategoryExpanded"
                                             class="flex items-center gap-2 cursor-pointer hover:bg-slate-100 p-2 rounded-md">
                                            <i class="fa-solid fa-caret-right self-end" x-show=!subcategoryExpanded></i>
                                            <i class="fa-solid fa-caret-down self-end" x-show=subcategoryExpanded></i>
                                            <div>
                                                <span class="text-xs uppercase text-slate-400">{{ __('Subcategory') }}</span>
                                                <h6 class="text-slate-600 text-lg sm:text-nowrap leading-none">{{ $subcategory->name }}</h6>
                                            </div>
                                            <span class="text-slate-400 leading-none self-end">{{ $subcategory->knowledge->count() }}</span>
                                            <div class="grow"></div>
                                            @if($mode !== 'kiosk')
                                                <x-button-outline :href="route('subcategory.edit', [$subcategory, 'course' => $course])" @click.stop>
                                                    <i class="fa-solid fa-wrench"></i>
                                                </x-button-outline>
                                                <x-button-outline :href="route('knowledge.create', [$subcategory, 'course' => $course])" @click.stop>
                                                    <i class="fa-solid fa-plus"></i>
                                                </x-button-outline>
                                            @endif
                                        </div>

                                        <div x-show="subcategoryExpanded" x-collapse>
                                            <div class="border border-slate-200 rounded-md p-1 ps-4">
                                                @forelse($subcategory->knowledge as $knowledge)
                                                    @if($mode === 'kiosk')
                                                        <div class=" cursor-pointer hover:bg-slate-100 rounded-md"
                                                        @click="document.getElementById('knowledge-{{$knowledge->id}}').checked = true" >
                                                    @endif

                                                    <div class="flex items-center gap-2 p-2 rounded-md ">
                                                        <i class="fa-solid fa-graduation-cap"></i>
                                                        <div>
                                                            <span class="text-xs uppercase text-slate-400">{{ __('Knowledge') }}</span>
                                                            <h6 class="text-slate-600 text-lg sm:text-nowrap leading-none">{{ $knowledge->name }}</h6>
                                                        </div>
                                                        <span class="text-slate-400 text-xs leading-none self-end">{{ $knowledge->courses->count() }}</span>
                                                        <div class="grow"></div>
                                                        @if($mode !== 'kiosk')
                                                            <x-button-outline :href="route('knowledge.edit', [$knowledge, 'course' => $course])">
                                                                <i class="fa-solid fa-wrench"></i>
                                                            </x-button-outline>
                                                        @endif
                                                        @if($course && $mode !== 'kiosk')
                                                            <label for="knowledge-{{$knowledge->id}}" class="hidden">{{ $knowledge->name }}</label>
                                                            <input form="{{ $formName }}" id="knowledge-{{$knowledge->id}}" type="checkbox" name="knowledge[]" value="{{ $knowledge->id }}" @checked($isChecked($course, $knowledge))>
                                                        @elseif($mode === 'kiosk')
                                                            <label for="knowledge-{{$knowledge->id}}" class="hidden">{{ $knowledge->name }}</label>
                                                            <input form="{{ $formName }}" id="knowledge-{{$knowledge->id}}" type="radio" name="knowledge_id" value="{{ $knowledge->id }}" @checked(old('knowledge_id') == $knowledge->id)>
                                                        @endif
                                                    </div>
                                                    <p>{{ $knowledge->description }}</p>

                                                    @if($mode === 'kiosk')</div>@endif
                                                @empty
                                                    <p class="text-slate-400">{{ __('There are no knowledge units in this subcategory field. Create one.') }}</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-slate-400">{{ __('There are no subcategories in this category field. Create one.') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400">{{ __('There are no categories in this education field. Create one.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
@empty
    <p class="text-slate-400">{{ __('There are no education fields. Create one.') }}</p>
@endforelse
