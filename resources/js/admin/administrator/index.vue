<script setup>
import { ref } from "vue";
import {useBuildQuery} from '@/Composables/useBuildQuery.js'
import {router } from '@inertiajs/vue3';
const props  = defineProps({
    administrators: Object,
    title:String,
    search_title:String,
    is_admin:Boolean,
    trash:Boolean,
});
const params = ref({
    search_title:'',
})
const filter = () => {
    const endpoint = ref(useBuildQuery(route('page.index'),params.value));
    router.get(endpoint.value);
}
</script>
<template>
    <AppLayout>
       <div class="flex flex-wrap mt-4">
         <div class="w-full mb-12 px-4">
            <div
                class="relative flex flex-col min-w-0 break-words w-full mb-6"
            >
                  <div class="rounded-t mb-0 px-6 py-4 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full max-w-full flex">
                            <h3 class="font-bold text-lg">
                                {{title}}
                            </h3>
                            <div class="ml-auto">
                              <SecondaryLink  :href="route('administrator.create')" class="px-3 py-1 rounded-none rounded-l-md">Create New</SecondaryLink>
                              <SecondaryLink  :href="route('administrator.index', { trash:'1' })" class="px-3 py-1 rounded-none rounded-r-md bg-red-500">Trash </SecondaryLink>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block w-full overflow-x-auto">
                <table class="items-center w-full bg-transparent border-collapse">
                    <thead>
                    <tr>
                        <Th>Username</Th>
                        <Th>Email</Th>
                        <Th>Created Dates</Th>
                        <Th></Th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(administrator,index) in administrators.data" :key="index">
                        <Td>{{administrator.name}}</Td>
                         <Td>{{administrator.email}}</Td>
                         <Td>{{administrator.created_at}}</Td>
                          <Td>
                            <div v-if="trash">
                                <SecondaryLink  class="px-3 py-2 bg-green-500 rounded-none rounded-l-md" :href="route('administrator.restore', {  administrator:administrator.id  })" method="post" as="button">
                                    <i class="fas fa-rotate-right"></i>
                                </SecondaryLink>
                                <SecondaryLink  class="px-3 py-2 bg-red-500 rounded-none rounded-r-md" :href="route('administrator.destroy', {  administrator:administrator.id  })" method="delete" as="button">
                                    <i class="fas fa-trash-can"></i>
                                </SecondaryLink>
                            </div>
                            <div v-else>
                                <SecondaryLink class="px-3 py-2 bg-indigo-500 rounded-none rounded-l-md" :href="route('administrator.edit', {  administrator:administrator.id })">
                                    <i class="fas fa-pencil"></i>
                                </SecondaryLink>
                                <SecondaryLink   class="px-3 py-2 bg-red-500 rounded-none rounded-r-md" :href="route('administrator.delete', {  administrator:administrator.id  })" method="post" as="button">
                                    <i class="fas fa-trash-can"></i>
                                </SecondaryLink>
                            </div>
                        </Td>
                    </tr>
                    </tbody>
                </table>

                </div>
            </div>
         </div>
       </div>
   </AppLayout>
 </template>
