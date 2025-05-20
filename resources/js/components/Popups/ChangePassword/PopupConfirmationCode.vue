<script setup>
import axios from "axios";
import {ref, nextTick, onMounted} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {OTP_STATUS, POPUP_CHANGE_PASSWORD} from "@/Constants";

const emit = defineEmits(['close']);
const success = ref(false);
const lengthCode = ref(6);
const codeInput = ref([]);
const message = ref('');
const isLoading = ref(false);
const timeResendCountDown = ref(30);
let timer;
let props = defineProps({
    data: Array,
});

const close = () => {
    emit('close', true);
    clearInterval(timer)
}

const form = useForm({
    otp: null
});

const keyDownHandler = (event, index) => {
    event.stopPropagation()
    event.preventDefault()
    if (event.code.toLowerCase() === 'backspace') {
        codeInput.value[index - 1] = '';
        codeInput.value[index - 2] = '';
        nextTick(() => {
            goto(index -1);
        })
    }
    if (event.code.slice(0, 5).toLowerCase() === 'digit' && codeInput.value.length + 1 <= lengthCode.value) {
        console.log(lengthCode.value, codeInput.value.length + 1);
        codeInput.value[index - 1] = event.key;
        nextTick(() => {
            goto(index + 1);
        });
    }
}
const countDown = () => {
    return setInterval(() => {
        if (timeResendCountDown.value > 0) {
            timeResendCountDown.value--;
        } else {
            clearInterval(timer)
        }
    }, 1000)
}
const goto = (n) => {
    if (!n || n > lengthCode.value + 1) {
        n = 1
    }
    if (codeInput.value.length + 1 <= lengthCode.value) {
        let el = document.getElementById(`code-${n}`);
        el.focus();
    }
}

const resendVerifivationCode = () => {
    timeResendCountDown.value = 30;
    timer = countDown();
}

const submitHandler = () => {
    isLoading.value = !isLoading.value;
    form.otp = parseInt(codeInput.value.join(''));
    axios.post(route('user.check-otp'), form)
        .then((res) => {
            if (res.data?.status === OTP_STATUS.OTP_SUCCESS) {
                message.value = res.data?.message;
                setTimeout(() => {
                    emit("open", POPUP_CHANGE_PASSWORD.changePasswordForm);
                }, 5000);
            }
            if (res.data?.status === OTP_STATUS.OTP_INVALID) {
                message.value = res.data?.message;
                isLoading.value = !isLoading.value;
            }
    })
}

onMounted(() => timer = countDown());

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
                    <div class="w-full " v-if="!success">
                        <h2 class="font-bold text-xl mb-5">Masukan Kode</h2>
                        <p>Kode konfirmasi sudah terkirim ke email anda</p>
                        <div class="relative flex align-center justify-center gap-3 mt-5 mb-3">
                            <TextInput
                                v-for="index in lengthCode"
                                :name="`code-${index}`"
                                maxlength="1"
                                :key="index"
                                :id="`code-${index}`"
                                @keydown="keyDownHandler($event, index)"
                                @keydown.ctrl.a.prevent
                                @mousemove.prevent.stop
                                :value="codeInput[index - 1] !== '' ? codeInput[index - 1] : ''"
                                type="number"
                                :class="`mt-1 h-11 !w-10 text-xl font-bold code-input text-center block focus:ring-blue-500 ${ error ? 'border-red-500' : '' }`" required />
                        </div>
                        <span class="" v-if="message" v-html="message"></span>
                        <div class="justify-center mt-7">
                            <SecondaryButton :isLoading="isLoading" @click="submitHandler" type="button" as="button" class="mx-2 justify-center capitalize disabled:cursor-not-allowed disabled:bg-blue-300">
                                Verifikasi
                            </SecondaryButton>
                        </div>
                        <h4 class="mt-9 text-md font-semibold">Tidak dapat kode verifikasi?
                            <span v-if="timeResendCountDown !== 0" class="text-blue-500">{{ timeResendCountDown }}</span>
                            <span v-else class="text-blue-600 cursor-pointer hover:text-blue-300"><a @click="resendVerifivationCode">Kirim ulang</a></span>
                        </h4>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
