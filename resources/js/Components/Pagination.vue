<template>
    <nav v-if="pagination.last_page > 1" class="flex items-center justify-center space-x-2 mt-8" aria-label="分页导航">
        <!-- First Page -->
        <Link
            v-if="showFirstPage"
            :href="pagination.first_page_url"
            class="ink-pagination-link"
            aria-label="第一页"
        >
            ≪
        </Link>
        
        <!-- Previous Page -->
        <Link
            v-if="pagination.prev_page_url"
            :href="pagination.prev_page_url"
            class="ink-pagination-link"
            aria-label="上一页"
        >
            ‹
        </Link>
        
        <!-- Page Numbers with Sliding Window -->
        <template v-for="page in visiblePages" :key="page">
            <Link
                v-if="page !== '...'"
                :href="getPageUrl(page)"
                :class="[
                    'ink-pagination-link',
                    page === pagination.current_page ? 'ink-pagination-active' : ''
                ]"
                :aria-label="`第${page}页`"
                :aria-current="page === pagination.current_page ? 'page' : undefined"
            >
                {{ page }}
            </Link>
            <span v-else class="ink-pagination-ellipsis">...</span>
        </template>
        
        <!-- Next Page -->
        <Link
            v-if="pagination.next_page_url"
            :href="pagination.next_page_url"
            class="ink-pagination-link"
            aria-label="下一页"
        >
            ›
        </Link>
        
        <!-- Last Page -->
        <Link
            v-if="showLastPage"
            :href="pagination.last_page_url"
            class="ink-pagination-link"
            aria-label="最后一页"
        >
            ≫
        </Link>
    </nav>
    
    <!-- Pagination Info -->
    <div v-if="pagination.last_page > 1" class="text-center mt-4 text-sm ink-comment-meta">
        显示第 {{ pagination.from || 0 }} 到 {{ pagination.to || 0 }} 条，共 {{ pagination.total }} 条结果
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    pagination: {
        type: Object,
        required: true
    }
})

// Generate sliding window of page numbers
const visiblePages = computed(() => {
    const current = props.pagination.current_page
    const last = props.pagination.last_page
    const windowSize = 5 // Show 5 page numbers at a time
    
    let start = Math.max(1, current - Math.floor(windowSize / 2))
    let end = Math.min(last, start + windowSize - 1)
    
    // Adjust start if we're near the end
    if (end - start + 1 < windowSize) {
        start = Math.max(1, end - windowSize + 1)
    }
    
    const pages = []
    
    // Add ellipsis at the beginning if needed
    if (start > 1) {
        pages.push(1)
        if (start > 2) {
            pages.push('...')
        }
    }
    
    // Add the sliding window pages
    for (let i = start; i <= end; i++) {
        pages.push(i)
    }
    
    // Add ellipsis at the end if needed
    if (end < last) {
        if (end < last - 1) {
            pages.push('...')
        }
        pages.push(last)
    }
    
    return pages
})

// Show first page button when current page > 3
const showFirstPage = computed(() => {
    return props.pagination.current_page > 3 && props.pagination.last_page > 5
})

// Show last page button when current page < last_page - 2
const showLastPage = computed(() => {
    return props.pagination.current_page < props.pagination.last_page - 2 && props.pagination.last_page > 5
})

// Generate URL for a specific page
const getPageUrl = (page) => {
    const url = new URL(props.pagination.path)
    url.searchParams.set('page', page)
    return url.toString()
}
</script>

<style scoped>
.ink-pagination-link {
    @apply inline-flex items-center justify-center px-3 py-2 text-sm font-medium leading-5 transition duration-150 ease-in-out;
    background: linear-gradient(135deg, var(--ink-paper) 0%, #f8f6f0 100%);
    border: 1px solid var(--ink-border);
    border-radius: 6px;
    color: var(--ink-gray);
    min-width: 2.5rem;
    text-decoration: none;
}

.ink-pagination-link:hover {
    border-color: var(--ink-earth);
    color: var(--ink-earth);
    transform: translateY(-1px);
    box-shadow: 0 2px 6px var(--ink-shadow);
}

.ink-pagination-active {
    background: linear-gradient(135deg, var(--ink-earth) 0%, var(--ink-earth-light) 100%);
    border-color: var(--ink-earth);
    color: var(--ink-paper);
}

.ink-pagination-active:hover {
    background: linear-gradient(135deg, var(--ink-earth-light) 0%, var(--ink-earth) 100%);
    color: var(--ink-paper);
}

.ink-pagination-ellipsis {
    @apply px-3 py-2 text-sm;
    color: var(--ink-light-gray);
}

/* Responsive design */
@media (max-width: 640px) {
    .ink-pagination-link {
        @apply px-2 py-1 text-xs;
        min-width: 2rem;
    }
    
    .ink-pagination-ellipsis {
        @apply px-2 py-1 text-xs;
    }
}
</style>