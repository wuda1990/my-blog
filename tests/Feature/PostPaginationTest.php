<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_are_paginated_on_index_page()
    {
        // Create a user for the posts
        $user = User::factory()->create();

        // Create 25 posts to test pagination (10 per page)
        Post::factory()->count(25)->create([
            'user_id' => $user->id,
        ]);

        // Visit the posts index page
        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertInertia(function ($page) {
            $page->component('Posts/Index')
                ->has('posts.data', 10) // Should have exactly 10 posts on first page
                ->has('posts.current_page')
                ->has('posts.last_page')
                ->has('posts.total')
                ->where('posts.current_page', 1)
                ->where('posts.per_page', 10)
                ->where('posts.total', 25)
                ->where('posts.last_page', 3);
        });
    }

    public function test_posts_second_page_contains_correct_data()
    {
        // Create a user for the posts
        $user = User::factory()->create();

        // Create 25 posts to test pagination
        Post::factory()->count(25)->create([
            'user_id' => $user->id,
        ]);

        // Visit the second page
        $response = $this->get(route('posts.index', ['page' => 2]));

        $response->assertStatus(200);
        $response->assertInertia(function ($page) {
            $page->component('Posts/Index')
                ->has('posts.data', 10) // Should have exactly 10 posts on second page
                ->where('posts.current_page', 2)
                ->where('posts.per_page', 10)
                ->where('posts.total', 25);
        });
    }

    public function test_posts_last_page_contains_remaining_posts()
    {
        // Create a user for the posts
        $user = User::factory()->create();

        // Create 25 posts to test pagination
        Post::factory()->count(25)->create([
            'user_id' => $user->id,
        ]);

        // Visit the last page (page 3)
        $response = $this->get(route('posts.index', ['page' => 3]));

        $response->assertStatus(200);
        $response->assertInertia(function ($page) {
            $page->component('Posts/Index')
                ->has('posts.data', 5) // Should have 5 posts on last page (25 total, 10 per page)
                ->where('posts.current_page', 3)
                ->where('posts.per_page', 10)
                ->where('posts.total', 25);
        });
    }

    public function test_empty_posts_page_shows_no_pagination()
    {
        // Visit the posts index page with no posts
        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertInertia(function ($page) {
            $page->component('Posts/Index')
                ->has('posts.data', 0) // Should have no posts
                ->where('posts.current_page', 1)
                ->where('posts.per_page', 10)
                ->where('posts.total', 0)
                ->where('posts.last_page', 1);
        });
    }
}