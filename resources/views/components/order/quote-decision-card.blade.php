{{-- Quote Decision Card with Countdown Timer --}}
@props(['acceptUrl' => '', 'rejectUrl' => '', 'expiresIn' => '08:42:15'])

<section class="bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/10">
    <h3 class="font-headline font-bold text-lg mb-4">اتخاذ قرار</h3>
    
    <p class="text-on-surface-variant text-sm mb-6 leading-relaxed font-body">
        يرجى مراجعة تفاصيل عرض السعر أدناه. ينتهي هذا العرض خلال الفترة المحددة في العداد.
    </p>
    
    <div class="flex flex-col gap-3 mb-6">
        {{-- Accept Button --}}
        <form action="{{ $acceptUrl }}" method="POST" class="w-full">
            @csrf
            <button 
                type="submit"
                class="w-full py-4 bg-primary text-on-primary font-bold rounded-xl flex items-center justify-center gap-2 hover:opacity-90 transition-all active:scale-95 font-headline"
            >
                <span class="material-symbols-outlined">check_circle</span>
                قبول العرض والتعميد
            </button>
        </form>
        
        {{-- Reject Button --}}
        <form action="{{ $rejectUrl }}" method="POST" class="w-full">
            @csrf
            <button 
                type="submit"
                class="w-full py-4 border-2 border-error text-error font-bold rounded-xl flex items-center justify-center gap-2 hover:bg-error/5 transition-all active:scale-95 font-headline"
            >
                <span class="material-symbols-outlined">cancel</span>
                رفض العرض
            </button>
        </form>
    </div>
    
    {{-- Countdown Timer --}}
    <div class="pt-6 border-t border-outline-variant/20 flex flex-col items-center">
        <span class="text-xs text-on-surface-variant mb-3 font-body">ينتهي العرض في:</span>
        <div class="flex gap-4 text-center font-headline">
            {{-- Hours --}}
            <div>
                <div class="text-xl font-black text-secondary" id="hours">08</div>
                <div class="text-[10px] uppercase text-on-surface-variant font-body">ساعة</div>
            </div>
            
            <div class="text-xl font-black text-secondary">:</div>
            
            {{-- Minutes --}}
            <div>
                <div class="text-xl font-black text-secondary" id="minutes">42</div>
                <div class="text-[10px] uppercase text-on-surface-variant font-body">دقيقة</div>
            </div>
            
            <div class="text-xl font-black text-secondary">:</div>
            
            {{-- Seconds --}}
            <div>
                <div class="text-xl font-black text-secondary" id="seconds">15</div>
                <div class="text-[10px] uppercase text-on-surface-variant font-body">ثانية</div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Simple countdown timer (in production, calculate from server)
    function updateTimer() {
        let hours = parseInt(document.getElementById('hours').textContent);
        let minutes = parseInt(document.getElementById('minutes').textContent);
        let seconds = parseInt(document.getElementById('seconds').textContent);
        
        if (seconds > 0) {
            seconds--;
        } else if (minutes > 0) {
            seconds = 59;
            minutes--;
        } else if (hours > 0) {
            seconds = 59;
            minutes = 59;
            hours--;
        }
        
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }
    
    setInterval(updateTimer, 1000);
</script>
@endpush
