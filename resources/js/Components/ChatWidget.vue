<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    enabled: { type: Boolean, default: false },
    greeting: { type: String, default: "Hi! How can I help you?" },
    email: { type: String, default: "" },
    brandColor: { type: String, default: "#06b6d4" },
    secondaryColor: { type: String, default: "#8b5cf6" },
    workspaceName: { type: String, default: "" },
});

const isOpen = ref(false);
const showGreeting = ref(true);
const msgName = ref("");
const msgEmail = ref("");
const msgText = ref("");
const sent = ref(false);
const sending = ref(false);

const gradient = computed(
    () =>
        `linear-gradient(135deg, ${props.brandColor}, ${props.secondaryColor})`,
);

const dismissGreeting = () => {
    showGreeting.value = false;
};

const sendMessage = async () => {
    if (!msgText.value.trim()) return;
    sending.value = true;

    try {
        // Send as mailto (lightweight — no backend needed)
        const subject = encodeURIComponent(
            `Chat from ${msgName.value || "Visitor"} via RevenueForge`,
        );
        const body = encodeURIComponent(
            `Name: ${msgName.value}\nEmail: ${msgEmail.value}\n\nMessage:\n${msgText.value}`,
        );

        if (props.email) {
            window.open(
                `mailto:${props.email}?subject=${subject}&body=${body}`,
                "_blank",
            );
        }

        sent.value = true;
    } finally {
        sending.value = false;
    }
};

const resetForm = () => {
    sent.value = false;
    msgText.value = "";
};
</script>

<template>
    <div v-if="enabled" class="fixed bottom-6 right-6 z-50">
        <!-- Greeting Bubble -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showGreeting && !isOpen"
                class="absolute bottom-16 right-0 mb-2"
            >
                <div
                    class="relative bg-white rounded-2xl rounded-br-sm shadow-2xl px-4 py-3 max-w-[240px]"
                >
                    <button
                        @click="dismissGreeting"
                        class="absolute -top-2 -left-2 w-5 h-5 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center text-gray-500 text-[10px] transition-colors"
                    >
                        ✕
                    </button>
                    <p class="text-sm text-gray-800">{{ greeting }}</p>
                </div>
            </div>
        </transition>

        <!-- Chat Window -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-90 translate-y-4"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-90 translate-y-4"
        >
            <div
                v-if="isOpen"
                class="absolute bottom-16 right-0 mb-2 w-80 bg-white rounded-2xl shadow-2xl overflow-hidden"
                style="max-height: 480px"
            >
                <!-- Header -->
                <div
                    class="px-5 py-4 text-white"
                    :style="{ background: gradient }"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-sm">
                                {{ workspaceName || "Live Chat" }}
                            </h3>
                            <p class="text-[11px] opacity-80 mt-0.5">
                                We typically reply quickly
                            </p>
                        </div>
                        <button
                            @click="isOpen = false"
                            class="text-white/60 hover:text-white transition-colors text-lg"
                        >
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-5">
                    <template v-if="!sent">
                        <!-- Bot message -->
                        <div class="flex gap-2 mb-4">
                            <div
                                class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs shrink-0"
                                :style="{ background: gradient }"
                            >
                                💬
                            </div>
                            <div
                                class="bg-gray-100 rounded-2xl rounded-tl-sm px-3.5 py-2.5 text-sm text-gray-700 max-w-[85%]"
                            >
                                {{ greeting }}
                            </div>
                        </div>

                        <!-- Form -->
                        <div class="space-y-2.5">
                            <input
                                v-model="msgName"
                                type="text"
                                placeholder="Your name"
                                class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:border-gray-300 focus:outline-none"
                            />
                            <input
                                v-model="msgEmail"
                                type="email"
                                placeholder="Your email"
                                class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:border-gray-300 focus:outline-none"
                            />
                            <textarea
                                v-model="msgText"
                                rows="3"
                                placeholder="Type your message..."
                                class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:border-gray-300 focus:outline-none resize-none"
                            />
                            <button
                                @click="sendMessage"
                                :disabled="!msgText.trim() || sending"
                                class="w-full py-2.5 text-white text-sm font-semibold rounded-lg transition-all disabled:opacity-50"
                                :style="{ background: gradient }"
                            >
                                {{ sending ? "Sending..." : "Send Message" }}
                            </button>
                        </div>
                    </template>

                    <!-- Sent confirmation -->
                    <template v-else>
                        <div class="text-center py-6">
                            <div class="text-4xl mb-3">✅</div>
                            <h4 class="font-semibold text-gray-800 text-sm">
                                Message sent!
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                We'll get back to you shortly.
                            </p>
                            <button
                                @click="resetForm"
                                class="mt-4 text-xs font-medium px-4 py-1.5 rounded-full transition-colors"
                                :style="{
                                    color: brandColor,
                                    border: `1px solid ${brandColor}30`,
                                }"
                            >
                                Send another message
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Powered by -->
                <div
                    class="px-5 py-2 bg-gray-50 border-t border-gray-100 text-center"
                >
                    <span class="text-[10px] text-gray-400"
                        >Powered by RevenueForge</span
                    >
                </div>
            </div>
        </transition>

        <!-- FAB Button -->
        <button
            @click="
                isOpen = !isOpen;
                showGreeting = false;
            "
            class="w-14 h-14 rounded-full text-white shadow-lg hover:shadow-xl transition-all hover:scale-105 flex items-center justify-center text-2xl"
            :style="{ background: gradient }"
        >
            <span v-if="!isOpen">💬</span>
            <span v-else class="text-xl">✕</span>
        </button>
    </div>
</template>
