<template>
    <div class="max-w-2xl mx-auto mt-12 mb-16 bg-white rounded-2xl p-8 shadow-xl">
        <h1 class="text-4xl font-extrabold text-center text-indigo-600 mb-8 flex items-center justify-center gap-2">
            心灵的诗篇
        </h1>
        <Link
            :href="route('posts.create')"
            class="block w-fit mr-auto mb-8 bg-gradient-to-r from-indigo-500 to-blue-400 text-white px-6 py-2 rounded-md font-semibold text-lg shadow transition hover:scale-105 hover:from-blue-400 hover:to-indigo-500"
        >
            ➕ 发布
        </Link>
        <ul class="divide-y divide-gray-200">
            <li
                v-for="post in posts"
                :key="post.id"
                class="flex flex-col sm:flex-row sm:items-center justify-between py-5 px-2 bg-indigo-50 hover:bg-indigo-100 rounded-lg mb-4 transition"
            >
                <div class="flex-1">
                    <Link
                        :href="route('posts.show', post.id)"
                        class="text-xl font-semibold text-indigo-700 hover:underline transition"
                    >
                        {{ post.title }}
                    </Link>
                    <span class="ml-2 text-sm text-gray-500 italic">by {{ post.author }}</span>
                </div>
                <div class="flex gap-3 mt-3 sm:mt-0">
                    <Link
                        :href="route('posts.edit', post.id)"
                        class="inline-block px-4 py-1 bg-white border border-indigo-300 text-indigo-600 rounded hover:bg-indigo-100 transition font-medium"
                    >
                        ✏️ Edit
                    </Link>
                    <form :action="route('posts.destroy', post.id)" method="POST" @submit.prevent="deletePost(post.id)">
                        <input type="hidden" name="_method" value="DELETE" />
                        <button
                            type="submit"
                            class="inline-block px-4 py-1 bg-white border border-red-300 text-red-500 rounded hover:bg-red-100 transition font-medium"
                        >
                            🗑️ Delete
                        </button>
                    </form>
                </div>
            </li>
            <li v-if="!posts.length" class="text-center text-gray-400 py-8">No posts yet. Be the first to write one!</li>
        </ul>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
defineProps({ posts: Array })

function deletePost(id) {
    if (confirm('Are you sure you want to delete this post?')) {
        router.delete(route('posts.destroy', id))
    }
}
</script>
