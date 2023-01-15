<template>
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 bg-default" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
                <img src="/assets/img/logo.png" class="navbar-brand-img h-100" alt="main_logo" />
                <span class="ms-1 font-weight-bold">Team Performance </span>
            </a>
        </div>
        <hr class="horizontal dark" />
        <div id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li v-if="permissions.includes('view-dashboard')" class="nav-item">
                    <Link class="nav-link" href="/" :class="$page.component === 'Dashboard/Index' ? 'active' : 'btn-white'">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </Link>
                </li>
                <li v-if="permissions.includes('view-report')" class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Report</h6>
                </li>
                <li v-if="permissions.includes('view-report')" class="nav-item">
                    <Link class="nav-link" href="/daily" :class="$page.component === 'Daily/Index' ? 'active' : 'btn-white'">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Daily</span>
                    </Link>
                </li>
                <li v-if="permissions.includes('view-report')" class="nav-item">
                    <Link class="nav-link" href="/reports" :class="$page.component === 'Report/Index' || $page.component === 'Report/Create' || $page.component === 'Report/Edit' || $page.component === 'Report/Show' ? 'active' : 'btn-white'">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-album-2 text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Monthly</span>
                    </Link>
                </li>
                <li v-if="permissions.includes('manage-report') || permissions.includes('manage-account')" class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Master</h6>
                </li>
                <li v-if="permissions.includes('manage-report')" class="nav-item">
                    <Link class="nav-link" href="/projects" :class="$page.component === 'Project/Index' || $page.component === 'Project/Create' || $page.component === 'Project/Edit' ? 'active' : 'btn-white'">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-collection text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Project</span>
                    </Link>
                </li>
                <li v-if="permissions.includes('manage-account')" class="nav-item">
                    <Link class="nav-link" href="/users" :class="$page.component === 'User/Index' || $page.component === 'User/Create' || $page.component === 'User/Edit' ? 'active' : 'btn-white'">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Account</span>
                    </Link>
                </li>
                <hr v-if="permissions.includes('doing-attendance')" class="horizontal dark" />
                <li v-if="permissions.includes('doing-attendance')" class="nav-item">
                    <Link class="nav-link" href="/attendance">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-watch-time text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Attendance</span>
                    </Link>
                </li>
            </ul>
        </div>
    </aside>
</template>

<style>
.btn-white:hover,
.btn.bg-gradient-white:hover {
    background-color: unset !important;
    border-color: unset !important;
}
</style>

<script setup>
import { computed } from "vue";
import { usePage, Link } from "@inertiajs/inertia-vue3";

const permissions = computed(() => usePage().props.value.auth.user.permissions);
</script>
