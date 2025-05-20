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
                summary: '',
            },
        default : [{ title: null, summary: null}],
    };
  },
  created() {
    this.formdata = this.modelValue ?   this.parser() : {
                title: '',
                summary: '',
            };
  },

  methods:{
    save(){
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
        <div class="block">
            <TextAreaInput type="text" placeholder="Summary" v-model="formdata.summary" @change="save" />
        </div>
    </div>
  </div>
</template>
<style>
.vtlist.he-tree {
  overflow:unset !important;
}
</style>
