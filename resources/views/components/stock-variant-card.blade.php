@props(['index' => 0, 'variant' => [], 'branches' => []])

<div class="variant-card bg-white p-6 rounded-3xl border border-gray-200 shadow-sm relative transition-all duration-300 mb-6" data-index="{{ $index }}">
    <!-- Header Card Varian -->
    <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-[#CC9863]/10 text-[#CC9863] flex items-center justify-center text-xs variant-number">{{ is_numeric($index) ? ((int)$index + 1) : '' }}</span>
            Varian Produk
        </h3>
        <button type="button" onclick="this.closest('.variant-card').remove(); updateVariantNumbers();" class="btn-remove-variant text-red-500 hover:text-red-700 text-xs font-bold transition-colors {{ (string)$index === '0' ? 'hidden' : '' }}">Hapus Varian</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
        <!-- Foto Varian -->
        <div class="md:col-span-12" x-data="{ imagePreview: null }">
            <label class="block text-xs font-bold text-gray-700 mb-2">Foto Produk (Opsional)</label>
            <div class="flex items-center gap-4">
                <label class="flex flex-col items-center justify-center w-20 h-20 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 cursor-pointer hover:bg-gray-100 hover:border-[#CC9863] transition-all relative overflow-hidden group">
                    <template x-if="!imagePreview">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </template>
                    <template x-if="imagePreview">
                        <img :src="imagePreview" class="w-full h-full object-cover">
                    </template>
                    <input type="file" name="variants[{{ $index }}][image]" accept="image/png, image/jpeg, image/webp" class="hidden"
                        @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }">
                </label>
                <div class="text-[10px] text-gray-500 font-medium leading-relaxed">
                    <p>Format yang didukung: <span class="font-bold">JPG, PNG, WEBP</span>.</p>
                    <p>Maksimal ukuran file: <span class="font-bold">2MB</span>.</p>
                </div>
            </div>
        </div>

        <!-- Info Varian -->
        <div class="md:col-span-4">
            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama / Ukuran Varian <span class="text-red-500">*</span></label>
            <input type="text" name="variants[{{ $index }}][name]" value="{{ $variant['name'] ?? '' }}"
                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                placeholder="Cth: 30ml / Refill Murni" required>
        </div>
        <div class="md:col-span-4">
            <label class="block text-xs font-semibold text-gray-700 mb-1">SKU (Kode)</label>
            <input type="text" name="variants[{{ $index }}][sku]" value="{{ $variant['sku'] ?? '' }}"
                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                placeholder="PRFM-30">
        </div>
        <div class="md:col-span-4">
            <label class="block text-xs font-semibold text-gray-700 mb-1">Satuan Produk <span class="text-red-500">*</span></label>
            <select name="variants[{{ $index }}][unit]"
                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white font-bold text-[#CC9863] focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                required>
                @php $unit = $variant['unit'] ?? 'pcs'; @endphp
                <option value="pcs" {{ $unit == 'pcs' ? 'selected' : '' }}>Pcs / Botol</option>
                <option value="ml" {{ $unit == 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
                <option value="liter" {{ $unit == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                <option value="gram" {{ $unit == 'gram' ? 'selected' : '' }}>Gram (g)</option>
            </select>
        </div>

        <!-- Harga -->
        <div class="md:col-span-6">
            <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Modal (Rp)</label>
            <input type="number" name="variants[{{ $index }}][cost]" value="{{ $variant['cost'] ?? '' }}"
                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                placeholder="0">
        </div>
        <div class="md:col-span-6">
            <label class="block text-xs font-bold text-gray-900 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="variants[{{ $index }}][price]" value="{{ $variant['price'] ?? '' }}"
                class="w-full px-3 py-2.5 border border-[#CC9863]/50 rounded-lg bg-orange-50 font-black text-[#CC9863] focus:outline-none focus:ring-2 focus:ring-[#CC9863]"
                placeholder="0" required>
        </div>

        <!-- Stok Area (Setup Awal) -->
        <div class="md:col-span-12 mt-2 pt-4 border-t border-dashed border-gray-200" x-data="{ showBranches: {{ isset($variant['distribute']) ? 'true' : 'false' }} }">

            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-800 uppercase tracking-wide">
                        Stok Awal Gudang Pusat
                    </label>
                    <p class="text-[9px] text-gray-500 font-medium">Stok master sebelum didistribusikan.</p>
                </div>
                <div class="relative w-28 shrink-0">
                    <input type="number" name="variants[{{ $index }}][stock_pusat]" value="{{ $variant['stock_pusat'] ?? '' }}"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-center font-black text-gray-900 focus:outline-none focus:border-[#CC9863]"
                        placeholder="0">
                </div>
            </div>

            <!-- Toggle Distribusi Cabang Langsung -->
            <div>
                <label class="flex items-center gap-2 cursor-pointer mb-3">
                    <input type="checkbox" name="variants[{{ $index }}][distribute]"
                        x-model="showBranches" value="1"
                        class="w-4 h-4 text-[#CC9863] rounded focus:ring-[#CC9863]">
                    <span class="text-sm font-bold text-gray-700">Distribusikan langsung ke cabang lain?</span>
                </label>

                <div x-show="showBranches" x-transition style="display: none;"
                    class="grid grid-cols-1 sm:grid-cols-2 gap-3 pl-6 border-l-2 border-gray-100">
                    @foreach ($branches->where('id', '!=', 1) as $branch)
                        @php
                            $bAssign = isset($variant['branches'][$branch->id]['assign']);
                            $bStock = $variant['branches'][$branch->id]['stock'] ?? '';
                            $bSource = $variant['branches'][$branch->id]['source'] ?? 'direct';
                        @endphp
                        <div class="flex flex-col gap-2 p-3 border border-gray-100 rounded-xl bg-white shadow-sm hover:border-[#CC9863]/30 transition-colors"
                            x-data="{ isAssigned: {{ $bAssign ? 'true' : 'false' }} }">
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 cursor-pointer flex-1">
                                    <input type="checkbox"
                                        name="variants[{{ $index }}][branches][{{ $branch->id }}][assign]"
                                        x-model="isAssigned" value="1"
                                        class="w-4 h-4 text-[#CC9863] rounded focus:ring-[#CC9863]">
                                    <span class="text-xs font-bold text-gray-800 truncate"
                                        title="{{ $branch->nama_cabang }}">{{ $branch->nama_cabang }}</span>
                                </label>
                                <div class="relative w-20 shrink-0">
                                    <input type="number"
                                        name="variants[{{ $index }}][branches][{{ $branch->id }}][stock]" value="{{ $bStock }}"
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-center text-sm font-bold disabled:bg-gray-100 disabled:opacity-50"
                                        placeholder="0" :disabled="!isAssigned">
                                </div>
                            </div>
                            <select name="variants[{{ $index }}][branches][{{ $branch->id }}][source]"
                                class="w-full text-[10px] font-semibold bg-gray-50 border border-gray-200 rounded-md p-1.5 text-gray-600 focus:outline-none focus:border-[#CC9863] disabled:bg-gray-100 disabled:opacity-50"
                                :disabled="!isAssigned">
                                <option value="direct" {{ $bSource == 'direct' ? 'selected' : '' }}>Dari Supplier Luar</option>
                                <option value="from_pusat" {{ $bSource == 'from_pusat' ? 'selected' : '' }}>Transfer dari Pusat</option>
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>