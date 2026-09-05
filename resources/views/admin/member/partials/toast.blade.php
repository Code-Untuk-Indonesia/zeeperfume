<div id="member-toast" class="fixed right-4 top-4 z-50 hidden w-[min(24rem,calc(100vw-2rem))]" role="status" aria-live="polite">
    <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-4 shadow-xl shadow-gray-900/10">
        <div class="flex items-start gap-3">
            <div id="member-toast-icon" class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p id="member-toast-title" class="text-sm font-bold text-gray-900">Berhasil</p>
                <p id="member-toast-message" class="mt-1 text-sm font-semibold text-gray-700"></p>
                <div id="member-toast-actions" class="mt-3 hidden gap-2">
                    <button type="button" id="member-toast-cancel" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50">Batal</button>
                    <button type="button" id="member-toast-confirm" class="rounded-lg bg-red-600 px-3 py-2 text-xs font-bold text-white hover:bg-red-700">Hapus</button>
                </div>
            </div>
            <button type="button" id="member-toast-close" class="text-gray-400 hover:text-gray-600" aria-label="Tutup notifikasi">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toast = document.getElementById('member-toast');
        const icon = document.getElementById('member-toast-icon');
        const title = document.getElementById('member-toast-title');
        const message = document.getElementById('member-toast-message');
        const actions = document.getElementById('member-toast-actions');
        const close = document.getElementById('member-toast-close');
        const cancel = document.getElementById('member-toast-cancel');
        const confirm = document.getElementById('member-toast-confirm');
        let pendingForm = null;
        let dismissTimer = null;

        function hideToast() {
            window.clearTimeout(dismissTimer);
            toast.classList.add('hidden');
            actions.classList.add('hidden');
            pendingForm = null;
        }

        function showToast(toastTitle, toastMessage, requiresConfirmation = false) {
            window.clearTimeout(dismissTimer);
            title.textContent = toastTitle;
            message.textContent = toastMessage;
            actions.classList.toggle('hidden', !requiresConfirmation);
            toast.className = requiresConfirmation
                ? 'fixed inset-0 z-50 flex items-center justify-center bg-gray-900/25 px-4'
                : 'fixed right-4 top-4 z-50 w-[min(24rem,calc(100vw-2rem))]';
            toast.setAttribute('role', requiresConfirmation ? 'alertdialog' : 'status');
            toast.setAttribute('aria-modal', requiresConfirmation ? 'true' : 'false');
            icon.className = requiresConfirmation
                ? 'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600'
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

        document.querySelectorAll('[data-delete-member]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                pendingForm = form;
                showToast('Konfirmasi penghapusan', 'Member ini akan dihapus dari daftar aktif.', true);
            });
        });

        @if (session('success'))
            showToast('Berhasil', @json(session('success')));
        @endif
    });
</script>
