<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem, type SharedData, type User,type NavItem } from '@/types';


interface Props {
    status?: string;
    method?: string;
    administrator: User;
}

const props = defineProps<Props>();

const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('administrator.updatePassword',{administrator:props.administrator.id}), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (errors: any) => {
            if (errors.password) {
                form.reset('password', 'password_confirmation');
                if (passwordInput.value instanceof HTMLInputElement) {
                    passwordInput.value.focus();
                }
            }
        },
    });
};

const navItems: NavItem[] = [
    {
        title: 'Profile',
        href: props.administrator.id ? '/backend/administrator/' + props.administrator.id + '/edit' : '/backend/administrator/create',
        type: ''
    },
    {
        title: 'Password',
        href: '/backend/administrator/' + props.administrator.id + '/password',
        type: ''
    },
    {
        title: 'Roles & Permissions',
        href: `/backend/administrator/${props.administrator.id}/role-permissions`,
        type: '',
    },
]
</script>

<template>
    <AppLayout>
        <Head title="Password settings" />

        <SettingsLayout title="Administrator settings" description="Manage administrator profile and account settings." :navItems="navItems">
            <div class="space-y-6">
                <HeadingSmall title="Update password" description="Ensure this administrator is using a long, random password to stay secure" />

                <form @submit.prevent="updatePassword" class="space-y-6">

                    <div class="grid gap-2">
                        <Label for="password">New password</Label>
                        <Input
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                            placeholder="New password"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirm password</Label>
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                            placeholder="Confirm password"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save password</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
