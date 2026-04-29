@props([
    'disabled' => false,
    'options' => [],
    'selected' => null,
    'required' => null,
    'name' => '',
    'id' => '',
    'placeholder' => '',
    'multiple' => false,
    'keyEqualToValue'=>false
])

@php
    $id = $id ?: $name;
@endphp

<select {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $multiple ? 'multiple' : '' }}
        {!! $attributes->merge([
            'class' =>
                'w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm',
        ]) !!}>

    @if ($placeholder)
        <option value="" hidden>
            {{ $placeholder }}
        </option>
    @endif

    @foreach ($options as $key => $value)
        <option value="{{ $keyEqualToValue ? $value : $key }}"
                {{ $selected == ($keyEqualToValue ? $value : $key) ? 'selected' : '' }}>
            {{ $value }}
        </option>
    @endforeach

</select>
