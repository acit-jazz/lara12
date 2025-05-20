<template>
    <div>
    <div>
        <div class="flex flex-wrap gap-2">
            <div v-for="(post,i) in selectedPosts" :key="i" class="rounded-md bg-primary text-white text-sm py-1 px-2 flex gap-2">
                <span>{{ post.title }}</span>
                <button type="button" @click="remove(post)" class="mb-auto">
                    <i class="fa fa-close"></i>
                </button>
            </div>
        </div>
        <input
            id="search"
            type="text"
            v-model="searchTerm"
            @input="fetchPosts"
            placeholder="Search posts by title"
            class="w-full border-gray-300 rounded-md focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:bg-gray-100 disabled:border-gray-300 shadow-sm mt-2"
        />
    </div>

      <div v-if="filteredPosts.length" class="border rounded mt-2 p-2 h-[200px] overflow-y-scroll">
        <p class="text-gray-600 text-sm">Select posts:</p>
        <div v-for="post in filteredPosts" :key="post.id" class="py-2 border-b">
          <label class="flex">
            <input
              type="checkbox"
              :value="post.id"
              @change="updateSelectedPosts($event,post)"
              class="mt-2 mr-3"
              :checked="selectedPostIds.includes(post.id)"
            />
            <span>{{ post.title }}</span>
          </label>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  const props = defineProps(["modelValue","limit"]);
  const searchTerm = ref('');
  const filteredPosts = ref([]);
  const selectedPostIds = ref(props.modelValue ?? []);
  const selectedPosts = ref([]);
  const emit = defineEmits(['update:modelValue']);

  // Fetch posts based on the search term

  const initialPosts = async () => {
    if(selectedPostIds.value.length <= 0) return;
    try {
      const response = await axios.get(route('api.post.index'), {
        params: { where_id: selectedPostIds.value.join(',') },
      });
      selectedPosts.value = response.data.data;
    } catch (error) {
      console.error("Error fetching posts:", error);
    }
  };

  initialPosts();
  const fetchPosts = async () => {
    try {
      const response = await axios.get(route('api.post.index'), {
        params: { search_title: searchTerm.value,limit:props.limit ?? 5 },
      });
      filteredPosts.value = response.data.data;
    } catch (error) {
      console.error("Error fetching posts:", error);
    }
  };

  const remove = (post) => {
      selectedPostIds.value = selectedPostIds.value.filter(id => id !== post.id);
      selectedPosts.value = selectedPosts.value.filter(data => data.id !== post.id);
      emit('update:modelValue', selectedPostIds.value)
  }
  // Update the selectedPostIds array
  const updateSelectedPosts = (event,post) => {
    console.log(event.target.value)
    const postId = parseInt(event.target.value);
    if (event.target.checked) {
      if (!selectedPostIds.value.includes(postId)) {
        selectedPostIds.value.push(postId);
        selectedPosts.value.push(post);
      }
    } else {
      selectedPostIds.value = selectedPostIds.value.filter(id => id !== postId);
      selectedPosts.value = selectedPosts.value.filter(data => data.id !== postId);
    }
    emit('update:modelValue', selectedPostIds.value)
  };
  </script>

  <style scoped>
  /* Optional: Add custom styles */
  </style>
