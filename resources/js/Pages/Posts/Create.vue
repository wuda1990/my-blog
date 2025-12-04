<template>
    <div class="ink-page">
        <div class="max-w-2xl mx-auto mt-12 mb-16 ink-container p-8">
            <h2 class="text-2xl font-semibold ink-heading mb-6 flex items-center gap-2 ink-brushstroke">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                创建文章
            </h2>
            <form @submit.prevent="submit">
                <div class="mb-5">
                    <label class="block ink-body font-medium mb-1" for="title">标题</label>
                    <input v-model="form.title" id="title" type="text"
                           class="w-full ink-input"
                           required>
                </div>
                <div class="mb-5">
                    <label class="block ink-body font-medium mb-1" for="author">作者</label>
                    <input v-model="form.author" id="author" type="text"
                           class="w-full ink-input"
                           required>
                </div>
                <div class="mb-5">
                    <label class="block ink-body font-medium mb-1" for="content">内容</label>
                    <textarea v-model="form.content" id="content" rows="8"
                              class="w-full ink-input"
                              required></textarea>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="ink-button">
                        发布
                    </button>
                    <Link :href="route('posts.index')" class="ink-link text-sm">返回</Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    author: '',
    content: ''
})

function submit() {
    form.post(route('posts.store'))
}
</script>
