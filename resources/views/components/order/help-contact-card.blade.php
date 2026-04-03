@props([
    'title' => 'هل تحتاج للمساعدة؟',
    'description' => '',
    'buttonText' => 'تحدث مع المستشار',
    'buttonUrl' => '#',
    'variant' => 'secondary', // secondary or primary
])

@php
    $bgColor = $variant === 'secondary' ? 'bg-secondary-container text-on-secondary-container' : 'bg-primary-container text-on-primary-container';
    $buttonBgColor = $variant === 'secondary' ? 'bg-on-secondary-container' : 'bg-on-primary-container';
@endphp

<div class="{{ $bgColor }} p-6 rounded-xl relative overflow-hidden">
    {{-- Decorative Icon Background --}}
    <span class="material-symbols-outlined absolute -bottom-6 -left-6 text-9xl opacity-10 rotate-12">psychology</span>

    {{-- Content --}}
    <h4 class="font-bold mb-2 relative z-10">{{ $title }}</h4>
    <p class="text-sm opacity-90 mb-4 leading-relaxed relative z-10">{{ $description }}</p>

    {{-- Button --}}
    <a 
        href="{{ $buttonUrl }}"
        class="w-full {{ $buttonBgColor }} text-white py-3 rounded-lg font-bold text-sm transition-all hover:bg-opacity-90 block text-center relative z-10"
    >
        {{ $buttonText }}
    </a>

    {{ $slot }}
</div>
