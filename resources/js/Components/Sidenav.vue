<template>
    <div class="col-lg-1 col-md-1 pt-5 pt-lg-0 ms-lg-5 text-center">
        <a href="javascript:;" class="avatar avatar-md border-0 d-block mb-3" data-bs-toggle="tooltip" data-bs-placement="left" title="My Profile">
            <img class="border-radius-lg" alt="Image placeholder" src="/assets/img/team-1.jpg" />
        </a>
        <button class="btn btn-white border-radius-lg p-2 mt-0 mt-md-2 d-block mx-2 mx-md-0" type="button" data-bs-toggle="tooltip" data-bs-placement="left" title="Home">
            <i class="fas fa-home p-2"></i>
        </button>
        <button class="btn btn-white border-radius-lg p-2 d-block" type="button" data-bs-toggle="tooltip" data-bs-placement="left" title="History">
            <i class="fas fa-history p-2"></i>
        </button>
        <button class="btn btn-white border-radius-lg p-2 d-block ms-2 ms-md-0" type="button" data-bs-toggle="tooltip" data-bs-placement="left" title="Report">
            <i class="fas fa-chart-line p-2"></i>
        </button>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { useToggleStore } from "../stores/ToggleStore";

let toggle = useToggleStore();
</script>
