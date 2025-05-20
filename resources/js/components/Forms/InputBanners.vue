<script>
import { defineComponent } from "vue";
export default defineComponent({
  props:['modelValue'],
  data() {
    return {
        rows :null,
        banners:null,
        default : [{ title: null, subtitle: null, summary: null, desktop: null, mobile: null, btn_text:null, btn_url:null}],
    };
  },
  created() {
    this.rows = this.modelValue ?  this.parser() : this.default;
  },

  methods:{
    addRow(){
        this.$refs.tree.add({ title: null, subtitle: null, summary: null, desktop: null, mobile: null, btn_text:null, btn_url:null });
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
            return obj.title != null;
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
                <input type="text" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" placeholder="Sub Title" v-model="node.subtitle"
                    @change="save" />
                <TextAreaInput  class=" !border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" placeholder="Summary" v-model="node.summary" @change="save"/>
                <div class="flex w-full gap-3 mb-4 mt-3">
                    <div class="w-full">
                        <acit-jazz-upload
                        class="mt-1 block w-full"
                        :title="`${node.title}-desktop`"
                        folder="banners"
                        name="desktop"
                        :limit="1"
                        filetype="image/*"
                        label="Desktop Banner"
                        v-model="node.desktop"
                        @uploaded="uploaded(stat)"
                        >
                        </acit-jazz-upload>
                    </div>
                    <div class="w-full">
                        <acit-jazz-upload
                        class="mt-1 block w-full"
                        :title="`${node.title}-mobile`"
                        folder="banners"
                        name="mobile"
                        :limit="1"
                        filetype="image/*"
                        label="Mobile Banner"
                        v-model="node.mobile"
                        @uploaded="uploaded(stat)"
                        >
                        </acit-jazz-upload>
                    </div>
                </div>
                <div class="flex w-full gap-3 mb-4 mt-3">
                    <input type="text" class=" border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" placeholder="Button Text" v-model="node.btn_text"
                    @change="save" />
                    <input type="text" class=" border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" placeholder="Button Url" v-model="node.btn_url"
                    @change="save" />
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
        <SecondaryButton @click="addRow" type="button" class="justify-center "> <i class="fas text-white-400 mr-2 text-sm fa-plus"></i> Add New Banner</SecondaryButton>
    </div>
  </div>
</template>
<style>
.vtlist.he-tree {
  overflow:unset !important;
}
</style>
