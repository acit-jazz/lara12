<script setup>
import axios from "axios";
import { defineAsyncComponent, ref } from "vue";
import { POPUP_CHANGE_PASSWORD } from "@/Constants";

const PopupConfirmationCode = defineAsyncComponent(() =>
    import("@/Components/Popups/ChangePassword/PopupConfirmationCode.vue")
);
const PopupConfirmation = defineAsyncComponent(() =>
    import("@/Components/Popups/ChangePassword/PopupConfirmation.vue")
);
const PopupChangePasswordForm = defineAsyncComponent(() =>
    import("@/Components/Popups/ChangePassword/PopupChangePasswordForm.vue")
);

const openForm = ref("");

const close = () => {
    openForm.value = !openForm.value;
};
const openPopup = (value) => {
    openForm.value = value;
};
let props = defineProps({
    data: Array,
});
</script>

<template>
    <div>
        <a
            @click="openPopup(POPUP_CHANGE_PASSWORD.confirmation)"
            class="cursor-pointer inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
        >
            Ubah Kata Sandi
        </a>
        <PopupConfirmation
            @close="close"
            @open="openPopup"
            v-if="openForm === POPUP_CHANGE_PASSWORD.confirmation"
        />
        <PopupConfirmationCode
            @close="close"
            @open="openPopup"
            v-if="openForm === POPUP_CHANGE_PASSWORD.confirmationCode"
        />
        <PopupChangePasswordForm
            @close="close"
            @open="openPopup"
            v-if="openForm === POPUP_CHANGE_PASSWORD.changePasswordForm"
        />
    </div>
</template>
