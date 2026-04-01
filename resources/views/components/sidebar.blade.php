@props(['categories' => []])

<!-- Sidebar Navigation -->
<aside class="hidden lg:fixed lg:right-0 lg:top-16 lg:h-[calc(100vh-64px)] lg:w-64 bg-surface border-l border-outline-variant/15 flex flex-col py-6 gap-2 z-40">
    <!-- User Greeting -->
    <div class="px-6 mb-8">
        <h2 class="text-primary font-bold text-lg font-headline">
            @auth
                مرحباً، {{ Auth::user()->name }}
            @else
                مرحباً بك
            @endauth
        </h2>
        <p class="text-on-surface-variant text-xs font-medium">
            @auth
                {{ Auth::user()->customer_type === 'farmer' ? 'مزارع معتمد' : 'تاجر محاصيل' }}
            @else
                زائر
            @endauth
        </p>
    </div>
    
    <!-- Categories List -->
    <div class="flex flex-col gap-1">
        @forelse($categories as $index => $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}"
               class="flex flex-row-reverse items-center gap-3 transition-all duration-300 text-sm font-medium
                   @if($index === 0 && !request('category'))
                       bg-primary-fixed text-primary rounded-r-full px-4 py-3 ml-4
                   @elseif(request('category') == $category->id)
                       bg-primary-fixed text-primary rounded-r-full px-4 py-3 ml-4
                   @else
                       text-on-surface-variant hover:bg-surface-container-low px-4 py-3 mx-2 rounded-md
                   @endif">
                
                <!-- Dynamic Icons -->
                <span class="material-symbols-outlined text-base">
                    @switch($category->name)
                        @case('مبيدات')
                            pest_control
                            @break
                        @case('محاصيل')
                            psychiatry
                            @break
                        @case('أسمدة')
                            agriculture
                            @break
                        @case('مصنعون')
                            factory
                            @break
                        @case('معدات الوقاية')
                            health_and_safety
                            @break
                        @default
                            package
                    @endswitch
                </span>
                <span>{{ $category->name }}</span>
            </a>
        @empty
            <p class="text-on-surface-variant text-sm px-4 py-2">لا توجد فئات متاحة</p>
        @endforelse
    </div>
    
    <!-- Pro Tip Section -->
    <div class="mt-auto px-6 py-4 border-t border-outline-variant/10">
        <div class="p-4 bg-tertiary-fixed rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-on-tertiary-fixed flex-shrink-0">info</span>
            <p class="text-xs text-on-tertiary-fixed leading-relaxed font-medium">
                احصل على استشارة زراعية مجانية عند أول طلب لك.
            </p>
        </div>
    </div>
</aside>
