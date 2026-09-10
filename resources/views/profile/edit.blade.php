@extends($layout)
@section('title', 'Profil Saya')

@section('content')
<main class="flex-1 min-h-0 overflow-y-auto bg-[#FAFAFA] px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
    <div class="mx-auto w-full max-w-6xl">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 md:text-3xl">Profil Saya</h1>
                <p class="mt-1 max-w-2xl text-sm font-medium text-gray-500">Perbarui nama, username, atau password akun yang sedang digunakan.</p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#CC9863] focus-visible:ring-offset-2">Kembali</a>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                <p class="font-extrabold">Profil belum dapat diperbarui.</p>
                <ul class="mt-1 list-disc space-y-1 pl-5 font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(18rem,0.65fr)]">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <section class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-6 py-5 sm:px-8">
                        <div class="flex items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#1C1D21] text-xl font-extrabold text-white" aria-hidden="true">
                                {{ mb_strtoupper(mb_substr($profile->nama_lengkap, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-lg font-extrabold text-gray-900">Informasi akun</h2>
                                <p class="truncate text-sm font-medium text-gray-500">{{ $profile->username }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5 px-6 py-6 sm:px-8">
                        <div>
                            <label for="nama_lengkap" class="mb-1.5 block text-sm font-bold text-gray-700">Nama lengkap <span class="text-red-500">*</span></label>
                            <input id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap', $profile->nama_lengkap) }}" autocomplete="name" required autofocus class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-900 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                            @error('nama_lengkap')<p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="username" class="mb-1.5 block text-sm font-bold text-gray-700">Username <span class="text-red-500">*</span></label>
                            <input id="username" name="username" type="text" value="{{ old('username', $profile->username) }}" autocomplete="username" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-900 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                            @error('username')<p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-6 py-5 sm:px-8">
                        <h2 class="text-lg font-extrabold text-gray-900">Keamanan akun</h2>
                        <p class="mt-1 text-sm font-medium text-gray-500">Kosongkan password baru jika tidak ingin menggantinya.</p>
                    </div>

                    <div class="space-y-5 px-6 py-6 sm:px-8">
                        <div>
                            <label for="current_password" class="mb-1.5 block text-sm font-bold text-gray-700">Password saat ini</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-900 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20" placeholder="Wajib diisi saat mengganti password">
                            @error('current_password')<p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="password" class="mb-1.5 block text-sm font-bold text-gray-700">Password baru</label>
                                <input id="password" name="password" type="password" autocomplete="new-password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-900 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20" placeholder="Minimal 8 karakter">
                                @error('password')<p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="mb-1.5 block text-sm font-bold text-gray-700">Konfirmasi password baru</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-900 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('home') }}" class="inline-flex min-h-12 items-center justify-center rounded-2xl border border-gray-200 bg-white px-6 py-3 text-sm font-extrabold text-gray-700 shadow-sm transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#CC9863] focus-visible:ring-offset-2">Batal</a>
                    <button type="submit" class="inline-flex min-h-12 items-center justify-center rounded-2xl bg-[#1C1D21] px-7 py-3 text-sm font-extrabold text-white shadow-lg shadow-gray-900/15 transition hover:bg-black focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#CC9863] focus-visible:ring-offset-2">Simpan perubahan</button>
                </div>
            </div>

            <aside class="h-fit rounded-3xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-lg font-extrabold text-gray-900">Akses akun</h2>
                <p class="mt-1 text-sm font-medium text-gray-500">Informasi ini dikelola oleh pengaturan akses aplikasi.</p>

                <dl class="mt-6 divide-y divide-gray-100">
                    <div class="py-4 first:pt-0">
                        <dt class="text-xs font-extrabold uppercase tracking-[0.14em] text-gray-400">Role</dt>
                        <dd class="mt-1 text-base font-extrabold capitalize text-gray-900">{{ $profile->nama_role }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-xs font-extrabold uppercase tracking-[0.14em] text-gray-400">Cabang</dt>
                        <dd class="mt-1 text-base font-extrabold text-gray-900">{{ $profile->nama_cabang ?? 'Akses global' }}</dd>
                    </div>
                    <div class="py-4 last:pb-0">
                        <dt class="text-xs font-extrabold uppercase tracking-[0.14em] text-gray-400">Status</dt>
                        <dd class="mt-1 inline-flex items-center gap-2 text-base font-extrabold text-gray-900">
                            <span class="h-2.5 w-2.5 rounded-full {{ $profile->status_aktif ? 'bg-green-500' : 'bg-red-500' }}" aria-hidden="true"></span>
                            {{ $profile->status_aktif ? 'Aktif' : 'Nonaktif' }}
                        </dd>
                    </div>
                </dl>
            </aside>
        </form>
    </div>
</main>
@endsection
