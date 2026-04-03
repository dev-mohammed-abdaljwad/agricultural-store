{{-- resources/views/customer/orders/confirmed.blade.php --}}
@extends('layouts.customer')

@section('title', 'تأكيد الطلب')

@section('content')
    <main class="pt-24 pb-20 md:pr-64 min-h-screen" data-order-id="{{ $order['id'] ?? 0 }}" data-order-status="{{ $order['status'] ?? 'confirmed' }}">
        <x-order.order-confirmed 
            :order="$order"
            :items="$items"
            :totalAmount="$order['total']"
        />
    </main>

    <script>
        // Auto-refresh status every 10 seconds
        const orderId = document.querySelector('main').dataset.orderId;
        let lastStatus = document.querySelector('main').dataset.orderStatus;
        
        async function checkOrderStatus() {
            try {
                const response = await fetch(`/api/v1/orders/${orderId}/status`);
                const data = await response.json();
                
                if (data.status && data.status !== lastStatus) {
                    lastStatus = data.status;
                    // Reload page when status changes
                    location.reload();
                }
            } catch (error) {
                console.error('Error checking order status:', error);
            }
        }
        
        // Check immediately and then every 10 seconds
        checkOrderStatus();
        setInterval(checkOrderStatus, 10000);
        
        // Also check when page becomes visible (after minimizing/switching tabs)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                checkOrderStatus();
            }
        });
    </script>
@endsection
