<script setup>
    const props = defineProps({
        message: String
    })
</script>
<template>
    <div>
        <div class="loader"></div>
        <span v-if="message" class="font-semibold text-sm mt-5">{{ message }}</span>
    </div>
</template>
<style scoped>
.loader {
    margin: auto;
    width: 20px;
    padding: 5px;
    aspect-ratio: 1;
    border-radius: 50%;
    background: #2b329e;
    --_m:
        conic-gradient(#0000 10%,#000),
        linear-gradient(#000 0 0) content-box;
    -webkit-mask: var(--_m);
    mask: var(--_m);
    -webkit-mask-composite: source-out;
    mask-composite: subtract;
    animation: l3 1s infinite linear;
}
@keyframes l3 {to{transform: rotate(1turn)}}
</style>
