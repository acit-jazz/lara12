<script setup>
import { ref,onMounted } from 'vue';
const emit = defineEmits(['update:modelValue']);
const props = defineProps(['modelValue']);
const formdata = ref(null);
const save = () => {
        emit('update:modelValue', formdata.value);
    }
    formdata.value = props.modelValue ?   props.modelValue : {
                title: '',
                summary: '',
                placeholder: '',
            };
</script>

<template>
    <div class="block w-full overflow-x-auto">
        <div class="block">
            <InputLabel class="text-left" for="title" value="Title" />
            <TextInput type="text"  @change="save" class="mt-1 block w-full" v-model="formdata.title"  />
        </div>

        <div class="block mt-4">
            <InputLabel class="text-left" for="placeholder" value="Placeholder" />
            <TextInput type="text"  @change="save" class="mt-1 block w-full" v-model="formdata.placeholder"  />
        </div>

        <div class="block mt-4">
            <InputLabel class="text-left" for="summary" value="Summary" />
            <TextAreaInput  @change="save" class="mt-1 block w-full" v-model="formdata.summary"  />
        </div>
    </div>
</template>
