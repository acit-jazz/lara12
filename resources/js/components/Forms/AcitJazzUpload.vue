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
   resfiles.value = resfiles.value.filter((f) => f.id != file.id);
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

// POPUP GALLERY
const medias = ref([])
const showGallery = ref(false);
const openGallery = () => {
  showGallery.value = true;
  getMedia()
};
const closeGallery = () => {
  showGallery.value = false;
};
const choose = (file) => {
  resfiles.value.push(file);
  emit("update:modelValue", JSON.stringify(resfiles.value));
  emit("uploaded", resfiles.value);
  closeGallery();
};
const getMedia = (url) => {
  axios
    .get(url ? url : route("media.index"))
    .then((response) => {
      medias.value = response.data;
    })
    .catch((thrown) => {
      console.log("Request canceled", thrown);
    });
};
const removeMedia = (file) => {
    const confirmed = window.confirm(`"Are you sure you want to delete this media?\nThis action is irreversible and the file cannot be recovered."`);
    if (!confirmed) return; // Batal jika user klik "Cancel"
    const formData = new FormData();
    formData.append("_token", usePage().props.csrf_token);
    axios({
      method: "POST",
      url: route("media.destroy", { media: file.id }),
      data: formData,
    })
    .then((response) => {
      resfiles.value = resfiles.value.filter((f) => f.id != file.id);
      medias.value = medias.value.data.filter((f) => f.id != file.id);
      emit("update:modelValue", resfiles.value);
      emit("uploaded", resfiles.value);
    })
    .catch((thrown) => {
      console.log("Request canceled", thrown);
    });
};
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
        class="block text-xs px-2 py-1 border-gray-300 dark:border-primary rounded- border relative"
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
        class="block text-xs px-2 py-1 border-gray-300 dark:border-primary rounded- border relative"
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
      <span v-else class="block text-xs px-2 py-1 border-gray-300 dark:border-primary rounded- border">
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
    <div v-else class="flex">
      <div  v-bind="getRootProps()" class="w-full">
        <input v-bind="getInputProps()" />
        <div class="text-center border-gray-300 dark:border-primary rounded- border py-3 px-2 cursor-pointer">
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
      <div class="min-w-[50px] flex flex-col text-center items-center justify-center bg-blue-400 text-white">
        <button class="cursor-pointer" @click="openGallery"><i class="fa fa-image leading-1"></i>  <small class="text-xs block leading-3">Media</small></button>
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


    <div v-if="showGallery">
      <div class="fixed left-0 top-0 w-full h-full z-[1000]">
        <div @click="close" class="fixed left-0 top-0 w-full h-full bg-[rgba(0,0,0,0.7)]"></div>
        <div class="w-full h-full p-10 z-10 relative">
          <div class="bg-white relative rounded-xl">
              <div class="border-b bg-black text-white border-gray-300 mb-3 px-5 py-3 flex w-full items-center">
                <h2 class="font-bold text-xl">Choose From Media Gallery</h2>
                <button
                  @click="closeGallery"
                  class="cursor-pointer ml-auto bg-black hover:bg-blue-900 w-8 h-8 text-white text-xl z-20 rounded-full"
                >
                  <i class="fa fa-xmark"></i>
                </button>
              </div>
              <div class="p-5">
                <div class="grid grid-cols-5 gap-3 w-full">
                  <div class="p-3 border-black border h-auto rounded-md" v-for="(file, i) in medias.data" :key="i">
                    <div class="w-full h-32 relative">
                      <div @click="choose(file)" class="cursor-pointer relative group">
                        <img
                          :src="asset_url + file.path"
                          class="cursor-pointer w-full h-full object-cover rounded-md"
                          :alt="file.filename"
                        />
                        <i class="opacity-0 group-hover:opacity-100  transition-all duration-75 fa fa-check text-4xl absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white"></i>
                      </div>
                      <i
                        @click="removeMedia(file)"
                        class="fa fa-trash-can hover:bg-rose-800 -right-2 -top-2 text-sm cursor-pointer absolute w-7 h-7 bg-primary text-white rounded-full !flex items-center justify-center"
                      ></i>
                    </div>
                    <h4 class="text-sm pt-2 text-center">{{ file.name }}</h4>
                  </div>
                </div>
                <div class="flex justify-between items-center mt-5">
                  <button class="cursor-pointer bg-black text-white rounded-full px-5 py-2 hover:bg-blue-900" @click="getMedia(medias.meta.links.previous)"><i class="fa fa-chevron-left"></i> Prev</button>
                  <button class="cursor-pointer bg-black text-white rounded-full px-5 py-2 hover:bg-blue-900" @click="getMedia(medias.meta.links.next)">Next <i class="fa fa-chevron-right"></i> </button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
