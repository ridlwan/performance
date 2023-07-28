<template>
    <nav
        class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
        id="navbar-main"
        data-scroll="false"
    >
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <slot name="heading"></slot>
            </nav>
            <div
                class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4"
                id="navbar"
            >
                <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
                <ul class="navbar-nav justify-content-end">
                    <li
                        class="nav-item d-flex align-items-center"
                        :class="{
                            'px-3': permissions.includes('view-dashboard'),
                        }"
                    >
                        <Link
                            href="/profile"
                            class="nav-link text-white font-weight-bold px-0"
                        >
                            <i class="fa fa-user me-sm-1"></i>
                            <span class="d-sm-inline d-none">{{
                                username
                            }}</span>
                        </Link>
                    </li>
                    <li
                        class="nav-item d-xl-none me-3 d-flex align-items-center"
                    >
                        <a
                            class="nav-link text-white p-0"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasExample"
                            role="button"
                            aria-controls="offcanvasExample"
                        >
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                            </div>
                        </a>
                    </li>
                    <li
                        v-if="permissions.includes('view-dashboard')"
                        class="nav-item d-flex align-items-center"
                    >
                        <Link href="/" class="nav-link text-white p-0"
                            ><i class="fa-solid fa-desktop"></i
                        ></Link>
                    </li>
                    <li class="nav-item px-3 d-flex align-items-center">
                        <Link
                            href="/logout"
                            method="post"
                            class="nav-link text-white p-0"
                            ><i class="fa-solid fa-power-off"></i
                        ></Link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { computed } from "vue";
import { usePage, Link } from "@inertiajs/inertia-vue3";
import { useToggleStore } from "../stores/ToggleStore";

const username = computed(() => usePage().props.value.auth.user.username);
const permissions = computed(() => usePage().props.value.auth.user.permissions);

let toggle = useToggleStore();

function navbarMinimize(el) {
    var sidenavShow = document.getElementsByClassName("g-sidenav-show")[0];

    if (!el.getAttribute("checked")) {
        sidenavShow.classList.remove("g-sidenav-pinned");
        sidenavShow.classList.add("g-sidenav-hidden");
        el.setAttribute("checked", "true");
    } else {
        sidenavShow.classList.remove("g-sidenav-hidden");
        sidenavShow.classList.add("g-sidenav-pinned");
        el.removeAttribute("checked");
    }
}
</script>
