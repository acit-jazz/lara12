<script>
import { defineComponent } from "vue";
export default defineComponent({
  props:['modelValue'],
  data() {
    return {
        rows :null,
        banners:null,
        default : [{ title: null, summary: null, image:null,featured:null}],
    };
  },
  created() {
    this.rows = this.modelValue ?  this.parser() : this.default;
  },

  methods:{
    addRow(){
        this.$refs.tree.add({ title: null, summary: null, image:null,featured:null });
        this.save();
    },
    removeRow(stat){
        if(this.rows.length > 1){
          this.$refs.tree.remove(stat);
        }
        this.save();
    },
    save(){
        this.banners = this.$refs.tree.getData().filter(function( obj ) {
            return obj.image != null;
        });
        this.$emit('update:modelValue', JSON.stringify(this.banners));
    },
    parser(){
        try {
            return JSON.parse(this.modelValue)
        } catch (error) {
            return (this.modelValue.length == 0 ?  this.default : this.modelValue) ;
        }
    },
    uploaded(stat){
        this.save();
    }
  },
});
</script>

<template>
  <div>
    <Draggable v-model="rows" ref="tree" virtualization
        :maxLevel="1"
        :watermark="false">
      <template #default="{ node, stat }">
        <span v-if="stat.children.length" @click="stat.open = !stat.open">
          {{ stat.open ? "-" : "+" }}
        </span>
        <div class="w-full mb-5">
          <div class="flex w-full">
            <a @click="removeRow(stat)" class="block text-center py-3 px-3 justify-center">
                <i class="fas text-grey-400 mr-2 text-sm fa-vector-square cursor-move"></i>
            </a>
            <div class="flex-initial w-full mr-4">
                <input type="text" class=" border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" placeholder="Title" v-model="node.title"
                    @change="save" />
                <TextAreaInput  class=" !border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" placeholder="Summary" v-model="node.summary" @change="save"/>
                <div class="flex items-center mt-3 gap-3">
                    <input type="checkbox" class=" border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block" v-model="node.featured"
                    @change="save" /> 
                    <label for="featured">Set As Featured</label>
                </div>
                <div class="flex w-full gap-3 mb-4 mt-3">
                    <div class="w-full">
                        <acit-jazz-upload
                        class="mt-1 block w-full"
                        :title="`${node.title}-gallery`"
                        folder="banners"
                        name="gallery"
                        :limit="1"
                        filetype="image/*"
                        label="Desktop Banner"
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
        <SecondaryButton @click="addRow" type="button" class="justify-center "> <i class="fas text-white-400 text-sm fa-plus"></i> Add New Image</SecondaryButton>
    </div>
  </div>
</template>
<style>
.vtlist.he-tree {
  overflow:unset !important;
}
</style>
