<script setup>
import {watch, onMounted, ref } from 'vue';

const props = defineProps(['modelValue','source']);

const emit = defineEmits(['update:modelValue']);

const color = ref(props.modelValue);
watch(() => props.source, (after, before) => {
    if(after){
        color.value = after;
        emit('update:modelValue', after)
    }
});
function changeInput(data)  {
    if(data){
        color.value = data.target.value;
        emit('update:modelValue', data.target.value)
    }else{
        emit('update:modelValue', color.value )
    }
};

</script>

<template>
    <div class="relative w-full">
         <input type="text" @change="changeInput"  v-model="color" class="border-gray-300 w-full focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
         <input type="color" class="w-10 h-10 absolute right-0 appearance-none cursor-pointer !border-0 !p-1 top-0" :value="modelValue" @input="changeInput"  ref="input">
    </div>
</template>
