<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    broadcasts: Array,
    customerCount: Number,
});

const showCompose = ref(false);

const form = useForm({
    subject: "",
    body: "",
});

const submit = () => {
    if (!confirm(`Send this email to ${props.customerCount} customers?`))
        return;
    form.post(route("app.broadcasts.store"), {
        onSuccess: () => {
            showCompose.value = false;
            form.reset();
        },
    });
};

const deleteBroadcast = (id) => {
    if (confirm("Delete this broadcast?")) {
        router.delete(route("app.broadcasts.destroy", id));
    }
};

const statusColors = {
    Draft: "bg-gray-500/20 text-gray-400",
    Sending: "bg-amber-500/20 text-amber-400",
    Sent: "bg-emerald-500/20 text-emerald-400",
};
</script>

<template>
    <Head title="Email Broadcasts" />
    <AppLayout>
        <template #header>
            <h1 class="text-xl font-bold text-white">Email Broadcasts</h1>
        </template>

        <template #actions>
            <button
                @click="showCompose = !showCompose"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20"
            >
                <span v-if="!showCompose">✉️ Compose</span>
                <span v-else>✕ Cancel</span>
            </button>
        </template>

        <!-- Compose Form -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div
                v-if="showCompose"
                class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-6 mb-6"
            >
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-white">
                        Compose Broadcast
                    </h2>
                    <span class="text-xs text-gray-500">
                        📨 Will be sent to
                        <strong class="text-cyan-400">{{
                            customerCount
                        }}</strong>
                        customers
                    </span>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-400 mb-1"
                            >Subject</label
                        >
                        <input
                            v-model="form.subject"
                            type="text"
                            class="w-full px-4 py-2.5 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none"
                            placeholder="🎉 Exciting news for you!"
                        />
                        <p
                            v-if="form.errors.subject"
                            class="text-red-400 text-xs mt-1"
                        >
                            {{ form.errors.subject }}
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-400 mb-1"
                            >Message Body</label
                        >
                        <textarea
                            v-model="form.body"
                            rows="8"
                            class="w-full px-4 py-3 bg-white/[0.06] border border-white/[0.1] rounded-lg text-white text-sm focus:border-cyan-500/50 focus:outline-none resize-none"
                            placeholder="Write your message here..."
                        />
                        <p
                            v-if="form.errors.body"
                            class="text-red-400 text-xs mt-1"
                        >
                            {{ form.errors.body }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            type="submit"
                            :disabled="form.processing || customerCount === 0"
                            class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg hover:from-cyan-400 hover:to-violet-500 transition-all shadow-lg shadow-cyan-500/20 disabled:opacity-50"
                        >
                            {{
                                form.processing
                                    ? "Sending..."
                                    : `Send to ${customerCount} Customers`
                            }}
                        </button>
                        <span
                            v-if="customerCount === 0"
                            class="text-xs text-amber-400"
                            >No customers to send to yet.</span
                        >
                    </div>
                </form>
            </div>
        </transition>

        <!-- Broadcasts History -->
        <div
            v-if="broadcasts && broadcasts.length > 0"
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] overflow-hidden"
        >
            <div class="px-5 py-3 border-b border-white/[0.06]">
                <h3 class="text-sm font-semibold text-white">
                    📧 Broadcast History
                </h3>
            </div>
            <div class="divide-y divide-white/[0.04]">
                <div
                    v-for="b in broadcasts"
                    :key="b.id"
                    class="px-5 py-4 flex items-center justify-between hover:bg-white/[0.02] transition-colors"
                >
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">
                            {{ b.subject }}
                        </p>
                        <div class="flex items-center gap-3 mt-1">
                            <span
                                :class="[
                                    'text-[10px] font-semibold px-2 py-0.5 rounded-full',
                                    statusColors[b.status],
                                ]"
                            >
                                {{ b.status }}
                            </span>
                            <span class="text-xs text-gray-500"
                                >{{ b.sent_count }}/{{
                                    b.recipients_count
                                }}
                                delivered</span
                            >
                            <span class="text-xs text-gray-600">{{
                                b.sent_at || b.created_at
                            }}</span>
                        </div>
                    </div>
                    <button
                        @click="deleteBroadcast(b.id)"
                        class="ml-3 p-1.5 text-gray-500 hover:text-red-400 rounded-lg hover:bg-white/[0.06] transition-colors"
                    >
                        🗑️
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else-if="!showCompose"
            class="rounded-xl bg-white/[0.04] border border-white/[0.08] p-12 text-center"
        >
            <div class="text-5xl mb-4">📧</div>
            <h3 class="text-lg font-semibold text-white mb-2">
                No Broadcasts Yet
            </h3>
            <p class="text-sm text-gray-500 mb-4">
                Send marketing emails to all your customers at once.
            </p>
            <button
                @click="showCompose = true"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-cyan-500 to-violet-600 rounded-lg"
            >
                ✉️ Compose First Broadcast
            </button>
        </div>
    </AppLayout>
</template>
