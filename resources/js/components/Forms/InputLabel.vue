<script setup>
defineProps(['value']);
</script>

<template>
    <label class="block font-medium text-sm  text-primary mb-2" v-if="value">
        {{ value }}
    </label>
    <label class="block font-medium text-sm  text-primary mb-2" v-else>
        <slot />
    </label>
</template>
