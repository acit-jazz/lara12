<script setup>

import { ref,onMounted  } from 'vue'
import mobile from "@/Snippets/mobile";
let props  = defineProps({
    data: Object,
    partners:Array,
    clients:Array,
});
const isMobile = mobile();  // This is already a reactive ref
</script>

<template>
    <div class="py-[50px]">
        <Container class="relative overflow-hidden xl:overflow-visible">
            <div class="text-center pb-10">
                <h2 class="text-3xl font-bold mb-3">{{ data.title }}</h2>
                <p>{{ data.summary }}</p>
            </div>
            <div v-if="clients.length > 0">
                <h3 class="font-bold  mb-4 text-xl ">Clients</h3>
                <div class="grid grid-cols-3 lg:grid-cols-8 gap-5">
                    <div class="flex items-center  justify-center" v-for="(client,i) in clients">
                        <a :href="client.url" target="_blank"><img class="w-full" :src="client.image" :alt="client.image_alt"></a>
                    </div>
                </div>
            </div>
            <div v-if="partners.length > 0">
                <h3 class="font-bold mb-4 text-xl ">Partners</h3>
                <div class="grid grid-cols-3 lg:grid-cols-8 gap-5">
                    <div class="flex items-center  justify-center" v-for="(partner,i) in partners">
                        <a :href="partner.url" target="_blank"><img class="w-full" :src="partner.image" :alt="partner.image_alt"></a>
                    </div>
                </div>
            </div>
        </Container>
    </div>
</template>
