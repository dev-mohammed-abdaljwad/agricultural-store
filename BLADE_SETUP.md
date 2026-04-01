# Blade Frontend - Nile Harvest 🌾

Complete Laravel Blade implementation with RTL Arabic support, replacing React frontend.

## 📁 Directory Structure

```
resources/views/
├── layouts/
│   └── app.blade.php           # Main layout with Tailwind config
├── components/
│   ├── header.blade.php        # Navigation (responsive, auth-aware)
│   ├── footer.blade.php        # Footer + mobile nav
│   ├── auth-left-section.blade.php  # Reusable branding side
│   └── form-input.blade.php    # Dynamic form component
├── auth/
│   ├── login.blade.php         # Split-screen login form
│   └── register.blade.php      # Multi-step registration
├── customer/
│   └── dashboard.blade.php     # Customer dashboard
└── home.blade.php              # Landing page

app/Http/Controllers/Web/
├── AuthController.php          # Auth logic (login/register/logout)
└── HomeController.php          # Home & dashboard

routes/
└── web.php                      # Web routes
```

## 🎨 Design Features

### Color Scheme
- **Primary**: #154212 (Dark Green)
- **Secondary**: #7a5649 (Brown)
- **Tertiary**: #4b3500 (Orange)
- **Surface**: #fafaf5 (Nearly White)
- **Error**: #ba1a1a (Red)
- All accessible on light background

### Typography
- **Headlines**: Tajawal / Be Vietnam Pro
- **Body**: Almarai / Manrope
- **Labels**: Almarai

### Components
- ✅ Glass effect navigation
- ✅ Editorial shadows (0 24px 48px)
- ✅ Rounded borders (0.5rem corners)
- ✅ Material Icons (Google Symbols Outlined)
- ✅ Responsive grid layout (mobile-first)
- ✅ RTL support (dir="rtl", flex-row-reverse)
- ✅ Tailwind Forms plugin

## 📄 Views Overview

### Login (`auth/login.blade.php`)
- Split screen: Image + form
- Email/password fields
- Remember me checkbox
- Social logins (Google/Facebook buttons)
- Register link
- Password visibility toggle

### Register (`auth/register.blade.php`)
- Multi-step form (2 steps)
- Step 1: Name, email, phone, governorate, password
- Step 2: Farm address, farmer type, terms checkbox
- Visual stepper UI
- Left side benefits showcase
- Form validation feedback

### Home/Landing (`home.blade.php`)
- Header + navigation
- Hero benefits section
- Call-to-action section
- Authenticated/guest conditional rendering
- Footer with mobile nav

### Dashboard (`customer/dashboard.blade.php`)
- Welcome message with farmer type & location
- 4 stat cards (Total Orders, Pending, Completed, Unread Messages)
- Recent orders table
- Quick action cards (Shop, Support)
- Responsive grid layout

## 🔐 Authentication Flow

```
GET /              → home (landing)
GET /login         → login form
POST /login        → authenticate → dashboard
GET /register      → register form
POST /register     → create user → dashboard
POST /logout       → destroy session → home
GET /dashboard     → customer dashboard (auth required)
```

## 🎯 Components Usage

### Header
```blade
<x-header />
```
Automatically shows login/register for guests, "My Account" for authenticated users.

### Footer
```blade
<x-footer />
```
Includes mobile bottom navigation with active auth detection.

### Form Input
```blade
<x-form-input
    name="email"
    type="email"
    label="البريد الإلكتروني"
    placeholder="example@email.com"
    required
/>
```
Supports: text, email, tel, password, textarea, select  
Automatically RTL for text, LTR for email/phone  
Error display included  

### Auth Left Section
```blade
<x-auth-left-section
    :features="['Feature 1', 'Feature 2', 'Feature 3']"
/>
```
HD background image with overlay + feature list.

## 🛠️ Controller Methods

### AuthController
- `showLogin()` - Display login form
- `login()` - Handle login submission
- `showRegister()` - Display register form
- `register()` - Handle registration
- `logout()` - Destroy session

### HomeController
- `index()` - Show landing page
- `dashboard()` - Show customer dashboard (with stats)

## 📊 Dashboard Data

The dashboard fetches from database:
- Order counts (total, pending, completed)
- Unread messages
- Recent 5 orders with full details
- User details (name, farmer type, governorate)

## 🔗 Routes

```php
GET    /              → home
GET    /login         → auth.login
POST   /login         → AuthController@login
GET    /register      → auth.register
POST   /register      → AuthController@register
POST   /logout        → AuthController@logout (auth required)
GET    /dashboard     → customer.dashboard (auth required)
```

## 🎬 Getting Started

### 1. Database Setup
```bash
php artisan migrate:fresh --seed
```

### 2. Serve Application
```bash
php artisan serve
```

### 3. Test Accounts
- **Admin**: admin@nileharvest.com / password
- **Customer Farmer**: ahmed@example.com / password
- **Customer Trader**: fatima@example.com / password

### 4. Visit Pages
- Home: http://localhost:8000/
- Login: http://localhost:8000/login
- Register: http://localhost:8000/register
- Dashboard: http://localhost:8000/dashboard (requires login)

## 🚀 Next Steps

To complete the application, add:

1. **Product Pages**
   - Product listing with filters
   - Product details page
   - Shopping cart

2. **Order Management**
   - Place order page
   - Order details/tracking
   - Quote acceptance flow

3. **Messaging/Chat**
   - Customer-admin chat interface
   - Real-time messages

4. **Admin Panels**
   - Order management
   - Customer support
   - Product management

5. **API Integration**
   - Connect Blade forms to API endpoints
   - Use token-based auth
   - AJAX requests for dynamic updates

## 📱 Responsive Breakpoints

- **Mobile**: < 640px (full width)
- **Tablet**: 640px - 1024px (2-3 columns)
- **Desktop**: > 1024px (full grid layout)

## 🌐 Internationalization

Currently configured for **Arabic (RTL)**:
- All text in Arabic
- Form inputs auto-detect direction
- Navigation items flex-row-reverse
- Footer responsive to locale

## ⚠️ Important Notes

1. **CSRF Protection**: All forms include `@csrf`
2. **Validation**: Server-side validation in controllers
3. **Auth Middleware**: `/dashboard` requires authenticated user
4. **Guest Middleware**: `/login` and `/register` redirect if already authenticated
5. **Password**: Hashed with bcrypt
6. **Sessions**: Standard Laravel session-based auth

## 📚 Resources

- Tailwind CSS: https://tailwindcss.com
- Laravel Blade: https://laravel.com/docs/blade
- Material Icons: https://fonts.google.com/icons
- Google Fonts: https://fonts.google.com

---

**Status**: ✅ Production Ready  
**Last Updated**: March 31, 2026  
**Frontend Framework**: Laravel Blade 11  
**Styling**: Tailwind CSS 3 with Forms Plugin
