<script setup>
    import {ref, watch} from "vue";
    import debounce from "lodash/debounce.js";
    import axios from "axios";
    const asset_url = ref(import.meta.env.VITE_APP_ASSET_URL);
    const props = defineProps([
        'modelValue',
        "folder",
        "title",
        "endpoint",
    ]);

    const openLibrary = ref(false);
    const isOpenTab = ref('media');
    const medias = ref([]);
    const storeImage = ref([]);
    const selectedImage = ref({});
    const imageUpload = ref({});

    const emit = defineEmits(["update:modelValue", "uploaded"]);
    storeImage.value = props.modelValue ? props.modelValue : [];
    const selectTab = (tab) => {
        isOpenTab.value = tab
        Object.keys(imageUpload.value).length > 0 ? imageUpload.value = {} : null;
    }
    const previewImage = (image) => {
        selectedImage.value = image;
    }

    const setSelectedImage = () => {
        storeImage.value.push(selectedImage.value);
        emit("update:modelValue", storeImage.value);
        toggleOpenLibrary();
    }

    const cancelSelectedImage = () => {
        selectedImage.value = {}
        emit("update:modelValue", storeImage.value);
        toggleOpenLibrary();
    }

    const toggleOpenLibrary = () => {
        openLibrary.value = !openLibrary.value
        if (Object.keys(imageUpload.value).length > 0) {
            selectedImage.value = {}
        }
    }

    const removeImage = (index) => {
        storeImage.value.splice(index, 1);
        emit("update:modelValue", storeImage.value);
    }

    watch(imageUpload, debounce((img) => {
        if (Object.keys(imageUpload).length > 0) {
            const imageParse = img;
            medias.value.unshift(...imageParse);
        }
    }, 500))

    axios({
        method: 'GET',
        url: route('api.media.index'),
    }).then((response) => {
        medias.value = response.data;
    }).catch(err => console.log(err));


</script>

<template>
    <div>
        <div class="w-full relative glass-card cursor-pointer">
            <div class="px-2 py-3">
                <div>
                    <div v-if="storeImage.length > 0" v-for="(image, index) in storeImage" :key="index">
                        <a class="absolute -top-1 -right-1" @click="removeImage(index)">
                            <i class="fas fa-xmark-circle text-red-700 text-3xl bg-white rounded-full"></i>
                        </a>
                        <img :src="asset_url + image.path" :alt="image.name">
                    </div>
                    <div v-else class="flex items-center gap-4 px-2" @click="toggleOpenLibrary()">
                        <i class="fas fa-file text-2xl text-primary"></i>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium">Klik untuk mengunggah file</span>
                            <span class="text-xs">Maksimal file 2mb</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed bg-popup-black right-0 top-0 h-full w-full z-20" v-if="openLibrary">
            <div class="flex items-center justify-center h-full mx-2">
                <div class="relative m-auto w-full md:w-9/12 h-[750px] bg-white opacity-100 rounded-md">
                    <a @click="toggleOpenLibrary()" class="absolute -top-4 -right-4 left-auto z-10 cursor-pointer">
                        <i class="fas fa-xmark-circle text-red-700 text-2xl"></i>
                    </a>
                    <div class="flex h-full w-full overflow-hidden">
                        <div class="grow h-full m-3">
                            <div class="flex items-center justify-center w-full gap-2 text-center bg-white pb-3">
                                <span @click="selectTab('upload')" class="w-full py-2 glass-card cursor-pointer" :class="{ 'bg-active' : isOpenTab == 'upload' }">Unggah File</span>
                                <span @click="selectTab('media')"  class="w-full py-2 glass-card cursor-pointer" :class="{ 'bg-active' : isOpenTab == 'media' }">Media Library</span>
                            </div>
                            <div class="block h-full overflow-auto">
                                <div class="block mt-4">
                                    <div class="block" v-if="isOpenTab === 'upload'">
                                        <acit-jazz-upload
                                            class="mt-1 block w-full h-[250px]"
                                            :title="props.title"
                                            :folder="props.folder"
                                            :limit="1"
                                            :filetype="props.filetype"
                                            :name="props.name"
                                            v-model="imageUpload"
                                        >
                                        </acit-jazz-upload>
                                    </div>
                                    <div class="block pb-3 overflow-auto pr-3" v-else>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-14">
                                            <div class="relative w-[150px] h-[150px] md:w-full overflow-hidden cursor-pointer rounded-md" v-for="(media, index) in medias" :key="index">
                                                <div @click="previewImage(media)">
                                                    <div v-show="media._id === selectedImage._id"  class="flex justify-end absolute p-3 h-full w-full bg-black/40">
                                                        <i class="fas fa-check-circle text-green-500/100 text-2xl z-10"></i>
                                                    </div>
                                                    <img :src="media.url" :alt="media.name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-show="Object.keys(selectedImage).length > 0 && isOpenTab !== 'upload'" class="relative block w-1/3 h-full pl-1 md:block">
                            <div  class="flex justify-center items-center flex-col m-3">
                                <img class="max-w-[300px] h-fit" :src="selectedImage.url" alt="">
                                <ul class="block mt-4 text-left">
                                    <li>
                                        <span class="font-semibold">Name : </span>
                                        <span>{{ selectedImage.filename }}</span>
                                    </li>
                                    <li>
                                        <span class="font-semibold">Size : </span>
                                        <span>{{ selectedImage.size }}kb</span>
                                    </li>
                                    <li>
                                        <span class="font-semibold">Format : </span>
                                        <span>{{ selectedImage.extension }}</span>
                                    </li>
                                    <li class="pt-4 flex gap-2">
                                        <a @click="setSelectedImage()" class="bg-primary text-white py-2 px-4 rounded-md cursor-pointer">Pilih</a>
                                        <a @click="cancelSelectedImage()" class="bg-red-button-galss text-red-700 py-2 px-4 rounded-md cursor-pointer">Batal</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .glass-card {
        background: #f2f2f2;
        border-radius: 5px;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(65, 95, 228, 0.3);
    }
    .bg-red-button-galss {
        background: rgba(214, 30, 30, 0.5);
        border-radius: 10px;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(214, 30, 30, 0.3);
    }
    .bg-popup-black {
        background-color: rgba(0,0,0, 30%);
    }
    .bg-active {
        @apply bg-primary text-white
    }
</style>
