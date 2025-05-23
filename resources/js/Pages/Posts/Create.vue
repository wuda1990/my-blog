<template>
    <div>
        <h1>Create Post</h1>
        <form @submit.prevent="submit">
            <input v-model="form.title" placeholder="Title" required />
            <input v-model="form.author" placeholder="Author" required />
            <textarea v-model="form.content" placeholder="Content" required></textarea>
            <button type="submit">Create</button>
        </form>
        <Link :href="route('posts.index')">Back</Link>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    content: '',
    author: ''
})

function submit() {
    form.post(route('posts.store'))
}
</script>
