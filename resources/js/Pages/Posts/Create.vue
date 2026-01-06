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
                <div class="mb-5">
                    <label class="block ink-body font-medium mb-1">上传文件</label>
                    <input type="file" @change="handleFileUpload" multiple
                           class="w-full ink-input"
                           accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif">
                    <div class="mt-2 text-sm text-gray-500">
                        支持的文件类型：PDF, DOC, DOCX, TXT, JPG, JPEG, PNG, GIF (最大10MB)
                    </div>
                    <div v-if="selectedFiles.length > 0" class="mt-2">
                        <div class="text-sm text-gray-600">已选择 {{ selectedFiles.length }} 个文件</div>
                        <div class="mt-1 space-y-1">
                            <div v-for="(file, index) in selectedFiles" :key="index" class="text-xs text-gray-500">
                                {{ file.name }} ({{ formatFileSize(file.size) }})
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="uploading" class="mb-5 text-sm text-gray-600">
                    文件上传中，请稍候...
                </div>
                <div v-if="error" class="mb-5 text-sm text-red-500">
                    {{ error }}
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="ink-button" :disabled="uploading">
                        {{ uploading ? '发布中...' : '发布' }}
                    </button>
                    <Link :href="route('posts.index')" class="ink-link text-sm" :disabled="uploading">返回</Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
    title: '',
    author: '',
    content: ''
})

const selectedFiles = ref([])
const uploading = ref(false)
const error = ref('')

function handleFileUpload(event) {
    selectedFiles.value = [...event.target.files]
    error.value = ''
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

async function submit() {
    if (selectedFiles.value.length > 0) {
        uploading.value = true
        error.value = ''
    }
    
    try {
        // 创建文章
        const response = await form.post(route('posts.store'), {
            preserveScroll: true,
            onSuccess: () => {
                // 文章创建成功，继续执行
            }
        })
        
        // 获取当前URL中的文章ID
        const url = window.location.href
        const postId = url.split('/').pop()
        
        // 上传文件
        if (selectedFiles.value.length > 0) {
            await uploadFiles(postId)
        }
        
        // 重置状态
        uploading.value = false
        selectedFiles.value = []
    } catch (err) {
        uploading.value = false
        error.value = '操作失败，请重试'
        console.error('创建文章或上传文件失败:', err)
    }
}

async function uploadFiles(postId) {
    const uploadPromises = selectedFiles.value.map(file => {
        const formData = new FormData()
        formData.append('file', file)
        
        return window.axios.post(`/posts/${postId}/files`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
    })
    
    await Promise.all(uploadPromises)
}
</script>
