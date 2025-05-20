<script setup>

let props  = defineProps({
    data: Object,
});
</script>

<template>
    <div class="py-[50px] ">
        <Container class="relative overflow-hidden xl:overflow-visible">
            <div class="text-center pb-10">
                <h2 class="text-3xl font-bold mb-3">{{ data.title }}</h2>
                <p>{{ data.summary }} adada ada</p>
            </div>
            <div class="lg:grid grid-cols-2 gap-3">
                <div v-for="(card,i) in data.cards" class="mb-5 lg:mb-0 flex flex-col items-center bg-primary rounded-xl">
                        <div class="mx-auto feature-content py-10 text-center">
                            <div class="flex w-[50px] mb-3 h-[50px] mx-auto justify-center relative z-3 items-center bg-secondary rounded-full p-2 shadow">
                                <img class="w-[30px] invert" :src="card.icon" width="30" height="30">
                            </div>
                            <div class="text-white py-3 px-4 mx-4">
                               <p>{{ card.summary }}</p>
                            </div>
                        </div>
                </div>
            </div>
        </Container>
    </div>
</template>
