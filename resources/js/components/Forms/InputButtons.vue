<script>
import "@he-tree/vue/style/default.css";
import { reactive, defineComponent, onMounted } from "vue";

export default defineComponent({
  props:['modelValue'],
  data() {
    return {
        rows: [],
        buttons: null,
        showButton: true,
        types: [
          { title: 'Self', value:'self' },
          { title: 'Redirect', value:'redirect' },
          { title: 'Popup', value:'popup' },
        ],
        styles: [
          { title: 'Primary', value:'primary' },
          { title: 'Outline', value:'outline' },
        ]
    };
  },
  created() {
    this.rows = this.modelValue;
  },
  methods:{
    addRow(){
        this.$refs.tree.add({ label: null, value:null });
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
            return obj.label != null;
        });
        this.$emit('update:modelValue', this.buttons);
    },
    uploaded(stat){
        this.save();
    }
  },
});
</script>

<template>
  <div @mouseleave="save" id="input-buttons" class="text-left mb-5">
    <Draggable v-model="rows" ref="tree" virtualization
        :maxLevel="1"
        :watermark="false">
      <template #default="{ node, stat }">
        <span v-if="stat.children.length" @click="stat.open = !stat.open">
          {{ stat.open ? "-" : "+" }}
        </span>
        <div class="flex w-full mb-2 max-h-[55px]">
            <div class="flex w-full gap-3">
                <input type="text" class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" placeholder="Label" v-model="node.label"
                    @change="save" />

                <input type="text" class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" placeholder="Value" v-model="node.value"
                    @change="save" />
            </div>

            <a @click="removeRow(stat)" class="block text-center py-3 px-3 justify-center">
                <i class="fas text-red-400 mr-2 text-sm fa-trash"></i>
            </a>
        </div>
      </template>
    </Draggable>
    <SecondaryButton @click="addRow"  type="button" class="justify-center "> <i class="fas text-white-400 mr-2 text-sm fa-plus"></i> Add Option</SecondaryButton>
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
