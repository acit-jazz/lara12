<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 367.92 202.08">
    <g id="Layer_1-2" data-name="Layer 1">
        <g>
        <circle class="cls-3" cx="307.64" cy="60.27" r="60.27"/>
        <polygon class="cls-2" points="96.13 143.6 153.13 202.08 219.94 132.98 219.83 0 96.13 143.6"/>
        <polygon class="cls-3" points="210.1 143.58 153.11 202.08 87.82 135.06 87.82 0 210.1 143.58"/>
        <path class="cls-1" d="M87.82,202.08H0V50.05C0,22.41,22.41,0,50.05,0h37.77v202.08Z"/>
        <path class="cls-1" d="M257.6,202.08h-37.77V0s87.82,0,87.82,0v152.03c0,27.64-22.41,50.05-50.05,50.05Z"/>
        </g>
    </g>
    </svg>
</template>
<style scoped>
    .cls-1 {
    fill: #6e65c9;
    }

    .cls-2 {
    fill: #2c3493;
    }

    .cls-3 {
    fill: #425dbc;
    }
</style>