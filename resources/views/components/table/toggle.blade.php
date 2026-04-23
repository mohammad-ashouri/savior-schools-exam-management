@props([
    'model' => null,
    'checked' => $checked,
    'id' => 'toggle-'.uniqid(),
])
<div class="flex justify-end items-center select-none" dir="ltr"
     x-data="{ on: @js($checked) }"
     @if($model)
         x-init="$watch('on', value => $wire.set('{{ $model }}', value))"
    @endif
>
    <button
        id="{{ $id }}"
        type="button"
        @click="on = !on; $dispatch('change-status',{'id':{{ $id }}})"
        class="relative inline-flex h-6 w-12 items-center rounded-full transition-all duration-300 ease-in-out focus:outline-none border-2"
        :class="on
            ? 'bg-emerald-400/20 border-emerald-400 shadow-[0_0_8px_rgba(16,185,129,0.6)]'
            : 'bg-red-400/20 border-red-400 shadow-[0_0_6px_rgba(239,68,68,0.6)]'"
    >
        <span
            :class="on
                ? 'translate-x-[26px] bg-emerald-400 shadow-[0_0_6px_rgba(16,185,129,0.8)]'
                : 'translate-x-[2px] bg-red-400 shadow-[0_0_6px_rgba(239,68,68,0.8)]'"
            class="absolute z-10 inline-block h-4 w-4 transform rounded-full transition-transform duration-300 ease-in-out"
        ></span>
    </button>
</div>
