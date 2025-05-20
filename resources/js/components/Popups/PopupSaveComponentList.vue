<script setup>
import axios from "axios";
import { ref } from "vue";
const emit = defineEmits(["close", "choosed"]);
const close = () => {
  emit("close", true);
};
const success = ref(false);
const choosed = (comp) => {
  emit("choosed", comp);
};
let props = defineProps({
  data: Array,
});
const form =  ref({
                title: '',
                contents:props.data,
            });
const save = ()=>{
    axios.post(route('component.store'),form.value).then(res => {
        success.value = true;
        setInterval(() => {
          success.value = false;
          emit("close", true);
        }, 2000);
    })
}
</script>

<template>
  <div class="fixed left-0 top-0 w-full h-full z-[1000]">
    <div
      @click="close"
      class="fixed left-0 top-0 w-full h-full bg-[rgba(0,0,0,0.7)]"
    ></div>
    <div class="w-full h-full flex items-center justify-center">
      <div class="flex flex-wrap w-full p-5">
        <div
          class="p-10 bg-white max-w-[700px] w-[90%] text-center mx-auto rounded-md relative"
        >
          <button
            @click="close"
            class="absolute top-2 right-2 bg-white w-10 h-10 text-black text-3xl z-20 rounded-xl"
          >
            <i class="fa fa-xmark"></i>
          </button>

          <div class="w-full" v-if="!success">
             <h2 class="font-bold text-xl mb-5">Save Component List</h2>
            <div class="block mt-4">
                <InputLabel class="text-left" for="title" value="List Name" />
                <TextInput type="text" placeholder="List Name" v-model="form.title" />
            </div>
            <div class="flex w-full mt-4">
                <SecondaryButton  @click="save" type="button" as="button" class="justify-center ">
                     Save Component List
                </SecondaryButton>
                <AOutlineButton  @click="close" type="button" as="button" class="ml-auto">
                     Cancel
                </AOutlineButton>
            </div>
          </div>
          <div v-else>
                <div class="bg-green-700 w-14 h-14 rounded-full mx-auto flex items-center justify-center mb-4"><i class="text-white fa fa-check"></i></div>
                <h3 class="font-medium"> Component List Has Been Saved</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
