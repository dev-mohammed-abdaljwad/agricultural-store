{{-- Order Tracking Timeline Component --}}
@props(['steps' => []])

<section class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
    <h3 class="font-headline font-bold text-lg mb-6">تتبع الطلب</h3>
    
    <div class="relative space-y-0">
        {{-- Progress Line --}}
        <div class="absolute right-[11px] top-2 bottom-2 w-0.5 bg-outline-variant/30"></div>
        
        {{-- Completed Steps --}}
        @foreach($steps as $index => $step)
            <div class="relative flex gap-4 pb-8 {{ $step['status'] === 'future' ? 'opacity-40' : '' }}">
                {{-- Step Circle --}}
                <div class="z-10 w-6 h-6 rounded-full flex items-center justify-center text-white font-bold text-sm font-headline
                    {{ $step['status'] === 'completed' ? 'bg-primary' : '' }}
                    {{ $step['status'] === 'current' ? 'bg-blue-600 animate-pulse-custom' : '' }}
                    {{ $step['status'] === 'future' ? 'bg-outline-variant' : '' }}
                ">
                    @if($step['status'] === 'completed')
                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">check</span>
                    @elseif($step['status'] === 'current')
                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">sync</span>
                    @endif
                </div>
                
                {{-- Step Content --}}
                <div>
                    <p class="text-sm font-bold font-headline
                        {{ $step['status'] === 'completed' ? 'text-primary' : '' }}
                        {{ $step['status'] === 'current' ? 'text-blue-600' : '' }}
                        {{ $step['status'] === 'future' ? 'text-on-surface' : '' }}
                    ">
                        {{ $step['title'] }}
                    </p>
                    <p class="text-xs text-on-surface-variant font-body">
                        {{ $step['date'] ?? '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</section>
