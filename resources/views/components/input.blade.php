@props(['disabled' => false])

<div class="relative w-full">
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' => 'peer block w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-2.5 pb-2.5 pt-5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500'
    ]) !!} placeholder=" " />
    {{ $slot }}
</div>