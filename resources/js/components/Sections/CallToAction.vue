<script setup>

import { ref,onMounted  } from 'vue'
import mobile from "@/Snippets/mobile";

let props  = defineProps({
    data: Object,
});
const isMobile = mobile();  // This is already a reactive ref
</script>
<template>
        <div class="relative">
            <img v-if="!isMobile" class="w-full min-h-[400px] object-cover" :src="data.image" :alt="data.image_alt" width="1400" height="600">
            <img v-else class="w-full" :src="data.image_mobile" :alt="data.image_mobile_alt" width="1400" height="600">
            <div class="absolute w-full h-full left-0 top-0 bg-gradient-to-t from-[rgba(0,0,0,0.4)] top-[rgba(0,0,0,0,)] flex">
                <Container class="mt-auto">
                    <div class="lg:grid grid-cols-2 pb-12 lg:pb-32  gap-20 text-white ">
                        <div>
                            <h2 class="text-4xl leading-none font-bold" v-html="data.title"></h2>
                        </div>
                        <div>
                            <p class="mb-10 text-xl">{{ data.summary }}</p>
                            <a class="bg-primary hover:bg-secondary hover:text-dark py-4 min-w-[200px] text-center block w-fit px-5 rounded-full font-semibold text-sm" :href="data.btn_url">{{ data.btn_text }}</a>
                        </div>
                    </div>
                </Container>
            </div>
        </div>

</template>
