@extends('template.sidebar')
@section('title', 'Edit Member')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 sm:px-6 lg:px-10 py-6 lg:py-8 w-full">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 lg:mb-8">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2 font-medium">
                <a href="{{ route('owner.member.index') }}" class="hover:text-[#CC9863] transition">Member</a>
                <span>/</span>
                <span class="text-gray-900">Edit Data</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Member</h1>
        </div>
        <a href="{{ route('owner.member.index') }}" class="bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition text-sm">Batal &amp; Kembali</a>
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

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden max-w-3xl">
        <div class="p-6 md:p-8">
            <div class="mb-6 rounded-xl bg-blue-50 border border-blue-100 p-4 text-sm text-blue-800">
                <p class="font-bold">{{ $member->kode_member }}</p>
                <p class="mt-1">Total belanja: Rp {{ number_format($member->total_belanja, 0, ',', '.') }} dari {{ number_format($member->transactions_count, 0, ',', '.') }} transaksi.</p>
            </div>

            <form action="{{ route('owner.member.update', $member->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-[#CC9863] uppercase tracking-wider">Informasi Pribadi</h3>
                    <div>
                        <label for="nama" class="block text-sm font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input id="nama" name="nama" type="text" value="{{ old('nama', $member->nama) }}" required autofocus class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] bg-gray-50 focus:bg-white">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="no_telp" class="block text-sm font-bold text-gray-700 mb-1.5">Nomor HP</label>
                            <input id="no_telp" name="no_telp" type="text" value="{{ old('no_telp', $member->no_telp) }}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Email <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input id="email" name="email" type="email" value="{{ old('email', $member->email) }}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-[#CC9863] uppercase tracking-wider">Pengaturan Member</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_bergabung" class="block text-sm font-bold text-gray-700 mb-1.5">Tanggal Bergabung <span class="text-red-500">*</span></label>
                            <input id="tanggal_bergabung" name="tanggal_bergabung" type="date" value="{{ old('tanggal_bergabung', \Illuminate\Support\Carbon::parse($member->tanggal_bergabung)->toDateString()) }}" required class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label for="poin" class="block text-sm font-bold text-gray-700 mb-1.5">Poin Saat Ini</label>
                            <input id="poin" name="poin" type="number" min="0" value="{{ old('poin', $member->poin) }}" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <button type="submit" form="delete-member-form" class="text-red-500 text-sm font-bold hover:underline order-2 md:order-1">Hapus Member</button>
                    <button type="submit" class="w-full md:w-auto bg-[#CC9863] text-white px-8 py-3.5 rounded-xl font-bold hover:bg-[#b58555] transition order-1 md:order-2">Simpan Perubahan</button>
                </div>
            </form>
            <form id="delete-member-form" action="{{ route('owner.member.destroy', $member->id) }}" method="POST" class="hidden" data-delete-member>
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</main>
@include('admin.member.partials.toast')
@endsection
