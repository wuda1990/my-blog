<template>
    <div class="min-h-screen bg-gray-100">
        <div class="max-w-2xl mx-auto mt-12 mb-16 bg-white rounded-lg shadow border border-gray-200 p-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900 break-words">{{ post.title }}</h1>
                <Link :href="route('posts.index')" class="text-gray-500 hover:text-gray-900 underline text-sm">返回</Link>
            </div>
            <div class="mb-5 text-gray-400 text-sm flex items-center gap-4">
                <span>作者：{{ post.author }}</span>
                <span>·</span>
                <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
            </div>
            <article class="prose max-w-none text-gray-800">
                {{ post.content }}
            </article>
            <div class="mt-8 flex gap-4">
                <Link
                    :href="route('posts.edit', post.id)"
                    class="inline-flex items-center px-4 py-1.5 bg-gray-50 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 transition text-sm font-medium"
                >编辑</Link>
                <form :action="route('posts.destroy', post.id)" method="POST" @submit.prevent="deletePost(post.id)">
                    <input type="hidden" name="_method" value="DELETE" />
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-1.5 bg-white border border-gray-300 text-red-600 rounded hover:bg-red-50 transition text-sm font-medium"
                    >删除</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
const props = defineProps({ post: Object })

function deletePost(id) {
    if (confirm('确定要删除这篇文章吗？')) {
        router.delete(route('posts.destroy', id))
    }
}
</script>
