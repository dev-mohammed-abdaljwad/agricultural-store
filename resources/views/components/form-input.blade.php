@props([
    'name' => '',
    'type' => 'text',
    'label' => '',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'options' => [],
])

<!-- Form Input Component -->
<div class="space-y-2">
    <label class="block text-sm font-bold text-on-surface-variant pr-1" for="{{ $name }}">
        {{ $label }}
    </label>
    
    @if($type === 'textarea')
        <textarea 
            id="{{ $name }}"
            name="{{ $name }}"
            class="w-full bg-surface-container-highest border-none rounded-lg px-4 py-3 text-on-surface placeholder:text-outline focus:ring-0 transition-all @error($name) border-error @enderror"
            placeholder="{{ $placeholder ?? '' }}"
            dir="{{ in_array($name, ['phone']) ? 'ltr' : 'rtl' }}"
            @if($required)required @endif
        >{{ old($name, $value ?? '') }}</textarea>
    
    @elseif($type === 'select')
        <select 
            id="{{ $name }}"
            name="{{ $name }}"
            class="w-full bg-surface-container-highest border-none rounded-lg px-4 py-3 text-on-surface placeholder:text-outline focus:ring-0 transition-all appearance-none @error($name) border-error @enderror"
            dir="rtl"
            @if($required)required @endif
        >
            @if(!empty($options))
                @foreach($options as $value => $label)
                    <option value="{{ $value }}" @selected(old($name, $value ?? '') == $value)>
                        {{ $label }}
                    </option>
                @endforeach
            @endif
        </select>
    
    @else
        <input 
            id="{{ $name }}"
            type="{{ $type }}"
            name="{{ $name }}"
            class="w-full bg-surface-container-highest border-none rounded-lg px-4 py-3 text-on-surface placeholder:text-outline focus:ring-0 transition-all @error($name) border-error @enderror"
            placeholder="{{ $placeholder ?? '' }}"
            value="{{ old($name, $value ?? '') }}"
            dir="{{ in_array($type, ['email', 'tel']) || in_array($name, ['phone', 'email']) ? 'ltr' : 'rtl' }}"
            @if($required)required @endif
        />
    @endif
    
    @error($name)
        <p class="text-error text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
