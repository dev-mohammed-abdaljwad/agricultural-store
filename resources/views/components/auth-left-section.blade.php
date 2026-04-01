@props(['features' => [], 'title' => 'حصاد النيل', 'subtitle' => 'منصة زراعية متكاملة لمصر'])

<!-- Auth Left Section: Visual Branding -->
<section class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center p-12">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img 
            alt="حقول القمح الخضراء" 
            class="w-full h-full object-cover" 
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDljPk0OV5HNJpn5qqEPPgBydTkbo0DBCld6clgbUKbFaDq-la7zr1dn0j7nbGWQJ7ewVXjgG18Y19GcffoztIm2XOhLQk-sFvGvDVq4jmLUjIMLYOCqmlTbDnT0syCxx5-jri8AmZnmRU1Xtsc0z93wW8EgwBHBJD_Gty5fkSPpFZgZsg4QvIT7qMBXBNHTXrKzrc70jDFrZDJPVsk9LugACUCSVWolHma_AuVikMnlrPG_fkDv8lk48bDrHvj6d88uIf3cFfSvBa_"
        />
        <div class="absolute inset-0 bg-harvest-overlay"></div>
    </div>
    
    <!-- Content Overlay -->
    <div class="relative z-10 max-w-md text-center">
        <h1 class="font-headline text-5xl font-black text-white tracking-tight mb-4">
            {{ $title }}
        </h1>
        <p class="font-headline text-xl text-primary-fixed mb-12">
            {{ $subtitle }}
        </p>
        
        <ul class="space-y-6 text-right inline-block">
            @foreach($features as $feature)
                <li class="flex items-center gap-4 text-white">
                    <span class="material-symbols-outlined text-primary-fixed" style="font-variation-settings: 'FILL' 1;">
                        check_circle
                    </span>
                    <span class="text-lg font-medium">{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</section>
