@extends('layouts.admin')

@section('title', 'المستخدمون - حصاد')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 pb-20">
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">إدارة المستخدمين</h2>
        <p class="text-on-surface-variant text-sm">عرض وإدارة جميع مستخدمي المنصة</p>
    </section>

    <!-- Users Table -->
    <section class="bg-surface-container-lowest rounded-lg overflow-hidden border border-outline-variant/10">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">الاسم</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant hidden sm:table-cell">البريد الإلكتروني</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">النوع</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">الحالة</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/30">
                    @forelse($users as $user)
                        <tr class="hover:bg-surface-container/50">
                            <td class="px-4 sm:px-6 py-4 font-bold">{{ $user->name }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm hidden sm:table-cell">{{ $user->email }}</td>
                            <td class="px-4 sm:px-6 py-4 text-sm">
                                {{ $user->customer_type === 'trader' ? 'تاجر' : 'مزارع' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold 
                                    {{ $user->status === 'active' ? 'bg-success-container text-on-success-container' : 'bg-error-container text-on-error-container' }}">
                                    {{ $user->status === 'active' ? 'نشط' : 'معطل' }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-left">
                                <button class="text-primary hover:underline text-sm">تعديل</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">لا يوجد مستخدمون</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="flex justify-center">
            {{ $users->links() }}
        </div>
    @endif
</main>
@endsection
