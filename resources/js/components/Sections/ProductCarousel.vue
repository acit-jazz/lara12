<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { Pagination,Navigation,Autoplay } from "swiper";

import { ref,onMounted  } from 'vue'
import mobile from "@/Snippets/mobile";
import axios from "axios";
let props  = defineProps({
    data: Object,
});
const isMobile = mobile();  // This is already a reactive ref
const modules = ref(
    [Navigation,Pagination,Autoplay]
)
const perView = ref(isMobile.value ? 1.5 : 4.5)

const products = ref([]);
const getProduct = () =>{
    axios.get(route('api.product.index',{limit : props.data.limit ?? 10})).then(res => {
        products.value = res.data;
    }).catch(err => console.log(err));
}
onMounted(() => {
    getProduct();
});
</script>

<template>
    <div class="pb-20">
        <Container>
            <swiper
                :pagination="true"
                :space-between="20"
                :modules="modules"
                :speed="1500"
                :loop="true"
                :autoplay="{
                delay: 2500,
                disableOnInteraction: false,
                }"
                :breakpoints="{
                    640: {  // layar >= 640px
                        slidesPerView: 1.5,
                        spaceBetween: 10
                    },
                    768: {  // layar >= 768px
                        slidesPerView: 3.5,
                        spaceBetween: 15
                    },
                    1024: { // layar >= 1024px
                        slidesPerView: 3.5,
                        spaceBetween: 20
                    },
                    1280: { // layar >= 1280px
                        slidesPerView: 4.5,
                        spaceBetween: 30
                    }
                }"
                class="pb-14"
            >
                <swiper-slide v-for="(product, i) in products">
                    <div :style="'background-color :' + product.card_color" class="rounded-[40px] flex flex-col p-4 border-4 border-white min-h-[470px]">
                            <div class="pb-2">
                                <img class="w-full" :src="product.thumbnails" :alt="product.thumbnails_alt" width="588" height="533">
                            </div>
                            <div class="text-center pb-4">
                                <h3 class="font-bold">{{ product.title }}</h3>
                                <p>{{ product.subtitle }}</p>
                            </div>
                            <div class="flex mt-auto items-center justify-center gap-2 mb-5">
                                <span :style="'background-color :' + product.size_color"
                                    class="text-white py-2 px-5 text-[16px] rounded-[5px] font-bold"
                                >{{ product.size }}</span>
                                <span class="bg-white py-2 px-5 text-[16px] rounded-[5px]">Isi <strong>{{ product.qty }}</strong></span>
                            </div>
                            <a class="bg-primary hover:bg-secondary hover:text-dark text-white py-3 w-full block text-center block px-5 rounded-full font-semibold text-sm" :href="product.url">Lihat Produk</a>
                    </div>
                </swiper-slide>
            </swiper>
        </Container>
    </div>
</template>
