<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    isLoading: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <button :disabled="isLoading" :type="type" class="gap-3 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:shadow-outline-blue transition ease-in-out duration-150">
        <SpinnerLoader v-if="isLoading" />
        <slot />
    </button>
</template>
