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

.breadcrumb-item.text-white::before {
    color: #fff;
    font-size: 1.1rem;
}
</style>

<script setup>
import Sidebar from "./Sidebar.vue";
import Header from "./Header.vue";
import Footer from "./Footer.vue";
import { onMounted, computed, onBeforeMount, onBeforeUpdate } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { useToast } from "vue-toastification";
import Echo from "laravel-echo";

const toast = useToast();

const username = computed(() => usePage().props.value.auth.user.username);
const darkmode = computed(() => usePage().props.value.auth.user.darkmode);
const created = computed(() => usePage().props.value.flash.created);
const updated = computed(() => usePage().props.value.flash.updated);
const deleted = computed(() => usePage().props.value.flash.deleted);

window.Echo.channel("status-channel").listen(".status-event", (e) => {
    if (username.value != e.user) {
        if (e.message == "just checked in") {
            toast(e.user + " " + e.message, {
                icon: "fa-solid fa-person-walking-arrow-right",
            });
        }

        if (e.message == "just checked out") {
            toast.error(e.user + " " + e.message, {
                icon: "fa-solid fa-person-walking-dashed-line-arrow-right",
            });
        }

        if (e.message == "is marked out off office") {
            toast.error(e.user + " " + e.message, {
                icon: "fas fa-person-walking-luggage ms-1",
            });
        }

        if (e.message == "is marked out sick") {
            toast.error(e.user + " " + e.message, {
                icon: "fas fa-bed ms-1",
            });
        }
    }
});

window.Echo.channel("activity-channel").listen(".activity-event", (e) => {
    if (username.value != e.user) {
        if (e.message == "just added a new activity") {
            toast.success(e.user + " " + e.message, {
                icon: "fa-solid fa-pen-to-square",
            });
        }
        if (e.message == "just updated the activity") {
            toast.info(e.user + " " + e.message, {
                icon: "fa-solid fa-eraser",
            });
        }
        if (e.message == "is struggling in current activity") {
            toast.warning(e.user + " " + e.message, {
                icon: "fa-solid fa-user-ninja",
            });
        }
    }
});

onMounted(() => {
    document.body.classList.remove("virtual-reality");
    document.getElementById("navbar-main").classList.remove("mt-3");
    document.getElementById("navbar-main").classList.remove("mx-3");
    document.getElementById("navbar-main").classList.remove("bg-primary");

    if (darkmode.value == "Yes") {
        document.body.classList.add("dark-version");
    } else {
        document.body.classList.remove("dark-version");
    }

    if (created.value) {
        toast.success(created.value, {
            icon: "fa-solid fa-check",
            timeout: 3000,
        });
    }

    if (updated.value) {
        toast.info(updated.value, {
            icon: "fa-solid fa-pen-to-square",
            timeout: 3000,
        });
    }
});

onBeforeUpdate(() => {
    if (deleted.value) {
        toast.error(deleted.value, {
            icon: "fa-solid fa-trash-can",
            timeout: 3000,
        });
    }
});
</script>
