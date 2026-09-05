<div id="outlet-toast" class="fixed right-4 top-4 z-50 hidden w-[min(24rem,calc(100vw-2rem))]" role="status" aria-live="polite">
    <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-4 shadow-xl shadow-gray-900/10">
        <div class="flex items-start gap-3">
            <div id="outlet-toast-icon" class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p id="outlet-toast-title" class="text-sm font-bold text-gray-900">Berhasil</p>
                <p id="outlet-toast-message" class="mt-1 text-sm font-semibold text-gray-700"></p>
                <div id="outlet-toast-actions" class="mt-3 hidden gap-2">
                    <button type="button" id="outlet-toast-cancel" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50">Batal</button>
                    <button type="button" id="outlet-toast-confirm" class="rounded-lg bg-red-600 px-3 py-2 text-xs font-bold text-white hover:bg-red-700">Lanjutkan</button>
                </div>
            </div>
            <button type="button" id="outlet-toast-close" class="text-gray-400 hover:text-gray-600" aria-label="Tutup notifikasi">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toast = document.getElementById('outlet-toast');
        const icon = document.getElementById('outlet-toast-icon');
        const title = document.getElementById('outlet-toast-title');
        const message = document.getElementById('outlet-toast-message');
        const actions = document.getElementById('outlet-toast-actions');
        const close = document.getElementById('outlet-toast-close');
        const cancel = document.getElementById('outlet-toast-cancel');
        const confirm = document.getElementById('outlet-toast-confirm');
        let pendingForm = null;
        let dismissTimer = null;

        function hideToast() {
            window.clearTimeout(dismissTimer);
            toast.classList.add('hidden');
            actions.classList.add('hidden');
            pendingForm = null;
        }

        function showToast(toastTitle, toastMessage, requiresConfirmation = false, isRestore = false) {
            window.clearTimeout(dismissTimer);
            title.textContent = toastTitle;
            message.textContent = toastMessage;
            actions.classList.toggle('hidden', !requiresConfirmation);
            confirm.textContent = isRestore ? 'Aktifkan' : 'Nonaktifkan';
            confirm.className = isRestore
                ? 'rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white hover:bg-green-700'
                : 'rounded-lg bg-red-600 px-3 py-2 text-xs font-bold text-white hover:bg-red-700';
            toast.className = requiresConfirmation
                ? 'fixed inset-0 z-50 flex items-center justify-center bg-gray-900/25 px-4'
                : 'fixed right-4 top-4 z-50 w-[min(24rem,calc(100vw-2rem))]';
            toast.setAttribute('role', requiresConfirmation ? 'alertdialog' : 'status');
            toast.setAttribute('aria-modal', requiresConfirmation ? 'true' : 'false');
            icon.className = requiresConfirmation
                ? (isRestore
                    ? 'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600'
                    : 'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600')
                : 'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600';
            toast.classList.remove('hidden');

            if (!requiresConfirmation) {
                dismissTimer = window.setTimeout(hideToast, 4500);
            }
        }

        close.addEventListener('click', hideToast);
        cancel.addEventListener('click', hideToast);
        confirm.addEventListener('click', function () {
            if (pendingForm) {
                pendingForm.submit();
            }
        });

        document.addEventListener('submit', function (event) {
            const form = event.target.closest('[data-outlet-action]');
            if (!form) {
                return;
            }

            event.preventDefault();
            pendingForm = form;
            const isRestore = form.dataset.outletAction === 'restore';
            showToast(
                isRestore ? 'Konfirmasi aktivasi' : 'Konfirmasi penonaktifan',
                isRestore ? 'Outlet ini akan diaktifkan kembali.' : 'Outlet ini akan disembunyikan dari daftar outlet aktif.',
                true,
                isRestore,
            );
        });

        @if (session('success'))
            showToast('Berhasil', @json(session('success')));
        @endif
    });
</script>
