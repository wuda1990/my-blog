<template>
    <div class="ink-page">
        <div class="max-w-2xl mx-auto mt-12 mb-16 ink-container p-8">
            <h2 class="text-2xl font-semibold ink-heading mb-6 flex items-center gap-2 ink-brushstroke">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11v2h2l7.293-7.293a1 1 0 00-1.414-1.414L9 11zm-6 9V7a2 2 0 012-2h7"></path>
                </svg>
                编辑文章
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
                </div>
                
                <!-- 已上传文件列表 -->
                <div v-if="props.post.files && props.post.files.length > 0" class="mb-5">
                    <h3 class="block ink-body font-medium mb-3">已上传文件</h3>
                    <div class="space-y-2">
                        <div v-for="file in props.post.files" :key="file.id" class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm">{{ file.name }}</span>
                                <span class="text-xs text-gray-500">({{ formatFileSize(file.size) }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="downloadFile(file)" class="text-sm ink-link">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </button>
                                <button type="button" @click="deleteFile(file)" class="text-sm text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <button type="submit" class="ink-button">
                        保存
                    </button>
                    <Link :href="route('posts.index')" class="ink-link text-sm">返回</Link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
const props = defineProps({ post: Object })

const form = useForm({
    title: props.post.title,
    author: props.post.author,
    content: props.post.content
})

const files = ref([])

function handleFileUpload(event) {
    files.value = [...event.target.files]
    // 立即上传文件
    uploadFiles()
}

function uploadFiles() {
    if (files.value.length === 0) return
    
    const formData = new FormData()
    files.value.forEach(file => {
        formData.append('file', file)
    })
    
    fetch(route('files.store', props.post.id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // 重新加载页面以显示新上传的文件
        window.location.reload()
    })
    .catch(error => {
        console.error('文件上传失败:', error)
    })
}

function deleteFile(file) {
    if (confirm('确定要删除这个文件吗？')) {
        fetch(route('files.destroy', file.id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // 重新加载页面以更新文件列表
            window.location.reload()
        })
        .catch(error => {
            console.error('文件删除失败:', error)
        })
    }
}

function downloadFile(file) {
    window.location.href = route('files.download', file.id)
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

function submit() {
    form.put(route('posts.update', props.post.id))
}
</script>
