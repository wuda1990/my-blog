<template>
    <div class="ink-page">
        <div class="max-w-2xl mx-auto mt-12 mb-16 ink-container p-8">
            <!-- Post header -->
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold ink-heading break-words ink-brushstroke">{{ post.title }}</h1>
                <Link :href="route('posts.index')" class="ink-link text-sm">返回</Link>
            </div>
            <div class="mb-5 ink-comment-meta flex items-center gap-4">
                <span>作者：{{ post.author }}</span>
                <span>发布者：{{ post.user.name }}</span>
                <span>·</span>
                <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
            </div>
            <article class="ink-body">
               <pre class="whitespace-pre-wrap font-serif">{{post.content}}</pre>
            </article>
            <div v-if="user && post.user && user.id === post.user.id" class="mt-8 flex gap-4">
                <Link
                    :href="route('posts.edit', post.id)"
                    class="ink-comment-action border border-gray-300 rounded px-4 py-1.5"
                >编辑</Link>
                <form :action="route('posts.destroy', post.id)" method="POST" @submit.prevent="deletePost(post.id)">
                    <input type="hidden" name="_method" value="DELETE" />
                    <button
                        type="submit"
                        class="ink-comment-action border border-red-300 rounded px-4 py-1.5 text-red-600 hover:bg-red-50"
                    >删除</button>
                </form>
            </div>
            <!-- Comments section -->
            <section class="mt-12">
                <h2 class="text-lg font-semibold ink-heading mb-4 ink-brushstroke">评论</h2>
                <form @submit.prevent="addComment" class="mb-6 flex flex-col md:flex-row gap-2 items-stretch">
                    <input
                        v-model="commentForm.content"
                        class="flex-1 ink-input"
                        placeholder="添加评论…"
                        required
                    >
                    <button type="submit" class="ink-button">发布</button>
                </form>
                <ul class="space-y-3">
                    <li v-for="comment in post.comments" :key="comment.id" class="ink-comment">
                        <div class="ink-comment-meta">
                            <span class="ink-comment-author">{{ comment.user?.name || '匿名' }}</span>
                            <span class="text-xs">{{ formatDate(comment.created_at) }}</span>
                        </div>
                        <div class="ink-comment-content">{{ comment.content }}</div>
                        <div v-if="user && comment.user && user.id === comment.user.id" class="ink-comment-actions">
                            <button @click="editComment(comment)" class="ink-comment-action">编辑</button>
                            <button @click="deleteComment(comment)" class="ink-comment-action text-red-600 hover:text-red-700">删除</button>
                        </div>
                        <!-- 编辑评论表单 -->
                        <form v-if="editingId === comment.id" @submit.prevent="updateComment(comment)" class="mt-3 flex gap-2">
                            <input v-model="editForm.content" class="flex-1 ink-input text-sm" required>
                            <button type="submit" class="ink-button text-xs px-3 py-1">保存</button>
                            <button @click="cancelEdit" type="button" class="ink-comment-action text-xs">取消</button>
                        </form>
                    </li>
                    <li v-if="!post.comments || !post.comments.length" class="text-center py-8">
                        <div class="ink-container p-6">
                            <p class="ink-body">暂无评论，快来抢沙发吧！</p>
                        </div>
                    </li>
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
