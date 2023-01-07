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
import { ref, onBeforeMount } from "vue";
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

onBeforeMount(() => {
    renderChart();
});

const renderChart = () => {
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
