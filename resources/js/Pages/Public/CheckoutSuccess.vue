<script setup>
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Payment Successful" />
    <PublicLayout>
        <div class="min-h-[80vh] flex items-center justify-center px-6">
            <div class="max-w-md w-full text-center">
                <!-- Success Icon -->
                <div
                    class="w-20 h-20 mx-auto mb-6 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center"
                >
                    <svg
                        class="w-10 h-10 text-emerald-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>

                <h1 class="text-2xl md:text-3xl font-bold text-white mb-3">
                    Payment Successful! 🎉
                </h1>
                <p class="text-gray-400 mb-8 leading-relaxed">
                    Terima kasih atas pembelian Anda! Akses Anda akan diaktifkan
                    secara otomatis setelah pembayaran dikonfirmasi oleh sistem.
                </p>

                <div
                    class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-5 mb-8 text-left space-y-2"
                >
                    <div class="flex items-center gap-2 text-sm">
                        <svg
                            class="w-4 h-4 text-cyan-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                        <span class="text-gray-300">Pembayaran diterima</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <svg
                            class="w-4 h-4 text-cyan-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                        <span class="text-gray-300">Akses sedang diproses</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <svg
                            class="w-4 h-4 text-gray-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <span class="text-gray-400"
                            >Cek email Anda untuk detail akses</span
                        >
                    </div>
                </div>

                <Link
                    href="/"
                    class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-xl hover:from-cyan-400 hover:to-violet-500 transition-all"
                >
                    Kembali ke Beranda
                </Link>
            </div>
        </div>
    </PublicLayout>
</template>
