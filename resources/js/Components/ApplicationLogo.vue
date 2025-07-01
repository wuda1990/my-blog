<template>
    <img 
        :src="logoSrc" 
        :alt="logoAlt"
        :class="logoClasses"
        @error="onImageError"
    />
</template>

<script>
export default {
    name: 'ApplicationLogo',
    data() {
        return {
            imageError: false,
            logoSrc: '/images/winking_panda.png',
            logoAlt: 'Winking Panda Logo'
        }
    },
    computed: {
        logoClasses() {
            // Preserve the classes passed from parent, but remove SVG-specific ones
            const classes = this.$attrs.class || '';
            return `${classes} ink-logo object-contain`.replace(/fill-current|text-gray-500/g, '').trim();
        }
    },
    methods: {
        onImageError() {
            if (!this.imageError) {
                // First fallback: try SVG version
                this.imageError = true;
                this.logoSrc = '/images/winking_panda.svg';
                this.logoAlt = 'Winking Panda Logo (SVG)';
            } else {
                // Second fallback: inline SVG with panda emoji
                this.logoSrc = 'data:image/svg+xml;base64,' + btoa(`
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="100" fill="#faf8f2"/>
                        <circle cx="50" cy="50" r="35" fill="#8b7355" stroke="#2a2a2a" stroke-width="2" opacity="0.8"/>
                        <text x="50" y="60" text-anchor="middle" font-family="serif" font-size="24" fill="#2a2a2a">🐼</text>
                    </svg>
                `);
                this.logoAlt = 'Panda Logo (Fallback)';
            }
        }
    }
}
</script>

<style scoped>
.ink-logo {
    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.1));
    transition: all 0.3s ease;
}

.ink-logo:hover {
    filter: drop-shadow(2px 2px 8px rgba(0, 0, 0, 0.2));
    transform: scale(1.05);
}
</style>
