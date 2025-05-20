<script setup>
import { ref, onMounted } from "vue";
const emit = defineEmits(["update:modelValue"]);
const props = defineProps(["modelValue"]);
const formdata = ref(null);
const save = () => {
  emit("update:modelValue", formdata.value);
};
const colors = [
  { title: "Soft Blue", value: "#F8FBFF" },
  { title: "Gray", value: "#F3F4F6" },
  { title: "White", value: "#ffffff" },
];
const types = [
  { title: "Full Screen", value: "full-screen" },
  { title: "Full Width", value: "full-wdith" },
  { title: "2 Column", value: "2column" },
];
formdata.value = props.modelValue
  ? props.modelValue
  : {
      title: "",
      summary: "",
      btn_text: "",
      btn_url: "",
      image: "",
      image_mobile: null,
    };
const uploaded = () => {
  save();
};
</script>

<template>
  <div class="block w-full overflow-x-auto">
    <div class="block mt-4">
      <InputLabel class="text-left" for="title" value="Title" />
      <TextInput
        type="text"
        placeholder="Title"
        @change="save"
        class="mt-1 block w-full"
        v-model="formdata.title"
      />
    </div>
    <div class="block mt-4">
      <InputLabel class="text-left" for="summary" value="Summary" />
      <TextAreaInput
        placeholder="summary"
        @change="save"
        class="mt-1 block w-full"
        v-model="formdata.summary"
      />
    </div>
    <div class="block mt-4">
      <InputLabel class="text-left" for="btn_text" value="Button Text" />
      <TextInput
        type="text"
        placeholder="Button Text"
        @change="save"
        class="mt-1 block w-full"
        v-model="formdata.btn_text"
      />
    </div>
    <div class="block mt-4">
      <InputLabel class="text-left" for="btn_url" value="Button Url" />
      <TextInput
        type="text"
        placeholder="Button Url"
        @change="save"
        class="mt-1 block w-full"
        v-model="formdata.btn_url"
      />
    </div>
    <div class="block mt-4">
    <acit-jazz-upload
        class="mt-1 block w-full"
        :title="`${formdata.title}-image`"
        folder="call-to-action"
        name="image"
        :limit="1"
        filetype="image/*"
        label="Background Image Desktop"
        v-model="formdata.image"
        @uploaded="uploaded(stat)"
    >
    </acit-jazz-upload>
    </div>
    <div class="block mt-4">
    <acit-jazz-upload
        class="mt-1 block w-full"
        :title="`${formdata.title}-image`"
        folder="call-to-action"
        name="image"
        :limit="1"
        filetype="image/*"
        label="Background Image Mobile"
        v-model="formdata.image_mobile"
        @uploaded="uploaded(stat)"
    >
    </acit-jazz-upload>
    </div>
  </div>
</template>
