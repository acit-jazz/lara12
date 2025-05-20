<script>
import "@he-tree/vue/style/default.css";
import SecondaryButton from '@/Admin/Components/Buttons/SecondaryButton.vue';
import InputSelect from '@/Admin/Components/Forms/InputSelect.vue';
import { reactive, defineComponent, onMounted } from "vue";
import VueSelect from "vue-select";
import axios from "axios";

export default defineComponent({
  props:['modelValue','locale','title'],
  components: {  SecondaryButton, InputSelect, VueSelect },
  data() {
    return {
        rows: null,
        buttons: null,
        showButton: true,
        types: [
          { title: 'Self', value:'_self' },
          { title: 'New Tab', value:'_blank' },
          { title: 'Click', value:'click' },
          { title: 'Title', value:'title' },
        ],
        colors: [
          { title: 'Red', value:'red' },
          { title: 'Blue', value:'blue' },
          { title: 'Gray', value:'gray' },
        ],
        pages:[],
    };
  },
  created() {
    this.rows = this.modelValue ? JSON.parse(this.modelValue) : [{ title: null, url:null, page_id:null, color:null, image:null, type: '_self' }];
    this.getPage();
  },
  methods:{
    addRow(){
        this.$refs.tree.add({ title: null, url:null, page_id:null, color:null, image:null, type: '_self' });
        this.save();
    },
    removeRow(stat){
        if(this.rows.length > 0){
          this.$refs.tree.remove(stat);
        }
        this.save();
    },
    save(){
        this.buttons = this.$refs.tree.getData().filter(function( obj ) {
            return obj.title != null;
        });
        this.$emit('update:modelValue', JSON.stringify(this.buttons));
    },
    getPage(){
        axios.get(route('page.menu')).then( res => {
            this.pages = res.data;
        }).catch({

        });
    },
    uploaded(stat){
        this.save();
    }
  },
});
</script>

<template>
  <div @mouseleave="save" id="input-buttons">
    <Draggable v-model="rows" ref="tree" virtualization
        :maxLevel="4"
        :watermark="false">
      <template #default="{ node, stat }">
        <div class="flex w-full mb-2 max-h-[55px]">
            <a class="block text-center py-3 px-3 justify-center">
                <i class="fas text-grey-400 mr-2 text-sm fa-vector-square cursor-move"></i>
            </a>
            <div class="flex w-full mr-4" v-if="pages.length > 0">
                <input type="text" class="w-full m-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" placeholder="Title" v-model="node.title"
                    @change="save" />
                <InputSelectMenu v-if="node.type == '_self'" classname="inputButtonSelect w-full m-2 !border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block"
                  placeholder="Page" v-model="node.page_id"  :options="pages" label="title" />
                <input v-else type="text" class="w-full m-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                    placeholder="Url" v-model="node.url"
                    @change="save" />
                 <InputSelect v-if="stat.level == 1" classname="inputButtonSelect w-full m-2 !border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block"
                  placeholder="Color Theme" v-model="node.color" :options="colors" label="title" store="value"/>
                 <InputSelect @change="(e)=> e == 'click' ? node.url = '#' : null" classname="inputButtonSelect w-full m-2 !border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block"
                  placeholder="Type Button" v-model="node.type" :options="types" label="title" store="value"/>
            </div>

            <a @click="removeRow(stat)" class="block text-center py-3 px-3 mb-auto cursor-pointer justify-center">
                <i class="fas text-red-400 text-sm fa-trash"></i>
            </a>
        </div>
      </template>
    </Draggable>
    <SecondaryButton @click="addRow"  type="button" class="justify-center "> <i class="fas text-white-400text-sm fa-plus"></i></SecondaryButton>
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
