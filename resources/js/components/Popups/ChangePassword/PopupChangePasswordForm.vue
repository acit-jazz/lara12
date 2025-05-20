<script setup>
import axios from "axios";
import { defineAsyncComponent, ref } from "vue";
import { CHANGE_PASSWORD_STATUS } from "@/Constants";

const emit = defineEmits(["close"]);

const success = ref(false);
const showPassword = ref(false);
const showPasswordCurrent = ref(false);
const showPasswordConfirm = ref(false);
const isLoader = ref(false);
const message = ref("");

const form = ref({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const close = () => {
    emit("close", true);
};

const save = () => {
    isLoader.value = true;
    axios.post(route("user.change-password"), form.value).then((res) => {
        if (res.data?.status === CHANGE_PASSWORD_STATUS.passwordChangeSuccess) {
            message.value = res.data?.message;
            success.value = true;
            setTimeout(() => {
                success.value = false;
                emit("close", true);
            }, 3000);
        }
    });
};
</script>
<template>
    <div class="fixed left-0 top-0 w-full h-full z-[1000]">
        <div
            @click="close"
            class="fixed left-0 top-0 w-full h-full bg-[rgba(0,0,0,0.7)]"
        ></div>
        <div class="w-full h-full flex items-center justify-center">
            <div class="flex flex-wrap w-full p-5">
                <div
                    class="p-10 bg-white max-w-[700px] w-[90%] text-center mx-auto rounded-md relative"
                >
                    <button
                        @click="close"
                        class="absolute top-2 right-2 bg-white w-10 h-10 text-black text-3xl z-20 rounded-xl"
                    >
                        <i class="fa fa-xmark"></i>
                    </button>

                    <div class="w-full" v-if="!success">
                        <h2 class="font-bold text-xl mb-5">Ubah Password</h2>
                        <div class="relative mt-4">
                            <TextInput
                                id="current_password"
                                :type="
                                    showPasswordCurrent ? 'text' : 'password'
                                "
                                placeholder="Masukan kata sandi saat ini"
                                class="mt-1 block w-full"
                                v-model="form.current_password"
                                required
                                autocomplete="current-password"
                            />
                            <i
                                class="fa absolute right-3 top-3 cursor-pointer"
                                :class="[
                                    { 'fa-eye': showPasswordCurrent },
                                    {
                                        'fa-eye-slash': !showPasswordCurrent,
                                    },
                                ]"
                                @click="
                                    showPasswordCurrent = !showPasswordCurrent
                                "
                            ></i>
                        </div>
                        <div class="relative mt-4">
                            <TextInput
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="Masukan kata sandi baru"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                            <i
                                class="fa absolute right-3 top-3 cursor-pointer"
                                :class="[
                                    { 'fa-eye': showPassword },
                                    { 'fa-eye-slash': !showPassword },
                                ]"
                                @click="showPassword = !showPassword"
                            ></i>
                        </div>
                        <div class="relative mt-4">
                            <TextInput
                                id="password"
                                :type="
                                    showPasswordConfirm ? 'text' : 'password'
                                "
                                placeholder="Konfirmasi kata sandi baru"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="current-password"
                            />
                            <i
                                class="fa absolute right-3 top-3 cursor-pointer"
                                :class="[
                                    { 'fa-eye': showPasswordConfirm },
                                    {
                                        'fa-eye-slash': !showPasswordConfirm,
                                    },
                                ]"
                                @click="
                                    showPasswordConfirm = !showPasswordConfirm
                                "
                            ></i>
                        </div>

                        <div
                            class="flex w-full mt-9 items-center justify-center"
                        >
                            <SpinnerLoader v-if="isLoader" />
                            <div v-else class="flex gap-5">
                                <SecondaryButton
                                    @click="save"
                                    type="button"
                                    as="button"
                                    class="justify-center"
                                >
                                    Ubah Kata Sandi
                                </SecondaryButton>
                                <AOutlineButton
                                    @click="close"
                                    type="button"
                                    as="button"
                                    class="ml-auto"
                                >
                                    Cancel
                                </AOutlineButton>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <ANSuccessCheck />
                        <h3 class="font-medium">{{ message }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
