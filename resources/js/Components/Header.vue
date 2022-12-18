<template>
    <div class="header navbar">
        <div class="header-container">
            <ul class="nav-left">
                <li>
                    <a id="sidebar-toggle" class="sidebar-toggle" href="javascript:void(0);" @click="toggle.toggleClick"><i class="fa-solid fa-bars"></i></a>
                </li>
                <slot name="heading"></slot>
            </ul>

            <ul class="nav-right">
                <li class="notifications dropdown">
                    <span class="counter bgc-red">3</span><a href="" class="dropdown-toggle no-after" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-bell"></i></a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li class="pX-20 pY-15 bdB"><i class="fa-regular fa-bell pR-10"></i><span class="fsz-sm fw-600 c-grey-900">Notifications</span></li>
                        <li>
                            <ul class="ovY-a pos-r scrollable lis-n p-0 m-0 fsz-sm ps">
                                <li>
                                    <a href="" class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                                        <div class="peer mR-15"><img class="w-3r bdrs-50p" src="https://randomuser.me/api/portraits/men/1.jpg" alt="" /></div>
                                        <div class="peer peer-greed">
                                            <span
                                                ><span class="fw-500">John Doe</span><span class="c-grey-600">liked your <span class="text-dark">post</span></span></span
                                            >
                                            <p class="m-0"><small class="fsz-xs">5 mins ago</small></p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="" class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                                        <div class="peer mR-15"><img class="w-3r bdrs-50p" src="https://randomuser.me/api/portraits/men/2.jpg" alt="" /></div>
                                        <div class="peer peer-greed">
                                            <span
                                                ><span class="fw-500">Moo Doe</span><span class="c-grey-600">liked your <span class="text-dark">cover image</span></span></span
                                            >
                                            <p class="m-0"><small class="fsz-xs">7 mins ago</small></p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="" class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                                        <div class="peer mR-15"><img class="w-3r bdrs-50p" src="https://randomuser.me/api/portraits/men/3.jpg" alt="" /></div>
                                        <div class="peer peer-greed">
                                            <span
                                                ><span class="fw-500">Lee Doe</span><span class="c-grey-600">commented on your <span class="text-dark">video</span></span></span
                                            >
                                            <p class="m-0"><small class="fsz-xs">10 mins ago</small></p>
                                        </div>
                                    </a>
                                </li>
                                <div class="ps__rail-x" style="left: 0px; bottom: 0px">
                                    <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px"></div>
                                </div>
                                <div class="ps__rail-y" style="top: 0px; right: 0px">
                                    <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px"></div>
                                </div>
                            </ul>
                        </li>
                        <li class="pX-20 pY-15 ta-c bdT">
                            <span><a href="" class="c-grey-600 cH-blue fsz-sm td-n">View All Notifications</a></span>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-bs-toggle="dropdown">
                        <div class="peer mR-10"><img class="w-2r bdrs-50p" src="https://randomuser.me/api/portraits/men/10.jpg" alt="" /></div>
                        <div class="peer">
                            <span class="fsz-sm c-grey-900">{{ username }}</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu fsz-sm">
                        <li>
                            <Link href="/logout" method="post" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="fa-solid fa-power-off mR-10"></i><span>Logout</span></Link>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage, Link } from "@inertiajs/inertia-vue3";
import { useToggleStore } from "../stores/ToggleStore";

const username = computed(() => usePage().props.value.auth.user.username);

let toggle = useToggleStore();
</script>
