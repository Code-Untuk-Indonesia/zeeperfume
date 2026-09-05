@extends('template.sidebar')
@section('title', 'Tambah Outlet Baru')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('owner.outlet.index') }}" class="hover:text-[#CC9863] transition">Manajemen Outlet</a>
            <span>/</span>
            <span class="text-[#CC9863] font-semibold">Tambah Outlet</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Outlet Baru</h1>
        <p class="text-gray-500 text-sm mt-1">Masukkan informasi lokasi dan kontak cabang baru.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm max-w-3xl">
        <form action="{{ route('owner.outlet.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="nama_cabang" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Cabang / Outlet <span class="text-red-500">*</span></label>
                <input id="nama_cabang" type="text" name="nama_cabang" value="{{ old('nama_cabang') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Cabang Ayani Megamall" required autofocus>
            </div>
            <div>
                <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                <input id="no_telepon" type="text" name="no_telepon" value="{{ old('no_telepon') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: 081234567890">
            </div>
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                <textarea id="alamat" name="alamat" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Tuliskan alamat lengkap cabang...">{{ old('alamat') }}</textarea>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 justify-end pt-2">
                <a href="{{ route('owner.outlet.index') }}" class="px-6 py-3.5 rounded-xl border border-gray-200 text-center text-gray-700 font-bold hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-6 py-3.5 rounded-xl bg-[#1C1D21] text-white font-bold hover:bg-gray-800 transition">Simpan Outlet</button>
            </div>
        </form>
    </div>
</main>
@endsection
