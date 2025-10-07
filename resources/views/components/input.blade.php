@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'text-white bg-white/10 border-gray-500 focus:border-gray-300 focus:ring-gray-300 rounded-md shadow-sm',
]) !!}>
