<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import Toastify from 'toastify-js';
import "toastify-js/src/toastify.css";
import { computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { BreadcrumbItemType } from '@/types';

interface Props {
  breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});

const page = usePage();

// Ambil flash message dari props
const flash = computed(() => page.props.flash);

// Watch perubahan flash
watch(flash, (val) => {
  if (val?.has_flash) {
    Toastify({
      text: val.message,
      duration: 3000,
      newWindow: true,
      close: true,
      gravity: "top",
      position: "right",
      stopOnFocus: true,
      style: {
        background: "linear-gradient(to right, #00b09b, #96c93d)",
        borderRadius: "10px 10px 0 10px",
      },
      onClick: function () {},
    }).showToast();
  }
}, { deep: true });
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" >
        <slot />
    </AppLayout>
</template>
