<script setup>
import { Link, router } from '@inertiajs/vue3'
defineProps({ posts: Array })

function deletePost(id) {
    router.delete(route('posts.destroy', id))
}
</script>

<template>
    <div>
        <h1>Blog Posts</h1>
        <Link :href="route('posts.create')">Create New Post</Link>
        <ul>
            <li v-for="post in posts" :key="post.id">
                <Link :href="route('posts.show', post.id)">{{ post.title }}</Link>
                <span> by {{ post.author }}</span>
                <Link :href="route('posts.edit', post.id)">Edit</Link>
                <form :action="route('posts.destroy', post.id)" method="POST" style="display:inline" @submit.prevent="deletePost(post.id)">
                    <input type="hidden" name="_method" value="DELETE" />
                    <button type="submit">Delete</button>
                </form>
            </li>
        </ul>
    </div>
</template>


