<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { Pagination,Navigation,Autoplay } from "swiper";

import { ref,onMounted  } from 'vue'
import mobile from "@/Snippets/mobile";
let props  = defineProps({
    data: Object,
});
const isMobile = mobile();  // This is already a reactive ref
const modules = ref(
    [Navigation,Pagination,Autoplay]
)
</script>

<template>
    <Container>

        <swiper
            :pagination="false"
            :space-between="20"
            :modules="modules"
            :speed="1500"
            :loop="true"
            :autoplay="{
            delay: 2500,
            disableOnInteraction: false,
            }"
            :breakpoints="{
                320: {  // layar >= 640px
                    slidesPerView: 1.1,
                    spaceBetween: 10
                },
                768: {  // layar >= 768px
                    slidesPerView: 3,
                    spaceBetween: 15
                },
                1024: { // layar >= 1024px
                    slidesPerView: 3,
                    spaceBetween: 20
                },
            }"
        >
            <swiper-slide v-for="(slider, i) in data.cards">
                <div class="relative">
                    <div class="bg-primary">
                        <img class="w-full" :src="slider.image" :alt="slider.image_alt" width="588" height="533">
                    </div>
                    <div class="py-5">
                        <div class="pb-5">
                            <h3 class="text-xl leading-none font-bold mb-5" v-html="slider.title"></h3>
                            <p class="mb-2" v-html="slider.summary"></p>
                        </div>
                    </div>
                </div>
            </swiper-slide>
        </swiper>
    </Container>
</template>
