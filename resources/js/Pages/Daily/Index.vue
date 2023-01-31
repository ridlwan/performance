<template>
    <Layout>
        <template #heading>
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-white active" style="width: 300px">
                    <h6 class="font-weight-bolder text-white mb-0">Daily Report</h6>
                </li>
            </ol>
        </template>

        <div class="row">
            <div class="col-lg-12 mb-lg-0">
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
                                    <input type="date" class="form-control" v-model="startDate" @change="filter" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input type="date" class="form-control" v-model="endDate" @change="filter" />
                                </div>
                            </div>
                            <div class="col-md-2">
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
                        </div>
                    </div>
                    <div class="card-body mt-3 px-0 pt-0 pb-2" style="min-height: 480px">
                        <div v-if="showChart" class="p-3 pt-0">
                            <apexchart type="line" height="600" :options="chartOptions" :series="series" @dataPointSelection="showDetail"></apexchart>
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
                                            <div class="d-flex px-2">
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
                        <a href="javascript:;" id="openActivityModal" data-bs-toggle="modal" data-bs-target="#activityModal" hidden></a>
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
    </Layout>
</template>

<style>
.col-20 {
    flex: 0 0 auto;
    width: 20%;
}

.dp__main {
    padding: 0px !important;
}
.dp__input_wrap {
    border: unset !important;
    padding-top: 1px;
    padding-bottom: 1.5px;
}
.dp__input {
    border: unset;
    border-radius: 8px;
    width: 150px;
}
</style>

<script setup>
import { ref, onBeforeMount, onBeforeUpdate } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Layout from "../../Components/Layout.vue";
import Pagination from "../../Components/Pagination.vue";
import moment from "moment";
import apexchart from "vue3-apexcharts";
import "axios";

const props = defineProps({
    attendances: Object,
    users: Array,
    filters: Object,
    showChart: Boolean,
    series: Array,
    dates: Array,
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
let chartOptions = ref({});

onBeforeMount(() => {
    if (props.showChart) {
        renderChart();
    }
});

onBeforeUpdate(() => {
    if (props.showChart) {
        renderChart();
    }
});

const renderChart = () => {
    chartOptions.value = {
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
        colors: ["#008ffb", "#8e6cef", "#c759d0", "#ff0000", "#ff7300", "#ffec01", "#53d726", "#24d7ae", "#5fb7d4", "#97d9ff"],
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
                user: props.series[config.seriesIndex].name,
                date: props.dates[config.dataPointIndex],
            },
        })
        .then((response) => {
            if (response.data == "Out of Office" || response.data == "Out Sick" || response.data == "Not Available") {
                status.value = response.data;
            } else {
                if (response.data == "Working") {
                    status.value = "Not Available";
                } else {
                    activities.value = response.data;
                }
            }
            username.value = props.series[config.seriesIndex].name;
            at.value = props.dates[config.dataPointIndex];
            document.getElementById("openActivityModal").click();
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
        "/daily",
        {
            search: search.value,
            startDate: startDate.value,
            endDate: endDate.value,
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
