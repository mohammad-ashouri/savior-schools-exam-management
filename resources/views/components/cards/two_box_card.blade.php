@props([
    'title',
    'blue_side_label',
    'blue_side_value',
    'green_side_label',
    'green_side_value',
    'red_side_label',
    'red_side_value',
    'grid_cols'=>2,
    'class'=>''
])
<div class="p-4 {{ $class }}">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-5 flex flex-col gap-2 transition">

        <!-- عنوان سرویس -->
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                {{ $title ?? null }}
            </h2>
        </div>

        <div class="grid grid-cols-{{$grid_cols}} gap-4">

            @if(isset($blue_side_value))
                <div class="flex flex-col items-center justify-center bg-blue-100 dark:bg-blue-900 rounded-lg py-4">
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $blue_side_label ?? 'لیبل آبی' }}</span>
                    <span class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                    {{ number_format($blue_side_value) ?? 0 }}
                </span>
                </div>
            @endif

            @if(isset($green_side_value))
                <div class="flex flex-col items-center justify-center bg-green-100 dark:bg-green-900 rounded-lg py-4">
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $green_side_label ?? 'لیبل سبز' }}</span>
                    <span class="text-2xl font-bold text-green-700 dark:text-green-300">
                    {{ number_format($green_side_value) ?? 0 }}
                </span>
                </div>
            @endif

            @if(isset($green_side_value))
                <div class="flex flex-col items-center justify-center bg-red-100 dark:bg-red-900 rounded-lg py-4">
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $red_side_label ?? 'لیبل قرمز' }}</span>
                    <span class="text-2xl font-bold text-red-700 dark:text-red-300">
                    {{ number_format($red_side_value) ?? 0 }}
                </span>
                </div>
            @endif

        </div>
    </div>
</div>
