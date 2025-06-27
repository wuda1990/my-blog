<template>
    <div class="min-h-screen bg-gray-100">
        <div class="max-w-2xl mx-auto mt-12 mb-16 bg-white rounded-lg shadow border border-gray-200 p-8">
            <!-- Post header -->
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-900 break-words">{{ post.title }}</h1>
                <Link :href="route('posts.index')" class="text-gray-500 hover:text-gray-900 underline text-sm">返回</Link>
            </div>
            <div class="mb-5 text-gray-400 text-sm flex items-center gap-4">
                <span>作者：{{ post.author }}</span>
                <span>发布者：{{ post.user.name }}</span>
                <span>·</span>
                <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
            </div>
            <article class="prose max-w-none text-gray-800">
                {{ post.content }}
            </article>
            <div v-if="user && post.user && user.id === post.user.id" class="mt-8 flex gap-4">
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
            <!-- Comments section -->
            <section class="mt-12">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">评论</h2>
                <form @submit.prevent="addComment" class="mb-6 flex flex-col md:flex-row gap-2 items-stretch">
                    <input
                        v-model="commentForm.content"
                        class="flex-1 border border-gray-300 rounded px-3 py-2 bg-gray-50"
                        placeholder="添加评论…"
                        required
                    >
                    <button type="submit"
                            class="bg-gray-900 text-white rounded px-4 py-2 hover:bg-gray-700 transition font-semibold"
                    >发布</button>
                </form>
                <ul>
                    <li v-for="comment in post.comments" :key="comment.id" class="mb-4 p-4 bg-gray-50 rounded border border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-semibold">{{ comment.user?.name || '匿名' }}</span>
                            <span class="text-xs text-gray-400">{{ formatDate(comment.created_at) }}</span>
                        </div>
                        <div class="mt-1 text-gray-800">{{ comment.content }}</div>
                        <div v-if="user && comment.user && user.id === comment.user.id" class="mt-2 flex gap-2 text-sm">
                            <button @click="editComment(comment)" class="text-gray-500 hover:text-gray-900 underline">编辑</button>
                            <button @click="deleteComment(comment)" class="text-red-500 hover:text-red-700 underline">删除</button>
                        </div>
                        <!-- 编辑评论表单 -->
                        <form v-if="editingId === comment.id" @submit.prevent="updateComment(comment)" class="mt-2 flex gap-2">
                            <input v-model="editForm.content" class="flex-1 border border-gray-300 rounded px-2 py-1" required>
                            <button type="submit" class="bg-gray-900 text-white rounded px-3 py-1 hover:bg-gray-700 text-xs">保存</button>
                            <button @click="cancelEdit" type="button" class="text-gray-500 underline text-xs">取消</button>
                        </form>
                    </li>
                    <li v-if="!post.comments || !post.comments.length" class="text-center text-gray-400 py-4">暂无评论，快来抢沙发吧！</li>
                </ul>
            </section>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    post: Object,
})

const user = usePage().props.auth?.user

function deletePost(id) {
    if (confirm('确定要删除这篇文章吗？')) {
        router.delete(route('posts.destroy', id))
    }
}

// 评论功能
const commentForm = ref({
    content: '',
    post_id: props.post.id,
})

// 新增评论
function addComment() {
    if (!user) {
        // 跳转到登录页，保留当前页面地址用于登录后跳转回来
        window.location.href = route('login', { redirect: window.location.pathname });
        return
    }
    router.post(route('comments.store'), commentForm.value, {
        onSuccess: () => {
            commentForm.value.content = ''
        }
    })
}

// 编辑功能
const editingId = ref(null)
const editForm = ref({ content: '' })

function editComment(comment) {
    if (!user || !comment.user || user.id !== comment.user.id) return
    editingId.value = comment.id
    editForm.value = { content: comment.content }
}
function cancelEdit() {
    editingId.value = null
}
function updateComment(comment) {
    router.put(route('comments.update', comment.id), editForm.value, {
        onSuccess: () => {
            editingId.value = null
        }
    })
}
function deleteComment(comment) {
    if (!user || !comment.user || user.id !== comment.user.id) return
    if (confirm('确定要删除这条评论吗？')) {
        router.delete(route('comments.destroy', comment.id))
    }
}
function formatDate(str) {
    if (!str) return ''
    const d = new Date(str)
    return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')} ${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}`
}
</script>
