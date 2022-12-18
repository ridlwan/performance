<template>
    <div class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-logo">
                <div class="peers ai-c fxw-nw">
                    <div class="peer peer-greed">
                        <Link class="sidebar-link td-n" href="/">
                            <div class="peers ai-c fxw-nw">
                                <div class="peer">
                                    <div class="logo"><img src="https://colorlib.com/polygon/adminator/assets/static/images/logo.png" alt="" /></div>
                                </div>
                                <div class="peer peer-greed">
                                    <h5 class="lh-1 mB-0 logo-text">Adminator</h5>
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
                <li class="nav-item mT-30">
                    <Link class="sidebar-link" href="/"
                        ><span class="icon-holder"><i class="fa-solid fa-house c-blue-500"></i></span><span class="title">Dashboard</span></Link
                    >
                </li>
                <li class="nav-item mT-10">
                    <Link class="sidebar-link" href="/documents"
                        ><span class="icon-holder"><i class="fa-solid fa-rectangle-list c-blue-500"></i></span><span class="title">Document List</span></Link
                    >
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { useToggleStore } from "../stores/ToggleStore";

let toggle = useToggleStore();
</script>
