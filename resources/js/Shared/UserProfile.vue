<template>
    <div class="ink-container p-6">
        <div class="flex flex-col items-center space-y-4">
            <!-- Profile Picture -->
            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center ink-splash">
                <!-- Simple SVG avatar placeholder -->
                <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </div>

            <!-- User Name -->
            <div class="text-center">
                <h3 class="text-lg font-semibold ink-heading">
                    {{ user.name }}
                </h3>
                <p class="text-sm ink-comment-meta">
                    {{ user.email }}
                </p>
            </div>

            <!-- Logout Button -->
            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="w-full ink-button text-sm py-2 px-4 bg-red-50 border border-red-200 text-red-700 hover:bg-red-100 transition-colors rounded"
            >
                退出登录
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>