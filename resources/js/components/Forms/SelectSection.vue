<script setup>
import "@he-tree/vue/style/default.css";
import { ref, defineAsyncComponent, onMounted } from "vue";
const PopupComponentList = defineAsyncComponent(
    () => import('@/Components/Popups/PopupComponentList.vue')
)
const PopupSaveComponentList = defineAsyncComponent(
    () => import('@/Components/Popups/PopupSaveComponentList.vue')
)
const PopupLoadComponentList = defineAsyncComponent(
    () => import('@/Components/Popups/PopupLoadComponentList.vue')
)
const props = defineProps(['modelValue']);

const emit = defineEmits(['update:modelValue','onsave']);
const showPopup = ref(false)
const rows = ref([]);
const useComponent = ref({id:null});
const tree = ref(null);
const isEdit = ref(false);
const save = () => {
    emit('update:modelValue', tree.value.modelValue);
}
const savedata = () => {
    const hasData = rows.value.findIndex( e => e.id === useComponent.value.id);
    if(hasData >= 0){
        console.log('update');
        tree.value.modelValue[hasData] = useComponent.value;
    }else{
        console.log('new');
        tree.value.add(useComponent.value);
    }
    emit('update:modelValue', tree.value.modelValue);
    emit('onsave', tree.value.modelValue);
    isEdit.value = false;
    showPopup.value = false;
}
const removeRow = (stat) => {
    if(rows.value.length > 0){
        tree.value.remove(stat);
    }
    save();
}
const edit = (node) => {
    useComponent.value = node;
    showPopup.value = useComponent.value.id;
    isEdit.value = true;
    save();
}
const close = () => {
    useComponent.value = {id:null};
    if(isEdit.value){
     isEdit.value = false;
     showPopup.value = false;
    }else{
     showPopup.value = 'component-list';
    }
    save();
}
const getId = () =>{
        return (Math.random() + 1).toString(36).substring(2);
}
const choosedComponent = (comp) => {
    useComponent.value = {id:'section-'+getId(), data:null, type: comp.value, title:comp.title, show:true };

    showPopup.value = useComponent.value.id;
    save();
}
const choosedList = (comp) => {
    rows.value = comp.contents;
    showPopup.value = false;
}
onMounted(() => {
    rows.value = props.modelValue ?  (props.modelValue.length == 0 ?  [] : props.modelValue) : [];
})
</script>

<template>
  <div @mouseleave="save">
    <Draggable v-model="rows" ref="tree" virtualization
        :maxLevel="1"
        class="mb-2"
        :watermark="false">
      <template #default="{ node, stat }">
        <span v-if="stat.children.length" @click="stat.open = !stat.open">
          {{ stat.open ? "-" : "+" }}
        </span>
        <div class="flex w-full mb-2 border-b">
            <a  class="block text-center py-3 px-3 justify-center">
                <i class="fas text-grey-400 mr-2 text-sm fa-vector-square cursor-move"></i>
            </a>
            <div class="flex w-full mr-4 items-center">
                <div class="w-full m-2 text-sm">
                    <strong class="font-semibold">{{ node.title }}</strong> Component
                </div>
            </div>
            <div class="flex items-center ml-auto">
                <label class="relative inline-flex items-center cursor-pointer mr-2">
                    <input type="checkbox" @change="save()" v-model="node.show" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
                <a @click="removeRow(stat)" class="block text-center py-3 px-3 justify-center cursor-pointer">
                    <i class="fas text-red-400 text-sm fa-trash"></i>
                </a>
                <a @click="edit(node)" class="block text-center py-3 px-3 justify-center cursor-pointer">
                    <i class="fas text-secondary text-sm fa-pencil"></i>
                </a>
            </div>
        </div>
      </template>
    </Draggable>
    <div class="flex">
        <AOutlineButton  @click="showPopup = 'component-list'"  type="button" class="justify-center "> <i class="fas mr-2 text-sm fa-plus"></i> Add New Component</AOutlineButton>
    </div>
    <PopupComponentList v-if="showPopup == 'component-list'" @close="showPopup = false" @choosed="choosedComponent"></PopupComponentList>

    <div class="fixed left-0 top-0 w-full h-full  z-[1000]" v-if="showPopup == useComponent.id">
        <div  class="fixed left-0 top-0 w-full h-full bg-[rgba(0,0,0,0.7)]"></div>
        <div class="w-full h-full  justify-center max-h-screen overflow-scroll">
            <div class="flex flex-wrap w-full p-5">
                <div class="pt-5 px-5 bg-white max-w-[700px] w-[90%] text-center mx-auto rounded-md relative" >
                    <div class="border-b  pb-3  mb-5 flex gap-5 ">
                        <h3 class="font-bold">{{ useComponent.title }} Component</h3>
                    </div>
                    <keep-alive>
                        <component :is="useComponent.type" v-model="useComponent.data"></component>
                    </keep-alive>
                    <div class="border-t mt-5 py-5 flex gap-5 justify-end">
                        <AOutlineButton @click="close"  class="justify-center cursor-pointer"> <i class="fas mr-2 text-sm fa-xmark"></i> Close</AOutlineButton>
                        <APrimaryButton @click="savedata"  class="justify-center cursor-pointer"> <i class="fas mr-2 text-sm fa-check"></i> Save</APrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>
<style lang="scss">
#input-buttons {
  .vtlist.he-tree {
    overflow:unset !important;
  }
  .inputButtonSelect .vs__dropdown-toggle {
    height: 100%;
  }
}

</style>
