<script setup>
import axios from "axios";
import { ref } from "vue";
const emit = defineEmits(["close", "choosed"]);
const close = () => {
  emit("close", true);
};
const choosed = (comp) => {
  emit("choosed", comp);
};
const components = ref([]);

const getComponent = () =>{
    axios.get(route('component.get')).then( res => {
         components.value = res.data;
    });
}
const remove = (comp) => {
    axios.post(route('component.forceDelete', { component:comp })).then(res => {
         components.value = res.data;
    });
}
getComponent();
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
          <h2 class="font-bold text-xl mb-5">Choose Component From List</h2>
          <button
            @click="close"
            class="absolute top-2 right-2 bg-white w-10 h-10 text-black text-3xl z-20 rounded-xl"
          >
            <i class="fa fa-xmark"></i>
          </button>

          <div class="w-full" v-if="components.length > 0">
                <div class="block w-full overflow-auto max-h-[400px]">
                <table class="items-center w-full bg-transparent border-collapse">
                    <thead>
                    <tr class="hidden lg:table-row">
                        <Th>Title</Th>
                        <Th></Th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(comp,index) in components" :key="index" class="hover:bg-gray-100 cursor-pointer relative py-3 block lg:py-0 lg:table-row border-t lg:border-0">
                        <Td>
                            <strong class="block lg:hidden">Title</strong>
                            <span>{{comp.title}}</span>
                        </Td>
                        <Td width="100">
                            <APrimaryButton
                            @click="choosed(comp)"
                            class="px-3 py-2 !bg-blue-500 rounded-none rounded-l-md"
                            >
                            Choose
                            </APrimaryButton>
                            <APrimaryButton v-tooltip="'Destroy'" class="px-3 py-2 bg-red-500 rounded-none rounded-r-md" @click="remove(comp)">
                                Remove
                            </APrimaryButton>
                        </Td>
                    </tr>
                    </tbody>
                </table>
                </div>
          </div>
          <div v-else>
                <h3>Component list not available.</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
