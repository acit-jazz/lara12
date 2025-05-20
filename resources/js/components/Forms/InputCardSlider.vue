<script>
import { defineComponent } from "vue";
export default defineComponent({
  props:['modelValue'],
  data() {
    return {
        rows :null,
        banners:null,
        formdata:{
                title: '',
                cards:[{ title: null, summary: null, image: null, url:null}],
            },
        targets : [
            { title: 'Self', value:'_self' },
            { title: 'New Tab', value:'_blank' },
        ],
        default : [{ title: null, summary: null, image: null}],
    };
  },
  created() {
    this.formdata = this.modelValue ?   this.parser() : {
                title: '',
                cards: this.default,
            };
  },

  methods:{
    addRow(){
        this.$refs.tree.add({ title: null, summary: null, image: null });
        this.save();
    },
    removeRow(stat){
        if(this.formdata.cards.length > 1){
          this.$refs.tree.remove(stat);
        }
        this.save();
    },
    save(){
        this.formdata.cards = this.$refs.tree.modelValue;
        this.$emit('update:modelValue', JSON.stringify(this.formdata));
    },
    parser(){
        try {
            return JSON.parse(this.modelValue)
        } catch (error) {
            return this.modelValue ? this.modelValue : this.formdata ;
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
    <div class="block w-full overflow-x-auto mb-5">
        <div class="block">
            <TextInput type="text" placeholder="Title" v-model="formdata.title" @change="save" />
        </div>
    </div>
    <Draggable v-model="formdata.cards" ref="tree" virtualization
        :maxLevel="1"
        :watermark="false">
      <template #default="{ node, stat }">
        <div class="w-full">
          <div class="flex w-full">
            <a class="block text-center py-3 px-3 justify-center">
                <i class="fas text-grey-400 mr-2 text-sm fa-vector-square cursor-move"></i>
            </a>
            <div class="flex-initial text-left w-full mr-4 mb-4">
                <TextInput
                    type="text"
                    placeholder="Title"
                    v-model="node.title"
                    class="mb-3"
                    @change="save"
                />
                <TextAreaInput
                    placeholder="Summary"
                    class="mb-3"
                    v-model="node.summary"
                    @change="save"
                />
                <div class="w-full">
                    <acit-jazz-upload
                    class="mt-1 block w-full"
                    :title="`${node.title}-image`"
                    folder="card-slider"
                    name="image"
                    :limit="1"
                    filetype="image/*"
                    label="Image"
                    v-model="node.image"
                    @uploaded="uploaded(stat)"
                    >
                    </acit-jazz-upload>
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
        <SecondaryButton v-if="formdata.cards.length <= 3" @click="addRow" type="button" class="justify-center "> <i class="fas text-white-400 mr-2 text-sm fa-plus"></i> Add New Slider</SecondaryButton>
    </div>
  </div>
</template>
<style>
.vtlist.he-tree {
  overflow:unset !important;
}
</style>
