<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { Pagination, Navigation, Autoplay } from "swiper";
import { ref, onMounted, onBeforeUnmount } from 'vue';
import mobile from "@/Snippets/mobile";
import * as THREE from 'three';
import VANTA from 'vanta/dist/vanta.globe.min';

let props = defineProps({
    data: Object,
});

const isMobile = mobile(); // This is already a reactive ref
const modules = ref([Navigation, Pagination, Autoplay]);

const vantaEffect = ref(null);
const vantaContainer = ref(null);
onMounted(() => {
  if (VANTA) {
    vantaEffect.value = VANTA({
        el: vantaContainer.value,
        THREE: THREE,
        color: "#49C4F0", // Adjust color as needed
        color2: "#49C4F0", // Adjust color as needed
        backgroundColor: 0x36311F, // Matches `bg-primary`
        size: 2.00, // Adjust for globe size
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        showDots:true,
        scaleMobile: 1.00
    });
  } else {
    console.error("VANTA.GLOBE is not available");
  }
});

onBeforeUnmount(() => {
    if (vantaEffect.value) vantaEffect.value.destroy();
});
</script>
<style>

canvas.vanta-canvas{
    opacity: 0.5;
}
</style>
<template>
    <div ref="vantaContainer" class="overflow-hidden bg-primary py-10 relative">
        <swiper
            :slides-per-view="1"
            :navigation="true"
            :effect="'fade'"
            :modules="modules"
            :speed="4000"
            :loop="true"
            :autoplay="{
                delay: 4500,
                disableOnInteraction: false,
            }"
            class="!p-0 relative z-10"
        >
            <swiper-slide v-for="(slider, i) in data" :key="i">
                <div class="from-[rgba(114,76,58,0.3)] top-[rgba(0,0,0,0)] flex">
                    <Container class="mt-auto">
                        <div class="lg:grid lg:grid-cols-2 gap-5 md:gap-10 text-white md:items-center">
                            <div class="mb-5 lg:mb-0">
                                <h2 class="text-[40px] mb-2 md:text-[50px] leading-none font-bold" v-html="slider.title"></h2>
                                <h3 v-if="slider.subtitle" class="text-[20px] md:text-[20px] leading-none font-bold" v-html="slider.subtitle"></h3>
                                <p v-if="slider.summary" class="text-[16px] mt-4 md:text-[16px]  font-bold" v-html="slider.summary"></p>
                            </div>
                            <div v-if="slider.desktop">
                                <img v-if="isMobile" class="rounded-xl w-full object-cover" :src="slider.desktop" :alt="slider.desktop_alt" width="1400" height="600">
                                <img v-else class="rounded-xl w-full object-cover" :src="slider.mobile" :alt="slider.mobile_alt" width="1400" height="600">
                            </div>
                        </div>
                    </Container>
                </div>
            </swiper-slide>
        </swiper>
    </div>
</template>
