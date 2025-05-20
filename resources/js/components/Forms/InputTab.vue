<script setup>
import "@he-tree/vue/style/default.css";
import { ref, defineAsyncComponent, onMounted } from "vue";
import SecondaryButton from '@/Admin/Components/Buttons/SecondaryButton.vue';
const PopupComponentList = defineAsyncComponent(
    () => import('@/Admin/Components/Popups/PopupComponentList.vue')
)
const props = defineProps(['modelValue']);

const getId = () =>{
        return (Math.random() + 1).toString(36).substring(2);
}
const emit = defineEmits(['update:modelValue']);
const showPopup = ref(false)
const rows = ref([]);
const defaultdata = { id:getId(), data:null, component: {title:null,value:null}, title:null };
const useComponent = ref(null);
const tabtree = ref(null);
const save = () => {
    emit('update:modelValue', tabtree.value.modelValue);
}
const removeRow = (stat) => {
    if(rows.value.length > 0){
        tabtree.value.remove(stat);
    }
    save();
}
const addRow = () =>{
    tabtree.value.add({ id:getId(), data:null, component: {title:null,value:null}, title:null });
    save();
}
const choosedComponent = (comp,index) => {
    const hasData = tabtree.value.modelValue.findIndex( e => e.id === index);
    if(hasData >= 0){
        console.log('update');
        tabtree.value.modelValue[hasData].component = comp;
    }
    useComponent.value = comp.value;
    showPopup.value = 'popup-'+comp.value;
    save();
}
onMounted(() => {
rows.value = props.modelValue ?  (props.modelValue.length == 0 ?  [defaultdata] : props.modelValue) : [defaultdata];
})
</script>

<template>
  <div @mouseleave="save">
    <Draggable v-model="rows" ref="tabtree" virtualization
        :maxLevel="1"
        class="mb-2"
        :watermark="false">
      <template #default="{ node, stat }">
        <div class="flex w-full mb-2 ">
            <a  class="block text-center py-3 px-3 justify-center">
                <i class="fas text-grey-400 mr-2 text-sm fa-vector-square cursor-move"></i>
            </a>
            <div class="flex w-full mr-4 items-center">
                <div class="w-full flex gap-5 items-center">
                    <input type="text"
                    class="w-6/12 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block "
                    placeholder="Tab Title" v-model="node.title"
                    @change="save" />
                    <Badge v-if="node.component.value" class="bg-gray-400 text-white h-fit">{{ node.component.title }} Component</Badge>
                   <AOutlineButton v-else @click="showPopup = 'popup-add-'+node.id"  type="button" class="justify-center w-5/12 "> <i class="fas text-white-400 mr-2 text-sm fa-plus"></i> Add New Component</AOutlineButton>
                    <PopupComponentList v-if="showPopup == 'popup-add-'+node.id" @close="showPopup = false" @choosed="choosedComponent($event,node.id)" except="InputTab"></PopupComponentList>
                </div>
                <div class="fixed left-0 top-0 w-full h-full bg-[rgba(0,0,0,0.7)] z-[1000]" v-if="showPopup == 'popup-'+node.id">
                    <div class="w-full h-full  justify-center max-h-screen overflow-scroll">
                        <div class="flex flex-wrap w-full p-5">
                            <div class="pt-5 px-5 bg-white max-w-[700px] w-[90%] text-center mx-auto rounded-md relative" >
                                <div class="border-b  pb-3  mb-5 flex gap-5 ">
                                    <h3 class="font-bold">{{ node.component.title}} Component</h3>
                                </div>
                                <keep-alive>
                                    <component :is="node.component.value" v-model="node.data"></component>
                                </keep-alive>
                                <div class="border-t mt-5 py-5 flex gap-5 justify-end">
                                    <AOutlineButton @click="showPopup = false"  class="justify-center cursor-pointer"> <i class="fas text-white-400 mr-2 text-sm fa-xmark"></i> Close</AOutlineButton>
                                    <APrimaryButton @click="showPopup = false"  class="justify-center cursor-pointer"> <i class="fas text-white-400 mr-2 text-sm fa-check"></i> Save</APrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <a @click="removeRow(stat)" class="block text-center py-3 px-3 justify-center cursor-pointer">
                <i class="fas text-red-400 mr-2 text-sm fa-trash"></i>
            </a>
            <a @click="showPopup = 'popup-'+node.id" class="block text-center py-3 px-3 justify-center cursor-pointer">
                <i class="fas text-red-400 mr-2 text-sm fa-pencil"></i>
            </a>
        </div>
      </template>
    </Draggable>
    <div class="flex w-full">
    <SecondaryButton @click="addRow" type="button" class="justify-center mr-auto"> <i class="fas text-white-400 mr-2 text-sm fa-plus"></i> Add New Tab</SecondaryButton>
    </div>
  </div>
</template>
<style lang="scss">
#input-buttons {
  .vtlist.he-tabtree {
    overflow:unset !important;
  }
  .inputButtonSelect .vs__dropdown-toggle {
    height: 100%;
  }
}

</style>
