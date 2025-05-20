<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-0 py-0 border-t">
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <h3 v-if="item.type == 'header'" class="font-semibold text-sm px-2 border-y py-3">{{ item.title }}</h3>
                <SidebarMenuButton  v-else
                    as-child :is-active="item.href === page.url"
                    :tooltip="item.title"
                    class="!py-3 !px-4 h-auto"
                >
                    <Link :href="item.href">
                        <i class="fa " :class="item.icon"></i>
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
