<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
const emit = defineEmits(["close", "choosed"]);
let props = defineProps({
  title: String,
  message: String,
  url: String,
  btn: [String],
  except:{
    default:'All',
    type:String
  }
});

const closeGallery = () => {
  showGallery.value = false;
  emit("close", true);
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
</template>
