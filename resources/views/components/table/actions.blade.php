<div class="flex gap-1 justify-center">
    @if($buttons)
        @if(in_array('edit',$buttons))
            {{-- دکمه ویرایش --}}
            @if(isset($route))
                <x-secondary-button
                        wire:navigate
                        href="{{ $route }}"
                        title="ویرایش"
                >ویرایش
                </x-secondary-button>
            @else
                <x-secondary-button
                        x-on:click="$dispatch('open-modal', 'edit'); $dispatch('get_data', { id: {{ $row->id }} } );"
                        title="ویرایش"
                >ویرایش
                </x-secondary-button>
            @endif
        @endif

        @if(in_array('delete',$buttons))
            {{-- دکمه حذف --}}
            <x-danger-button
                    x-on:click="$dispatch('open-modal', 'confirm-delete'); $dispatch('set_delete_id', { id: {{ $row->id }} }); $dispatch('set-selected-id', [{{ $row->id }}]);"
                    title="حذف"
            >
                حذف
            </x-danger-button>
        @endif

        @if(in_array('episode_buttons',$buttons))
            {{-- دکمه حذف --}}
            <x-success-button
                    wire:navigate
                    href="{{ $episodes_route }}"
                    title="قسمت ها">
                قسمت ها
            </x-success-button>
        @endif

        @if(in_array('event_buttons',$buttons))
            <a wire:navigate
               href="{{ $videos_route }}">
                <x-success-button
                        title="ویدئوها"
                >ویدئوها
                </x-success-button>
            </a>
            <a wire:navigate
               href="{{ $audios_route }}">
                <x-success-button
                        title="صوت ها"
                >صوت ها
                </x-success-button>
            </a>
        @endif

        @if(in_array('images_gallery_button',$buttons))
            <a wire:navigate
               href="{{ $images_gallery_route }}">
                <x-success-button
                        title="گالری تصاویر"
                >گالری تصاویر
                </x-success-button>
            </a>
        @endif

        @if(in_array('professors_button',$buttons))
            <a wire:navigate
               href="{{ $professors_route }}">
                <x-success-button
                        title="اساتید"
                >اساتید
                </x-success-button>
            </a>
        @endif

        @if(in_array('cooperators_button',$buttons))
            <a wire:navigate
               href="{{ $cooperators_route }}">
                <x-success-button
                        title="همکاران"
                >همکاران
                </x-success-button>
            </a>
        @endif
    @endif
</div>
