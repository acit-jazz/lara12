<script>
import { defineComponent } from "vue";
export default defineComponent({
  props: ["modelValue"],
  data() {
    return {
      rows: null,
      banners: null,
      types: [
        { title: "Vertical", value: "vertical" },
        { title: "Horizotal", value: "horizontal" },
      ],
      colors: [
        { title: "Soft Blue", value: "#F8FBFF" },
        { title: "Gray", value: "#F3F4F6" },
        { title: "White", value: "#ffffff" },
      ],
      aligns: [
        { title: "Left", value: "text-left" },
        { title: "Center", value: "text-center" },
        { title: "Right", value: "text-right" },
        ],
        targets : [
            { title: 'Self', value:'_self' },
            { title: 'New Tab', value:'_blank' },
        ],
      formdata: {
        title: "",
        summary: "",
        align: "",
        type: null,
        image: null,
        bg_color: null,
        cards: [{ title: null, btn_target: null, btn_url:null, btn_text:null, summary: null, image: null }],
      },
      default: [{ title: null, btn_target: null, btn_url:null, btn_text:null, summary: null, image: null }],
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
      this.$refs.tree.add({ title: null, summary: null, image: null });
      this.save();
    },
    removeRow(stat) {
      if (this.formdata.cards.length > 1) {
        this.$refs.tree.remove(stat);
      }
      this.save();
    },
    save() {
      this.formdata.cards = this.$refs.tree.modelValue;
      console.log(this.formdata);
      this.$emit("update:modelValue", JSON.stringify(this.formdata));
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
  <div>
    <div class="block w-full overflow-x-auto mb-5">
      <div class="block">
        <InputLabel class="text-left" for="type" value="Card Type" />
        <InputSelect
          placeholder="Card Type"
          v-model="formdata.type"
          @change="save"
          :options="types"
          label="title"
          store="value"
        />
      </div>
      <div class="block mt-4">
        <InputLabel class="text-left" for="bg_color" value="Background Color" />
        <InputSelect
          placeholder="Background Color"
          v-model="formdata.bg_color"
          @change="save"
          :options="colors"
          label="title"
          store="value"
        />
      </div>
      <div class="block mt-4">
        <InputLabel class="text-left" for="color" value="Title Align" />
        <InputSelect
          placeholder="Title Align"
          v-model="formdata.align"
          :options="aligns"
          label="title"
          store="value"
          @change="save"
        />
      </div>
      <div class="block mt-4">
        <InputLabel class="text-left" for="title" value="Title" />
        <TextInput
          type="text"
          placeholder="Title"
          v-model="formdata.title"
          @change="save"
        />
      </div>
      <div class="block mt-4">
        <InputLabel class="text-left" for="summary" value="Summary" />
        <TextAreaInput
          placeholder="Summary"
          v-model="formdata.summary"
          @change="save"
        />
      </div>
    </div>
    <InputLabel class="text-left" for="card" value="Card Items" />
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
            <div class="flex-initial w-full mr-4">
              <TextInput
                type="text"
                placeholder="Title"
                v-model="node.title"
                class="mb-3"
                @change="save"
              />
                <div class="flex w-full gap-3 mb-3">
                    <TextInput class="!w-4/12" type="text" placeholder="Button Text" v-model="node.btn_text" @change="save" />
                    <TextInput class="!w-4/12" type="text" placeholder="Button Url" v-model="node.btn_url" @change="save" />
                    <InputSelect
                        class="!w-4/12"
                        placeholder="Button Target"
                        v-model="node.btn_target"
                        :options="targets"
                        label="title"
                        store="value"
                    />
                </div>
              <TextAreaInput
                placeholder="Summary"
                class="mb-3"
                v-model="node.summary"
                @change="save"
              />

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
      <SecondaryButton @click="addRow" type="button" class="justify-center">
        <i class="fas text-white-400 mr-2 text-sm fa-plus"></i> Add New
        Card</SecondaryButton
      >
    </div>
  </div>
</template>
<style>
.vtlist.he-tree {
  overflow: unset !important;
}
</style>
