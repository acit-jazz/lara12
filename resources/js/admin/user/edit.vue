<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User, type NavItem } from '@/types';

interface Props {
    status?: string;
    method?: string;
    user: User;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User settings',
        href: '/backend/settings/profile',
    },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

const submit = () => {
    form.patch(route('user.update',{user:props.user.id}), {
        preserveScroll: true,
    });
};

const navItems: NavItem[] = [
    {
        title: 'Profile',
        href: props.user.id ? '/backend/user/' + props.user.id + '/edit' : '/backend/user/create',
        type: ''
    },
    {
        title: 'Password',
        href: '/backend/user/' + props.user.id + '/password',
        type: ''
    },
]
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="User settings" />

        <SettingsLayout title="User settings" description="Manage user profile and account settings." :navItems="navItems">
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="User information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

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
