<script setup>
import { ref, onMounted } from "vue";
const emit = defineEmits(["update:modelValue"]);
const props = defineProps(["modelValue"]);
const formdata = ref(null);
const save = () => {
  emit("update:modelValue", formdata.value);
};
const colordatas = [
    { title: 'Soft Blue', value:'#EEF4FA' },
    { title: 'Gray', value:'#F3F4F6' },
    { title: 'White', value:'#ffffff' },
];
const aligns = [
  { title: "Left", value: "text-left" },
  { title: "Center", value: "text-center" },
  { title: "Right", value: "text-right" },
];
formdata.value = props.modelValue
  ? props.modelValue
  : {
      title: "",
      subtitle: "",
      align: null,
      summary: "",
      icon: null,
      cover: "",
      color: null,
    };
</script>

<template>
  <div class="block w-full overflow-x-auto">
    <div class="block">
      <InputLabel class="text-left" for="color" value="Background Color" />
      <InputSelect
        placeholder="Background Color"
        v-model="formdata.color"
        :options="colordatas"
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
      />
    </div>
    <div class="block  mt-4">
      <InputLabel class="text-left" for="title" value="Title" />
      <TextInput
        type="text"
        @change="save"
        class="mt-1 block w-full"
        v-model="formdata.title"
      />
    </div>
    <div class="block mt-4">
      <InputLabel class="text-left" for="subtitle" value="Sub Title" />
      <TextInput
        type="text"
        @change="save"
        class="mt-1 block w-full"
        v-model="formdata.subtitle"
      />
    </div>
    <div class="block mt-4">
      <InputLabel class="text-left" for="summary" value="Summary" />
        <TextAreaInput placeholder="Summary" v-model="formdata.summary" @change="save"/>
    </div>
  </div>
</template>
