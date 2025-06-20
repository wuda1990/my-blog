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
            <article class="prose max-w-none text-gray-800 whitespace-normal break-words">
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
            <section class="mt-10">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Comments</h2>
                <form @submit.prevent="addComment" class="mb-6 flex gap-2">
                    <input v-model="commentForm.content" placeholder="Add a comment…" class="flex-1 border border-gray-300 rounded px-3 py-2 bg-gray-50" required>
                    <button type="submit" class="bg-gray-900 text-white rounded px-4 py-2 hover:bg-gray-700">Post</button>
                </form>
                <ul>
                    <li v-for="comment in post.comments" :key="comment.id" class="mb-4 p-4 bg-gray-50 rounded border border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-semibold">{{ comment.author }}</span>
                            <span class="text-xs text-gray-400">{{ new Date(comment.created_at).toLocaleString() }}</span>
                        </div>
                        <div class="mt-1 text-gray-800">{{ comment.content }}</div>
                        <div class="mt-2 flex gap-2 text-sm">
                            <button @click="editComment(comment)" class="text-gray-500 hover:text-gray-900 underline">Edit</button>
                            <button @click="deleteComment(comment)" class="text-red-500 hover:text-red-700 underline">Delete</button>
                        </div>
                        <!-- Edit form (if editing this comment) -->
                        <form v-if="editingId === comment.id" @submit.prevent="updateComment(comment)" class="mt-2 flex gap-2">
                            <input v-model="editForm.content" class="flex-1 border border-gray-300 rounded px-2 py-1" required>
                            <button type="submit" class="bg-gray-900 text-white rounded px-3 py-1 hover:bg-gray-700 text-xs">Save</button>
                            <button @click="cancelEdit" class="text-gray-500 underline text-xs">Cancel</button>
                        </form>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
const props = defineProps({ post: Object })

function deletePost(id) {
    if (confirm('确定要删除这篇文章吗？')) {
        router.delete(route('posts.destroy', id))
    }
}

const commentForm = ref({
    author: '',
    content: '',
    post_id: props.post.id,
})

function addComment() {
    router.post(route('comments.store'), commentForm.value, {
        onSuccess: () => {
            commentForm.value.author = ''
            commentForm.value.content = ''
        }
    })
}

const editingId = ref(null)
const editForm = ref({ author: '', content: '' })

function editComment(comment) {
    editingId.value = comment.id
    editForm.value = { author: comment.author, content: comment.content }
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
    if (confirm('Delete this comment?')) {
        router.delete(route('comments.destroy', comment.id))
    }
}
</script>
