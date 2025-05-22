<script setup>
import { useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

let props = defineProps({
  page: Object,
  type: [String, Boolean],
  method: String,
  templates: Array,
  is_admin:Boolean,
});

const form = ref(useForm(props.page));

onMounted(() => {
  form.value.type = props.type;
});

const submit = () => {
  if (props.method == "store") {
    form.value.post(route("page.store", form.value.id), {
      preserveScroll: false,
      onFinish: () => console.log("ok"),
      onSuccess: (res) => {
        form.value.reset();
      },
    });
  }
  if (props.method == "update") {
    form.value.patch(route("page.update", { page: props.page }), {
      preserveScroll: false,
      onFinish: () => console.log("ok"),
      onSuccess: (res) => {
        form.value = useForm(res.props.page);
      },
    });
  }
};

const tab = ref("content");
const changeTab = (newtab) => {
  tab.value = newtab;
};
</script>

<template>
    <Head title="Content" />
    <AppLayout>
        <div>
          <form class="flex flex-wrap mt-4" @submit.prevent="default">
            <div class="w-full lg:w-8/12">
                <div class="relative flex flex-col min-w-0 break-words w-full mb-5 px-5" >
                    <div class="block w-full overflow-x-auto">
                        <div class="rounded-t mb-5 px-5 ">
                            <div class="text-sm font-medium text-center  border-b border-gray-200">
                                <ul class="flex flex-wrap -mb-px">
                                    <li class="mr-2">
                                        <a @click="changeTab('content')" class="block cursor-pointer font-bold p-4 rounded-t-lg border-b-2 border-transparent"
                                        :class="{'!border-blue-600  text-blue-600 active dark:text-blue-500 dark:border-blue-500' : tab == 'content'}"
                                        aria-current="page">Content</a>
                                    </li>
                                    <li class="mr-2">
                                        <a @click="changeTab('sections')"  class="block cursor-pointer font-bold p-4 rounded-t-lg border-b-2 border-transparent"
                                        :class="{'!border-blue-600  text-blue-600 active dark:text-blue-500 dark:border-blue-500' : tab == 'sections'}"
                                        >Section</a>
                                    </li>
                                    <li class="mr-2">
                                        <a @click="changeTab('seo')"  class="block cursor-pointer font-bold p-4 rounded-t-lg border-b-2 border-transparent"
                                        :class="{'!border-blue-600  text-blue-600 active dark:text-blue-500 dark:border-blue-500' : tab == 'seo'}"
                                        >SEO</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="block w-full overflow-x-auto lg:px-4" :class="{hidden : tab != 'content'}">
                            <div class="block">
                                <InputLabel for="title" value="Title" />
                                <TextInput type="text" class="mt-1 block w-full" v-model="form.title"  />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div class="block mt-4">
                                <InputLabel for="slug" value="Slug" />
                                <InputSlug  type="text" class="mt-1 block w-full" :source="form.title" v-model="form.slug"  />
                                <InputError class="mt-2" :message="form.errors.slug" />
                            </div>


                            <div class="block mt-4">
                                <InputLabel :for="form.summary" value="Summary" />
                                <TextAreaInput  class="mt-1 block w-full" v-model="form.summary"  />
                            </div>
                            <div class="block mt-4">
                                <InputLabel for="description" value="Description" />
                                <tiny-editor
                                placeholder="Description"
                                v-model="form.description"
                                ></tiny-editor>
                                <InputError
                                class="mt-2"
                                :message="form.errors.description"
                                />
                            </div>

                        </div>
                        <div class="block w-full lg:px-4" :class="{hidden : tab != 'sections'}">
                            <SelectSection v-model="form.sections" @onsave="submit"></SelectSection>
                        </div>
                        <div class="block w-full overflow-x-auto lg:px-4" :class="{hidden : tab != 'seo'}">
                                <div class="block">
                                    <InputLabel :for="form.meta.title" value="Meta Title" />
                                    <TextInput type="text" class="mt-1 block w-full" v-model="form.meta.title"  />
                                </div>

                                <div class="block mt-4">
                                    <InputLabel :for="form.meta.description" value="Meta Description" />
                                    <TextAreaInput  class="mt-1 block w-full" v-model="form.meta.description"  />
                                </div>

                                <div class="block mt-4">
                                    <InputLabel :for="form.meta.image" value="Meta Image" />
                                    <acit-jazz-upload
                                    class="mt-1 block w-full"
                                    title="meta"
                                    folder="meta"
                                    :limit="1"
                                    filetype="image/*"
                                    name="meta"
                                    v-model="form.meta.image"
                                    >
                                    </acit-jazz-upload>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="w-full lg:w-4/12">
          <div
            class="relative flex flex-col min-w-0 break-words w-full px-5"
          >
            <div class="block mt-4">
              <InputLabel for="published_at" value="Published Date" />
              <TextInput
                type="datetime-local"
                v-model="form.published_at"
                format="dd/MM/yyyy hh:mm"
                placeholder="Select Published Date"
              />
              <InputError class="mt-2" :message="form.errors.published_at" />
            </div>
            <div class="block mt-4">
              <InputLabel :for="form.banners" value="Banner" />
              <acit-jazz-upload
                class="mt-1 block w-full"
                title="banner"
                folder="page-banner"
                :limit="1"
                filetype="image/*"
                name="banner"
                v-model="form.banners"
              >
              </acit-jazz-upload>
            </div>
          </div>
        </div>
          <div class="px-5 w-full mt-5 mb-10">
              <PrimaryButton  @click="submit" class="w-full block cursor-pointer text-center py-3 px-3 justify-center rounded-md">
                  Save
              </PrimaryButton>
          </div>
      </form>
    </div>
  </AppLayout>
</template>
