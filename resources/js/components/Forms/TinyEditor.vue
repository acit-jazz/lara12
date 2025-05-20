<template>
    <div>
      <QuillEditor
        :name="name"
        :id="name"
        v-model="content"
        :options="options"
        :modules="modules"
        @textChange="update"
        ref="editor"
        class="!h-[200px]"
        :toolbar="'#'+toolbar"
      >

    <template #toolbar>
      <div :id="toolbar">
        <span class="ql-formats">
            <button class="ql-bold"></button>
            <button class="ql-italic"></button>
            <button class="ql-underline"></button>
        </span>
        <span class="ql-formats">
            <select class="ql-color"></select>
            <select class="ql-background"></select>
        </span>
        <span class="ql-formats">
            <button class="ql-script" value="sub"></button>
            <button class="ql-script" value="super"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-header" value="1"></button>
            <button class="ql-header" value="2"></button>
            <button class="ql-blockquote"></button>
            <button class="ql-code-block"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-list" value="ordered"></button>
            <button class="ql-list" value="bullet"></button>
            <button class="ql-indent" value="-1"></button>
            <button class="ql-indent" value="+1"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-direction" value="rtl"></button>
            <select class="ql-align"></select>
        </span>
        <span class="ql-formats">
            <button class="ql-link"></button>
            <button class="ql-image"></button>
            <button class="ql-video"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-clean"></button>
        </span>
      </div>
    </template>
    </QuillEditor>
    </div>
  </template>

<script setup>
import { usePage } from "@inertiajs/inertia-vue3";
import { onMounted, ref, watch } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import ImageUploader from 'quill-image-uploader';
import axios from 'axios';

// Register the ImageUploader module
  // Mendefinisikan props
  const props = defineProps({
    modelValue: {
      type: String,
      default: ''
    },
    toolbar:{
      type: String,
      default: 'my-toolbar'
    },
    is_price: Boolean,
    name: String,
    placeholder: String,
    height: [String,Number]
  });

  // Mendefinisikan emits
  const editor = ref(null);
  const emit = defineEmits(['update:modelValue']);

  // Mengatur opsi untuk QuillEditor
  const options = {
    placeholder: props.placeholder,
    theme: 'snow'
  };
  const modules = ref({
  name: 'imageUploader',
  module: ImageUploader,
  options: {
    upload: file => {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append("_token", usePage().props.value.csrf_token);
        formData.append("name", props.name);
        formData.append("folder", 'content');
        formData.append("file[]", file);

        axios.post(route('media.store'), formData)
        .then(res => {
          console.log(res.data.data[0].url)
          resolve(res.data.data[0].url);
        })
        .catch(err => {
          reject("Upload failed");
          console.error("Error:", err)
        })
      })
    }
  }
})

  // Mengatur nilai content berdasarkan modelValue dari props
  const content = ref(props.modelValue);

  // Fungsi update untuk mengemit event update:modelValue
  const update = (data) => {
     emit('update:modelValue', editor.value.getHTML());
  };
    // Watch perubahan pada modelValue dan update content editor
    watch(() => props.modelValue, (newValue) => {
    if (editor.value && newValue !== editor.value.getHTML()) {
        editor.value.setHTML(newValue);
    }
    });

    // Inisialisasi editor saat mounted
    onMounted(() => {
        if (editor.value) {
            editor.value.setHTML(props.modelValue);
        }
    });

  </script>
