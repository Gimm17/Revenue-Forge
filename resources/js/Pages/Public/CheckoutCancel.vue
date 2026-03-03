<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Payment Cancelled" />
    <PublicLayout>
        <div class="min-h-[80vh] flex items-center justify-center px-6">
            <div class="max-w-md w-full text-center">
                <div
                    class="w-20 h-20 mx-auto mb-6 rounded-full bg-amber-500/10 border border-amber-500/20 flex items-center justify-center"
                >
                    <svg
                        class="w-10 h-10 text-amber-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                        />
                    </svg>
                </div>

                <h1 class="text-2xl md:text-3xl font-bold text-white mb-3">
                    Payment Cancelled
                </h1>
                <p class="text-gray-400 mb-8">
                    Pembayaran Anda dibatalkan. Tidak ada biaya yang dikenakan.
                    Anda dapat mencoba lagi kapan saja.
                </p>

                <div class="flex items-center justify-center gap-3">
                    <button
                        onclick="history.back()"
                        class="px-6 py-3 text-sm font-semibold text-cyan-400 border border-cyan-500/30 rounded-xl hover:bg-cyan-500/10 transition-all"
                    >
                        Coba Lagi
                    </button>
                    <Link
                        href="/"
                        class="px-6 py-3 text-sm font-semibold text-gray-400 hover:text-white transition-colors"
                    >
                        Kembali ke Beranda
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
