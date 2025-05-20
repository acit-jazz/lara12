<script setup>

import { ref,onMounted  } from 'vue'
import mobile from "@/Snippets/mobile";
import axios from "axios";
let props  = defineProps({
    data: Object,
});
const isMobile = mobile();  // This is already a reactive ref

const articles = ref([]);
const getProduct = () =>{
    axios.get(route('api.post.index',{limit : props.data.limit ?? 10})).then(res => {
        articles.value = res.data.data;
    }).catch(err => console.log(err));
}
onMounted(() => {
    getProduct();
});
</script>

<template>
    <div class="bg-lightyellow py-10">
        <Container>
            <div class="flex items-center">
                <h3 class="text-3xl font-bold max-w-[300px]" v-html="data.title"></h3>
                <Link class="rounded-full w-fit bg-secondary font-bold text-sm py-2 px-5 ml-auto hover:bg-primary hover:text-white">Lihat Semua</Link>
            </div>

            <div class="py-14">
                <div class="flex flex-col gap-5 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-10 lg:gap-20 py-14 border-b" v-for="(article, index) in articles" :key="index">
                    <div class="order-1 md:order-1 lg:order-3 box-3">
                        <img class="w-full rounded-xl" :src="article.image" alt="">
                    </div>

                    <div class="flex flex-col order-2 md:order-2 lg:order-1 box-1">
                        <h4 class="text-2xl font-bold mb-2">{{ article.title }}</h4>
                        <span class="text-sm">{{ article.published_at }}</span>
                        <Link :href="article.url" class="hidden md:block rounded-full w-fit bg-secondary hover:bg-primary hover:text-white font-bold text-sm py-2 px-5 mt-auto">
                            Baca Artikel
                        </Link>
                    </div>

                     <div class="order-3 col-span-2 lg:col-span-1 md:order-3 lg:order-2 box-2">
                        <p  v-html="article.summary"></p>
                        <Link :href="article.url" class="block md:hidden mt-5 rounded-full w-fit bg-secondary hover:bg-primary hover:text-white font-bold text-sm py-2 px-5">
                            Baca Artikel
                        </Link>
                     </div>
                </div>

            </div>
        </Container>
    </div>
</template>
