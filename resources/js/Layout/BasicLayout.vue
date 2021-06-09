<template>
    <div class="fixed top-0 left-0 w-1/2 h-full bg-gray-50" aria-hidden="true" />
    <div class="fixed top-0 right-0 w-1/2 h-full bg-gray-50" aria-hidden="true" />
    <div class="relative min-h-screen flex flex-col">
        <div>
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        components: {},
        data() {
            return {}
        },
        methods: {}
    }
</script>
