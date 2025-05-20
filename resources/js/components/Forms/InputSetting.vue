<script>
import "@he-tree/vue/style/default.css";
import SecondaryButton from '@/Admin/Components/Buttons/SecondaryButton.vue';
import InputSelect from '@/Admin/Components/Forms/InputSelect.vue';
import { reactive, defineComponent, onMounted } from "vue";
import VueSelect from "vue-select";
import axios from "axios";

export default defineComponent({
  props:['modelValue','locale','title','static_locales'],
  components: {  SecondaryButton, InputSelect, VueSelect },
  data() {
    return {
        rows: null,
        buttons: null,
        showButton: true,
        pages:[],
    };
  },
  created() {
    this.rows = this.modelValue ? JSON.parse(this.modelValue) : [{ key: null, value:null, }];
    this.getPage();
  },
  methods:{
    addRow(){
        this.$refs.tree.add({ key: null, value:null, });
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
            return obj.key != null;
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
        <div class="flex w-full mb-1 max-h-[55px]">
            <a class="block text-center py-3 px-3 justify-center">
                <i class="fas text-grey-400 mr-2 text-sm fa-vector-square cursor-move"></i>
            </a>
            <div class="flex w-full gap-2" v-if="pages.length > 0">

            <InputSelect
                class="!w-5/12"
                placeholder="-----Choose Key-----"
                v-model="node.key"
                :options="static_locales"
                label="title"
                store="value"
                @change="save"
            />
                <input type="text" class="w-7/12 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block" placeholder="Value" v-model="node.value"
                    @change="save" />
            </div>

        </div>
      </template>
    </Draggable>
    <SecondaryButton @click="addRow"  type="button" class="justify-center mt-5 "> <i class="fas text-white-400text-sm fa-plus"></i></SecondaryButton>
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
