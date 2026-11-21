@props(['status' => ''])

@php
    $statusColor = match ($status) {
        'Aman' => 'bg-green-100 text-green-800',
        'Menipis' => 'bg-yellow-100 text-yellow-800',
        'Habis' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {$statusColor}"]) }}>
    {{ $status }}
</span>
