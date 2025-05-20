<script setup>

import { ref,onMounted  } from 'vue'
import mobile from "@/Snippets/mobile";
let props  = defineProps({
    data: Object,
    services:Array,
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
            <div class="lg:grid grid-cols-3 gap-3">
                <div v-for="(service, i) in services" class="mb-5 lg:mb-0 flex flex-col items-center bg-primary rounded-xl overflow-hidden">
                        <div class="text-white w-full feature-content">
                            <div class="flex items-center gap-5 bg-secondary px-7 py-5">
                                <div class="flex w-[50px] h-[50px]  justify-center relative z-3 items-center bg-primary rounded-full p-2 shadow">
                                    <img class="w-[30px] invert " :src="service.image" :alt="service.image_alt" width="30" height="30">
                                </div>
                                <h3 class="leading-none text-xl text-primary font-bold" v-html="service.title"></h3>
                            </div>
                            <div class="min-h-[250px]  p-7">
                                <div>
                                    <ul class="">
                                        <li v-for="(item, i) in service.services" class="">
                                            <a :href="item.url" class="py-2 block hover:text-secondary flex items-center block border-b border-[rgba(255,255,255,0.2)]">
                                                <span> {{ item.title }}</span>
                                                <font-awesome-icon icon="fa-solid fa-arrow-right-long" class="ml-auto w-2 hidden lg:block rotate-[-45deg]"/>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </Container>
    </div>
</template>
