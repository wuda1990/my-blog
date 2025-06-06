<template>
    <div class="min-h-screen bg-gray-100">
        <div class="max-w-2xl mx-auto mt-12 mb-16 bg-white rounded-lg shadow border border-gray-200 p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                创建文章
            </h2>
            <form @submit.prevent="submit">
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-1" for="title">标题</label>
                    <input v-model="form.title" id="title" type="text"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-gray-50"
                           required>
                </div>
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-1" for="author">作者</label>
                    <input v-model="form.author" id="author" type="text"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-gray-50"
                           required>
                </div>
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-1" for="content">内容</label>
                    <textarea v-model="form.content" id="content" rows="8"
                              class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-gray-50"
                              required></textarea>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-gray-900 text-white rounded-md shadow hover:bg-gray-700 transition font-semibold">
                        发布
                    </button>
                    <Link :href="route('posts.index')"
                          class="text-gray-500 hover:text-gray-900 underline text-sm"
                    >返回</Link>
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
