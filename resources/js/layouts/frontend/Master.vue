<template>
    <div>
        <Head></Head>
        <Header></Header>
        <slot />
        <Footer></Footer>
    </div>
</template>
<script setup>
    import Header from '@/layouts/frontend/Header.vue';
    import Footer from '@/layouts/frontend/Footer.vue';
</script>
