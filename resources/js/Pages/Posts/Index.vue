<template>
    <div class="ink-page">
        <!-- Header -->
        <header class="ink-header">
            <meta name="description" content="这是一家专注于古诗文的网站，提供丰富的古代诗歌和文学作品。">
            <div class="max-w-5xl mx-auto flex items-center justify-between px-6 py-4">
                <h1 class="text-2xl sm:text-3xl ink-heading flex items-center gap-2 ink-brushstroke">
<!--                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">-->
<!--                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>-->
<!--                    </svg>-->
                    <Link :href="route('dashboard')">
                        <ApplicationLogo
                            class="block h-9 w-auto fill-current text-gray-800"
                        />
                    </Link>
                    熊猫韵律
                </h1>
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('posts.create')"
                        class="ink-button text-sm"
                    >
<!--                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">-->
<!--                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>-->
<!--                    </svg>-->
                        发布
                    </Link>
                    <UserDropdown v-if="user" :user="user" />
                    <Link
                        v-else
                        :href="route('login')"
                        class="ink-button text-sm bg-gray-50 border border-gray-200"
                    >
                        登录
                    </Link>
                </div>
            </div>
        </header>
        <!-- Main Content -->
        <main class="max-w-5xl mx-auto px-4 py-10">
            <ul>
                <li
                    v-for="post in posts"
                    :key="post.id"
                    class="ink-post ink-splash"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <Link
                                :href="route('posts.show', post.id)"
                                class="text-lg font-semibold ink-link ink-heading"
                            >
                                {{ post.title }}
                            </Link>
                            <span class="ml-3 text-xs ink-comment-meta">{{post.author}}</span>
                        </div>
                        <div v-if="user && post.user && user.id === post.user.id" class="flex gap-2">
                            <Link
                                :href="route('posts.edit', post.id)"
                                class="ink-comment-action border border-gray-300 rounded px-3 py-1"
                            >编辑</Link>
                            <form :action="route('posts.destroy', post.id)" method="POST" @submit.prevent="deletePost(post.id)">
                                <input type="hidden" name="_method" value="DELETE" />
                                <button
                                    type="submit"
                                    class="ink-comment-action border border-red-300 rounded px-3 py-1 text-red-600 hover:bg-red-50"
                                >删除</button>
                            </form>
                        </div>
                    </div>
                    <article class="ink-body">
                        <pre class="whitespace-pre-wrap font-serif">{{post.content}}</pre>
                    </article>
                </li>
                <li v-if="!posts.length" class="text-center py-16">
                    <div class="ink-container p-8">
                        <p class="ink-body text-lg">还没有文章，快来发布第一篇吧！</p>
                    </div>
                </li>
            </ul>
        </main>
    </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import UserDropdown from "@/Shared/UserDropdown.vue";
defineProps({ posts: Array })

const user = usePage().props.auth?.user

function deletePost(id) {
    if (confirm('确定要删除这篇文章吗？')) {
        router.delete(route('posts.destroy', id))
    }
}
</script>
