<div id="app-toast-region"
    class="pointer-events-none fixed inset-x-3 top-3 z-[100] flex flex-col items-end gap-3 sm:left-auto sm:right-5 sm:top-5 sm:w-[min(24rem,calc(100vw-2.5rem))]"
    aria-live="polite" aria-atomic="false"></div>

<dialog id="app-confirm-dialog" aria-modal="true" aria-labelledby="app-confirm-title" aria-describedby="app-confirm-message"
    class="m-0 h-full w-full max-h-none max-w-none bg-transparent p-4 text-left">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-5 shadow-2xl shadow-gray-950/20 sm:p-6">
            <div class="flex items-start gap-3">
                <div id="app-confirm-icon"
                    class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 003.84 21h16.32a2 2 0 001.73-3.14l-8.18-14a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 id="app-confirm-title" class="text-base font-extrabold text-gray-900">Konfirmasi tindakan</h2>
                    <p id="app-confirm-message" class="mt-1 whitespace-pre-line text-sm font-medium leading-6 text-gray-600">
                        Lanjutkan tindakan ini?
                    </p>
                </div>
                <button type="button" id="app-confirm-close"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#CC9863]"
                    aria-label="Tutup dialog">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" id="app-confirm-cancel"
                    class="min-h-11 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-bold text-gray-700 transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#CC9863]">
                    Batal
                </button>
                <button type="button" id="app-confirm-submit"
                    class="min-h-11 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>
</dialog>

<style>
    #app-confirm-dialog::backdrop {
        background: rgb(17 24 39 / 0.38);
        backdrop-filter: blur(2px);
    }

    #app-confirm-dialog[open] {
        display: block;
    }

    @media (prefers-reduced-motion: no-preference) {
        #app-confirm-dialog[open] > div > div {
            animation: app-feedback-dialog-in 180ms cubic-bezier(0.22, 1, 0.36, 1) both;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        #app-toast-region [data-feedback-toast] {
            transition: none;
        }
    }

    @keyframes app-feedback-dialog-in {
        from {
            opacity: 0;
            transform: translateY(8px) scale(0.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
</style>

<script>
    (() => {
        const toastRegion = document.getElementById('app-toast-region');
        const confirmDialog = document.getElementById('app-confirm-dialog');
        const confirmContainer = confirmDialog.firstElementChild;
        const confirmIcon = document.getElementById('app-confirm-icon');
        const confirmTitle = document.getElementById('app-confirm-title');
        const confirmMessage = document.getElementById('app-confirm-message');
        const confirmClose = document.getElementById('app-confirm-close');
        const confirmCancel = document.getElementById('app-confirm-cancel');
        const confirmSubmit = document.getElementById('app-confirm-submit');
        let activeConfirm = null;
        let activeTrigger = null;
        let toastSequence = 0;

        const icons = {
            success: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
            error: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14A2 2 0 003.84 21h16.32a2 2 0 001.73-3.14l-8.18-14a2 2 0 00-3.42 0z" /></svg>',
            warning: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z" /></svg>',
            info: '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z" /></svg>',
        };

        const styles = {
            success: {
                icon: 'bg-green-50 text-green-700',
                border: 'border-green-200',
                title: 'Berhasil',
            },
            error: {
                icon: 'bg-red-50 text-red-700',
                border: 'border-red-200',
                title: 'Terjadi kesalahan',
            },
            warning: {
                icon: 'bg-amber-50 text-amber-700',
                border: 'border-amber-200',
                title: 'Perlu diperiksa',
            },
            info: {
                icon: 'bg-blue-50 text-blue-700',
                border: 'border-blue-200',
                title: 'Informasi',
            },
        };

        function normalizeToastOptions(message, options = {}) {
            if (typeof message === 'object' && message !== null) {
                return message;
            }

            return {
                ...options,
                message,
            };
        }

        function dismissToast(toast) {
            if (!toast || toast.dataset.feedbackDismissed === 'true') {
                return;
            }

            toast.dataset.feedbackDismissed = 'true';
            toast.classList.add('translate-y-2', 'opacity-0');
            window.setTimeout(() => toast.remove(), 180);
        }

        function showToast(message, options = {}) {
            const settings = normalizeToastOptions(message, options);
            const type = styles[settings.type] ? settings.type : 'info';
            const variant = styles[type];
            const toast = document.createElement('div');
            const title = settings.title || variant.title;
            const duration = Number.isFinite(settings.duration) ? settings.duration : 4500;
            const toastId = `app-toast-${++toastSequence}`;

            toast.id = toastId;
            toast.dataset.feedbackToast = 'true';
            toast.className = `pointer-events-auto w-full rounded-2xl border ${variant.border} bg-white p-4 shadow-xl shadow-gray-950/10 transition duration-200 ease-out translate-y-2 opacity-0`;
            toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
            toast.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl ${variant.icon}">
                        ${icons[type]}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-extrabold text-gray-900"></p>
                        <p data-feedback-message class="mt-1 whitespace-pre-line text-sm font-semibold leading-5 text-gray-700"></p>
                    </div>
                    <button type="button" data-feedback-close class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#CC9863]" aria-label="Tutup notifikasi">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            `;

            toast.querySelector('p').textContent = title;
            toast.querySelector('[data-feedback-message]').textContent = settings.message || '';
            toast.querySelector('[data-feedback-close]').addEventListener('click', () => dismissToast(toast));
            toastRegion.appendChild(toast);

            window.requestAnimationFrame(() => toast.classList.remove('translate-y-2', 'opacity-0'));

            if (duration > 0) {
                window.setTimeout(() => dismissToast(toast), duration);
            }

            return {
                id: toastId,
                dismiss: () => dismissToast(toast),
            };
        }

        function closeConfirm(result) {
            if (!activeConfirm) {
                return;
            }

            const resolve = activeConfirm;
            activeConfirm = null;
            if (confirmDialog.open) {
                confirmDialog.close();
            }

            if (activeTrigger && typeof activeTrigger.focus === 'function') {
                activeTrigger.focus();
            }

            activeTrigger = null;
            resolve(result);
        }

        function confirmAction(options = {}) {
            if (activeConfirm) {
                closeConfirm(false);
            }

            const tone = options.tone === 'success' ? 'success' : 'danger';
            confirmTitle.textContent = options.title || 'Konfirmasi tindakan';
            confirmMessage.textContent = options.message || 'Lanjutkan tindakan ini?';
            confirmSubmit.textContent = options.confirmText || 'Lanjutkan';
            confirmIcon.className = tone === 'success'
                ? 'mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-700'
                : 'mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600';
            confirmSubmit.className = tone === 'success'
                ? 'min-h-11 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2'
                : 'min-h-11 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2';
            activeTrigger = document.activeElement;

            confirmDialog.showModal();
            window.requestAnimationFrame(() => confirmCancel.focus());

            return new Promise((resolve) => {
                activeConfirm = resolve;
            });
        }

        function confirmForm(form) {
            if (form.dataset.feedbackBypass === 'true') {
                delete form.dataset.feedbackBypass;
                return;
            }

            if (form.dataset.feedbackPending === 'true') {
                return;
            }

            form.dataset.feedbackPending = 'true';
            const options = {
                title: form.dataset.feedbackConfirmTitle,
                message: form.dataset.feedbackConfirmMessage,
                confirmText: form.dataset.feedbackConfirmLabel,
                tone: form.dataset.feedbackConfirmTone,
            };

            confirmAction(options).then((confirmed) => {
                form.dataset.feedbackPending = 'false';

                if (!confirmed) {
                    return;
                }

                form.dataset.feedbackBypass = 'true';
                form.requestSubmit();
                window.setTimeout(() => delete form.dataset.feedbackBypass, 0);
            });
        }

        confirmClose.addEventListener('click', () => closeConfirm(false));
        confirmCancel.addEventListener('click', () => closeConfirm(false));
        confirmSubmit.addEventListener('click', () => closeConfirm(true));
        confirmDialog.addEventListener('cancel', (event) => {
            event.preventDefault();
            closeConfirm(false);
        });
        confirmContainer.addEventListener('click', (event) => {
            if (event.target === confirmContainer) {
                closeConfirm(false);
            }
        });

        document.addEventListener('submit', (event) => {
            const form = event.target.closest('[data-feedback-confirm]');
            if (!form) {
                return;
            }

            if (form.dataset.feedbackBypass === 'true') {
                delete form.dataset.feedbackBypass;
                return;
            }

            event.preventDefault();
            confirmForm(form);
        });

        window.AppFeedback = {
            toast: showToast,
            success: (message, options = {}) => showToast(message, { ...options, type: 'success' }),
            error: (message, options = {}) => showToast(message, { ...options, type: 'error' }),
            warning: (message, options = {}) => showToast(message, { ...options, type: 'warning' }),
            info: (message, options = {}) => showToast(message, { ...options, type: 'info' }),
            confirm: confirmAction,
            confirmForm,
        };

        @if (session('success'))
            showToast(@json(session('success')), { type: 'success' });
        @endif

        @if (session('error'))
            showToast(@json(session('error')), { type: 'error', duration: 0 });
        @endif
    })();
</script>
