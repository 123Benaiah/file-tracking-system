@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white border-gray-300 text-gray-900 placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 rounded-lg shadow-sm']) }}>
