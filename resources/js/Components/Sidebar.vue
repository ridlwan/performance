<template>
    <div class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-logo">
                <div class="peers ai-c fxw-nw">
                    <div class="peer peer-greed">
                        <Link class="sidebar-link td-n" href="/">
                            <div class="peers ai-c fxw-nw">
                                <div class="peer mT-20">
                                    <div class="logo"><img src="/assets/logo.png" alt="logo" width="170" /></div>
                                </div>
                            </div>
                        </Link>
                    </div>
                    <div class="peer">
                        <div class="mobile-toggle sidebar-toggle">
                            <a href="javascript:void(0);" class="td-n" @click="toggle.toggleClick"><i class="fa-solid fa-chevron-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="sidebar-menu scrollable pos-r">
                <li
                    class="nav-item"
                    :class="{
                        'nav-active': $page.component === 'Dashboard',
                    }"
                >
                    <Link class="sidebar-link" href="/"
                        ><span class="icon-holder"
                            ><i
                                class="fa-solid fa-house c-blue-500"
                                :class="{
                                    'span-active': $page.component === 'Dashboard',
                                }"
                            ></i></span
                        ><span
                            class="title"
                            :class="{
                                'span-active': $page.component === 'Dashboard',
                            }"
                            >Dashboard</span
                        ></Link
                    >
                </li>
                <li
                    class="nav-item mT-5"
                    :class="{
                        'nav-active': $page.component === 'Document/Index' || $page.component === 'Document/Create' || $page.component === 'Document/Edit' || $page.component === 'Document/Show',
                    }"
                >
                    <Link class="sidebar-link" href="/documents"
                        ><span class="icon-holder"
                            ><i
                                class="fa-solid fa-rectangle-list c-blue-500"
                                :class="{
                                    'span-active': $page.component === 'Document/Index' || $page.component === 'Document/Create' || $page.component === 'Document/Edit' || $page.component === 'Document/Show',
                                }"
                            ></i></span
                        ><span
                            class="title"
                            :class="{
                                'span-active': $page.component === 'Document/Index' || $page.component === 'Document/Create' || $page.component === 'Document/Edit' || $page.component === 'Document/Show',
                            }"
                            >Document List</span
                        ></Link
                    >
                </li>
            </ul>
        </div>
    </div>
</template>

<style>
.nav-active {
    background: #006198 !important;
}

.span-active {
    color: white !important;
}
</style>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { useToggleStore } from "../stores/ToggleStore";

let toggle = useToggleStore();
</script>
