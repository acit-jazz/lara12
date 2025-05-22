<script setup>
import { useDropzone } from "vue3-dropzone";
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";

const props = defineProps([
  "modelValue",
  "folder",
  "title",
  "endpoint",
  "label",
]);

const emit = defineEmits(["update:modelValue", "uploaded"]);
const input = ref(null);
const asset_url = ref(usePage().props.env.asset_url);
const app_url = ref(usePage().props.env.app_url);
let resfiles = ref([]);
const preview = ref(null);
const showPreview = ref(false);
const altimage = ref('');
const changePreview = (data) => {
  preview.value = data;
  showPreview.value = true;
};
const close = () => {
  showPreview.value = false;
};
if(props.modelValue == undefined){
    resfiles.value = [];
}else if(props.modelValue == null){
    resfiles.value = [];
}else if(props.modelValue == app_url.value+'/images/telin-meta.jpg'){
    resfiles.value = [];
}else if(props.modelValue.length == 0){
    resfiles.value = [];
}else{
    try {
        resfiles.value = JSON.parse(props.modelValue);
        altimage.value = resfiles.value[0].altimage ?? '';
    } catch (error) {
        resfiles.value = [];
    }
}
const saveFile = (files) => {
  const formData = new FormData();
  formData.append("_token", usePage().props.csrf_token);
  formData.append("name", props.title);
  formData.append("folder", props.folder);
  formData.append("altimage",altimage.value)
  for (var x = 0; x < files.length; x++) {
    // files[x].url = "storage/" + url;
    formData.append("file[]", files[x]);
  }
  axios({
    method: "POST",
    url: props.endpoint ?? route("media.store"),
    data: formData,
    headers: {
      "Content-Type": "multipart/form-data",
    },
    onprocessfileprogress: (e) => {
      console.log(e.lengthComputable, e.loaded, e.total);
    },
  })
    .then((response) => {
      response.data.data[0].url = "/storage" + response.data.data[0].url;
      resfiles.value = response.data.data;
      emit("update:modelValue", JSON.stringify(resfiles.value));
      emit("uploaded", resfiles.value);
    })
    .catch((thrown) => {
      if (axios.isCancel(thrown)) {
        console.log("Request canceled", thrown.message);
      } else {
        // handle error
      }
    });
};
const onDrop = (acceptFiles, rejectReasons) => {
  saveFile(acceptFiles);
};

const remove = (file) => {
  const formData = new FormData();
  formData.append("_token", usePage().props.csrf_token);
  axios({
    method: "POST",
    url: route("media.destroy", { media: file.id }),
    data: formData,
  })
    .then((response) => {
      resfiles.value = resfiles.value.filter((f) => f.id != file.id);
      emit("update:modelValue", resfiles.value);
      emit("uploaded", resfiles.value);
    })
    .catch((thrown) => {
      console.log("Request canceled", thrown);
    });
};

const updateText = () => {
    resfiles.value[0].altimage = altimage.value;
    console.log('resfiles.value',resfiles.value)
    emit("update:modelValue", JSON.stringify(resfiles.value));
    emit("uploaded", resfiles.value);
}
//26,214,400

const options = ref({
    maxSize: 26214400,
    onDrop,
    accept: '.jpg,.jpeg,.png,.svg,.pdf,.zip,.gif,.mp4,.webp,.wav,.mp3,.webm',
})
const { getRootProps, getInputProps, ...rest } = useDropzone(options.value);
</script>

<template>
  <div>
    <div v-if="resfiles.length > 0" v-for="(file, i) in resfiles" :key="i">
      <span
        class="block text-xs px-2 py-1 border relative"
        v-if="file.extension == 'pdf'"
      >
        <APrimaryButton :href="asset_url + file.path" target="_blank">{{
          file.filename
        }}</APrimaryButton>
        <i
          @click="remove(file)"
          class="fa fa-xmark -right-2 -top-2 cursor-pointer absolute w-4 h-4 bg-primary text-white rounded-full !flex items-center justify-center"
        ></i>
      </span>
      <span
        class="block text-xs px-2 py-1 border-gray-300 dark:border-primary rounded-md border relative"
        v-else-if="file.extension == 'mp4'"
      >
         <video width="320" height="240" controls>
         <source :src="asset_url + file.path" type="video/mp4">
        Error Message
        </video>
        <i
          @click="remove(file)"
          class="fa fa-xmark -right-2 -top-2 cursor-pointer absolute w-4 h-4 bg-primary text-white rounded-full !flex items-center justify-center"
        ></i>
      </span>
      <span
        class="block text-xs px-2 py-1 border-gray-300 dark:border-primary rounded-md border relative"
        v-else-if="file.extension == 'wav' || file.extension == 'mp3'"
      >
       <audio controls>
        <source :src="asset_url + file.path" type="audio/wav">
        Your browser does not support the audio element.
      </audio>
        <i
          @click="remove(file)"
          class="fa fa-xmark -right-2 -top-2 cursor-pointer absolute w-4 h-4 bg-primary text-white rounded-full !flex items-center justify-center"
        ></i>
      </span>
      <span v-else class="block text-xs px-2 py-1 border-gray-300 dark:border-primary rounded-md border">
        <div class="relative max-w-[30%]  mx-auto">
            <img
            @click="changePreview(file)"
            :src="asset_url + file.path"
            class="cursor-pointer"
            :alt="file.filename"
          />

          <i
            @click="remove(file)"
            class="fa fa-xmark -right-2 -top-2 cursor-pointer absolute w-4 h-4 bg-primary text-white rounded-full !flex items-center justify-center"
          ></i>
        </div>
        <TextInput @change="updateText" placeholder="Alt image" type="text" v-model="altimage" class="mt-2"></TextInput>
      </span>
    </div>
    <div v-else v-bind="getRootProps()">
      <input v-bind="getInputProps()" />
      <div class="text-center border-gray-300 dark:border-primary rounded-md border py-3 px-2 cursor-pointer">
        <svg
          class="w-7 mx-auto"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 640 512"
        >
          <path
            class="fill-primary"
            d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128H144zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39V392c0 13.3 10.7 24 24 24s24-10.7 24-24V257.9l39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z"
          />
        </svg>
        <span class="text-xs text-primary"> {{ label ?? "Drag & Drop your files" }}</span>
      </div>
    </div>
    <div class="fixed left-0 top-0 w-full h-full z-[1000]" v-if="showPreview">
      <div
        class="fixed left-0 top-0 w-full h-full bg-[rgba(0,0,0,0.7)]"
        @click="close"
      ></div>
      <div
        class="w-full h-full flex items-center justify-center max-w-[90%] mx-auto"
      >
        <div class="relative w-full">
          <img :src="preview.url" alt="" class="w-full" />
          <i
            @click="close()"
            class="fa fa-xmark -right-5 -top-5 cursor-pointer absolute w-10 h-10 bg-primary text-white rounded-full !flex items-center justify-center"
          ></i>
        </div>
      </div>
    </div>
  </div>
</template>
