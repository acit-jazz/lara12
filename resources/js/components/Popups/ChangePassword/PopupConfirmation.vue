<script setup>
import axios from "axios";
import { ref } from "vue";
import {POPUP_CHANGE_PASSWORD} from "@/Constants";
const emit = defineEmits(['close', 'open']);

const success = ref(false);
const isLoader = ref(false);

const close = () => {
    emit("close", true);
}
let props = defineProps({
    data: Array,
});

const save = () =>{
    isLoader.value = true;
    axios.post(route('user.send-otp')).then(res => {
        setTimeout(() => {
            isLoader.value = false;
            emit('close', true);
            emit('open', POPUP_CHANGE_PASSWORD.confirmationCode)
        }, 3000);
    })
}
</script>

<template>
    <div class="fixed left-0 top-0 w-full h-full z-[1000]">
        <div @click="close"
             class="fixed left-0 top-0 w-full h-full bg-[rgba(0,0,0,0.7)]"
        ></div>
        <div class="w-full h-full flex items-center justify-center">
            <div class="flex flex-wrap w-full p-5">
                <div class="p-10 bg-white max-w-[700px] w-[90%] text-center mx-auto rounded-md relative">
                    <button
                        @click="close"
                        class="absolute top-2 right-2 bg-white w-10 h-10 text-black text-3xl z-20 rounded-xl"
                    >
                        <i class="fa fa-xmark"></i>
                    </button>
                    <div class="w-full" v-if="!success">
                        <h2 class="font-bold text-xl mb-5">Ubah Password</h2>
                        <p>Apakah anda yakin ingin mengubah kata sandi anda?</p>
                        <div class="justify-center mt-7">
                            <SpinnerLoader v-if="isLoader" />
                            <div v-else>
                                <SecondaryButton  @click="save" type="button" as="button" class="mx-2 justify-center capitalize">
                                    Ya
                                </SecondaryButton>
                                <AOutlineButton  @click="close" type="button" as="button" class="mx-2 bg-red-600 text-white ml-auto">
                                    Tidak
                                </AOutlineButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
