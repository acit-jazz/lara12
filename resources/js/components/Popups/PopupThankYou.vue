<script setup>
const emit = defineEmits(['close']);
const close = () => {
    emit('close',true);
}
let props  = defineProps({
    title: String,
    message: String,
    url: String,
    btn: [String],
});
</script>

<template>
    <div class="fixed left-0 top-0 w-full h-full bg-[rgba(0,0,0,0.7)] z-[1000]">
        <div class="w-full h-full flex items-center justify-center">
            <div class="flex flex-wrap w-full p-5">
                <div class="p-5 bg-white max-w-[300px] w-[90%] text-center mx-auto rounded-md" >
                    <div class="border border-green-500 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="fa fa-check text-3xl text-green-500"></i>
                    </div>
                    <h2 class="font-bold mb-2 text-xl">{{ title ?? 'Successful!' }}</h2>
                    <h2 class="mb-5 text-sm">{{ message ?? 'Thank you for submission' }}</h2>

                    <div class="flex gap-5  w-full">
                        <PrimaryButton   :href="url ?? route('home')"  class="cursor-pointer w-full block text-center py-2 px-3 rounded-xl justify-center  border-2">
                        {{ btn ?? 'Tutup' }}
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
