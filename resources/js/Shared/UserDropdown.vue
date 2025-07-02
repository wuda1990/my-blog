<template>
    <div class="relative">
        <!-- Username Button -->
        <button
            @click="toggleDropdown"
            class="flex items-center gap-2 px-3 py-2 text-sm ink-heading hover:bg-gray-50 rounded-lg transition-colors"
            :class="{ 'bg-gray-50': isOpen }"
        >
            <span>{{ user.name }}</span>
            <svg 
                class="w-4 h-4 transition-transform" 
                :class="{ 'rotate-180': isOpen }"
                fill="none" 
                stroke="currentColor" 
                stroke-width="2" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div
            v-if="isOpen"
            @click.stop
            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border ink-container z-50"
        >
            <div class="py-1">
                <Link
                    :href="route('profile.edit')"
                    class="block px-4 py-2 text-sm ink-body hover:bg-gray-50 transition-colors"
                    @click="closeDropdown"
                >
                    个人资料
                </Link>
                <hr class="my-1 border-gray-100">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                    @click="closeDropdown"
                >
                    退出登录
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    user: {
        type: Object,
        required: true
    }
})

const isOpen = ref(false)

const toggleDropdown = () => {
    isOpen.value = !isOpen.value
}

const closeDropdown = () => {
    isOpen.value = false
}

const handleClickOutside = (event) => {
    if (isOpen.value && !event.target.closest('.relative')) {
        closeDropdown()
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>