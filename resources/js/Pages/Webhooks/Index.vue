<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({ logs: Array });

const expanded = ref(null);

const toggle = (id) => {
    expanded.value = expanded.value === id ? null : id;
};

const statusColors = {
    processing: "bg-amber-500/20 text-amber-400",
    processed: "bg-emerald-500/20 text-emerald-400",
    failed: "bg-red-500/20 text-red-400",
};

const eventColors = {
    "payment.success": "text-emerald-400",
    "payment.completed": "text-emerald-400",
    "payment.failed": "text-red-400",
    "subscription.active": "text-cyan-400",
    "subscription.cancelled": "text-amber-400",
};
</script>

<template>
    <Head title="Webhook Logs" />
    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Webhook Logs</h1>
        </template>

        <div v-if="logs && logs.length > 0" class="space-y-2">
            <div
                v-for="log in logs"
                :key="log.id"
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden hover:border-white/[0.12] transition-colors"
            >
                <!-- Log Row -->
                <div
                    @click="toggle(log.id)"
                    class="flex items-center gap-3 px-5 py-3 cursor-pointer select-none"
                >
                    <span
                        :class="[
                            'text-xs font-mono',
                            eventColors[log.event_type] || 'text-gray-400',
                        ]"
                    >
                        {{ log.event_type }}
                    </span>
                    <span
                        :class="[
                            'text-[10px] font-semibold px-2 py-0.5 rounded-full',
                            statusColors[log.status] ||
                                'bg-gray-500/20 text-gray-400',
                        ]"
                    >
                        {{ log.status }}
                    </span>
                    <span
                        class="text-xs text-gray-600 font-mono truncate flex-1"
                        >{{ log.event_id || "—" }}</span
                    >
                    <span class="text-xs text-gray-600">{{
                        log.created_at
                    }}</span>
                    <svg
                        class="w-4 h-4 text-gray-500 transition-transform"
                        :class="expanded === log.id ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </div>

                <!-- Expanded Payload -->
                <transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0 max-h-0"
                    enter-to-class="opacity-100 max-h-[500px]"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0 max-h-0"
                >
                    <div
                        v-if="expanded === log.id"
                        class="border-t border-white/[0.06] px-5 py-4 space-y-3"
                    >
                        <div
                            v-if="log.error_message"
                            class="text-xs text-red-400 bg-red-500/10 rounded-lg p-3 font-mono"
                        >
                            ❌ {{ log.error_message }}
                        </div>
                        <div
                            class="flex items-center gap-4 text-xs text-gray-500"
                        >
                            <span
                                >Processed:
                                {{ log.processed_at || "Pending" }}</span
                            >
                        </div>
                        <pre
                            class="text-xs text-gray-400 bg-black/30 rounded-lg p-4 overflow-x-auto max-h-80 font-mono leading-relaxed"
                            >{{ log.payload_preview }}</pre
                        >
                    </div>
                </transition>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-12 text-center"
        >
            <div class="text-5xl mb-4">🔗</div>
            <h3 class="text-lg font-semibold text-white mb-2">
                No Webhook Logs
            </h3>
            <p class="text-sm text-gray-500">
                Incoming webhooks from Mayar will appear here for debugging.
            </p>
        </div>
    </AppLayout>
</template>
