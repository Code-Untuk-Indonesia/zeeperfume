@extends('template.kasir')

@section('title', 'Transaksi Kasir')

@section('content')

<div class="flex flex-col lg:flex-row h-full w-full relative overflow-hidden bg-[#FAFAFA]">

    {{-- ========================================================= --}}
    {{-- LEFT : PRODUCT AREA --}}
    {{-- ========================================================= --}}
    <section class="flex-1 min-w-0 flex flex-col h-full overflow-hidden p-3 sm:p-4 lg:p-6">

        {{-- ===================================================== --}}
        {{-- HEADER / SEARCH --}}
        {{-- ===================================================== --}}
        <div class="shrink-0">

            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-5">

                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                        Transaksi Parfum
                    </h1>

                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Pilih parfum botol, refill per ml, atau buat racikan custom.
                    </p>
                </div>


                {{-- QUICK CUSTOM --}}
                <a
                    href="{{ url('kasir/pos/custom') }}"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        gap-2
                        px-5
                        py-3
                        rounded-xl
                        bg-[#1C1D21]
                        text-white
                        text-sm
                        font-bold
                        hover:bg-black
                        transition
                        shrink-0
                    ">

                    <svg
                        class="w-5 h-5 text-[#CC9863]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4">
                        </path>

                    </svg>

                    Racikan Custom

                </a>

            </div>



            {{-- ================================================= --}}
            {{-- SALE MODE --}}
            {{-- ================================================= --}}
            <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-5">

                {{-- PCS --}}
                <button
                    type="button"
                    onclick="setSaleMode('pcs')"
                    id="mode-pcs"
                    class="
                        sale-mode
                        bg-[#1C1D21]
                        text-white
                        border
                        border-[#1C1D21]
                        rounded-2xl
                        px-2
                        sm:px-4
                        py-3
                        sm:py-4
                        flex
                        flex-col
                        sm:flex-row
                        items-center
                        justify-center
                        gap-1
                        sm:gap-3
                        transition
                    ">

                    <svg
                        class="w-5 h-5 sm:w-6 sm:h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                        </path>

                    </svg>

                    <div class="text-center sm:text-left">
                        <p class="text-[11px] sm:text-sm font-bold">
                            Produk Botol
                        </p>

                        <p class="hidden sm:block text-[10px] opacity-70 mt-0.5">
                            Jual per pcs
                        </p>
                    </div>

                </button>


                {{-- REFILL --}}
                <button
                    type="button"
                    onclick="setSaleMode('refill')"
                    id="mode-refill"
                    class="
                        sale-mode
                        bg-white
                        text-gray-600
                        border
                        border-gray-200
                        rounded-2xl
                        px-2
                        sm:px-4
                        py-3
                        sm:py-4
                        flex
                        flex-col
                        sm:flex-row
                        items-center
                        justify-center
                        gap-1
                        sm:gap-3
                        hover:border-[#CC9863]
                        transition
                    ">

                    <svg
                        class="w-5 h-5 sm:w-6 sm:h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                        </path>

                    </svg>

                    <div class="text-center sm:text-left">
                        <p class="text-[11px] sm:text-sm font-bold">
                            Refill / ML
                        </p>

                        <p class="hidden sm:block text-[10px] opacity-70 mt-0.5">
                            Jual per milliliter
                        </p>
                    </div>

                </button>


                {{-- CUSTOM --}}
                <a
                    href="{{ url('kasir/pos/custom') }}"
                    class="
                        bg-white
                        text-gray-600
                        border
                        border-gray-200
                        rounded-2xl
                        px-2
                        sm:px-4
                        py-3
                        sm:py-4
                        flex
                        flex-col
                        sm:flex-row
                        items-center
                        justify-center
                        gap-1
                        sm:gap-3
                        hover:border-[#CC9863]
                        hover:text-[#CC9863]
                        transition
                    ">

                    <svg
                        class="w-5 h-5 sm:w-6 sm:h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4">
                        </path>

                    </svg>

                    <div class="text-center sm:text-left">
                        <p class="text-[11px] sm:text-sm font-bold">
                            Custom
                        </p>

                        <p class="hidden sm:block text-[10px] opacity-70 mt-0.5">
                            Campur beberapa aroma
                        </p>
                    </div>

                </a>

            </div>



            {{-- ================================================= --}}
            {{-- SEARCH --}}
            {{-- ================================================= --}}
            <div class="flex flex-col sm:flex-row gap-3 mb-4">

                <div class="relative flex-1">

                    <div
                        class="
                            absolute
                            inset-y-0
                            left-0
                            pl-4
                            flex
                            items-center
                            pointer-events-none
                        ">

                        <svg
                            class="w-5 h-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                            </path>

                        </svg>

                    </div>


                    <input
                        type="text"
                        id="searchProduct"
                        autofocus
                        class="
                            block
                            w-full
                            pl-12
                            pr-4
                            py-3.5
                            bg-white
                            border
                            border-gray-100
                            rounded-2xl
                            shadow-sm
                            focus:outline-none
                            focus:ring-2
                            focus:ring-[#CC9863]/30
                            focus:border-[#CC9863]
                            text-sm
                            font-semibold
                            transition
                        "
                        placeholder="Scan barcode atau cari parfum...">

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- CATEGORY --}}
            {{-- ================================================= --}}
            <div class="flex gap-2 overflow-x-auto pb-2 mb-3 scrollbar-hide">

                <button
                    class="
                        px-5
                        py-2.5
                        bg-[#CC9863]
                        text-white
                        rounded-xl
                        text-sm
                        font-bold
                        whitespace-nowrap
                    ">
                    Semua
                </button>

                <button
                    class="
                        px-5
                        py-2.5
                        bg-white
                        border
                        border-gray-200
                        text-gray-600
                        rounded-xl
                        text-sm
                        font-semibold
                        whitespace-nowrap
                    ">
                    EDP
                </button>

                <button
                    class="
                        px-5
                        py-2.5
                        bg-white
                        border
                        border-gray-200
                        text-gray-600
                        rounded-xl
                        text-sm
                        font-semibold
                        whitespace-nowrap
                    ">
                    EDT
                </button>

                <button
                    class="
                        px-5
                        py-2.5
                        bg-white
                        border
                        border-gray-200
                        text-gray-600
                        rounded-xl
                        text-sm
                        font-semibold
                        whitespace-nowrap
                    ">
                    Body Mist
                </button>

                <button
                    class="
                        px-5
                        py-2.5
                        bg-white
                        border
                        border-gray-200
                        text-gray-600
                        rounded-xl
                        text-sm
                        font-semibold
                        whitespace-nowrap
                    ">
                    Biang Parfum
                </button>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- PRODUCT GRID --}}
        {{-- ===================================================== --}}
        <div class="flex-1 min-h-0 overflow-y-auto pr-1 pb-24 lg:pb-6">

            <div
                id="productGrid"
                class="
                    grid
                    grid-cols-2
                    sm:grid-cols-3
                    xl:grid-cols-4
                    2xl:grid-cols-5
                    gap-3
                    sm:gap-4
                ">


                {{-- ================================================= --}}
                {{-- PRODUCT 1 --}}
                {{-- ================================================= --}}
                <div
                    class="
                        product-card
                        bg-white
                        rounded-2xl
                        p-3
                        sm:p-4
                        border
                        border-gray-100
                        shadow-sm
                        hover:shadow-md
                        hover:border-[#CC9863]/50
                        transition
                        cursor-pointer
                        group
                    "
                    data-name="Midnight Amber"
                    data-pcs="true"
                    data-refill="true"
                    onclick="selectProduct({
                        id:'P001',
                        name:'Midnight Amber',
                        pcsPrice:250000,
                        mlPrice:5000,
                        stockPcs:45,
                        stockMl:1250
                    })">

                    <div
                        class="
                            h-24
                            sm:h-28
                            rounded-xl
                            bg-orange-50
                            flex
                            items-center
                            justify-center
                            mb-3
                            text-orange-400
                            relative
                            overflow-hidden
                        ">

                        <svg
                            class="w-9 h-9 sm:w-11 sm:h-11"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>

                        </svg>


                        <span
                            class="
                                absolute
                                top-2
                                right-2
                                px-2
                                py-1
                                rounded-md
                                text-[9px]
                                font-bold
                                bg-white
                                text-gray-500
                                shadow-sm
                            ">
                            EDP
                        </span>

                    </div>


                    <div>

                        <h3
                            class="
                                text-sm
                                font-bold
                                text-gray-900
                                leading-tight
                                line-clamp-2
                            ">
                            Midnight Amber
                        </h3>


                        {{-- PCS DATA --}}
                        <div class="product-pcs mt-2">

                            <p class="text-[10px] text-gray-400">
                                Stok: 45 botol
                            </p>

                            <p class="text-sm font-extrabold text-[#CC9863] mt-0.5">
                                Rp 250.000
                            </p>

                            <p class="text-[10px] text-gray-400">
                                per botol 50 ml
                            </p>

                        </div>


                        {{-- REFILL DATA --}}
                        <div class="product-refill hidden mt-2">

                            <p class="text-[10px] text-gray-400">
                                Stok refill: 1.250 ml
                            </p>

                            <p class="text-sm font-extrabold text-[#CC9863] mt-0.5">
                                Rp 5.000
                                <span class="text-[10px] font-semibold text-gray-400">
                                    / ml
                                </span>
                            </p>

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- PRODUCT 2 --}}
                {{-- ================================================= --}}
                <div
                    class="
                        product-card
                        bg-white
                        rounded-2xl
                        p-3
                        sm:p-4
                        border
                        border-gray-100
                        shadow-sm
                        hover:shadow-md
                        hover:border-[#CC9863]/50
                        transition
                        cursor-pointer
                    "
                    data-name="Vanilla Clouds"
                    data-pcs="true"
                    data-refill="true"
                    onclick="selectProduct({
                        id:'P002',
                        name:'Vanilla Clouds',
                        pcsPrice:85000,
                        mlPrice:4000,
                        stockPcs:12,
                        stockMl:700
                    })">

                    <div
                        class="
                            h-24
                            sm:h-28
                            rounded-xl
                            bg-blue-50
                            flex
                            items-center
                            justify-center
                            mb-3
                            text-blue-400
                            relative
                        ">

                        <svg
                            class="w-9 h-9 sm:w-11 sm:h-11"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>

                        </svg>


                        <span
                            class="
                                absolute
                                top-2
                                right-2
                                bg-white
                                px-2
                                py-1
                                rounded-md
                                text-[9px]
                                text-gray-500
                                font-bold
                                shadow-sm
                            ">
                            BODY MIST
                        </span>

                    </div>


                    <h3 class="text-sm font-bold text-gray-900">
                        Vanilla Clouds
                    </h3>


                    <div class="product-pcs mt-2">

                        <p class="text-[10px] text-gray-400">
                            Stok: 12 botol
                        </p>

                        <p class="text-sm font-extrabold text-[#CC9863]">
                            Rp 85.000
                        </p>

                        <p class="text-[10px] text-gray-400">
                            per botol 100 ml
                        </p>

                    </div>


                    <div class="product-refill hidden mt-2">

                        <p class="text-[10px] text-gray-400">
                            Stok refill: 700 ml
                        </p>

                        <p class="text-sm font-extrabold text-[#CC9863]">
                            Rp 4.000
                            <span class="text-[10px] text-gray-400">
                                / ml
                            </span>
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- CART --}}
    {{-- ========================================================= --}}
    <aside
        id="mobile-cart"
        class="
            w-full
            lg:w-[390px]
            xl:w-[420px]
            h-[88vh]
            lg:h-full
            bg-white
            border-l
            border-gray-200
            flex
            flex-col
            shrink-0
            absolute
            lg:relative
            bottom-0
            z-40
            rounded-t-3xl
            lg:rounded-none
            shadow-[0_-10px_40px_rgba(0,0,0,0.12)]
            lg:shadow-none
            transform
            translate-y-[86%]
            lg:translate-y-0
            transition-transform
            duration-300
        ">


        {{-- MOBILE HANDLE --}}
        <button
            type="button"
            onclick="toggleMobileCart()"
            class="
                lg:hidden
                h-11
                flex
                items-center
                justify-center
                border-b
                border-gray-100
                relative
            ">

            <div class="w-12 h-1.5 rounded-full bg-gray-300"></div>

            <span
                id="mobile-cart-hint"
                class="
                    absolute
                    right-4
                    text-[10px]
                    font-bold
                    text-[#CC9863]
                ">
                Buka Keranjang
            </span>

        </button>



        {{-- ===================================================== --}}
        {{-- CUSTOMER --}}
        {{-- ===================================================== --}}
        <div class="px-4 sm:px-5 py-4 border-b border-gray-100 bg-gray-50/50">

            <p
                class="
                    text-[10px]
                    font-bold
                    uppercase
                    tracking-wider
                    text-gray-500
                    mb-2
                ">
                Pelanggan
            </p>


            <div class="flex gap-2">

                <div class="relative flex-1">

                    <svg
                        class="
                            absolute
                            left-3
                            top-1/2
                            -translate-y-1/2
                            w-4
                            h-4
                            text-gray-400
                        "
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28">
                        </path>

                    </svg>


                    <input
                        type="text"
                        id="member-phone"
                        class="
                            w-full
                            pl-9
                            pr-3
                            py-3
                            border
                            border-gray-200
                            rounded-xl
                            text-sm
                            focus:outline-none
                            focus:border-[#CC9863]
                        "
                        placeholder="Nomor HP member">

                </div>


                <button
                    type="button"
                    onclick="checkMember()"
                    class="
                        w-11
                        bg-[#1C1D21]
                        text-white
                        rounded-xl
                        flex
                        items-center
                        justify-center
                    ">

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0">
                        </path>

                    </svg>

                </button>

            </div>


            <div
                id="member-info"
                class="
                    hidden
                    mt-3
                    p-3
                    bg-green-50
                    border
                    border-green-100
                    rounded-xl
                ">

                <p id="member-name" class="text-sm font-bold">
                    Member
                </p>

                <p class="text-[10px] text-green-600 font-semibold">
                    Diskon Member 10%
                </p>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- CART LIST --}}
        {{-- ===================================================== --}}
        <div
            id="cart-container"
            class="
                flex-1
                min-h-0
                overflow-y-auto
                px-4
                sm:px-5
                py-3
            ">

            <div class="h-full flex flex-col items-center justify-center text-gray-400">

                <svg
                    class="w-14 h-14 mb-3 opacity-40"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4">
                    </path>

                </svg>

                <p class="text-sm font-semibold">
                    Keranjang masih kosong
                </p>

                <p class="text-[11px] mt-1">
                    Pilih parfum untuk memulai transaksi
                </p>

            </div>

        </div>



        {{-- ===================================================== --}}
        {{-- PAYMENT --}}
        {{-- ===================================================== --}}
        <div class="shrink-0 border-t border-gray-100 px-4 sm:px-5 py-4 bg-white">

            <div class="space-y-2 pb-4 border-b border-gray-100">

                <div class="flex justify-between text-sm">

                    <span
                        id="subtotal-label"
                        class="text-gray-500">
                        Subtotal (0 item)
                    </span>

                    <span
                        id="subtotal-val"
                        class="font-bold text-gray-900">
                        Rp 0
                    </span>

                </div>


                <div class="flex justify-between text-sm">

                    <span class="text-gray-500">
                        Diskon
                    </span>

                    <span
                        id="discount-val"
                        class="font-semibold text-red-500">
                        - Rp 0
                    </span>

                </div>

            </div>


            <div class="flex justify-between items-end py-4">

                <span class="font-bold text-gray-900">
                    Total Tagihan
                </span>

                <span
                    id="total-val"
                    class="text-2xl sm:text-3xl font-extrabold text-[#CC9863]">
                    Rp 0
                </span>

            </div>


            {{-- PAYMENT TYPE --}}
            <div class="grid grid-cols-4 gap-2 mb-4">

                <button
                    type="button"
                    class="
                        py-2.5
                        rounded-xl
                        bg-[#1C1D21]
                        text-white
                        text-[10px]
                        font-bold
                    ">
                    Tunai
                </button>

                <button
                    type="button"
                    class="
                        py-2.5
                        rounded-xl
                        border
                        border-gray-200
                        text-gray-600
                        text-[10px]
                        font-bold
                    ">
                    QRIS
                </button>

                <button
                    type="button"
                    class="
                        py-2.5
                        rounded-xl
                        border
                        border-gray-200
                        text-gray-600
                        text-[10px]
                        font-bold
                    ">
                    Transfer
                </button>

                <button
                    type="button"
                    class="
                        py-2.5
                        rounded-xl
                        border
                        border-gray-200
                        text-gray-600
                        text-[10px]
                        font-bold
                    ">
                    Tempo
                </button>

            </div>


            <a
                href="{{ url('kasir/pos/success') }}"
                class="
                    w-full
                    min-h-[52px]
                    bg-[#CC9863]
                    text-white
                    rounded-2xl
                    font-bold
                    flex
                    items-center
                    justify-center
                    hover:bg-[#B58555]
                    transition
                ">
                Bayar Sekarang
            </a>

        </div>

    </aside>

</div>



{{-- ========================================================= --}}
{{-- REFILL MODAL --}}
{{-- ========================================================= --}}
<div
    id="refillModal"
    class="
        hidden
        fixed
        inset-0
        z-[100]
        bg-black/50
        backdrop-blur-sm
        items-end
        sm:items-center
        justify-center
        p-0
        sm:p-4
    ">

    <div
        class="
            bg-white
            w-full
            sm:max-w-md
            rounded-t-3xl
            sm:rounded-3xl
            shadow-2xl
            overflow-hidden
        ">

        <div class="p-5 border-b border-gray-100">

            <div class="flex justify-between items-start gap-4">

                <div>
                    <p
                        class="
                            text-[10px]
                            uppercase
                            font-bold
                            text-[#CC9863]
                            tracking-wider
                        ">
                        Refill Parfum
                    </p>

                    <h3
                        id="refillProductName"
                        class="text-xl font-bold text-gray-900 mt-1">
                        -
                    </h3>

                    <p
                        id="refillPriceText"
                        class="text-sm text-gray-500 mt-1">
                        -
                    </p>
                </div>


                <button
                    type="button"
                    onclick="closeRefillModal()"
                    class="
                        w-9
                        h-9
                        rounded-xl
                        bg-gray-100
                        text-gray-500
                        font-bold
                    ">
                    ×
                </button>

            </div>

        </div>


        <div class="p-5">

            <label class="block text-sm font-bold text-gray-700 mb-2">
                Jumlah Refill
            </label>


            <div
                class="
                    flex
                    items-center
                    border
                    border-gray-200
                    rounded-2xl
                    overflow-hidden
                    bg-gray-50
                ">

                <button
                    type="button"
                    onclick="changeRefillMl(-5)"
                    class="
                        w-14
                        h-14
                        text-xl
                        font-bold
                        text-gray-500
                        hover:bg-gray-200
                    ">
                    −
                </button>


                <div class="flex-1 relative">

                    <input
                        type="number"
                        id="refillMl"
                        value="10"
                        min="1"
                        step="1"
                        oninput="calculateRefill()"
                        class="
                            w-full
                            h-14
                            bg-transparent
                            text-center
                            text-xl
                            font-bold
                            focus:outline-none
                        ">

                    <span
                        class="
                            absolute
                            right-4
                            top-1/2
                            -translate-y-1/2
                            text-xs
                            font-bold
                            text-gray-400
                        ">
                        ML
                    </span>

                </div>


                <button
                    type="button"
                    onclick="changeRefillMl(5)"
                    class="
                        w-14
                        h-14
                        text-xl
                        font-bold
                        text-gray-500
                        hover:bg-gray-200
                    ">
                    +
                </button>

            </div>


            {{-- QUICK ML --}}
            <div class="grid grid-cols-4 gap-2 mt-3">

                <button
                    type="button"
                    onclick="setRefillMl(5)"
                    class="py-2 border rounded-xl text-xs font-bold">
                    5 ml
                </button>

                <button
                    type="button"
                    onclick="setRefillMl(10)"
                    class="py-2 border rounded-xl text-xs font-bold">
                    10 ml
                </button>

                <button
                    type="button"
                    onclick="setRefillMl(20)"
                    class="py-2 border rounded-xl text-xs font-bold">
                    20 ml
                </button>

                <button
                    type="button"
                    onclick="setRefillMl(30)"
                    class="py-2 border rounded-xl text-xs font-bold">
                    30 ml
                </button>

            </div>


            {{-- SUMMARY --}}
            <div
                class="
                    mt-5
                    p-4
                    rounded-2xl
                    bg-[#F6F5F2]
                ">

                <div class="flex justify-between text-sm text-gray-500">

                    <span>Harga per ml</span>

                    <span
                        id="refillUnitPrice"
                        class="font-bold text-gray-900">
                        Rp 0
                    </span>

                </div>


                <div class="flex justify-between items-end mt-3">

                    <span class="font-bold text-gray-900">
                        Total
                    </span>

                    <span
                        id="refillTotal"
                        class="text-2xl font-extrabold text-[#CC9863]">
                        Rp 0
                    </span>

                </div>

            </div>


            <button
                type="button"
                onclick="addRefillToCart()"
                class="
                    mt-5
                    w-full
                    py-4
                    bg-[#CC9863]
                    text-white
                    rounded-2xl
                    font-bold
                    hover:bg-[#B58555]
                    transition
                ">
                Tambah Refill ke Keranjang
            </button>

        </div>

    </div>

</div>



<script>

    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    let saleMode = 'pcs';

    let cart = [];

    let discountRate = 0;

    let selectedRefillProduct = null;



    /*
    |--------------------------------------------------------------------------
    | CURRENCY
    |--------------------------------------------------------------------------
    */

    function formatRupiah(number) {

        return new Intl.NumberFormat('id-ID', {

            style: 'currency',

            currency: 'IDR',

            minimumFractionDigits: 0

        }).format(number || 0);

    }



    /*
    |--------------------------------------------------------------------------
    | SALE MODE
    |--------------------------------------------------------------------------
    */

    function setSaleMode(mode) {

        saleMode = mode;


        document.querySelectorAll('.sale-mode').forEach(button => {

            button.classList.remove(
                'bg-[#1C1D21]',
                'text-white',
                'border-[#1C1D21]'
            );

            button.classList.add(
                'bg-white',
                'text-gray-600',
                'border-gray-200'
            );

        });


        const active = document.getElementById('mode-' + mode);

        active.classList.remove(
            'bg-white',
            'text-gray-600',
            'border-gray-200'
        );

        active.classList.add(
            'bg-[#1C1D21]',
            'text-white',
            'border-[#1C1D21]'
        );


        document.querySelectorAll('.product-pcs').forEach(el => {

            el.classList.toggle(
                'hidden',
                mode !== 'pcs'
            );

        });


        document.querySelectorAll('.product-refill').forEach(el => {

            el.classList.toggle(
                'hidden',
                mode !== 'refill'
            );

        });

    }



    /*
    |--------------------------------------------------------------------------
    | SELECT PRODUCT
    |--------------------------------------------------------------------------
    */

    function selectProduct(product) {

        if (saleMode === 'pcs') {

            addPcsToCart(product);

            return;

        }


        if (saleMode === 'refill') {

            openRefillModal(product);

        }

    }



    /*
    |--------------------------------------------------------------------------
    | ADD PCS
    |--------------------------------------------------------------------------
    */

    function addPcsToCart(product) {

        const cartId = `pcs-${product.id}`;


        const existing = cart.find(item => item.cartId === cartId);


        if (existing) {

            existing.qty += 1;

        } else {

            cart.push({

                cartId,

                productId: product.id,

                name: product.name,

                type: 'pcs',

                unit: 'pcs',

                price: product.pcsPrice,

                qty: 1

            });

        }


        renderCart();

        openMobileCart();

    }



    /*
    |--------------------------------------------------------------------------
    | REFILL MODAL
    |--------------------------------------------------------------------------
    */

    function openRefillModal(product) {

        selectedRefillProduct = product;


        document.getElementById('refillProductName').innerText =
            product.name;


        document.getElementById('refillPriceText').innerText =
            `${formatRupiah(product.mlPrice)} / ml • Stok ${product.stockMl} ml`;


        document.getElementById('refillMl').value = 10;


        calculateRefill();


        const modal = document.getElementById('refillModal');


        modal.classList.remove('hidden');

        modal.classList.add('flex');

    }


    function closeRefillModal() {

        const modal = document.getElementById('refillModal');


        modal.classList.add('hidden');

        modal.classList.remove('flex');


        selectedRefillProduct = null;

    }



    function setRefillMl(ml) {

        document.getElementById('refillMl').value = ml;

        calculateRefill();

    }



    function changeRefillMl(change) {

        const input = document.getElementById('refillMl');


        let value = parseFloat(input.value) || 1;


        value = Math.max(1, value + change);


        input.value = value;


        calculateRefill();

    }



    function calculateRefill() {

        if (!selectedRefillProduct) {
            return;
        }


        const ml =
            parseFloat(
                document.getElementById('refillMl').value
            ) || 0;


        const total =
            ml * selectedRefillProduct.mlPrice;


        document.getElementById('refillUnitPrice').innerText =
            formatRupiah(
                selectedRefillProduct.mlPrice
            );


        document.getElementById('refillTotal').innerText =
            formatRupiah(total);

    }



    /*
    |--------------------------------------------------------------------------
    | ADD REFILL
    |--------------------------------------------------------------------------
    */

    function addRefillToCart() {

        if (!selectedRefillProduct) {
            return;
        }


        const ml =
            parseFloat(
                document.getElementById('refillMl').value
            ) || 0;


        if (ml <= 0) {

            alert('Jumlah refill harus lebih dari 0 ml.');

            return;

        }


        if (ml > selectedRefillProduct.stockMl) {

            alert('Jumlah refill melebihi stok parfum.');

            return;

        }


        /*
        Refill dibuat sebagai line terpisah.
        Jadi 10 ml dan 20 ml tidak otomatis digabung.
        */

        const cartId =
            `refill-${selectedRefillProduct.id}-${Date.now()}`;


        cart.push({

            cartId,

            productId: selectedRefillProduct.id,

            name: selectedRefillProduct.name,

            type: 'refill',

            unit: 'ml',

            ml: ml,

            pricePerMl:
                selectedRefillProduct.mlPrice,

            price:
                selectedRefillProduct.mlPrice * ml,

            qty: 1

        });


        closeRefillModal();

        renderCart();

        openMobileCart();

    }



    /*
    |--------------------------------------------------------------------------
    | CART QTY
    |--------------------------------------------------------------------------
    */

    function updateQty(cartId, change) {

        const item =
            cart.find(
                item => item.cartId === cartId
            );


        if (!item) {
            return;
        }


        /*
        Refill tidak memakai + / - qty.
        Jumlahnya sudah dalam ml.
        */

        if (item.type === 'refill') {

            return;

        }


        item.qty += change;


        if (item.qty <= 0) {

            cart =
                cart.filter(
                    item => item.cartId !== cartId
                );

        }


        renderCart();

    }



    function removeCartItem(cartId) {

        cart =
            cart.filter(
                item => item.cartId !== cartId
            );


        renderCart();

    }



    /*
    |--------------------------------------------------------------------------
    | RENDER CART
    |--------------------------------------------------------------------------
    */

    function renderCart() {

        const container =
            document.getElementById(
                'cart-container'
            );


        container.innerHTML = '';


        let subtotal = 0;

        let totalItems = 0;


        if (cart.length === 0) {

            container.innerHTML = `

                <div class="h-full flex flex-col items-center justify-center text-gray-400">

                    <svg
                        class="w-14 h-14 mb-3 opacity-40"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4">
                        </path>

                    </svg>

                    <p class="text-sm font-semibold">
                        Keranjang masih kosong
                    </p>

                    <p class="text-[11px] mt-1">
                        Pilih produk atau refill
                    </p>

                </div>

            `;

        }



        cart.forEach(item => {

            let itemTotal = 0;


            if (item.type === 'pcs') {

                itemTotal =
                    item.price * item.qty;


                totalItems += item.qty;


                container.innerHTML += `

                    <div class="py-4 border-b border-gray-100">

                        <div class="flex justify-between gap-3">

                            <div class="min-w-0">

                                <div class="flex items-center gap-2">

                                    <span
                                        class="
                                            px-2
                                            py-0.5
                                            rounded-md
                                            bg-gray-100
                                            text-gray-500
                                            text-[9px]
                                            font-bold
                                        ">
                                        PCS
                                    </span>

                                    <h4
                                        class="
                                            font-bold
                                            text-sm
                                            text-gray-900
                                            truncate
                                        ">
                                        ${item.name}
                                    </h4>

                                </div>


                                <p class="text-xs text-gray-400 mt-1.5">
                                    ${formatRupiah(item.price)} / pcs
                                </p>


                                <p class="text-sm font-bold text-[#CC9863] mt-1">
                                    ${formatRupiah(itemTotal)}
                                </p>

                            </div>


                            <button
                                type="button"
                                onclick="removeCartItem('${item.cartId}')"
                                class="
                                    w-8
                                    h-8
                                    rounded-lg
                                    text-gray-400
                                    hover:bg-red-50
                                    hover:text-red-500
                                    shrink-0
                                ">
                                ×
                            </button>

                        </div>


                        <div
                            class="
                                flex
                                items-center
                                gap-2
                                mt-3
                                w-fit
                                bg-gray-50
                                border
                                border-gray-200
                                rounded-xl
                                p-1
                            ">

                            <button
                                type="button"
                                onclick="updateQty('${item.cartId}', -1)"
                                class="
                                    w-8
                                    h-8
                                    bg-white
                                    rounded-lg
                                    shadow-sm
                                    font-bold
                                ">
                                −
                            </button>


                            <span
                                class="
                                    w-7
                                    text-center
                                    text-sm
                                    font-bold
                                ">
                                ${item.qty}
                            </span>


                            <button
                                type="button"
                                onclick="updateQty('${item.cartId}', 1)"
                                class="
                                    w-8
                                    h-8
                                    bg-[#CC9863]
                                    text-white
                                    rounded-lg
                                    font-bold
                                ">
                                +
                            </button>

                        </div>

                    </div>

                `;

            }



            if (item.type === 'refill') {

                itemTotal = item.price;

                totalItems += 1;


                container.innerHTML += `

                    <div class="py-4 border-b border-gray-100">

                        <div class="flex justify-between gap-3">

                            <div class="min-w-0">

                                <div class="flex items-center gap-2">

                                    <span
                                        class="
                                            px-2
                                            py-0.5
                                            rounded-md
                                            bg-orange-50
                                            text-[#CC9863]
                                            text-[9px]
                                            font-bold
                                        ">
                                        REFILL
                                    </span>

                                    <h4
                                        class="
                                            font-bold
                                            text-sm
                                            text-gray-900
                                            truncate
                                        ">
                                        ${item.name}
                                    </h4>

                                </div>


                                <p class="text-xs text-gray-400 mt-1.5">
                                    ${item.ml} ml ×
                                    ${formatRupiah(item.pricePerMl)}
                                </p>


                                <p
                                    class="
                                        text-sm
                                        font-bold
                                        text-[#CC9863]
                                        mt-1
                                    ">
                                    ${formatRupiah(itemTotal)}
                                </p>

                            </div>


                            <button
                                type="button"
                                onclick="removeCartItem('${item.cartId}')"
                                class="
                                    w-8
                                    h-8
                                    rounded-lg
                                    text-gray-400
                                    hover:bg-red-50
                                    hover:text-red-500
                                    shrink-0
                                ">
                                ×
                            </button>

                        </div>

                    </div>

                `;

            }


            subtotal += itemTotal;

        });



        const discountAmount =
            subtotal * discountRate;


        const total =
            subtotal - discountAmount;


        document.getElementById(
            'subtotal-label'
        ).innerText =
            `Subtotal (${totalItems} item)`;


        document.getElementById(
            'subtotal-val'
        ).innerText =
            formatRupiah(subtotal);


        document.getElementById(
            'discount-val'
        ).innerText =
            '- ' + formatRupiah(discountAmount);


        document.getElementById(
            'total-val'
        ).innerText =
            formatRupiah(total);

    }



    /*
    |--------------------------------------------------------------------------
    | MEMBER
    |--------------------------------------------------------------------------
    */

    function checkMember() {

        const phone =
            document.getElementById(
                'member-phone'
            ).value;


        if (phone.length < 6) {

            alert(
                'Masukkan nomor HP yang valid.'
            );

            return;

        }


        document.getElementById(
            'member-info'
        ).classList.remove('hidden');


        discountRate = 0.10;


        renderCart();

    }



    /*
    |--------------------------------------------------------------------------
    | MOBILE CART
    |--------------------------------------------------------------------------
    */

    function toggleMobileCart() {

        const cart =
            document.getElementById(
                'mobile-cart'
            );


        const hint =
            document.getElementById(
                'mobile-cart-hint'
            );


        cart.classList.toggle(
            'translate-y-[86%]'
        );


        cart.classList.toggle(
            'translate-y-0'
        );


        hint.innerText =
            cart.classList.contains(
                'translate-y-0'
            )
                ? 'Tutup'
                : 'Buka Keranjang';

    }



    function openMobileCart() {

        if (window.innerWidth >= 1024) {
            return;
        }


        const cart =
            document.getElementById(
                'mobile-cart'
            );


        cart.classList.remove(
            'translate-y-[86%]'
        );


        cart.classList.add(
            'translate-y-0'
        );


        document.getElementById(
            'mobile-cart-hint'
        ).innerText =
            'Tutup';

    }



    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('searchProduct')
        .addEventListener(
            'input',
            function () {

                const search =
                    this.value.toLowerCase();


                document
                    .querySelectorAll(
                        '.product-card'
                    )
                    .forEach(card => {

                        const name =
                            card.dataset.name
                                .toLowerCase();


                        card.classList.toggle(
                            'hidden',
                            !name.includes(search)
                        );

                    });

            }
        );

</script>

@endsection

