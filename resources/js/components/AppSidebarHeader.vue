<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';

withDefaults(defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>(),{
    breadcrumbs:()=>$page.props.breadcumbs ?? [],
});
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2 w-full">
            <SidebarTrigger class="-ml-1" />
             {{ $page.props.title ?? 'Dashboard'}}
                <nav
                    class="left-0 ml-auto md:flex-row md:flex-nowrap md:justify-start hidden lg:flex items-center px-4 pb-0"
                    v-if="$page.props.breadcumb">
                    <div
                    class="w-full mx-autp items-center flex  md:flex-nowrap flex-wrap md:px-4 px-4 "
                    >
                    <!-- Brand -->
                    <div  v-for="(data,index) in $page.props.breadcumb" :key="index">
                        <a class="text-sm lg:inline-block opacity-50 hover:opacity-100" :href="data.url">
                        {{data.text}}
                        </a>
                        <span v-if="index != Object.keys($page.props.breadcumb).length - 1" class="text-sm  hidden lg:inline-block px-2 opacity-50"> /</span>
                    </div>
                    </div>
                </nav>
        </div>
    </header>
</template>
