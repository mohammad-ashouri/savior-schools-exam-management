@php use App\Models\Catalogs\Field; @endphp
@props([
    'messages',
    'model_name'=>null
])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ __(processValidationMessageForDynamicFields($message, $model_name ?? null)) }}</li>
        @endforeach
    </ul>
@endif
