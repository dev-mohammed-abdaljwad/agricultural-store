# Adding New Pages to Blade Frontend

## Template for Creating New Pages

### 1. Create the View

**File**: `resources/views/path/page-name.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Page Title - نيل هارفست')

@section('content')
<!-- Header -->
<x-header />

<!-- Main Content -->
<main class="flex-grow pt-28 pb-20 px-4 md:px-8 max-w-7xl mx-auto w-full">
    <!-- Your content here -->
</main>

<!-- Footer -->
<x-footer />
@endsection

@push('styles')
<style>
    /* Page-specific styles */
</style>
@endpush

@push('scripts')
<script>
    // Page-specific JavaScript
</script>
@endpush
```

### 2. Create Controller Method

**File**: `app/Http/Controllers/Web/PageController.php`

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function showPage()
    {
        $data = [
            'title' => 'Page Title',
            'items' => [], // fetch data
        ];

        return view('path.page-name', $data);
    }
}
```

### 3. Add Route

**File**: `routes/web.php`

```php
use App\Http\Controllers\Web\PageController;

Route::get('/page-url', [PageController::class, 'showPage'])->name('page.name');
```

---

## Example: Products Listing Page

### 1. Controller (`app/Http/Controllers/Web/ProductController.php`)

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->get();

        $products = Product::where('status', 'active')
            ->paginate(12);

        return view('products.index', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $product->category
                ->products()
                ->where('id', '!=', $product->id)
                ->limit(4)
                ->get(),
        ]);
    }
}
```

### 2. Routes

```php
// routes/web.php
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
```

### 3. View (`resources/views/products/index.blade.php`)

```blade
@extends('layouts.app')

@section('title', 'المنتجات - نيل هارفست')

@section('content')
<x-header />

<main class="flex-grow pt-28 pb-20 px-4 md:px-8 max-w-7xl mx-auto w-full">
    <h1 class="text-4xl font-headline font-black text-primary mb-12">جميع المنتجات</h1>

    <!-- Filter/Category Tabs -->
    <div class="flex flex-row-reverse gap-4 mb-12 overflow-x-auto pb-2">
        <a href="{{ route('products.index') }}"
           class="px-6 py-2 rounded-full whitespace-nowrap @if(!request('category')) bg-primary text-white @else bg-surface-container @endif">
            الكل
        </a>
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}"
               class="px-6 py-2 rounded-full whitespace-nowrap @if(request('category') == $category->id) bg-primary text-white @else bg-surface-container @endif">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <a href="{{ route('products.show', $product) }}"
               class="bg-surface-container-lowest rounded-xl overflow-hidden hover:shadow-lg transition-all">
                <div class="aspect-square bg-surface-container-high overflow-hidden">
                    @if($product->images->first())
                        <img src="{{ $product->images->first()->url }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl">image</span>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-headline font-bold text-primary mb-2">{{ $product->name }}</h3>
                    <p class="text-on-surface-variant text-sm line-clamp-2">{{ $product->description }}</p>
                </div>
            </a>
        @empty
            <p class="col-span-full text-center text-on-surface-variant py-12">
                عذراً، لا توجد منتجات متاحة حالياً
            </p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12 flex justify-center">
        {{ $products->links() }}
    </div>
</main>

<x-footer />
@endsection
```

---

## Example: User Profile Page

### 1. Controller

```php
public function profile()
{
    $user = Auth::user();

    return view('profile.edit', [
        'user' => $user,
    ]);
}

public function updateProfile(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|unique:users,phone,' . Auth::id(),
        'governorate' => 'required|string',
        'address' => 'required|string',
    ]);

    Auth::user()->update($validated);

    return back()->with('success', 'تم تحديث البيانات بنجاح');
}
```

### 2. Routes

```php
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
});
```

### 3. View (`resources/views/profile/edit.blade.php`)

```blade
@extends('layouts.app')

@section('title', 'الملف الشخصي - نيل هارفست')

@section('content')
<x-header />

<main class="flex-grow pt-28 pb-20 px-4 md:px-8 max-w-7xl mx-auto w-full">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-headline font-black text-primary mb-8">الملف الشخصي</h1>

        @if(session('success'))
            <div class="bg-primary/20 text-primary p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}"
              class="bg-surface-container-lowest p-8 rounded-xl editorial-shadow">
            @csrf
            @method('PATCH')

            <x-form-input name="name" type="text" label="الاسم" :value="$user->name" required />
            <x-form-input name="email" type="email" label="البريد الإلكتروني" :value="$user->email" disabled />
            <x-form-input name="phone" type="tel" label="رقم الهاتف" :value="$user->phone" required />
            <x-form-input name="governorate" type="text" label="المحافظة" :value="$user->governorate" required />
            <x-form-input name="address" type="textarea" label="العنوان" :value="$user->address" required />

            <button type="submit"
                    class="w-full bg-primary text-on-primary py-3 rounded-xl font-bold mt-6 hover:opacity-90">
                حفظ التغييرات
            </button>
        </form>
    </div>
</main>

<x-footer />
@endsection
```

---

## Best Practices

1. **Use Components**: Extract repeated HTML into components
2. **Validation**: Always validate input server-side
3. **Authorization**: Use middleware for protected routes
4. **Naming**: Camel case for views/methods, kebab-case for URLs
5. **Responsiveness**: Test on mobile (use `md:` breakpoints)
6. **RTL Support**: Always use `flex-row-reverse` for nav, `dir="rtl"` for text
7. **Accessibility**: Include proper `<label>` tags, alt text for images
8. **Performance**: Use database queries efficiently, paginate large datasets
9. **Error Handling**: Show user-friendly error messages
10. **Data**: Pass minimal data from controller to view

---

## Common Patterns

### Pagination Links

```blade
{{ $items->links(data: ['path' => route('items.index')]) }}
```

### Form Errors

```blade
@error('fieldName')
    <span class="text-error text-sm">{{ $message }}</span>
@enderror
```

### Conditional Rendering

```blade
@if(Auth::user()->role === 'admin')
    <!-- Admin content -->
@elseif(Auth::user()->role === 'customer')
    <!-- Customer content -->
@else
    <!-- Guest content -->
@endif
```

### Links

```blade
<a href="{{ route('products.show', $product) }}">View Product</a>
```

### Forms

```blade
<form method="POST" action="{{ route('action.store') }}">
    @csrf
    <!-- fields -->
</form>
```
