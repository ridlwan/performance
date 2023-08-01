<template>
    <div>
        <Header>
            <template #heading>
                <slot name="heading"></slot>
            </template>
        </Header>

        <div
            class="border-radius-xl mt-4 mx-4 mb-3 position-relative"
            :style="
                'background-image: url(' +
                backgroundImage +
                '); background-size: cover'
            "
        >
            <main class="main-content mt-1 border-radius-lg">
                <div class="section min-vh-85 position-relative">
                    <div class="container mb-3">
                        <div class="row pt-4 pb-3">
                            <div class="col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <a
                                            v-for="user in workingUsers"
                                            :key="user.id"
                                            href="javascript:;"
                                            class="ms-2"
                                            :title="user.name"
                                            v-tooltip="
                                                user.name +
                                                ' (' +
                                                user.status_text +
                                                ')'
                                            "
                                        >
                                            <img
                                                :src="
                                                    user.avatar
                                                        ? '/storage/' +
                                                          user.avatar
                                                        : '/assets/img/logo-sq.png'
                                                "
                                                class="rounded-circle img-fluid border border-3"
                                                style="
                                                    width: 60px;
                                                    height: 60px;
                                                    border-color: #5e72e4 !important;
                                                "
                                                :style="
                                                    darkmode == 'Yes'
                                                        ? 'background-color: #5e72e4;'
                                                        : 'background-color: white;'
                                                "
                                            />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="row"
                            :class="workingUsers.length > 0 ? 'pt-2' : 'pt-6'"
                        >
                            <Sidenav />

                            <div class="col-lg-10 col-md-10 mb-5">
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

<style>
.apexcharts-active {
    color: black !important;
}
</style>

<script setup>
import Header from "./Header.vue";
import Sidenav from "./Sidenav.vue";
import Footer from "./Footer.vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { ref, onMounted, computed } from "vue";
import "axios";
import Echo from "laravel-echo";
import { useToast } from "vue-toastification";

const toast = useToast();

let backgroundImage = ref("/assets/img/pexels-max-vakhtbovych-7750123.jpg");

const workingUsers = ref([]);
const username = computed(() => usePage().props.value.auth.user.username);
const background = computed(() => usePage().props.value.auth.user.background);
const darkmode = computed(() => usePage().props.value.auth.user.darkmode);

onMounted(() => {
    document.body.classList.add("virtual-reality");
    document.getElementById("navbar-main").classList.add("mt-3");
    document.getElementById("navbar-main").classList.add("mx-3");
    document.getElementById("navbar-main").classList.add("bg-primary");

    if (background.value) {
        backgroundImage.value = "/storage/" + background.value;
    }

    if (darkmode.value == "Yes") {
        document.body.classList.add("dark-version");
    } else {
        document.body.classList.remove("dark-version");
    }

    getWorkingUsers();
});

window.Echo.channel("status-channel").listen(".status-event", (e) => {
    if (username.value != e.user) {
        if (e.type == "checkIn") {
            toast(e.user + " " + e.message, {
                icon: "fa-solid fa-person-walking-arrow-right",
            });
        }

        if (e.type == "checkOut") {
            toast.error(e.user + " " + e.message, {
                icon: "fa-solid fa-person-walking-dashed-line-arrow-right",
            });
        }

        if (e.type == "outOffice") {
            toast.error(e.user + " " + e.message, {
                icon: "fas fa-person-walking-luggage ms-1",
            });
        }

        if (e.type == "outSick") {
            toast.error(e.user + " " + e.message, {
                icon: "fas fa-bed ms-1",
            });
        }
    }

    getWorkingUsers();
});

window.Echo.channel("activity-channel").listen(".activity-event", (e) => {
    if (username.value != e.user) {
        if (e.type == "add") {
            toast.success(e.user + " " + e.message, {
                icon: "fa-solid fa-pen-to-square",
            });
        }
        if (e.type == "update") {
            toast.info(e.user + " " + e.message, {
                icon: "fa-solid fa-eraser",
            });
        }
        if (e.type == "struggling") {
            toast.warning(e.user + " " + e.message, {
                icon: "fa-solid fa-user-ninja",
            });
        }
    }
});

window.Echo.channel("assignment-channel").listen(".assignment-event", (e) => {
    if (e.type == "assign") {
        toast.info(e.user + " " + e.message, {
            icon: "fa-solid fa-calendar-check",
        });
    }

    if (e.type == "unassign") {
        toast.warning(e.user + " " + e.message, {
            icon: "fa-solid fa-calendar-xmark",
        });
    }
});

const getWorkingUsers = () => {
    axios.get("/attendance/working-user").then((response) => {
        workingUsers.value = response.data;
    });
};
</script>
