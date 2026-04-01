<!-- Footer -->
<footer class="w-full py-8 sm:py-10 md:py-12 px-4 sm:px-6 md:px-8 mt-auto bg-surface-container-low border-t border-outline-variant/10">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row-reverse justify-between items-center gap-4 sm:gap-6 text-xs sm:text-sm font-label">
        <div class="text-base sm:text-lg font-bold text-primary font-headline">نيل هارفست</div>
        <div class="flex flex-row-reverse gap-4 sm:gap-8 flex-wrap justify-center">
            <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">سياسة الخصوصية</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">الشروط والأحكام</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">دليل المزارع</a>
        </div>
        <div class="text-on-surface-variant text-xs sm:text-sm text-center md:text-right">© ٢٠٢٤ نيل هارفست - جودة الأرض المصرية</div>
    </div>
</footer>

<!-- Mobile Bottom Navigation -->
<nav class="lg:hidden fixed bottom-0 left-0 w-full flex flex-row-reverse justify-around items-center px-2 sm:px-4 pb-2 sm:pb-4 pt-2 glass-nav shadow-[0_-4px_24px_rgba(21,66,18,0.06)] z-50 rounded-t-2xl">
    <a class="flex flex-col items-center justify-center text-on-surface-variant px-3 sm:px-5 py-2 hover:text-primary transition-colors" href="{{ route('home') }}">
        <span class="material-symbols-outlined text-xl sm:text-2xl">home</span>
        <span class="text-[10px] sm:text-[11px] font-label font-semibold mt-1">الرئيسية</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant px-3 sm:px-5 py-2 hover:text-primary transition-colors" href="#">
        <span class="material-symbols-outlined text-xl sm:text-2xl">grass</span>
        <span class="text-[10px] sm:text-[11px] font-label font-semibold mt-1">متجري</span>
    </a>
    @auth
        <a class="flex flex-col items-center justify-center bg-primary-fixed text-primary rounded-lg sm:rounded-xl px-3 sm:px-5 py-2" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined text-xl sm:text-2xl" style="font-variation-settings: 'FILL' 1;">person</span>
            <span class="text-[10px] sm:text-[11px] font-label font-semibold mt-1">حسابي</span>
        </a>
    @else
        <a class="flex flex-col items-center justify-center bg-primary-fixed text-primary rounded-lg sm:rounded-xl px-3 sm:px-5 py-2" href="{{ route('login') }}">
            <span class="material-symbols-outlined text-xl sm:text-2xl" style="font-variation-settings: 'FILL' 1;">person</span>
            <span class="text-[10px] sm:text-[11px] font-label font-semibold mt-1">حسابي</span>
        </a>
    @endauth
</nav>
