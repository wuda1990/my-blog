<template>
    <div>
        <h1>{{ post.title }}</h1>
        <p><em>by {{ post.author }}</em></p>
        <div>{{ post.content }}</div>
        <Link :href="route('posts.index')">Back</Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ post: Object })
</script>
