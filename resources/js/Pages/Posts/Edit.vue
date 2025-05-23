<template>
    <div>
        <h1>Edit Post</h1>
        <form @submit.prevent="submit">
            <input v-model="form.title" placeholder="Title" required />
            <input v-model="form.author" placeholder="Author" required />
            <textarea v-model="form.content" placeholder="Content" required></textarea>
            <button type="submit">Update</button>
        </form>
        <Link :href="route('posts.index')">Back</Link>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { computed } from 'vue'
const props = defineProps({ post: Object })

const form = useForm({
    title: props.post.title,
    content: props.post.content,
    author: props.post.author
})

function submit() {
    form.put(route('posts.update', props.post.id))
}
</script>
