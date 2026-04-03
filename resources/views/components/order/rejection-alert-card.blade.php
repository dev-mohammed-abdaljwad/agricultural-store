@props([
    'message' => 'تم رفض العرض',
    'description' => '',
    'showProgress' => true,
])

<section class="bg-surface-container-lowest rounded-xl p-8 editorial-shadow border-r-4 border-error">
    <div class="flex items-start gap-6">
        {{-- Error Icon --}}
        <div class="bg-error-container p-4 rounded-xl flex-shrink-0">
            <span class="material-symbols-outlined text-error text-4xl">gavel</span>
        </div>

        {{-- Content --}}
        <div class="space-y-2 flex-grow">
            <h2 class="text-xl font-headline font-bold text-on-surface">
                {{ $message }}
            </h2>
            <p class="text-on-surface-variant leading-relaxed">
                {{ $description }}
            </p>

            {{-- Progress Indicator --}}
            @if($showProgress)
            <div class="mt-6 flex items-center gap-3 text-primary bg-primary-fixed/30 px-4 py-3 rounded-lg w-fit">
                <span class="material-symbols-outlined animate-spin text-lg">sync</span>
                <span class="font-bold">جاري إعداد عرض جديد...</span>
            </div>
            @endif
        </div>
    </div>

    {{ $slot }}
</section>
