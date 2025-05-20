<script>
import { defineComponent } from "vue";
export default defineComponent({
  props:['modelValue'],
  data() {
    return {
        rows :[],
        default : [{ title: null, summary: null, image: null}],
    };
  },
  created() {
    this.rows = this.modelValue ?  this.modelValue : this.default;
  },

  methods:{
    addRow(){
        this.$refs.tree.add({ title: null, summary: null, image: null });
        this.save();
    },
    removeRow(stat){
        if(this.rows.length > 1){
          this.$refs.tree.remove(stat);
        }
        this.save();
    },
    save(){
        this.$emit('update:modelValue', this.$refs.tree.modelValue);
    },
    uploaded(stat){
        this.save();
    }
  },
});
</script>

<template>
 <div @mouseleave="save">
    <Draggable v-model="rows" ref="tree" virtualization
        :maxLevel="1"
        :watermark="false">
      <template #default="{ node, stat }">
        <div class="w-full">
          <div class="flex w-full">
            <a  class="block text-center py-3 px-3 justify-center">
                <i class="fas text-grey-400 mr-2 text-sm fa-vector-square cursor-move"></i>
            </a>
            <div class="flex-initial w-full mr-4">
                <TextInput class="mb-4" type="text"  placeholder="Title" v-model="node.title"
                    @change="save" />
                <tiny-editor placeholder="Summary" :height="200" v-model="node.summary"   @change="save"></tiny-editor>


                <div class="flex w-full gap-3 mb-4 mt-3">
                    <div class="w-full">
                        <acit-jazz-upload
                        class="mt-1 block w-full"
                        :title="`${node.title}-image`"
                        folder="cards"
                        name="image"
                        :limit="1"
                        filetype="image/*"
                        label="Card Image"
                        v-model="node.image"
                        @uploaded="uploaded(stat)"
                        >
                        </acit-jazz-upload>
                    </div>
                </div>

            </div>
            <a @click="removeRow(stat)" class="block text-center py-3 px-3 justify-center cursor-pointer">
                <i class="fas text-red-400 mr-2 text-sm fa-trash"></i>
            </a>
          </div>
        </div>
      </template>
    </Draggable>
    <div class="flex w-full px-12">
        <SecondaryButton  @click="addRow" type="button" class="justify-center "> <i class="fas text-white-400 mr-2 text-sm fa-plus"></i> Add New Section</SecondaryButton>
    </div>
  </div>
</template>
<style>
.vtlist.he-tree {
  overflow:unset !important;
}
</style>
