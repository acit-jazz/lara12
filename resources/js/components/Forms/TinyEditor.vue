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

    <div v-if="showGallery" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
      <div class="bg-white p-4 rounded-lg w-[90%] max-w-md">
        <h2 class="text-lg font-semibold mb-2">Pilih Gambar</h2>
        <div class="grid grid-cols-3 gap-2 max-h-64 overflow-y-auto">
          <img v-for="(img, index) in galleryImages" 
              :key="index" 
              :src="img" 
              class="cursor-pointer border hover:border-blue-500"
              @click="insertImage(img)" />
        </div>
        <button class="mt-4 bg-red-500 text-white px-3 py-1 rounded" @click="showGallery = false">Tutup</button>
      </div>
    </div>
    </div>
  </template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref, watch } from 'vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { QuillEditor, Quill } from '@vueup/vue-quill';
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
    name: {
      type: String,
      default: 'content'
    },
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
      console.log('uploading file:', file);
        const formData = new FormData();
        formData.append("_token", usePage().props.csrf_token);
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

            // Override tombol ql-image
            // const editorInstance = editor.value.getQuill()
            // console.log(editorInstance);
            // const toolbar = editorInstance.getModule('toolbar')
            // toolbar.addHandler('image', () => {
            //   showGallery.value = true
            // })
        }
    });

    const showGallery = ref(false)

    // // Galeri dummy
    // const galleryImages = ref([
    //   'https://ik.imagekit.io/pz3rg7qms/Titip/WhatsApp%20Image%202025-05-22%20at%2014.16.34_60ea1e90.jpg?updatedAt=1747898297421',
    //   'https://ik.imagekit.io/pz3rg7qms/Titip/WhatsApp%20Image%202025-05-22%20at%2014.16.34_60ea1e90.jpg?updatedAt=1747898297421',
    //   'https://ik.imagekit.io/pz3rg7qms/Titip/WhatsApp%20Image%202025-05-22%20at%2014.16.34_60ea1e90.jpg?updatedAt=1747898297421'
    // ])

    // // Fungsi insert image ke editor
    // const insertImage = (url) => {
    //   const range = editor.value.getQuill().getSelection()
    //   if (range) {
    //     editor.value.getQuill().insertEmbed(range.index, 'image', url)
    //     editor.value.getQuill().setSelection(range.index + 1)
    //   }
    //   showGallery.value = false
    // }
  </script>
