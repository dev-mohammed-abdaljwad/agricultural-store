{{-- Quantity Stepper Component --}}
@props(['quantity' => 1, 'itemId'])

<div class="flex items-center bg-surface-container-highest rounded-full px-2 py-1 gap-1">
    <form method="POST" action="{{ route('cart.update', $itemId) }}" style="display: inline;" onsubmit="handleQuantityUpdate(event, {{ $itemId }})">
        @csrf
        @method('PUT')
        <input type="hidden" name="quantity" id="quantity-{{ $itemId }}" value="{{ $quantity }}">
        
        <button 
            type="button"
            class="w-8 h-8 flex items-center justify-center text-primary font-black hover:bg-primary/10 rounded-full transition-colors"
            onclick="decreaseQty({{ $itemId }})"
            @if($quantity <= 1) disabled @endif
        >
            -
        </button>
    </form>
    
    <span class="px-3 font-bold text-on-surface whitespace-nowrap text-sm">{{ $quantity }} {{ $unit ?? 'كجم' }}</span>
    
    <form method="POST" action="{{ route('cart.update', $itemId) }}" style="display: inline;" onsubmit="handleQuantityUpdate(event, {{ $itemId }})">
        @csrf
        @method('PUT')
        <input type="hidden" name="quantity" id="quantity-{{ $itemId }}" value="{{ $quantity }}">
        
        <button 
            type="button"
            class="w-8 h-8 flex items-center justify-center text-primary font-black hover:bg-primary/10 rounded-full transition-colors"
            onclick="increaseQty({{ $itemId }})"
        >
            +
        </button>
    </form>
</div>

<script>
function increaseQty(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    if (input) {
        input.value = parseInt(input.value) + 1;
        submitQuantityForm(itemId);
    }
}

function decreaseQty(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    if (input && parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        submitQuantityForm(itemId);
    }
}

function submitQuantityForm(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    const newQty = parseInt(input.value);
    
    fetch("{{ route('cart.update', ':itemId') }}".replace(':itemId', itemId), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            quantity: newQty
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
