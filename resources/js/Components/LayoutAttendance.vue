<template>
    <div>
        <Header>
            <template #heading>
                <slot name="heading"></slot>
            </template>
        </Header>

        <div class="border-radius-xl mt-4 mx-4 position-relative" :style="'background-image: url(' + backgroundImage + '); background-size: cover'">
            <main class="main-content mt-1 border-radius-lg">
                <div class="section min-vh-85 position-relative">
                    <div class="container">
                        <div class="row pt-4 pb-3">
                            <div class="col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <a v-for="user in workingUsers" :key="user.id" href="javascript:;" class="ms-2" :title="user.name">
                                            <img :src="user.avatar ? '/storage/' + user.avatar : '/assets/img/logo-sq.png'" class="rounded-circle img-fluid border border-3 border-white" style="width: 60px; height: 60px" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" :class="workingUsers.length > 0 ? 'pt-2' : 'pt-6'">
                            <Sidenav />

                            <div class="col-lg-10 col-md-10">
                                <slot />
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- <Footer /> -->
    </div>
</template>

<script setup>
import Header from "./Header.vue";
import Sidenav from "./Sidenav.vue";
import Footer from "./Footer.vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { ref, onMounted, computed } from "vue";
import "axios";
import Echo from "laravel-echo";

let backgroundImage = ref("/assets/img/pexels-max-vakhtbovych-7750123.jpg");

const workingUsers = ref([]);
const background = computed(() => usePage().props.value.auth.user.background);

onMounted(() => {
    document.body.classList.add("virtual-reality");
    document.getElementById("navbar-main").classList.add("mt-3");
    document.getElementById("navbar-main").classList.add("mx-3");
    document.getElementById("navbar-main").classList.add("bg-primary");

    if (background.value) {
        backgroundImage.value = "/storage/" + background.value;
    }

    getWorkingUsers();
});

window.Echo.channel("status-channel").listen(".status-event", (e) => {
    console.log(e.message);
    getWorkingUsers();
});

const getWorkingUsers = () => {
    axios.get("/attendance/working-user").then((response) => {
        workingUsers.value = response.data;
    });
};
</script>
