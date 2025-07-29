@props(['title', 'value', 'color' => 'blue'])

@php
    $colorClasses = [
        'blue' => 'bg-blue-100 text-blue-800',
        'green' => 'bg-green-100 text-green-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'red' => 'bg-red-100 text-red-800',
        'indigo' => 'bg-indigo-100 text-indigo-800',
        'purple' => 'bg-purple-100 text-purple-800',
    ];
@endphp

<div class="bg-white rounded-xl shadow-md p-4">
    <div class="text-sm font-medium text-gray-600 mb-1">{{ $title }}</div>
    <div class="text-2xl font-bold {{ $colorClasses[$color] ?? $colorClasses['blue'] }}">
        {{ $value }}
    </div>
</div>
