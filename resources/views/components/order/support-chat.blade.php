{{-- Support Chat Component --}}
@props(['messages' => [], 'supportAgentName' => 'الدعم الفني واللوجستي', 'sendUrl' => ''])

<section class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/10 flex flex-col h-[450px]">
    {{-- Chat Header --}}
    <div class="p-4 border-b border-outline-variant/20 flex items-center justify-between">
        <div class="flex items-center gap-3">
            {{-- Agent Avatar --}}
            <div class="relative">
                <span class="material-symbols-outlined text-primary text-3xl">support_agent</span>
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
            </div>
            
            {{-- Agent Info --}}
            <div>
                <h4 class="font-bold text-sm font-headline">{{ $supportAgentName }}</h4>
                <p class="text-[10px] text-on-surface-variant font-body">متصل الآن - الرد خلال دقائق</p>
            </div>
        </div>
        
        {{-- Menu Button --}}
        <button class="text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">more_vert</span>
        </button>
    </div>
    
    {{-- Chat Messages --}}
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-surface-container-low/20">
        @forelse($messages as $message)
            @if($message['sender'] === 'agent')
                {{-- Agent Message (Left) --}}
                <div class="flex justify-start">
                    <div class="bg-white p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[80%] border border-outline-variant/10">
                        <p class="text-sm leading-relaxed font-body">{{ $message['text'] }}</p>
                        <span class="text-[10px] text-on-surface-variant mt-1 block font-body">{{ $message['time'] }}</span>
                    </div>
                </div>
            @else
                {{-- Customer Message (Right) --}}
                <div class="flex justify-end">
                    <div class="bg-primary-fixed p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%] text-on-primary-fixed">
                        <p class="text-sm leading-relaxed font-body">{{ $message['text'] }}</p>
                        <span class="text-[10px] opacity-70 mt-1 block font-body">{{ $message['time'] }}</span>
                    </div>
                </div>
            @endif
        @empty
            <div class="flex items-center justify-center h-full text-on-surface-variant text-center">
                <p class="text-sm font-body">لا توجد رسائل حتى الآن. ابدأ المحادثة!</p>
            </div>
        @endforelse
    </div>
    
    {{-- Chat Input --}}
    <div class="p-4 bg-white border-t border-outline-variant/20 flex items-center gap-2">
        {{-- Attachment Button --}}
        <button class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-all">
            <span class="material-symbols-outlined">attach_file</span>
        </button>
        
        {{-- Input Field --}}
        <input 
            class="flex-1 bg-surface-container-highest border-none rounded-xl focus:ring-0 text-sm px-4 h-11 font-body"
            placeholder="اكتب رسالتك هنا..."
            type="text"
        />
        
        {{-- Send Button --}}
        <button class="h-11 w-11 bg-primary text-on-primary rounded-xl flex items-center justify-center transition-all active:scale-95 shadow-lg shadow-primary/20 hover:opacity-90">
            <span class="material-symbols-outlined">send</span>
        </button>
    </div>
</section>
