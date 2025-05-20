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
      video_id: "",
      image: "",
      type: null,
      bg_color: null,
    };
const uploaded = () => {
  save();
};
</script>

<template>
  <div class="block w-full overflow-x-auto">
    <div class="block mt-4">
      <InputLabel class="text-left" for="bg_color" value="Type" />
      <InputSelect
        placeholder="Type"
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
      <InputLabel class="text-left" for="video_id" value="Youtube Video ID" />
      <TextInput
        type="text"
        placeholder="Youtube Video ID"
        @change="save"
        class="mt-1 block w-full"
        v-model="formdata.video_id"
      />
    </div>
    <div class="flex w-full gap-3 mb-4 mt-3">
      <div class="w-full">
        <acit-jazz-upload
          class="mt-1 block w-full"
          :title="`${formdata.title}-image`"
          folder="who-we-are"
          name="image"
          :limit="1"
          filetype="image/*"
          label="Image Video Cover"
          v-model="formdata.image"
          @uploaded="uploaded(stat)"
        >
        </acit-jazz-upload>
      </div>
    </div>
  </div>
</template>
