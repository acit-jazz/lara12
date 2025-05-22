<script setup>
const props = defineProps(['href', 'active']);

</script>

<template>
    <Link :href="href" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent 
        rounded-md text-xs font-medium text-white capitalize  hover:bg-gray-700 active:bg-gray-900 
        focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot />
    </Link>
</template>
