<template>
    <div class="col-lg-1 col-md-1 pt-5 pt-lg-0 ms-lg-5 text-center">
        <Link href="/profile" class="avatar avatar-md border-0 mb-3" title="My Profile">
            <img v-if="avatar" :src="'/storage/' + avatar" class="h-100 border-radius-lg" />
            <img v-else src="/assets/img/logo-sq.png" class="h-100 border-radius-lg" />
        </Link>
        <Link href="/attendance" :class="$page.component === 'Attendance/Index' ? 'btn-primary' : 'btn-white'" class="btn border-radius-lg p-2 mt-0 mt-md-2 mx-2 mx-md-0" title="Home">
            <i class="fas fa-home p-2"></i>
        </Link>
        <Link href="/attendance/history" :class="$page.component === 'Attendance/History' ? 'btn-primary' : 'btn-white'" class="btn border-radius-lg p-2" title="History">
            <i class="fas fa-history p-2"></i>
        </Link>
        <Link href="/attendance/performance" :class="$page.component === 'Attendance/Performance' ? 'btn-primary' : 'btn-white'" class="btn border-radius-lg p-2" title="Performance">
            <i class="fas fa-chart-line p-2"></i>
        </Link>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage, Link } from "@inertiajs/inertia-vue3";

const avatar = computed(() => usePage().props.value.auth.user.avatar);
</script>
