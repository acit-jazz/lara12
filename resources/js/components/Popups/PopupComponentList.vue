<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
const emit = defineEmits(["close", "choosed"]);
const close = () => {
  emit("close", true);
};
const choosed = (comp) => {
  emit("choosed", comp);
};
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

const components = ref(usePage().props.components);

if(props.except != 'All'){
    components.value = components.value.filter(c => c.value != props.except);
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
          <h2 class="font-bold text-xl mb-5">Choose Component</h2>
          <button
            @click="close"
            class="absolute top-2 right-2 bg-white w-10 h-10 text-black text-3xl z-20 rounded-xl"
          >
            <i class="fa fa-xmark"></i>
          </button>

          <div class="grid grid-cols-3 gap-3 w-full">
            <APrimaryButton
              v-for="(comp, i) in components"
              :key="i"
              @click="choosed(comp)"
              class="cursor-pointer w-full block text-center py-2 px-3 rounded-xl justify-center border-2"
            >
              {{ comp.title }}
            </APrimaryButton>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
