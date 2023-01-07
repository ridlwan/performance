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

<style>
.apexcharts-active {
    color: black !important;
}
</style>

<script setup>
import Sidebar from "./Sidebar.vue";
import Header from "./Header.vue";
import Footer from "./Footer.vue";
import { onMounted, computed } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { useToast } from "vue-toastification";

const toast = useToast();

const darkmode = computed(() => usePage().props.value.auth.user.darkmode);

onMounted(() => {
    document.body.classList.remove("virtual-reality");
    document.getElementById("navbar-main").classList.remove("mt-3");
    document.getElementById("navbar-main").classList.remove("mx-3");
    document.getElementById("navbar-main").classList.remove("bg-primary");

    toast.success("My toast content");

    if (darkmode.value == "Yes") {
        document.body.classList.add("dark-version");
    } else {
        document.body.classList.remove("dark-version");
    }
});
</script>
