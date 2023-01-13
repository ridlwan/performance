<template>
    <Layout>
        <template #heading>
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-white">
                    <Link href="/reports" class="opacity-5 text-white"><h6 class="font-weight-bolder text-white mb-0">Master Report</h6></Link>
                </li>
                <li class="breadcrumb-item text-white active" style="width: 300px">
                    <h6 class="font-weight-bolder text-white mb-0">
                        {{ report.name }} <Link v-if="report.published_text == 'No'" :href="`/reports/${report.id}/edit`"> <i class="fas fa-pencil-alt text-info ms-1"></i></Link>
                    </h6>
                </li>
            </ol>
        </template>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="bar" height="400" :options="jiraChart" :series="jiraSeries"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="bar" height="400" :options="developmentChart" :series="developmentSeries"></apexchart>
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
                            <apexchart type="bar" height="400" :options="testingChart" :series="testingSeries"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="bar" height="400" :options="overallChart" :series="overallSeries"></apexchart>
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
                            <apexchart type="donut" height="400" :options="supportChart" :series="supportSeries"></apexchart>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body mt-4 px-0 pt-0 pb-2">
                        <div class="p-3 pt-0 pb-0">
                            <apexchart type="pie" height="400" :options="resourceChart" :series="resourceSeries"></apexchart>
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
                                        <th class="text-center text-uppercase text-xs text-dark">No</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Name</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Role</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Project Assignment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(attendance, attendance_index) in attendances.data" :key="attendance_index">
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ attendance_index + 1 }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ attendance.user.name }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ attendance.user.name }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ attendance.user.name }}</span>
                                        </td>
                                    </tr>
                                    <tr v-if="attendances.data.length < 1">
                                        <td colspan="7" class="align-middle text-center text-secondary">Data not found</td>
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
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control" placeholder="Search" v-model="search" @change="filter" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text" style="background: #e9ecef"><i class="fa-regular fa-calendar"></i></span>
                                    <input type="date" class="form-control" v-model="startDate" disabled />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text" style="background: #e9ecef"><i class="fa-regular fa-calendar"></i></span>
                                    <input type="date" class="form-control" v-model="endDate" disabled />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" v-model="user" @change="filter">
                                    <option value="All" selected>All Personel</option>
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
                        </div>
                    </div>
                    <div class="card-body mt-3 px-0 pt-0 pb-2" style="min-height: 480px">
                        <div v-if="showChart" class="p-3 pt-0">
                            <apexchart type="line" height="600" :options="dailyChart" :series="dailySeries" @dataPointSelection="showDetail"></apexchart>
                        </div>
                        <div class="table-responsive p-3 pt-0 pb-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xs text-dark">Personel</th>
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
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img :src="attendance.user.avatar ? '/storage/' + attendance.user.avatar : '/assets/img/logo-sq.png'" class="avatar avatar-sm me-3" />
                                                </div>
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
                        <a href="javascript:;" id="openModalActivities" data-bs-toggle="modal" data-bs-target="#activityModal" hidden></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="activityModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title opacity-7" id="activityModalTitle" style="color: black !important">
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
                                        <p class="mb-1 opacity-7" style="color: black !important">{{ activity.description }}</p>
                                        <span v-if="activity.struggle_text == 'Yes'" class="badge bg-gradient-danger" style="text-transform: unset; width: 100px"><i class="fa-solid fa-user-ninja"></i> Struggling</span>
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
    </Layout>
</template>

<style>
.col-20 {
    flex: 0 0 auto;
    width: 20%;
}
</style>

<script setup>
import { ref, onBeforeMount, onBeforeUpdate } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Layout from "../../Components/Layout.vue";
import Pagination from "../../Components/Pagination.vue";
import moment from "moment";
import apexchart from "vue3-apexcharts";
import "axios";

const props = defineProps({
    report: Object,
    attendances: Object,
    users: Array,
    reportedUsers: Array,
    filters: Object,
    showChart: Boolean,
    dailySeries: Array,
    dates: Array,
    projects: Array,
    jiraSeries: Array,
    developmentSeries: Array,
    testingSeries: Array,
    overallSeries: Array,
    performanceHoursSeries: Array,
    performancePercentageSeries: Array,
});

let activities = ref([]);
let username = ref(null);
let at = ref(null);
let status = ref(null);
let search = ref(props.filters.search);
let startDate = ref(moment(String(props.filters.startDate)).format("YYYY-MM-DD"));
let endDate = ref(moment(String(props.filters.endDate)).format("YYYY-MM-DD"));
let user = ref(props.filters.user);
let paginate = ref(props.filters.paginate);
let jiraChart = ref({});
let developmentChart = ref({});
let testingChart = ref({});
let overallChart = ref({});

let supportChart = ref({});
let supportSeries = ref([4, 3, 5]);
let resourceChart = ref({});
let resourceSeries = ref([44, 55, 13, 43, 22, 47]);

let performanceHoursChart = ref({});
let performancePercentageChart = ref({});

let dailyChart = ref({});

onBeforeMount(() => {
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
    jiraChart.value = {
        chart: {
            type: "bar",
            height: 400,
        },
        title: {
            text: "Jira Planning",
            align: "left",
        },
        plotOptions: {
            bar: {
                endingShape: "rounded",
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
            categories: props.projects,
        },
        yaxis: {
            labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                },
            },
        },
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
    developmentChart.value = {
        chart: {
            type: "bar",
            height: 400,
        },
        title: {
            text: "Development",
            align: "left",
        },
        plotOptions: {
            bar: {
                endingShape: "rounded",
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
            categories: props.projects,
        },
        yaxis: {
            labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                },
            },
        },
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
    testingChart.value = {
        chart: {
            type: "bar",
            height: 400,
        },
        title: {
            text: "Testing",
            align: "left",
        },
        plotOptions: {
            bar: {
                endingShape: "rounded",
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
            categories: props.projects,
        },
        yaxis: {
            labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                },
            },
        },
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
    overallChart.value = {
        chart: {
            type: "bar",
            height: 400,
        },
        title: {
            text: "Overall",
            align: "left",
        },
        plotOptions: {
            bar: {
                endingShape: "rounded",
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
            categories: props.projects,
        },
        yaxis: {
            labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                },
            },
        },
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
    supportChart.value = {
        chart: {
            height: 400,
            type: "donut",
            toolbar: {
                show: true,
            },
        },
        title: {
            text: "Support",
            align: "left",
        },
        labels: ["Closed", "Completed", "Waiting for support"],
        legend: {
            position: "bottom",
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opt) {
                return opt.w.config.series[opt.seriesIndex];
            },
        },
    };
    resourceChart.value = {
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
        labels: ["PLB", "GSMART", "XOPS", "AMS", "PMO", "PLBx"],
        legend: {
            position: "bottom",
        },
    };
    performanceHoursChart.value = {
        chart: {
            type: "bar",
            height: 400,
        },
        title: {
            text: "Personel Performance (Hours)",
            align: "left",
        },
        plotOptions: {
            bar: {
                endingShape: "rounded",
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
            categories: props.reportedUsers,
        },
        yaxis: {
            labels: {
                show: false,
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
            text: "Personel Performance (Percentage)",
            align: "left",
        },
        plotOptions: {
            bar: {
                endingShape: "rounded",
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
            categories: props.reportedUsers,
        },
        yaxis: {
            labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                },
            },
        },
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
    startDate.value = moment(String(subStartDate)).format("YYYY-MM-DD");
    endDate.value = moment(String(new Date())).format("YYYY-MM-DD");
    user.value = "All";
    paginate.value = 10;

    filter();
};

const showDetail = (event, chartContext, config) => {
    resetActivity();

    axios
        .get("/activity", {
            params: {
                user: props.dailySeries[config.seriesIndex].name,
                date: props.dates[config.dataPointIndex],
            },
        })
        .then((response) => {
            if (response.data == "Out of Office" || response.data == "Out Sick" || response.data == "Not Available") {
                status.value = response.data;
            } else {
                activities.value = response.data;
            }
            username.value = props.dailySeries[config.seriesIndex].name;
            at.value = props.dates[config.dataPointIndex];
            document.getElementById("openModalActivities").click();
        });
};

const resetActivity = () => {
    activities.value = [];
    username.value = null;
    at.value = null;
    status.value = null;
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
