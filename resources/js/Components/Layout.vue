<template>
    <div>
        <div class="min-height-300 bg-primary position-absolute w-100"></div>
        <Sidebar />

        <main class="main-content position-relative border-radius-lg">
            <Header>
                <template #heading>
                    <slot name="heading"></slot>
                </template>
            </Header>

            <div class="container-fluid py-4">
                <slot />

                <Footer />
            </div>
        </main>
    </div>
</template>

<script setup>
import Sidebar from "./Sidebar.vue";
import Header from "./Header.vue";
import Footer from "./Footer.vue";
</script>
