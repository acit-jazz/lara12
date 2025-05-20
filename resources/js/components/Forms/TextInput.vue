<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue']);

const emit = defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
const changeInput = (event) =>{
    if(event.target.getAttribute('type') == 'datetime-local'){
        emit('update:modelValue', event.target.value.replace('T',' '))
    }else{
        emit('update:modelValue', event.target.value)
    }
}
</script>

<template>
    <input class="w-full py-2 px-3 border border-gray-300 rounded-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:bg-gray-100 disabled:border-gray-300 shadow-sm" :value="modelValue" @input="changeInput" ref="input">
</template>
