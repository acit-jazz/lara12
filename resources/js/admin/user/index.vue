<script setup>
import { ref } from "vue";
import {useBuildQuery} from '@/Composables/useBuildQuery.js'
import AppLayout from '@/layouts/AppLayout.vue';
import {router } from '@inertiajs/vue3';
const props  = defineProps({
    users: Object,
    title:String,
    search_name:String,
    is_admin:Boolean,
    trash:Boolean,
});
const params = ref({
    search_name:'',
})
const filter = () => {
    const endpoint = ref(useBuildQuery(route('user.index'),params.value));
    router.get(endpoint.value);
}
</script>
<template>
    <AppLayout>
       <div class="flex flex-wrap mt-4">
         <div class="w-full mb-12 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
                <div class="rounded-t mb-0 px-6 py-4 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative flex">
                            <div class="flex items-center">
                                <h3 class="font-bold text-lg">
                                    {{title}} 
                                </h3>
                                <div class="flex items-center ml-10 max-w-[300px] relative">
                                    <TextInput type="text" class="mt-1 !py-1 block w-full" v-model="params.search_name" @change="filter"  placeholder="Search by title..." />
                                    <i class="fa fa-search absolute right-2 text-gray-400 top-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto mt-3 lg:mt-0" >
                            <SecondaryLink   :href="route('user.create',)" class="px-3 py-1 rounded-none rounded-l-md">Create</SecondaryLink>
                            <SecondaryLink  :href="route('user.index', { trash:'1' })" class="px-3 py-1 rounded-none rounded-r-md bg-red-500">Trash</SecondaryLink>
                        </div>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto">
                <table class="items-center w-full bg-transparent border-collapse">
                    <thead>
                    <tr class="hidden lg:table-row">
                        <Th>Name</Th>
                        <Th>Email</Th>
                        <Th>Created Date</Th>
                        <Th></Th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(user,index) in users.data" :key="index" class=" cursor-pointer relative py-3 block lg:py-0 lg:table-row border-t lg:border-0">
                        <Td>
                            <strong class="block lg:hidden">Name</strong>
                            <span>{{user.name}}</span>
                        </Td>
                        <Td>
                            <strong class="block lg:hidden">Email</strong>
                            <span>{{user.email}}</span>
                        </Td>
                        <Td>
                            <strong class="block lg:hidden">Created Date</strong>
                            <span>{{user.created_at ?? '-'}}</span>
                        </Td>
                        <Td >
                            <div v-if="trash">
                                <SecondaryLink  class="px-3 py-2 bg-green-500 rounded-none rounded-l-md" :href="route('user.restore', { user:user })" method="post" as="button">
                                    <i class="fas fa-rotate-right"></i>
                                </SecondaryLink>
                                <SecondaryLink  class="px-3 py-2 bg-red-500 rounded-none rounded-r-md" :href="route('user.forceDelete', { user:user })" method="post" as="button">
                                    <i class="fas fa-trash-can"></i>
                                </SecondaryLink>
                            </div>
                            <div v-else>
                                <SecondaryLink  class="px-3 py-2 bg-indigo-500 rounded-none rounded-l-md" :href="route('user.edit', { user:user })">
                                    <i class="fas fa-pencil"></i>
                                </SecondaryLink>
                                <SecondaryLink  class="px-3 py-2 bg-red-500 rounded-none rounded-r-md" :href="route('user.delete', { user:user })" method="post" as="button">
                                    <i class="fas fa-trash-can"></i>
                                </SecondaryLink>
                            </div>
                        </Td>
                    </tr>
                    </tbody>
                </table>
                 <pagination class="mt-6" :links="users.meta.links" />
                </div>
            </div>
         </div>
       </div>
   </AppLayout>
</template>
