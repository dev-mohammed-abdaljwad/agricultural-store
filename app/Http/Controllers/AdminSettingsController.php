<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ToastService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        return view('admin.settings');
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
        ]);

        Auth::user()->update($validated);

        ToastService::updated('الملف الشخصي');
        return redirect()->route('admin.settings');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة',
            'password.required' => 'كلمة المرور الجديدة مطلوبة',
            'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        ToastService::updated('كلمة المرور');
        return redirect()->route('admin.settings');
    }

    /**
     * Update language preference
     */
    public function updateLanguage(Request $request)
    {
        $validated = $request->validate([
            'language' => 'required|in:ar,en',
        ]);

        Auth::user()->update([
            'language' => $validated['language'],
        ]);

        // Set app locale
        app()->setLocale($validated['language']);
        session()->put('locale', $validated['language']);

        ToastService::updated('اللغة');
        return redirect()->route('admin.settings');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'notify_orders' => 'boolean',
            'notify_messages' => 'boolean',
            'notify_products' => 'boolean',
            'notify_reports' => 'boolean',
        ]);

        // Update notification preferences as individual boolean columns
        Auth::user()->update([
            'notify_orders' => $request->has('notify_orders'),
            'notify_messages' => $request->has('notify_messages'),
            'notify_products' => $request->has('notify_products'),
            'notify_reports' => $request->has('notify_reports'),
        ]);

        ToastService::updated('التنبيهات');
        return redirect()->route('admin.settings');
    }
}
