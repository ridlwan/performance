<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">Performance</h6>
        </template>

        <div class="card">
            <div class="card-body pb-2">
                <div class="progress-wrapper mb-3 p-2">
                    <div class="progress-info text-center mb-2">
                        <div class="progress-percentage">
                            <span class="text-sm font-weight-bold text-dark"
                                >Your current performance is <span class="text-primary">{{ progressPercentage }}% ({{ progressHours }} hours)</span> of this month's target of <span class="text-primary">{{ mandays }} hours</span></span
                            >
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" :style="`width: ${progressPercentage}%`"></div>
                    </div>
                </div>
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
    mandays: Number,
    progressHours: Number,
    progressPercentage: Number,
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
            text: "Performance Graph",
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
