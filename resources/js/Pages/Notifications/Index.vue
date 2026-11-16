<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { CheckBadgeIcon, BellAlertIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    notifications: Object,
});

const markAsRead = (id) => {
    router.post(
        route("app.notifications.read", id),
        {},
        {
            preserveScroll: true,
        },
    );
};

const markAllRead = () => {
    router.post(
        route("app.notifications.read-all"),
        {},
        {
            preserveScroll: true,
        },
    );
};

const formatTime = (dateStr) => {
    return new Date(dateStr).toLocaleString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<template>
    <Head title="Notifications" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-[#00D261]/10 rounded-xl">
                    <BellAlertIcon class="w-6 h-6 text-[#00D261]" />
                </div>
                <div>
                    <h1
                        class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl"
                    >
                        Notifications
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        View your recent alerts, updates, and events.
                    </p>
                </div>
            </div>
        </template>

        <template #actions>
            <button
                @click="markAllRead"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-all shadow-sm"
            >
                <CheckBadgeIcon class="w-5 h-5 text-[#00D261]" />
                Mark all read
            </button>
        </template>

        <div class="max-w-4xl space-y-4">
            <div
                v-if="notifications.data.length === 0"
                class="text-center py-20 bg-white border border-gray-100 rounded-2xl shadow-sm"
            >
                <BellAlertIcon class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-bold text-gray-900">All caught up!</h3>
                <p class="text-sm text-gray-500 mt-1">
                    You have no notifications at this time.
                </p>
            </div>

            <div
                v-else
                class="bg-white border text-gray-900 border-gray-100 rounded-2xl shadow-sm overflow-hidden divide-y divide-gray-50"
            >
                <div
                    v-for="notification in notifications.data"
                    :key="notification.id"
                    :class="[
                        'p-5 transition-colors flex items-start gap-4',
                        notification.read_at
                            ? 'bg-white'
                            : 'bg-[#00D261]/[0.02]',
                    ]"
                >
                    <div class="text-2xl pt-1">
                        {{ notification.icon || "🔔" }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4
                                    :class="[
                                        'text-sm font-bold',
                                        notification.read_at
                                            ? 'text-gray-900'
                                            : 'text-gray-900',
                                    ]"
                                >
                                    {{ notification.title }}
                                </h4>
                                <p
                                    class="text-sm text-gray-600 mt-1 leading-relaxed"
                                >
                                    {{ notification.body }}
                                </p>
                            </div>
                            <span
                                class="text-xs font-semibold text-gray-400 whitespace-nowrap pt-1 uppercase tracking-wider"
                            >
                                {{ formatTime(notification.created_at) }}
                            </span>
                        </div>
                    </div>

                    <div
                        v-if="!notification.read_at"
                        class="pl-2 pt-2 shrink-0"
                    >
                        <button
                            @click="markAsRead(notification.id)"
                            class="p-1 rounded-full hover:bg-black/5 transition-colors shrink-0"
                            title="Mark as read"
                        >
                            <span
                                class="w-3 h-3 rounded-full bg-[#00D261] block shadow-sm shadow-[#00D261]/30"
                            ></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pagination (if applicable) -->
            <div
                v-if="notifications.links && notifications.data.length > 0"
                class="mt-6"
            >
                <!-- Simple pagination visualization, adapt based on Tailwind paginator used in project -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
