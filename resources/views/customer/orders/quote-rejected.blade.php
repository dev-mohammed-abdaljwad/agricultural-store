@extends('layouts.customer')

@section('title', 'عرض مرفوض')

@php
    // Get the first admin user for support conversation
    $supportUserId = \App\Models\User::where('role', 'admin')->first()?->id ?? 1;
@endphp

@section('content')
<x-order.quote-rejected 
    :orderNumber="$order['id']"
    orderNumberDisplay="طلب توريد #{{ $order['number'] }}"
    statusBadge="تم رفض عرض السعر — بانتظار عرض جديد"
    rejectionMessage="تم رفض العرض"
    rejectionDescription="سيقوم فريقنا بمراجعة السعر بناءً على ملاحظاتكم وتقديم عرض أفضل يتناسب مع معايير السوق الحالية. نعتذر عن أي تأخير."
    :products="$products"
    :rejectionNotes="$rejectionNotes"
    :trackingSteps="$trackingSteps"
    helpTitle="هل تحتاج للمساعدة؟"
    helpDescription="يمكنك التواصل مع مدير الحساب الخاص بك مباشرة لمناقشة تفاصيل الأسعار والكميات."
    helpButtonText="تحدث مع المستشار"
    :helpButtonUrl="route('chat.show', ['userId' => $supportUserId])"
/>
@endsection
