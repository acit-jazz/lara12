<script>
import { defineComponent } from "vue";
export default defineComponent({
  props: ["modelValue"],
  data() {
    return {
      rows: null,
      banners: null,
      types: [
        { title: "Text", component:'TextInput', type: "text", has_option:false },
        { title: "Textarea", component:'TextAreaInput', type: "textarea", has_option:false },
        { title: "Text Editor", component:'TinyEditor', type: "texteditor", has_option:false },
        { title: "Date", component:'TextInput', type: "date", has_option:false },
        { title: "Time", component:'TextInput', type: "time", has_option:false },
        { title: "Number", component:'TextInput', type: "number", has_option:false },
        { title: "Checkbox", component:'InputCheckbox', type: "checkboxdata", has_option:true },
        { title: "Radio", component:'InputRadio', type: "radiodata", has_option:true },
        { title: "Select Option", component:'InputSelect', type: "select", has_option:true },
        { title: "Checkbox With Other", component:'InputCheckboxWithOther', type: "checkbox-other", has_option:true },
        { title: "Radio With Other", component:'InputRadioWithOther', type: "radio-other", has_option:true },
        { title: "Select Option With Other", component:'InputSelectWithOther', type: "select-other", has_option:true }
      ],
      formdata: {
        title: "",
        summary: "",
        align: "",
        type: null,
        image: null,
        bg_color: null,
        cards: [{ id: 'section-'+this.getId(), name: null, type: null, label:null, component:null, placeholder:null,tooltip:null, required:null,has_option:false, options:[] }],
      },
      default: [{ id: 'section-'+this.getId(), name: null, type: null, label:null, component:null, placeholder:null,tooltip:null, required:null,has_option:false, options:[] }],
    };
  },
  created() {
    this.formdata = this.modelValue
      ? this.parser()
      : {
          title: "",
          summary: "",
          type: null,
          cards: this.default,
        };
  },

  methods: {
    addRow() {
      this.$refs.tree.add({ id: 'section-'+this.getId(), name: null, type: null, label:null, placeholder:null,tooltip:null, required:null,has_option:false, options:[] });
      this.save();
    },
    getId(){
            return (Math.random() + 1).toString(36).substring(2);
    },
    removeRow(stat) {
      if (this.formdata.cards.length > 1) {
        this.$refs.tree.remove(stat);
      }
      this.save();
    },
    snakeCase(string,id){
        const snake =  string.target.value.replace(/\W+/g, " ")
        .split(/ |\B(?=[A-Z])/)
        .map(word => word.toLowerCase())
        .join('_');

        var foundIndex = this.formdata.cards.findIndex((x) => x.id == id);
        this.formdata.cards[foundIndex].name = snake;
    },
    changeType(event,index){
        var foundIndex = this.formdata.cards.findIndex((x) => x.id == index);
        if(event){
            var type = this.types.find((x) => x.type == event);
            this.formdata.cards[foundIndex].type = type.type;
            this.formdata.cards[foundIndex].component = type.component;
            this.formdata.cards[foundIndex].has_option = type.has_option;
        }else{
            this.formdata.cards[foundIndex].type = null;
            this.formdata.cards[foundIndex].has_option = false;
        }
    },
    save() {
      this.formdata.cards = this.$refs.tree.modelValue;
      this.$emit("update:modelValue", this.formdata);
    },
    parser() {
      try {
        return JSON.parse(this.modelValue);
      } catch (error) {
        return this.modelValue ? this.modelValue : this.formdata;
      }
    },
    uploaded(stat) {
      this.save();
    },
  },
});
</script>

<template>
  <div class="w-full">
    <div class="block w-full overflow-x-auto mb-5">
      <div class="block mt-4">
        <InputLabel class="text-left" for="title" value="Title" />
        <TextInput
          type="text"
          placeholder="Title"
          v-model="formdata.title"
          class="w-full"
          @change="save"
        />
      </div>
      <div class="block mt-4">
        <InputLabel class="text-left" for="summary" value="Summary" />
        <TextAreaInput
          placeholder="Summary"
          v-model="formdata.summary"
          class="w-full"
          @change="save"
        />
      </div>
    </div>
    <InputLabel class="text-left" for="field" value="Fields" />
    <Draggable
      v-model="formdata.cards"
      ref="tree"
      virtualization
      :maxLevel="1"
      :watermark="false"
    >
      <template #default="{ node, stat }">
        <div class="w-full">
          <div class="flex w-full">
            <a class="block text-center py-3 px-3 justify-center">
              <i
                class="fas text-grey-400 mr-2 text-sm fa-vector-square cursor-move"
              ></i>
            </a>
            <div class="w-full mr-4">
                <div class="gap-5 flex items-center w-full">
                    <TextInput
                        type="text"
                        placeholder="Field Name"
                        @input="snakeCase($event,node.id)"
                        v-model="node.name"
                        class="mb-3 !w-6/12"
                        @change="save"
                    />
                    <TextInput
                        type="text"
                        placeholder="Label"
                        v-model="node.label"
                        class="mb-3 !w-6/12"
                        @change="save"
                    />
                </div>
                <div class="gap-5 flex items-center w-full">
                    <TextInput
                        type="text"
                        placeholder="Placeholder"
                        v-model="node.placeholder"
                        class="mb-3 !w-6/12"
                        @change="save"
                    />
                    <div class="mb-3 !w-6/12">
                        <InputSelect
                            placeholder="Field Type"
                            @change="changeType($event,node.id)"
                            v-model="node.type"
                            :options="types"
                            label="title"
                            store="type"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <TextInput
                        type="text"
                        placeholder="Tooltip"
                        v-model="node.tooltip"
                        class="mb-3 w-full"
                        @change="save"
                    />
                </div>
                <InputButtons v-if="node.has_option" v-model="node.options"></InputButtons>
            </div>
            <a
              @click="removeRow(stat)"
              class="block text-center py-3 px-3 justify-center cursor-pointer"
            >
              <i class="fas text-red-400 mr-2 text-sm fa-trash"></i>
            </a>
          </div>
        </div>
      </template>
    </Draggable>
    <div class="flex w-full px-12">
      <APrimaryButton @click="addRow" type="button" class="justify-center">
        <i class="fas text-white-400 mr-2 text-sm fa-plus"></i> Add New
        Field</APrimaryButton
      >
    </div>
  </div>
</template>
<style>
.vtlist.he-tree {
  overflow: unset !important;
}
</style>
