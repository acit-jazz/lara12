<script setup>
defineProps({
    href: {
        type: String,
    },
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <Link v-if="href"  class="inline-flex items-center px-4 py-2 bg-primary border
                        border-transparent rounded-md font-semibold text-xs text-white dark:text-black
                        hover:bg-indigo-400 active:bg-gray-900
                        focus:outline-none focus:border-gray-900 focus:shadow-outline-gray
                        transition ease-in-out duration-150">
        <slot />
    </Link>
    <button v-else  class="inline-flex items-center px-4 py-2 bg-primary border
                        border-transparent rounded-md font-semibold text-xs text-white dark:text-black
                        hover:bg-indigo-400 active:bg-gray-900
                        focus:outline-none focus:border-gray-900 focus:shadow-outline-gray
                        transition ease-in-out duration-150">
        <slot />
    </button>
</template>
