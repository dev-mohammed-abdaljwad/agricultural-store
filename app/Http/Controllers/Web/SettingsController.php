<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ToastService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        return view('customer.settings');
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
            'governorate' => 'nullable|string',
            'customer_type' => 'required|in:farmer,trader',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'customer_type.required' => 'نوع الحساب مطلوب',
        ]);

        Auth::user()->update($validated);

        ToastService::updated('الملف الشخصي');
        return redirect()->route('settings');
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
        return redirect()->route('settings');
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
        return redirect()->route('settings');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'notify_orders' => 'boolean',
            'notify_messages' => 'boolean',
            'notify_price_changes' => 'boolean',
            'notify_promotions' => 'boolean',
        ]);

        // Update notification preferences as individual boolean columns
        Auth::user()->update([
            'notify_orders' => (bool) $request->has('notify_orders'),
            'notify_messages' => (bool) $request->has('notify_messages'),
            'notify_price_changes' => (bool) $request->has('notify_price_changes'),
            'notify_promotions' => (bool) $request->has('notify_promotions'),
        ]);

        ToastService::updated('التنبيهات');
        return redirect()->route('settings');
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|current_password',
            'confirm_delete' => 'required|accepted',
        ], [
            'password.required' => 'كلمة المرور مطلوبة',
            'password.current_password' => 'كلمة المرور غير صحيحة',
            'confirm_delete.required' => 'يجب تأكيد حذف الحساب',
            'confirm_delete.accepted' => 'يجب قبول شروط حذف الحساب',
        ]);

        $user = Auth::user();

        // Start transaction for data cleaning
        DB::beginTransaction();

        try {
            // Delete related data first
            $user->orders()->delete();
            $user->conversations()->delete();
            $user->messages()->delete();
            $user->cartItems()->delete();
            
            // Delete user
            $user->delete();

            DB::commit();

            Auth::logout();

            session()->flash('toast', [
                'type' => 'success',
                'title' => 'تم الحذف',
                'message' => 'تم حذف حسابك بنجاح',
            ]);
            
            return redirect()->route('home');
        } catch (\Exception $e) {
            DB::rollBack();
            
            ToastService::deletionFailed('الحساب', $e->getMessage());
            return back();
        }
    }
}
