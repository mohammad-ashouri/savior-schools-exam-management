@props([
    'model' => null,
    'messages' => null,
])

@php
    $errorsList = $messages ?? ($model ? $errors->get($model) : []);
@endphp

@if (!empty($errorsList))
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $errorsList as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif