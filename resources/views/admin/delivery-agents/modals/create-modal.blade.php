{{-- Modal for creating new delivery agent --}}
<div id="createAgentModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4" onclick="closeCreateModal(event)">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        {{-- Modal Header --}}
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">إضافة عامل توصيل جديد</h2>
            <button onclick="closeCreateModal()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">×</button>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.delivery-agents.store') }}" method="POST" class="p-6">
            @csrf

            {{-- Personal Information --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">person</span>
                    البيانات الشخصية
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">الاسم الكامل <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">رقم الهاتف <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('phone') border-red-500 @enderror">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">البريد الإلكتروني <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">رقم الهوية</label>
                        <input type="text" name="id_number" value="{{ old('id_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('id_number') border-red-500 @enderror">
                        @error('id_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Address Information --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">location_on</span>
                    البيانات الجغرافية
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">المحافظة <span class="text-red-500">*</span></label>
                        <select name="governorate" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('governorate') border-red-500 @enderror">
                            <option value="">-- اختر المحافظة --</option>
                            @foreach($governorates as $key => $value)
                                <option value="{{ $key }}" {{ old('governorate') === $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('governorate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">العنوان التفصيلي <span class="text-red-500">*</span></label>
                        <textarea name="address" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Vehicle Information --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">local_shipping</span>
                    بيانات المركبة
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نوع المركبة <span class="text-red-500">*</span></label>
                        <select name="vehicle_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('vehicle_type') border-red-500 @enderror">
                            <option value="car" {{ old('vehicle_type') === 'car' ? 'selected' : '' }}>سيارة</option>
                            <option value="motorcycle" {{ old('vehicle_type') === 'motorcycle' ? 'selected' : '' }}>دراجة نارية</option>
                            <option value="bicycle" {{ old('vehicle_type') === 'bicycle' ? 'selected' : '' }}>دراجة</option>
                            <option value="van" {{ old('vehicle_type') === 'van' ? 'selected' : '' }}>فان</option>
                        </select>
                        @error('vehicle_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">رقم اللوحة</label>
                        <input type="text" name="license_plate" value="{{ old('license_plate') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('license_plate') border-red-500 @enderror">
                        @error('license_plate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Payment Information --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">payment</span>
                    بيانات الدفع
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نوع الراتب <span class="text-red-500">*</span></label>
                        <select name="salary_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('salary_type') border-red-500 @enderror">
                            <option value="fixed" {{ old('salary_type') === 'fixed' ? 'selected' : '' }}>راتب ثابت</option>
                            <option value="commission" {{ old('salary_type') === 'commission' ? 'selected' : '' }}>عمولة</option>
                            <option value="hybrid" {{ old('salary_type') === 'hybrid' ? 'selected' : '' }}>راتب + عمولة</option>
                        </select>
                        @error('salary_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">نسبة العمولة (%) <span class="text-red-500">*</span></label>
                        <input type="number" name="commission_rate" value="{{ old('commission_rate', 0) }}" step="0.01" min="0" max="100" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('commission_rate') border-red-500 @enderror">
                        @error('commission_rate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">رقم الحساب البنكي</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('bank_account') border-red-500 @enderror">
                        @error('bank_account') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-700">note</span>
                    ملاحظات
                </h3>
                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()" class="px-6 py-2 border border-gray-300 rounded-lg font-bold text-gray-700 hover:bg-gray-50">
                    إلغاء
                </button>
                <button type="submit" class="px-6 py-2 bg-green-700 text-white rounded-lg font-bold hover:bg-green-800">
                    إضافة عامل توصيل
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createAgentModal').classList.remove('hidden');
}

function closeCreateModal(event) {
    if (event && event.target.id !== 'createAgentModal') return;
    document.getElementById('createAgentModal').classList.add('hidden');
}
</script>
