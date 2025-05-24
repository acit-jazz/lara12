<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref ,nextTick,onMounted } from 'vue'

let props  = defineProps({
    page: Object,
    contact_submission:Object,
});
const form = ref(useForm(props.contact_submission));
const success = ref(false);
const submit = () => {
    form.value.post(route("contact-us.store"),{
        preserveScroll: false,
        onFinish: (res) => {
        },
        onError: (res) => {
        },
        onSuccess: (res) => {
          form.value.reset();
          success.value = true;
        },
      }
    );
};
</script>

<template>
    <Master>
        <section class="flex items-center min-h-screen ">
        <div class="w-2xl mx-auto">
            <div class="w-full">
            <div v-if="success"  class="py-10">
                <p><strong class="text-3xl font-bold block mb-3">Thank you for reaching out to us!</strong>Your message has been successfully sent.<br> Our team will review your inquiry and get back to you shortly.</p>
            </div>
            <div  v-else>
                    <h1 class="font-bold text-2xl">{{page.title ?? 'Contact Us | Get in Touch with Our Team'}}</h1>
                    <p>{{page.summary ?? 'Fill out the form below and our team will respond as soon as possible.'}}</p>
                    <form @submit.prevent="submit" class="py-10" >
                        <div class="block">
                            <TextInput
                                type="text"
                                class="mt-1 block w-full bg-[rgba(255,255,255,0.2)] py-3 rounded-none border-0 shadow-[0_0_15px_rgba(0,0,0,0.5)]"
                                placeholder="Your Name"
                                v-model="form.name"
                            />
                            <span class="text-rose-400 mt-2 text-sm" v-html="form.errors.name" ></span>
                        </div>
                        <div class="block mt-4">
                            <TextInput
                                type="email"
                                class="mt-1 block w-full bg-[rgba(255,255,255,0.2)] py-3 rounded-none border-0 shadow-[0_0_15px_rgba(0,0,0,0.5)]"
                                placeholder="Your Email"
                                v-model="form.email"
                            />
                            <span class="text-rose-400 mt-2 text-sm" v-html="form.errors.email" ></span>
                        </div>
                        <div class="block mt-4">
                            <TextInput
                                type="text"
                                class="mt-1 block w-full bg-[rgba(255,255,255,0.2)] py-3 rounded-none border-0 shadow-[0_0_15px_rgba(0,0,0,0.5)]"
                                placeholder="Your Phone"
                                v-model="form.phone"
                            />
                            <span class="text-rose-400 mt-2 text-sm" v-html="form.errors.phone" ></span>
                        </div>
                        <div class="block mt-4">
                            <TextInput
                                type="text"
                                class="mt-1 block w-full bg-[rgba(255,255,255,0.2)] py-3 rounded-none border-0 shadow-[0_0_15px_rgba(0,0,0,0.5)]"
                                placeholder="Subject"
                                v-model="form.subject"
                            />
                            <span class="text-rose-400 mt-2 text-sm" v-html="form.errors.subject" ></span>
                        </div>
                        <div class="block mt-4">
                        <TextAreaInput
                            class="mt-1 block w-full bg-[rgba(255,255,255,0.2)] min-h-[150px] rounded-none border-0 shadow-[0_0_15px_rgba(0,0,0,0.5)]"
                            placeholder="Message"
                            v-model="form.message"
                        />
                        <span class="text-rose-400 mt-2 text-sm" v-html="form.errors.message" ></span>
                        </div>
                        <div class="mt-10">
                            <PrimaryButton  type="button" @click="submit" class="w-full block cursor-pointer text-center py-3 px-3 justify-center  rounded-md">
                                Save
                            </PrimaryButton>
                        </div>
                    </form>
            </div>
            </div>
        </div>
        </section>
    </Master>
</template>
