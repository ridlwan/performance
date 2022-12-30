<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">Performance</h6>
        </template>

        <div class="card">
            <div class="card-body pb-2">
                <apexchart type="area" height="350" :options="chartOptions" :series="series"></apexchart>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Pagination from "../../Components/Pagination.vue";
import $ from "jquery";
import { Inertia } from "@inertiajs/inertia";
import { ref, reactive, onMounted, computed } from "vue";
import { usePage, Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Components/LayoutAttendance.vue";
import Swal from "sweetalert2";
import apexchart from "vue3-apexcharts";

const props = defineProps({
    hours: Array,
    dates: Array,
});

let series = ref([]);

let chartOptions = ref({});

onMounted(() => {
    document.body.classList.add("virtual-reality");
    document.getElementById("navbar-main").classList.add("mt-3");
    document.getElementById("navbar-main").classList.add("mx-3");
    document.getElementById("navbar-main").classList.add("bg-primary");

    setTimeout(() => {
        updateChart();
    }, 0);
});

const updateChart = () => {
    series.value = [
        {
            name: "Hours",
            data: props.hours,
        },
    ];

    chartOptions.value = {
        chart: {
            height: 350,
            type: "area",
            zoom: {
                enabled: false,
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
        },
        title: {
            text: "Current Performance",
            align: "left",
        },
        grid: {
            row: {
                colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                opacity: 0.5,
            },
        },
        xaxis: {
            labels: {
                rotate: -45,
            },
            categories: props.dates,
        },
        yaxis: {
            title: {
                text: "Hours",
            },
        },
    };
};
</script>
