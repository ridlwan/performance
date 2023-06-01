<template>
    <Layout>
        <template #heading>
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-white">
                    <Link href="/reports" class="opacity-5 text-white"><h6 class="font-weight-bolder text-white mb-0">Monthly Report</h6></Link>
                </li>
                <li class="breadcrumb-item text-white active" style="width: 300px">
                    <h6 class="font-weight-bolder text-white mb-0">
                        {{ report.name }} <Link v-if="permissions.includes('manage-report') && report.published_text == 'No'" :href="`/reports/${report.id}/edit`"> <i class="fas fa-pencil-alt text-info ms-1"></i></Link>
                    </h6>
                </li>
            </ol>
        </template>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="donut" height="400" :options="supportChart" :series="supportSeries"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="pie" height="400" :options="inchargeChart" :series="inchargeSeries" @dataPointSelection="showInchargeData"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body mt-3 px-0 pt-0 pb-2">
                        <div class="table-responsive p-3 pt-0 pb-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xs text-dark">Personnel</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Position</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Assignment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, userIndex) in users" :key="userIndex">
                                        <td class="align-middle">
                                            <div class="d-flex px-3">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ user.name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ user.email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ user.position }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <button v-for="(responsibility, responsibilityIndex) in user.responsibilities" :key="responsibilityIndex" class="btn btn-sm bg-gradient-primary mb-0 p-2 ms-2" type="button" @click="showUserInchargeData(user, responsibility)">
                                                <span class="btn-inner--text">{{ responsibility }}</span>
                                            </button>
                                            <button class="btn btn-sm bg-gradient-primary mb-0 p-2 ms-2" type="button" @click="showUserInchargeData(user, 'General')">
                                                <span class="btn-inner--text">General</span>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="users.length < 1">
                                        <td colspan="4" class="align-middle text-center text-secondary">Data not found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="bar" height="400" :options="performanceHoursChart" :series="performanceHoursSeries"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="bar" height="400" :options="performancePercentageChart" :series="performancePercentageSeries"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="bar" height="400" :options="outOfOfficeChart" :series="outOfOfficeSeries"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="bar" height="400" :options="outSickChart" :series="outSickSeries"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control" placeholder="Search" v-model="search" @change="filter" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" v-model="user" @change="filter">
                                    <option value="All" selected>All Personnel</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" v-model="paginate" @change="filter">
                                    <option value="10">Show 10 data</option>
                                    <option value="25">Show 25 data</option>
                                    <option value="50">Show 50 data</option>
                                    <option value="100">Show 100 data</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn bg-gradient-secondary" @click="reset">Reset</button>
                            </div>
                            <div class="col-md-2">
                                <a :href="`/reports/${report.id}/export`" class="btn bg-gradient-info">Download Activity</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-3 px-0 pt-0 pb-2" style="min-height: 480px">
                        <div v-if="showChart" class="p-3 pt-0">
                            <apexchart type="line" height="600" :options="dailyChart" :series="dailySeries" @dataPointSelection="showActivitiesData"></apexchart>
                        </div>
                        <div class="table-responsive p-3 pt-0 pb-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xs text-dark">Personnel</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Date</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Start</th>
                                        <th class="text-center text-uppercase text-xs text-dark">End</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Duration</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Status</th>
                                        <th class="text-center text-uppercase text-xs text-dark">View Activity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="attendance in attendances.data" :key="attendance.id">
                                        <td>
                                            <div class="d-flex px-3">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ attendance.user.name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ attendance.user.email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $filters.formatDate(attendance.created_at) }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $filters.formatTime(attendance.start) }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $filters.formatTime(attendance.end) }}</span>
                                        </td>
                                        <td v-if="attendance.duration" class="align-middle text-center">
                                            <span v-if="attendance.duration > 60" class="text-sm text-secondary"
                                                >{{ Math.floor(attendance.duration / 60) }} hours
                                                <span v-if="attendance.duration - Math.floor(attendance.duration / 60) * 60 > 0"> {{ attendance.duration - Math.floor(attendance.duration / 60) * 60 }} minutes </span>
                                            </span>
                                            <span v-else class="text-sm text-secondary">{{ attendance.duration }} minutes</span>
                                        </td>
                                        <td v-else class="align-middle text-center">
                                            <span>-</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-sm text-secondary">{{ attendance.status_text }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a v-if="attendance.activities.length > 0" href="javascript:;" @click="showActivities(attendance)" data-bs-toggle="modal" data-bs-target="#activityModal" class="text-primary font-weight-bold" title="See details"><i class="fa-solid fa-list-check"></i></a>
                                        </td>
                                    </tr>
                                    <tr v-if="attendances.data.length < 1">
                                        <td colspan="7" class="align-middle text-center text-secondary">History not found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination :links="attendances.links" />
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="activityModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title opacity-7" style="color: black !important">
                            {{ username }} <span class="opacity-6">at {{ $filters.formatDate(at) }}</span>
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: black; font-size: 20px; padding-top: 0px">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul v-if="activities.length > 0" class="list-group">
                            <li v-for="activity in activities" :key="activity.id" class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <span v-if="activity.end" class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-check"></i></span>
                                    <span v-else class="btn btn-icon-only btn-rounded btn-outline-warning mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-spinner"></i></span>
                                    <div class="d-flex flex-column">
                                        <p class="mb-0 opacity-7" style="color: black !important">{{ activity.description }}</p>
                                        <p class="mb-0">
                                            <span v-if="activity.project" class="badge bg-gradient-primary" style="text-transform: unset"><i class="fa-solid fa-check-to-slot"></i> {{ activity.project.name }}</span>
                                            <span v-else class="badge bg-gradient-primary" style="text-transform: unset"><i class="fa-solid fa-check-to-slot"></i> General</span>
                                            <span v-if="activity.struggle_text == 'Yes'" class="badge bg-gradient-danger ms-2" style="text-transform: unset; width: 100px"><i class="fa-solid fa-user-ninja"></i> Struggling</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-sm font-weight-bold">
                                    <div class="d-flex flex-column">
                                        <p class="mb-1 text-dark opacity-8" style="color: black !important">{{ $filters.formatTime(activity.start) }}</p>
                                        <span v-if="activity.duration">
                                            <span v-if="activity.duration > 60" class="text-xs text-secondary" style="white-space: nowrap"
                                                >{{ Math.floor(activity.duration / 60) }} hours
                                                <span v-if="activity.duration - Math.floor(activity.duration / 60) * 60 > 0">
                                                    <br />
                                                    {{ activity.duration - Math.floor(activity.duration / 60) * 60 }} minutes
                                                </span>
                                            </span>

                                            <span v-else class="text-xs text-secondary" style="white-space: nowrap">{{ activity.duration }} minutes</span>
                                        </span>
                                        <span v-else class="text-xs text-warning" style="white-space: nowrap">In Progress</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <h6 v-else class="text-center opacity-5" style="color: black !important">{{ status }}</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mt-0 mb-0 btn bg-gradient-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="projectModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title opacity-7" style="color: black !important">{{ project }} ({{ parseFloat((currentInchargeSeries / totalInchargeSeries) * 100).toFixed(1) }}%)</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: black; font-size: 20px; padding-top: 0px">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <apexchart type="pie" height="400" :options="personnelInchargeChart" :series="personnelInchargeSeries"></apexchart>
                            </div>
                            <div class="col-md-6">
                                <apexchart type="pie" height="400" :options="responsibilityInchargeChart" :series="responsibilityInchargeSeries"></apexchart>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive p-3 pt-0 pb-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-xs text-dark">Personnel</th>
                                            <th class="text-center text-uppercase text-xs text-dark">Position</th>
                                            <th class="text-center text-uppercase text-xs text-dark">Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(personnel, personnelIndex) in personnels" :key="personnelIndex">
                                            <td class="align-middle">
                                                <div class="d-flex px-3">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ personnel.name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ personnel.email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ personnel.position }}</span>
                                            </td>
                                            <td v-if="personnel.duration" class="align-middle text-center">
                                                <button type="button" class="btn btn-sm btn-outline-default mb-0" @click="showUserInchargeData(personnel, project)">
                                                    <span v-if="personnel.duration > 60" class="text-sm text-secondary"
                                                        >{{ Math.floor(personnel.duration / 60) }} hours
                                                        <span v-if="personnel.duration - Math.floor(personnel.duration / 60) * 60 > 0"> {{ personnel.duration - Math.floor(personnel.duration / 60) * 60 }} minutes </span>
                                                    </span>
                                                    <span v-else class="text-sm text-secondary">{{ personnel.duration }} minutes</span>
                                                </button>
                                            </td>
                                            <td v-else class="align-middle text-center">
                                                <span>-</span>
                                            </td>
                                        </tr>
                                        <tr v-if="personnels.length < 1">
                                            <td colspan="4" class="align-middle text-center text-secondary">Data not found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mt-0 mb-0 btn bg-gradient-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="userProjectModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title opacity-7" style="color: black !important">
                            {{ username }} <span class="opacity-6">on {{ project }} assignment</span>
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: black; font-size: 20px; padding-top: 0px">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul v-if="activities.length > 0" class="list-group">
                            <li v-for="activity in activities" :key="activity.id" class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <span v-if="activity.end" class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-check"></i></span>
                                    <span v-else class="btn btn-icon-only btn-rounded btn-outline-warning mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-spinner"></i></span>
                                    <div class="d-flex flex-column">
                                        <p class="mb-0 opacity-7" style="color: black !important">{{ activity.description }}</p>
                                        <p class="mb-0">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $filters.formatDate(activity.created_at) }}</span>
                                            <span v-if="activity.struggle_text == 'Yes'" class="badge bg-gradient-danger ms-2" style="text-transform: unset; width: 100px"><i class="fa-solid fa-user-ninja"></i> Struggling</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-sm font-weight-bold">
                                    <div class="d-flex flex-column">
                                        <p class="mb-1 text-dark opacity-8" style="color: black !important">{{ $filters.formatTime(activity.start) }}</p>
                                        <span v-if="activity.duration">
                                            <span v-if="activity.duration > 60" class="text-xs text-secondary" style="white-space: nowrap"
                                                >{{ Math.floor(activity.duration / 60) }} hours
                                                <span v-if="activity.duration - Math.floor(activity.duration / 60) * 60 > 0">
                                                    <br />
                                                    {{ activity.duration - Math.floor(activity.duration / 60) * 60 }} minutes
                                                </span>
                                            </span>

                                            <span v-else class="text-xs text-secondary" style="white-space: nowrap">{{ activity.duration }} minutes</span>
                                        </span>
                                        <span v-else class="text-xs text-warning" style="white-space: nowrap">In Progress</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <h6 v-else class="text-center opacity-5" style="color: black !important">{{ status }}</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mt-0 mb-0 btn bg-gradient-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <a href="javascript:;" ref="openActivityModal" data-bs-toggle="modal" data-bs-target="#activityModal" hidden></a>
        <a href="javascript:;" ref="openProjectModal" data-bs-toggle="modal" data-bs-target="#projectModal" hidden></a>
        <a href="javascript:;" ref="openUserProjectModal" data-bs-toggle="modal" data-bs-target="#userProjectModal" hidden></a>
    </Layout>
</template>

<style>
.col-20 {
    flex: 0 0 auto;
    width: 20%;
}
</style>

<script setup>
import { ref, computed, onBeforeMount, onBeforeUpdate } from "vue";
import { usePage, Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Layout from "../../Components/Layout.vue";
import Pagination from "../../Components/Pagination.vue";
import apexchart from "vue3-apexcharts";
import "axios";

const permissions = computed(() => usePage().props.value.auth.user.permissions);

const props = defineProps({
    report: Object,
    attendances: Object,
    users: Array,
    reportedUsers: Array,
    filters: Object,
    showChart: Boolean,
    dailySeries: Array,
    dates: Array,
    performanceHoursSeries: Array,
    performancePercentageSeries: Array,
    outOfOfficeSeries: Array,
    outSickSeries: Array,
    supportSeries: Array,
    supportData: Array,
    supportSla: Number,
    inchargeSeries: Array,
    inchargeData: Array,
    totalInchargeSeries: Number,
});

let activities = ref([]);
let username = ref(null);
let at = ref(null);
let status = ref(null);
let search = ref(props.filters.search);
let user = ref(props.filters.user);
let paginate = ref(props.filters.paginate);
let supportChart = ref({});
let inchargeChart = ref({});
let performanceHoursChart = ref({});
let performancePercentageChart = ref({});
let outOfOfficeChart = ref({});
let outSickChart = ref({});
let dailyChart = ref({});

let project = ref(null);
let personnels = ref([]);
let userAliases = ref([]);
let personnelInchargeSeries = ref([]);
let personnelInchargeChart = ref({});
let responsibilityInchargeSeries = ref([]);
let responsibilityInchargeChart = ref({});
let currentInchargeSeries = ref(null);

const openActivityModal = ref(null);
const openProjectModal = ref(null);
const openUserProjectModal = ref(null);

onBeforeMount(() => {
    props.reportedUsers.forEach((reportedUser) => {
        let alias = reportedUser.split(" ").slice(0, 1).join(" ");
        userAliases.value.push(alias);
    });

    if (props.showChart) {
        renderDaily();
    }

    renderReport();
});

onBeforeUpdate(() => {
    if (props.showChart) {
        renderDaily();
    }
});

const renderReport = () => {
    supportChart.value = {
        chart: {
            height: 400,
            type: "donut",
            toolbar: {
                show: true,
            },
        },
        title: {
            text: `Support (SLA: ${props.supportSla}%)`,
            align: "left",
        },
        labels: props.supportData,
        legend: {
            position: "bottom",
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opt) {
                return opt.w.config.series[opt.seriesIndex];
            },
        },
        colors: ["#008ffb", "#8e6cef", "#c759d0", "#f74a4a", "#ff7300", "#ffec01", "#53d726", "#24d7ae", "#5fb7d4", "#97d9ff"],
    };
    inchargeChart.value = {
        chart: {
            height: 400,
            type: "pie",
            toolbar: {
                show: true,
            },
        },
        title: {
            text: "Resource In Charge",
            align: "left",
        },
        labels: props.inchargeData,
        legend: {
            position: "bottom",
        },
        colors: ["#008ffb", "#8e6cef", "#c759d0", "#f74a4a", "#ff7300", "#ffec01", "#53d726", "#24d7ae", "#5fb7d4", "#97d9ff"],
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " hours";
                },
            },
        },
    };
    performanceHoursChart.value = {
        chart: {
            type: "bar",
            height: 400,
        },
        title: {
            text: "Personnel Performance (Hours)",
            align: "left",
        },
        plotOptions: {
            bar: {
                columnWidth: "50%",
                dataLabels: {
                    position: "top",
                },
            },
        },
        dataLabels: {
            offsetY: -20,
            style: {
                fontSize: "12px",
                colors: ["#304758"],
            },
        },
        xaxis: {
            categories: userAliases.value,
        },
        yaxis: {
            title: {
                text: "Hours",
            },
        },
        fill: {
            opacity: 1,
        },
    };
    performancePercentageChart.value = {
        chart: {
            type: "bar",
            height: 400,
        },
        title: {
            text: `Personnel Performance (Percentage) from ${props.report.mandays} mandays (${props.report.mandays * 8} hours)`,
            align: "left",
        },
        plotOptions: {
            bar: {
                columnWidth: "50%",
                dataLabels: {
                    position: "top",
                },
            },
        },
        dataLabels: {
            formatter: function (val) {
                return val + "%";
            },
            offsetY: -20,
            style: {
                fontSize: "12px",
                colors: ["#304758"],
            },
        },
        xaxis: {
            categories: userAliases.value,
        },
        yaxis: {
            title: {
                text: "Percentage",
            },
            labels: {
                formatter: function (val) {
                    return val + "%";
                },
            },
        },
        colors: ["#00e396"],
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + "%";
                },
            },
        },
    };
    outOfOfficeChart.value = {
        chart: {
            type: "bar",
            height: 400,
        },
        title: {
            text: `Personnel Out Office`,
            align: "left",
        },
        plotOptions: {
            bar: {
                columnWidth: "50%",
                dataLabels: {
                    position: "top",
                },
            },
        },
        dataLabels: {
            formatter: function (val) {
                return val + " days";
            },
            offsetY: -20,
            style: {
                fontSize: "12px",
                colors: ["#304758"],
            },
        },
        xaxis: {
            categories: userAliases.value,
        },
        yaxis: {
            show: false,
            labels: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        colors: ["#ff7300"],
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " days";
                },
            },
        },
    };
    outSickChart.value = {
        chart: {
            type: "bar",
            height: 400,
        },
        title: {
            text: `Personnel Out Sick`,
            align: "left",
        },
        plotOptions: {
            bar: {
                columnWidth: "50%",
                dataLabels: {
                    position: "top",
                },
            },
        },
        dataLabels: {
            formatter: function (val) {
                return val + " days";
            },
            offsetY: -20,
            style: {
                fontSize: "12px",
                colors: ["#304758"],
            },
        },
        xaxis: {
            categories: userAliases.value,
        },
        yaxis: {
            show: false,
            labels: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        colors: ["#f74a4a"],
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " days";
                },
            },
        },
    };
};

const renderDaily = () => {
    dailyChart.value = {
        chart: {
            height: 600,
            type: "line",
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
        },
        title: {
            text: "Performance",
            align: "left",
        },
        tooltip: {
            intersect: true,
            shared: false,
        },
        markers: {
            size: 3,
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
        colors: ["#008ffb", "#8e6cef", "#c759d0", "#f74a4a", "#ff7300", "#ffec01", "#53d726", "#24d7ae", "#5fb7d4", "#97d9ff"],
    };
};

const showActivities = (attendance) => {
    resetActivity();

    activities.value = attendance.activities;
    username.value = attendance.user.name;
    at.value = attendance.created_at;
};

const reset = () => {
    search.value = null;
    let subStartDate = new Date();
    subStartDate.setDate(subStartDate.getDate() - 6);
    user.value = "All";
    paginate.value = 10;

    filter();
};

const showActivitiesData = (event, chartContext, config) => {
    resetActivity();

    axios
        .get("/activity", {
            params: {
                user: props.dailySeries[config.seriesIndex].name,
                date: props.dates[config.dataPointIndex],
            },
        })
        .then((response) => {
            if (response.data == "Out Office" || response.data == "Out Sick" || response.data == "Not Available") {
                status.value = response.data;
            } else {
                if (response.data == "Working") {
                    status.value = "Not Available";
                } else {
                    activities.value = response.data;
                }
            }
            username.value = props.dailySeries[config.seriesIndex].name;
            at.value = props.dates[config.dataPointIndex];
            openActivityModal.value.click();
        });
};

const resetActivity = () => {
    activities.value = [];
    username.value = null;
    at.value = null;
    status.value = null;
};

const showInchargeData = (event, chartContext, config) => {
    currentInchargeSeries.value = props.inchargeSeries[config.dataPointIndex];
    // alert(config.dataPointIndex);
    // console.log(chartContext, config);
    axios
        .get(`/reports/${props.report.id}/project`, {
            params: {
                project: props.inchargeData[config.dataPointIndex],
            },
        })
        .then((response) => {
            project.value = props.inchargeData[config.dataPointIndex];
            personnels.value = response.data.personnels;
            personnelInchargeSeries.value = response.data.personnelInchargeSeries;
            responsibilityInchargeSeries.value = response.data.responsibilityInchargeSeries;

            personnelInchargeChart.value = {
                chart: {
                    height: 400,
                    type: "pie",
                    toolbar: {
                        show: true,
                    },
                },
                title: {
                    text: "Personnel In Charge",
                    align: "left",
                },
                labels: response.data.personnelInchargeData,
                legend: {
                    position: "bottom",
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " hours";
                        },
                    },
                },
            };

            responsibilityInchargeChart.value = {
                chart: {
                    height: 400,
                    type: "pie",
                    toolbar: {
                        show: true,
                    },
                },
                title: {
                    text: "Role In Charge",
                    align: "left",
                },
                labels: response.data.responsibilityInchargeData,
                legend: {
                    position: "bottom",
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " hours";
                        },
                    },
                },
            };

            openProjectModal.value.click();
        });
};

const showUserInchargeData = (user, projectName) => {
    axios
        .get(`/reports/${props.report.id}/project`, {
            params: {
                user: user.id,
                project: projectName,
            },
        })
        .then((response) => {
            username.value = user.name;
            project.value = projectName;
            activities.value = response.data;
            openUserProjectModal.value.click();
        });
};

const filter = () => {
    Inertia.get(
        `/reports/${props.report.id}`,
        {
            search: search.value,
            user: user.value,
            paginate: paginate.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};
</script>
