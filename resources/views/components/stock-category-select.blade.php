<div>
    <div class="flex justify-between items-center mb-1.5">
        <label id="category-label" for="category_search" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide">
            Kategori <span class="text-red-500">*</span>
        </label>
        <button type="button" @click.prevent="openCategoryModal = true"
            class="text-[10px] bg-orange-50 text-[#CC9863] px-2 py-1 rounded font-bold hover:bg-orange-100 transition focus:outline-none">
            + Baru
        </button>
    </div>

    <!-- Tetap tampil sebagai pilihan biasa jika JavaScript tidak tersedia. -->
    <select name="category_id" id="category_select" x-ref="categorySelect" x-model="selectedCategoryId"
        x-show="!categoryEnhanced" :required="!categoryEnhanced"
        class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800" required>
        <option value="">Pilih Kategori</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $product->kategori_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->nama_kategori }}
            </option>
        @endforeach
    </select>

    <!-- Searchable Combobox -->
    <div class="relative" @click.outside="closeCategoryDropdown()" x-init="initCategorySearch()">
        <input type="text" id="category_search" x-ref="categorySearch" x-model="categorySearch"
            @focus="openCategoryDropdown()" @input="updateCategorySearch()"
            @keydown.arrow-down.prevent="moveCategoryFocus(1)" @keydown.arrow-up.prevent="moveCategoryFocus(-1)"
            @keydown.enter.prevent="selectFocusedCategory()" @keydown.escape.prevent="closeCategoryDropdown()"
            role="combobox" aria-autocomplete="list" aria-controls="category_options"
            :aria-expanded="categoryDropdownOpen.toString()" aria-labelledby="category-label"
            :aria-activedescendant="activeCategoryIndex >= 0 && filteredCategories[activeCategoryIndex] ? `category-option-${filteredCategories[activeCategoryIndex].id}` : null"
            placeholder="Pilih Kategori" autocomplete="off"
            class="w-full px-4 py-3 pr-20 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800"
            :class="{'border-red-300 bg-red-50': showCategoryError}">

        <div class="absolute inset-y-0 right-2 flex items-center gap-1">
            <button x-show="selectedCategoryId" type="button" @click="clearCategory()" aria-label="Hapus kategori terpilih"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-200 hover:text-gray-700 focus:outline-none transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <button type="button" @click="categoryDropdownOpen ? closeCategoryDropdown() : openCategoryDropdown()" aria-label="Tampilkan pilihan kategori"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-200 hover:text-gray-700 focus:outline-none transition">
                <svg class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': categoryDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m6 9 6 6 6-6"></path>
                </svg>
            </button>
        </div>

        <div x-cloak x-show="categoryDropdownOpen" id="category_options" role="listbox"
            x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
            class="absolute z-40 mt-2 max-h-60 w-full overflow-y-auto rounded-xl border border-gray-100 bg-white p-1.5 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] custom-scroll">
            <template x-for="(category, index) in filteredCategories" :key="category.id">
                <button type="button" :id="`category-option-${category.id}`" role="option"
                    :aria-selected="(selectedCategoryId == category.id).toString()"
                    @mouseenter="activeCategoryIndex = index" @click="selectCategory(category)"
                    class="flex w-full items-center justify-between px-3 py-2.5 rounded-lg text-left text-sm transition-colors focus:outline-none"
                    :class="{'bg-orange-50/70 text-[#CC9863] font-extrabold': activeCategoryIndex === index || selectedCategoryId == category.id, 'text-gray-700 font-semibold hover:bg-gray-50': activeCategoryIndex !== index && selectedCategoryId != category.id}">
                    <span x-text="category.name"></span>
                    <svg x-show="selectedCategoryId == category.id" class="h-4 w-4 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </button>
            </template>
            <div x-show="filteredCategories.length === 0" class="px-4 py-6 text-center">
                <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <p class="text-sm font-bold text-gray-900">Kategori tidak ditemukan</p>
                <p class="text-xs text-gray-500 mt-1">Klik "+ Baru" untuk menambahkan.</p>
            </div>
        </div>
    </div>
    <p x-show="showCategoryError" class="text-xs text-red-500 mt-1.5 font-bold" x-transition>Kategori wajib dipilih!</p>
</div>