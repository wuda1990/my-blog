<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-5xl mx-auto flex items-center justify-between px-6 py-4">
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                    </svg>
                    博客文章
                </h1>
                <Link
                    :href="route('posts.create')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 bg-gray-50 text-sm font-medium rounded-md shadow-sm hover:bg-gray-200 hover:border-gray-400 transition"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    发布
                </Link>
            </div>
        </header>
        <!-- Main Content -->
        <main class="max-w-5xl mx-auto px-4 py-10">
            <ul>
                <li
                    v-for="post in posts"
                    :key="post.id"
                    class="bg-white rounded-lg shadow group border border-gray-200 hover:border-gray-400 p-6 mb-5 transition"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <Link
                                :href="route('posts.show', post.id)"
                                class="text-lg font-semibold text-gray-900 group-hover:underline"
                            >
                                {{ post.title }}
                            </Link>
                            <span class="ml-3 text-xs text-gray-500">by {{ post.user.name }}</span>
                        </div>
                        <div v-if="user && post.user && user.id === post.user.id" class="flex gap-2">
                            <Link
                                :href="route('posts.edit', post.id)"
                                class="inline-flex items-center px-3 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-gray-100 text-gray-700"
                            >编辑</Link>
                            <form :action="route('posts.destroy', post.id)" method="POST" @submit.prevent="deletePost(post.id)">
                                <input type="hidden" name="_method" value="DELETE" />
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-red-50 text-red-600"
                                >删除</button>
                            </form>
                        </div>
                    </div>
                    <!-- Reddit-style comment section inside your <li v-for="post in posts" ...> -->
                    <div class="mt-3" v-if="post.comments && post.comments.length">
                        <h3 class="font-semibold text-gray-700 text-sm mb-1">最新评论</h3>
                        <ul>
                            <li
                                v-for="comment in post.comments"
                                :key="comment.id"
                                class="bg-gray-50 border border-gray-200 rounded-md p-3 mb-2 flex items-start gap-3"
                            >
                                <div class="flex-shrink-0 mt-1">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-bold text-gray-900 text-xs">{{ comment.user?.name || '匿名' }}</span>
                                        <span class="text-gray-400 text-xs">· {{ new Date(comment.created_at).toLocaleString() }}</span>
                                    </div>
                                    <div class="text-gray-800 text-sm">
                                        {{ comment.content }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li v-if="!posts.length" class="text-center text-gray-400 py-16">还没有文章，快来发布第一篇吧！</li>
            </ul>
        </main>
    </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
defineProps({ posts: Array })

const user = usePage().props.auth?.user

function deletePost(id) {
    if (confirm('确定要删除这篇文章吗？')) {
        router.delete(route('posts.destroy', id))
    }
}
</script>
