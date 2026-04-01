# Quick Reference - Blade Components & Views

## 🎯 File Quick Map

### Layouts
| File | Purpose | Includes |
|------|---------|----------|
| `layouts/app.blade.php` | Master layout | Tailwind config, fonts, styles, @yield slots |

### Components (Reusable)
| Component | Usage | Parameters |
|-----------|-------|-----------|
| `header` | `<x-header />` | Auto-detects auth state |
| `footer` | `<x-footer />` | Mobile nav included |
| `auth-left-section` | `<x-auth-left-section :features="[...]" />` | `:features` array |
| `form-input` | `<x-form-input name="email" type="email" label="..." />` | See table below |

### Form Input Component Details
```php
<x-form-input
    name="fieldName"           // Required: input name
    type="text"                // text, email, tel, password, textarea, select
    label="Field Label"        // Required: label text
    placeholder="..."          // Optional: placeholder text
    value=""                   // Optional: default value
    required                   // Optional: makes field required
    :options="[...]"           // For select: key => label
/>
```

### Views (Full Pages)
| View | Route | Auth Required | Purpose |
|------|-------|--------------|---------|
| `auth/login.blade.php` | GET `/login` | No | Login form |
| `auth/register.blade.php` | GET `/register` | No | Multi-step registration |
| `home.blade.php` | GET `/` | No | Landing page |
| `customer/dashboard.blade.php` | GET `/dashboard` | Yes | Dashboard with stats |

## 🔧 Controllers

### AuthController
```php
// Routes
GET    /login              → showLogin()
POST   /login              → login()
GET    /register           → showRegister()
POST   /register           → register()
POST   /logout             → logout()

// Methods handle validation, auth, redirects
```

### HomeController
```php
// Routes
GET    /                   → index() [returns home view]
GET    /dashboard          → dashboard() [returns dashboard with data]

// dashboard() provides:
// - totalOrders
// - pendingOrders
// - completedOrders
// - unreadMessages
// - recentOrders
```

## 📝 Blade Syntax Quick Reference

### Directives Used
```blade
@extends('layouts.app')          // Extend layout
@section('content') ... @endsection  // Create section
@section('title', '...')         // Set title
@push('styles') ... @endpush     // Add CSS
@push('scripts') ... @endpush    // Add JS

@auth ... @endauth               // Show if authenticated
@guest ... @endguest             // Show if not authenticated

@foreach(...) ... @endforeach    // Loop
@if(...) ... @endif              // Conditional

@csrf                            // CSRF token
@error('field') ... @enderror    // Error display
@selected(...), @checked(...)    // Form helpers

{{ $variable }}                  // Echo with escaping
{!! $html !!}                   // Echo without escaping
