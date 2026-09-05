@extends('template.kasir')

@section('title', 'Tambah Produk Custom')

@section('content')

    <div class="w-full min-h-full overflow-y-auto bg-[#FAFAFA] p-3 sm:p-4 lg:p-8">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}
        <div class="max-w-6xl mx-auto mb-6 lg:mb-8">

            <a href="{{ url('kasir/pos') }}"
                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[#CC9863] transition mb-3">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                    </path>

                </svg>

                Kembali ke POS
            </a>


            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Racik Produk Custom
            </h1>

            <p class="text-gray-500 text-sm mt-1 max-w-2xl">
                Tambahkan beberapa parfum, tentukan jumlah ml dan harga per ml.
                Sistem akan menghitung total racikan secara otomatis.
            </p>

        </div>


        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}
        <form id="customProductForm" action="#" method="POST" class="max-w-6xl mx-auto">

            @csrf


            <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_360px] gap-5 lg:gap-6">


                {{-- ================================================= --}}
                {{-- LEFT COLUMN --}}
                {{-- ================================================= --}}
                <div class="min-w-0 space-y-5">


                    {{-- TYPE --}}
                    <div class="bg-white rounded-2xl lg:rounded-3xl border border-gray-100 shadow-sm p-4 sm:p-6">

                        <label class="block text-sm font-bold text-gray-800 mb-3">
                            Tipe Item Custom
                        </label>


                        <div class="grid grid-cols-3 gap-2 sm:gap-3">

                            <label class="cursor-pointer">

                                <input type="radio" name="custom_type" value="racikan" class="peer sr-only" checked>

                                <div
                                    class="
                                    px-2 sm:px-4
                                    py-3
                                    border-2
                                    border-gray-100
                                    rounded-xl
                                    text-center
                                    transition
                                    hover:bg-gray-50
                                    peer-checked:border-[#CC9863]
                                    peer-checked:bg-[#CC9863]/10
                                ">

                                    <span class="block text-xs sm:text-sm font-bold">
                                        Racikan Mix
                                    </span>

                                </div>

                            </label>


                            <label class="cursor-pointer">

                                <input type="radio" name="custom_type" value="refill" class="peer sr-only">

                                <div
                                    class="
                                    px-2 sm:px-4
                                    py-3
                                    border-2
                                    border-gray-100
                                    rounded-xl
                                    text-center
                                    transition
                                    hover:bg-gray-50
                                    peer-checked:border-[#CC9863]
                                    peer-checked:bg-[#CC9863]/10
                                ">

                                    <span class="block text-xs sm:text-sm font-bold">
                                        Refill
                                    </span>

                                </div>

                            </label>


                            <label class="cursor-pointer">

                                <input type="radio" name="custom_type" value="lainnya" class="peer sr-only">

                                <div
                                    class="
                                    px-2 sm:px-4
                                    py-3
                                    border-2
                                    border-gray-100
                                    rounded-xl
                                    text-center
                                    transition
                                    hover:bg-gray-50
                                    peer-checked:border-[#CC9863]
                                    peer-checked:bg-[#CC9863]/10
                                ">

                                    <span class="block text-xs sm:text-sm font-bold">
                                        Lainnya
                                    </span>

                                </div>

                            </label>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- PRODUCT INFO --}}
                    {{-- ================================================= --}}
                    <div class="bg-white rounded-2xl lg:rounded-3xl border border-gray-100 shadow-sm p-4 sm:p-6">

                        <h2 class="font-bold text-gray-900 mb-5">
                            Informasi Racikan
                        </h2>


                        <div class="space-y-5">

                            {{-- Name --}}
                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Nama Racikan
                                    <span class="text-red-500">*</span>
                                </label>

                                <input id="productName" type="text" name="name" required
                                    placeholder="Contoh: Vanilla Aqua Mix"
                                    class="
                                    w-full
                                    px-4
                                    py-3
                                    border
                                    border-gray-200
                                    rounded-xl
                                    bg-gray-50
                                    focus:bg-white
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-[#CC9863]/30
                                    focus:border-[#CC9863]
                                    transition
                                ">

                            </div>



                            {{-- Qty --}}
                            <div class="max-w-xs">

                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Jumlah Racikan / Botol
                                </label>

                                <div
                                    class="
                                    flex
                                    items-center
                                    border
                                    border-gray-200
                                    rounded-xl
                                    overflow-hidden
                                    bg-gray-50
                                ">

                                    <button type="button" id="qtyMinus"
                                        class="w-12 h-12 font-bold text-xl text-gray-500 hover:bg-gray-200 transition">

                                        −

                                    </button>


                                    <input id="qtyInput" type="number" name="qty" min="1" value="1"
                                        class="
                                        min-w-0
                                        flex-1
                                        h-12
                                        text-center
                                        font-bold
                                        bg-transparent
                                        focus:outline-none
                                    ">


                                    <button type="button" id="qtyPlus"
                                        class="w-12 h-12 font-bold text-xl text-gray-500 hover:bg-gray-200 transition">

                                        +

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- MIXING INGREDIENTS --}}
                    {{-- ================================================= --}}
                    <div class="bg-white rounded-2xl lg:rounded-3xl border border-gray-100 shadow-sm overflow-hidden">


                        {{-- Header --}}
                        <div class="p-4 sm:p-6 border-b border-gray-100">

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                                <div>

                                    <h2 class="font-bold text-gray-900">
                                        Komposisi Parfum
                                    </h2>

                                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                        Masukkan parfum, jumlah ml, dan harga setiap ml.
                                    </p>

                                </div>


                                <button type="button" id="addIngredient"
                                    class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
                                    px-4
                                    py-2.5
                                    rounded-xl
                                    bg-[#1C1D21]
                                    text-white
                                    text-sm
                                    font-bold
                                    hover:bg-black
                                    transition
                                ">

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4">
                                        </path>

                                    </svg>

                                    Tambah Bahan

                                </button>

                            </div>

                        </div>



                        {{-- Ingredient rows --}}
                        <div id="ingredientContainer" class="divide-y divide-gray-100">

                            {{-- FIRST ROW --}}
                            <div class="ingredient-row p-4 sm:p-5" data-index="0">


                                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">


                                    {{-- Perfume --}}
                                    <div class="md:col-span-5">

                                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                                            Nama Parfum
                                        </label>

                                        <input type="text" name="ingredients[0][name]"
                                            class="
                                            ingredient-name
                                            w-full
                                            px-3
                                            py-3
                                            rounded-xl
                                            border
                                            border-gray-200
                                            bg-gray-50
                                            focus:outline-none
                                            focus:ring-2
                                            focus:ring-[#CC9863]/30
                                            focus:border-[#CC9863]
                                        "
                                            placeholder="Contoh: Vanilla" required>

                                    </div>



                                    {{-- ML --}}
                                    <div class="md:col-span-2">

                                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                                            Jumlah
                                        </label>

                                        <div class="relative">

                                            <input type="number" name="ingredients[0][ml]" min="0" step="0.1"
                                                class="
                                                ingredient-ml
                                                w-full
                                                px-3
                                                pr-10
                                                py-3
                                                rounded-xl
                                                border
                                                border-gray-200
                                                bg-gray-50
                                                focus:outline-none
                                                focus:ring-2
                                                focus:ring-[#CC9863]/30
                                                focus:border-[#CC9863]
                                            "
                                                placeholder="0" required>

                                            <span
                                                class="
                                                absolute
                                                right-3
                                                top-1/2
                                                -translate-y-1/2
                                                text-xs
                                                font-bold
                                                text-gray-400
                                            ">
                                                ml
                                            </span>

                                        </div>

                                    </div>



                                    {{-- Price --}}
                                    <div class="md:col-span-3">

                                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                                            Harga / ml
                                        </label>

                                        <div class="relative">

                                            <span
                                                class="
                                                absolute
                                                left-3
                                                top-1/2
                                                -translate-y-1/2
                                                text-xs
                                                font-semibold
                                                text-gray-400
                                            ">
                                                Rp
                                            </span>

                                            <input type="number" name="ingredients[0][price_per_ml]" min="0"
                                                class="
                                                ingredient-price
                                                w-full
                                                pl-9
                                                pr-3
                                                py-3
                                                rounded-xl
                                                border
                                                border-gray-200
                                                bg-gray-50
                                                focus:outline-none
                                                focus:ring-2
                                                focus:ring-[#CC9863]/30
                                                focus:border-[#CC9863]
                                            "
                                                placeholder="0" required>

                                        </div>

                                    </div>



                                    {{-- Total row --}}
                                    <div class="md:col-span-2">

                                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                                            Subtotal
                                        </label>

                                        <div
                                            class="
                                            h-[50px]
                                            px-3
                                            rounded-xl
                                            bg-[#F6F5F2]
                                            flex
                                            items-center
                                            justify-between
                                            gap-2
                                        ">

                                            <span class="ingredient-subtotal text-sm font-bold text-gray-900 truncate">
                                                Rp 0
                                            </span>


                                            <button type="button"
                                                class="
                                                remove-ingredient
                                                hidden
                                                shrink-0
                                                w-8
                                                h-8
                                                rounded-lg
                                                text-gray-400
                                                hover:bg-red-50
                                                hover:text-red-500
                                                transition
                                            "
                                                title="Hapus bahan">

                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12">
                                                    </path>

                                                </svg>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>



                        {{-- SUMMARY INGREDIENT --}}
                        <div class="bg-[#F9F9F8] border-t border-gray-100 p-4 sm:p-5">

                            <div class="grid grid-cols-2 gap-4">


                                <div>

                                    <p class="text-xs text-gray-500">
                                        Total Volume
                                    </p>

                                    <p id="summaryMl" class="text-lg font-bold text-gray-900 mt-1">
                                        0 ml
                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="text-xs text-gray-500">
                                        Harga Racikan
                                    </p>

                                    <p id="summaryPrice" class="text-lg font-bold text-[#CC9863] mt-1">
                                        Rp 0
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- NOTES --}}
                    <div class="bg-white rounded-2xl lg:rounded-3xl border border-gray-100 shadow-sm p-4 sm:p-6">

                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Catatan Tambahan
                        </label>

                        <textarea name="notes" rows="3"
                            class="
                            w-full
                            px-4
                            py-3
                            border
                            border-gray-200
                            rounded-xl
                            bg-gray-50
                            focus:bg-white
                            focus:outline-none
                            focus:ring-2
                            focus:ring-[#CC9863]/30
                            focus:border-[#CC9863]
                            transition
                        "
                            placeholder="Contoh: Botol customer, tanpa box, tambah fixative, dll."></textarea>

                        <p class="text-[11px] text-gray-400 mt-1.5">
                            Catatan tambahan dapat ditampilkan pada detail transaksi atau struk.
                        </p>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- RIGHT PREVIEW --}}
                {{-- ================================================= --}}
                <aside class="w-full">


                    <div
                        class="
                        xl:sticky
                        xl:top-5
                        bg-[#1C1D21]
                        rounded-2xl
                        lg:rounded-3xl
                        border
                        border-gray-800
                        shadow-xl
                        overflow-hidden
                        text-white
                    ">


                        <div class="p-5 sm:p-6">

                            <div class="flex items-center justify-between border-b border-gray-700 pb-4 mb-5">

                                <h3
                                    class="
                                    text-xs
                                    font-bold
                                    text-gray-400
                                    uppercase
                                    tracking-wider
                                ">
                                    Preview Racikan
                                </h3>


                                <span id="previewType"
                                    class="
                                    px-2.5
                                    py-1
                                    rounded-full
                                    bg-[#CC9863]/15
                                    text-[#E5B481]
                                    text-[10px]
                                    font-bold
                                    uppercase
                                ">
                                    Racikan
                                </span>

                            </div>



                            {{-- Product --}}
                            <div class="flex items-start gap-4 mb-6">

                                <div
                                    class="
                                    w-12
                                    h-12
                                    rounded-xl
                                    bg-gray-800
                                    border
                                    border-gray-700
                                    flex
                                    items-center
                                    justify-center
                                    text-[#CC9863]
                                    shrink-0
                                ">

                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                        </path>

                                    </svg>

                                </div>


                                <div class="min-w-0">

                                    <p id="previewName" class="font-bold text-white leading-tight break-words">
                                        Racikan Custom
                                    </p>

                                    <p id="previewVolume" class="text-xs text-gray-400 mt-1">
                                        0 ml
                                    </p>

                                </div>

                            </div>



                            {{-- COMPOSITION PREVIEW --}}
                            <div class="mb-6">

                                <p
                                    class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-500
                                    mb-3
                                ">
                                    Komposisi
                                </p>


                                <div id="previewIngredients" class="space-y-3">

                                    <div class="text-xs text-gray-500">
                                        Belum ada komposisi.
                                    </div>

                                </div>

                            </div>



                            {{-- TOTAL --}}
                            <div class="space-y-3 border-t border-gray-700 pt-4">

                                <div class="flex justify-between gap-4 text-sm">

                                    <span class="text-gray-400">
                                        Harga Racikan
                                    </span>

                                    <span id="previewUnitPrice" class="font-semibold">
                                        Rp 0
                                    </span>

                                </div>


                                <div class="flex justify-between gap-4 text-sm">

                                    <span class="text-gray-400">
                                        Qty
                                    </span>

                                    <span id="previewQty" class="font-semibold">
                                        1
                                    </span>

                                </div>


                                <div
                                    class="
                                    flex
                                    justify-between
                                    items-end
                                    gap-4
                                    pt-4
                                    border-t
                                    border-gray-700
                                ">

                                    <span class="font-bold">
                                        Total Harga
                                    </span>

                                    <span id="previewGrandTotal" class="text-xl sm:text-2xl font-bold text-[#CC9863]">
                                        Rp 0
                                    </span>

                                </div>

                            </div>

                        </div>



                        {{-- ACTION --}}
                        <div class="p-4 sm:p-6 pt-0">

                            <button type="submit"
                                class="
                                w-full
                                min-h-[52px]
                                bg-[#CC9863]
                                text-white
                                rounded-xl
                                font-bold
                                hover:bg-[#B58555]
                                active:scale-[0.99]
                                transition
                                flex
                                items-center
                                justify-center
                                gap-2
                                shadow-[0_4px_15px_rgba(204,152,99,0.25)]
                            ">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>

                                </svg>

                                Tambah ke Keranjang

                            </button>


                            <a href="{{ url('kasir/pos') }}"
                                class="
                                mt-3
                                w-full
                                min-h-[48px]
                                rounded-xl
                                bg-gray-800
                                border
                                border-gray-700
                                text-gray-300
                                font-bold
                                hover:bg-gray-700
                                hover:text-white
                                transition
                                flex
                                items-center
                                justify-center
                            ">

                                Batal

                            </a>

                        </div>

                    </div>

                </aside>

            </div>


            {{-- Hidden calculated values --}}
            <input type="hidden" id="totalMlInput" name="total_ml" value="0">

            <input type="hidden" id="unitPriceInput" name="unit_price" value="0">

            <input type="hidden" id="grandTotalInput" name="grand_total" value="0">

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- SCRIPT --}}
    {{-- ========================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container = document.getElementById('ingredientContainer');
            const addButton = document.getElementById('addIngredient');

            const productName = document.getElementById('productName');
            const qtyInput = document.getElementById('qtyInput');
            const qtyMinus = document.getElementById('qtyMinus');
            const qtyPlus = document.getElementById('qtyPlus');

            const summaryMl = document.getElementById('summaryMl');
            const summaryPrice = document.getElementById('summaryPrice');

            const previewName = document.getElementById('previewName');
            const previewVolume = document.getElementById('previewVolume');
            const previewIngredients = document.getElementById('previewIngredients');
            const previewUnitPrice = document.getElementById('previewUnitPrice');
            const previewQty = document.getElementById('previewQty');
            const previewGrandTotal = document.getElementById('previewGrandTotal');
            const previewType = document.getElementById('previewType');

            const totalMlInput = document.getElementById('totalMlInput');
            const unitPriceInput = document.getElementById('unitPriceInput');
            const grandTotalInput = document.getElementById('grandTotalInput');

            let ingredientIndex = 1;


            function rupiah(value) {

                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value || 0);

            }


            function updateRemoveButtons() {

                const rows = container.querySelectorAll('.ingredient-row');

                rows.forEach(row => {

                    const removeButton = row.querySelector('.remove-ingredient');

                    if (rows.length > 1) {
                        removeButton.classList.remove('hidden');
                    } else {
                        removeButton.classList.add('hidden');
                    }

                });

            }


            function calculate() {

                const rows = container.querySelectorAll('.ingredient-row');

                let totalMl = 0;
                let unitPrice = 0;

                const previewData = [];


                rows.forEach(row => {

                    const nameInput = row.querySelector('.ingredient-name');
                    const mlInput = row.querySelector('.ingredient-ml');
                    const priceInput = row.querySelector('.ingredient-price');
                    const subtotalElement = row.querySelector('.ingredient-subtotal');

                    const name = nameInput.value.trim();

                    const ml = parseFloat(mlInput.value) || 0;
                    const pricePerMl = parseFloat(priceInput.value) || 0;

                    const subtotal = ml * pricePerMl;


                    totalMl += ml;
                    unitPrice += subtotal;


                    subtotalElement.textContent = rupiah(subtotal);


                    if (name || ml > 0 || pricePerMl > 0) {

                        previewData.push({
                            name: name || 'Parfum',
                            ml: ml,
                            price: pricePerMl,
                            subtotal: subtotal
                        });

                    }

                });


                const qty = Math.max(
                    1,
                    parseInt(qtyInput.value) || 1
                );

                qtyInput.value = qty;

                const grandTotal = unitPrice * qty;


                /*
                |--------------------------------------------------------------------------
                | Summary
                |--------------------------------------------------------------------------
                */

                summaryMl.textContent = `${formatMl(totalMl)} ml`;
                summaryPrice.textContent = rupiah(unitPrice);


                /*
                |--------------------------------------------------------------------------
                | Preview
                |--------------------------------------------------------------------------
                */

                previewName.textContent =
                    productName.value.trim() || 'Racikan Custom';

                previewVolume.textContent =
                    `${formatMl(totalMl)} ml × ${qty} botol`;

                previewUnitPrice.textContent =
                    rupiah(unitPrice);

                previewQty.textContent =
                    qty;

                previewGrandTotal.textContent =
                    rupiah(grandTotal);


                /*
                |--------------------------------------------------------------------------
                | Hidden Inputs
                |--------------------------------------------------------------------------
                */

                totalMlInput.value = totalMl;
                unitPriceInput.value = unitPrice;
                grandTotalInput.value = grandTotal;


                /*
                |--------------------------------------------------------------------------
                | Ingredient Preview
                |--------------------------------------------------------------------------
                */

                if (previewData.length === 0) {

                    previewIngredients.innerHTML = `
                <div class="text-xs text-gray-500">
                    Belum ada komposisi.
                </div>
            `;

                } else {

                    previewIngredients.innerHTML = previewData.map(item => `

                <div class="flex justify-between gap-3 text-xs">

                    <div class="min-w-0">

                        <p class="text-gray-200 font-semibold truncate">
                            ${escapeHtml(item.name)}
                        </p>

                        <p class="text-gray-500 mt-0.5">
                            ${formatMl(item.ml)} ml × ${rupiah(item.price)}/ml
                        </p>

                    </div>


                    <span class="text-gray-300 font-semibold shrink-0">
                        ${rupiah(item.subtotal)}
                    </span>

                </div>

            `).join('');

                }

            }


            function formatMl(value) {

                if (Number.isInteger(value)) {
                    return value;
                }

                return parseFloat(value.toFixed(2));

            }


            function escapeHtml(value) {

                const div = document.createElement('div');
                div.textContent = value;

                return div.innerHTML;

            }


            function addIngredient() {

                const row = document.createElement('div');

                row.className =
                    'ingredient-row p-4 sm:p-5';

                row.dataset.index = ingredientIndex;


                row.innerHTML = `

            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">


                <div class="md:col-span-5">

                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                        Nama Parfum
                    </label>

                    <input
                        type="text"
                        name="ingredients[${ingredientIndex}][name]"
                        class="
                            ingredient-name
                            w-full
                            px-3
                            py-3
                            rounded-xl
                            border
                            border-gray-200
                            bg-gray-50
                            focus:outline-none
                            focus:ring-2
                            focus:ring-[#CC9863]/30
                            focus:border-[#CC9863]
                        "
                        placeholder="Contoh: Aqua Blue"
                        required>

                </div>


                <div class="md:col-span-2">

                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                        Jumlah
                    </label>

                    <div class="relative">

                        <input
                            type="number"
                            name="ingredients[${ingredientIndex}][ml]"
                            min="0"
                            step="0.1"
                            class="
                                ingredient-ml
                                w-full
                                px-3
                                pr-10
                                py-3
                                rounded-xl
                                border
                                border-gray-200
                                bg-gray-50
                                focus:outline-none
                                focus:ring-2
                                focus:ring-[#CC9863]/30
                                focus:border-[#CC9863]
                            "
                            placeholder="0"
                            required>

                        <span
                            class="
                                absolute
                                right-3
                                top-1/2
                                -translate-y-1/2
                                text-xs
                                font-bold
                                text-gray-400
                            ">
                            ml
                        </span>

                    </div>

                </div>


                <div class="md:col-span-3">

                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                        Harga / ml
                    </label>

                    <div class="relative">

                        <span
                            class="
                                absolute
                                left-3
                                top-1/2
                                -translate-y-1/2
                                text-xs
                                font-semibold
                                text-gray-400
                            ">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="ingredients[${ingredientIndex}][price_per_ml]"
                            min="0"
                            class="
                                ingredient-price
                                w-full
                                pl-9
                                pr-3
                                py-3
                                rounded-xl
                                border
                                border-gray-200
                                bg-gray-50
                                focus:outline-none
                                focus:ring-2
                                focus:ring-[#CC9863]/30
                                focus:border-[#CC9863]
                            "
                            placeholder="0"
                            required>

                    </div>

                </div>


                <div class="md:col-span-2">

                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                        Subtotal
                    </label>

                    <div
                        class="
                            h-[50px]
                            px-3
                            rounded-xl
                            bg-[#F6F5F2]
                            flex
                            items-center
                            justify-between
                            gap-2
                        ">

                        <span
                            class="ingredient-subtotal text-sm font-bold text-gray-900 truncate">
                            Rp 0
                        </span>


                        <button
                            type="button"
                            class="
                                remove-ingredient
                                shrink-0
                                w-8
                                h-8
                                rounded-lg
                                text-gray-400
                                hover:bg-red-50
                                hover:text-red-500
                                transition
                            "
                            title="Hapus bahan">

                            <svg
                                class="w-4 h-4 mx-auto"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>

                            </svg>

                        </button>

                    </div>

                </div>

            </div>
        `;


                container.appendChild(row);

                ingredientIndex++;

                updateRemoveButtons();
                calculate();

            }


            /*
            |--------------------------------------------------------------------------
            | Add Ingredient
            |--------------------------------------------------------------------------
            */

            addButton.addEventListener('click', addIngredient);



            /*
            |--------------------------------------------------------------------------
            | Dynamic Row Events
            |--------------------------------------------------------------------------
            */

            container.addEventListener('input', function(event) {

                if (
                    event.target.classList.contains('ingredient-name') ||
                    event.target.classList.contains('ingredient-ml') ||
                    event.target.classList.contains('ingredient-price')
                ) {

                    calculate();

                }

            });


            container.addEventListener('click', function(event) {

                const button = event.target.closest('.remove-ingredient');

                if (!button) {
                    return;
                }

                const rows = container.querySelectorAll('.ingredient-row');

                if (rows.length <= 1) {
                    return;
                }

                button.closest('.ingredient-row').remove();

                updateRemoveButtons();
                calculate();

            });



            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            qtyMinus.addEventListener('click', function() {

                const current = parseInt(qtyInput.value) || 1;

                qtyInput.value = Math.max(1, current - 1);

                calculate();

            });


            qtyPlus.addEventListener('click', function() {

                const current = parseInt(qtyInput.value) || 1;

                qtyInput.value = current + 1;

                calculate();

            });


            qtyInput.addEventListener('input', calculate);



            /*
            |--------------------------------------------------------------------------
            | Product Name
            |--------------------------------------------------------------------------
            */

            productName.addEventListener('input', calculate);



            /*
            |--------------------------------------------------------------------------
            | Type
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('input[name="custom_type"]')
                .forEach(radio => {

                    radio.addEventListener('change', function() {

                        const names = {
                            racikan: 'Racikan',
                            refill: 'Refill',
                            lainnya: 'Lainnya'
                        };

                        previewType.textContent =
                            names[this.value] || 'Custom';

                    });

                });



            updateRemoveButtons();
            calculate();

        });
    </script>

@endsection
