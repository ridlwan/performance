<template>
    <div>
        <Sidebar />

        <div class="page-container">
            <Header>
                <template #heading>
                    <slot name="heading"></slot>
                </template>
            </Header>

            <slot />

            <Footer />
        </div>
    </div>
</template>

<script setup>
import Sidebar from "./Sidebar.vue";
import Header from "./Header.vue";
import Footer from "./Footer.vue";
</script>
