{{-- resources/views/customer/orders/confirmed.blade.php --}}
@extends('layouts.customer')

@section('title', 'تأكيد الطلب')

@section('content')
    <main class="pt-24 pb-20 md:pr-64 min-h-screen" data-order-id="{{ $order['id'] ?? '' }}" data-order-status="{{ $order['status'] ?? 'confirmed' }}">
        <x-order.order-confirmed 
            :order="$order"
            :items="$items"
            :totalAmount="$order['total']"
        />
    </main>

    <script>
        // Auto-refresh status every 10 seconds
        const mainElement = document.querySelector('main');
        const orderId = mainElement ? mainElement.dataset.orderId : null;
        let lastStatus = mainElement ? mainElement.dataset.orderStatus : null;
        
        async function checkOrderStatus() {
            if (!orderId) {
                console.warn('Order ID not available for status check');
                return;
            }
            
            try {
                // Use a web route instead of API to work with session authentication
                const response = await fetch(`/orders/${orderId}/status-check`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                
                // Check if response is OK before parsing JSON
                if (!response.ok) {
                    console.warn('Status check error:', response.status);
                    return;
                }
                
                // Check if response is actually JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    console.warn('Status check returned non-JSON response:', contentType);
                    return;
                }
                
                const data = await response.json();
                
                if (data.status && data.status !== lastStatus) {
                    lastStatus = data.status;
                    // Show notification
                    if (data.notification_message) {
                        // Show toast-like notification
                        const notification = document.createElement('div');
                        notification.className = 'fixed top-24 right-4 bg-success text-on-success px-6 py-3 rounded-lg shadow-lg z-50';
                        notification.innerHTML = '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-xl">check_circle</span><span>' + data.notification_message + '</span></div>';
                        document.body.appendChild(notification);
                        
                        // Auto-remove after 4 seconds
                        setTimeout(() => notification.remove(), 4000);
                        
                        console.log('✓ تحديث: ' + data.notification_message);
                    }
                    // Reload page when status changes
                    setTimeout(() => location.reload(), 1500);
                }
            } catch (error) {
                console.error('Error checking order status:', error.message);
            }
        }
        
        // Check immediately and then every 10 seconds (only if orderId is available)
        if (orderId) {
            checkOrderStatus();
            setInterval(checkOrderStatus, 10000);
        }
        
        // Also check when page becomes visible (after minimizing/switching tabs)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && orderId) {
                checkOrderStatus();
            }
        });
        
        // Listen for real-time order status updates via Pusher
        if (orderId && window.pusher) {
            // Subscribe to customer notifications channel
            const customerNotifications = window.pusher.subscribe('private-customer.notifications.' + {{ auth()->id() }});
            
            // Also subscribe to specific order channel
            const orderChannel = window.pusher.subscribe('private-order.' + orderId);
            
            // Listen for order status updates
            const handleStatusUpdate = function(data) {
                if (data.order_id == orderId && data.message) {
                    console.log('✓ تحديث مباشر من الإدارة: ' + data.message);
                    
                    // Update last known status
                    lastStatus = data.status;
                    
                    // Show notification
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-24 right-4 bg-success text-on-success px-6 py-3 rounded-lg shadow-lg z-50';
                    notification.innerHTML = '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-xl">check_circle</span><span>' + data.message + '</span></div>';
                    document.body.appendChild(notification);
                    
                    setTimeout(() => notification.remove(), 4000);
                    
                    // Reload page to show updated status
                    setTimeout(() => location.reload(), 1500);
                }
            };
            
            customerNotifications.bind('order-status-updated', handleStatusUpdate);
            orderChannel.bind('order-status-updated', handleStatusUpdate);
        }
    </script>
@endsection
