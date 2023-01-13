<template>
    <Layout>
        <template #heading>
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-white active" style="width: 300px">
                    <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
                </li>
            </ol>
        </template>

        <div class="row">
            <div class="col-20 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Onsite</p>
                                    <h5 class="font-weight-bolder text-success">{{ workingOnsiteData.length }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <a v-if="workingOnsiteData.length > 0" href="javascript:;" data-bs-toggle="modal" data-bs-target="#userModal" class="icon icon-shape bg-gradient-primary text-center rounded-circle" @click="showUser('workingOnsite')">
                                    <h3>
                                        <i class="fa-solid fa-person-shelter mt-2 text-white"></i>
                                    </h3>
                                </a>
                                <span v-else class="icon icon-shape bg-gradient-primary text-center rounded-circle">
                                    <h3>
                                        <i class="fa-solid fa-person-shelter mt-2 text-white"></i>
                                    </h3>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-20 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Remote</p>
                                    <h5 class="font-weight-bolder text-success">{{ workingRemoteData.length }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <a v-if="workingRemoteData.length > 0" href="javascript:;" data-bs-toggle="modal" data-bs-target="#userModal" class="icon icon-shape bg-gradient-primary text-center rounded-circle" @click="showUser('workingRemote')">
                                    <h3>
                                        <i class="fa-solid fa-person-snowboarding mt-2 text-white"></i>
                                    </h3>
                                </a>
                                <span v-else class="icon icon-shape bg-gradient-primary text-center rounded-circle">
                                    <h3>
                                        <i class="fa-solid fa-person-snowboarding mt-2 text-white"></i>
                                    </h3>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-20 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Out Office</p>
                                    <h5 class="font-weight-bolder text-warning">{{ outOfOfficeData.length }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <a v-if="outOfOfficeData.length > 0" href="javascript:;" data-bs-toggle="modal" data-bs-target="#userModal" class="icon icon-shape bg-gradient-primary text-center rounded-circle" @click="showUser('outOfOffice')">
                                    <h3>
                                        <i class="fa-solid fa-person-walking-luggage mt-2 text-white"></i>
                                    </h3>
                                </a>
                                <span v-else class="icon icon-shape bg-gradient-primary text-center rounded-circle">
                                    <h3>
                                        <i class="fa-solid fa-person-walking-luggage mt-2 text-white"></i>
                                    </h3>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-20 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Out Sick</p>
                                    <h5 class="font-weight-bolder text-danger">{{ outSickData.length }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <a v-if="outSickData.length > 0" href="javascript:;" data-bs-toggle="modal" data-bs-target="#userModal" class="icon icon-shape bg-gradient-primary text-center rounded-circle" @click="showUser('outSick')">
                                    <h3>
                                        <i class="fa-solid fa-bed mt-2 text-white"></i>
                                    </h3>
                                </a>
                                <span v-else class="icon icon-shape bg-gradient-primary text-center rounded-circle">
                                    <h3>
                                        <i class="fa-solid fa-bed mt-2 text-white"></i>
                                    </h3>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-20 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Unavailable</p>
                                    <h5 class="font-weight-bolder text-secondary">{{ notAvailableData.length }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <a v-if="notAvailableData.length > 0" href="javascript:;" data-bs-toggle="modal" data-bs-target="#userModal" class="icon icon-shape bg-gradient-primary text-center rounded-circle" @click="showUser('notAvailable')">
                                    <h3>
                                        <i class="fa-solid fa-person-through-window mt-2 text-white"></i>
                                    </h3>
                                </a>
                                <span v-else class="icon icon-shape bg-gradient-primary text-center rounded-circle">
                                    <h3>
                                        <i class="fa-solid fa-person-through-window mt-2 text-white"></i>
                                    </h3>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-9">
                                <h6 class="mb-2">Current Activity</h6>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" v-model="seletedUser" @change="checkSeletedUser">
                                    <option value="all">All Personel</option>
                                    <option v-for="user in teammate" :key="user.id" :value="user.name">{{ user.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3" style="min-height: 480px">
                        <ul v-if="activitiesData.length > 0" class="list-group">
                            <li v-for="activity in activitiesData" :key="activity.id" class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div v-if="seletedUser != 'all'" style="display: contents">
                                    <div v-if="activity.attendance.user.name == seletedUser" style="display: contents">
                                        <div class="d-flex align-items-center">
                                            <span v-if="activity.end" class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-check"></i></span>
                                            <span v-else class="btn btn-icon-only btn-rounded btn-outline-warning mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-spinner"></i></span>

                                            <span class="avatar avatar-md border-0 me-3" :title="activity.attendance.user.name">
                                                <img :src="activity.attendance.user.avatar ? '/storage/' + activity.attendance.user.avatar : '/assets/img/logo-sq.png'" class="h-100 border-radius-lg shadow-lg" style="width: 48px !important" />
                                            </span>

                                            <div class="d-flex flex-column">
                                                <div>
                                                    <span class="text-sm font-weight-bold opacity-8">{{ activity.attendance.user.name }}</span>

                                                    <span v-if="activity.struggle_text == 'Yes'" class="badge bg-gradient-danger ms-2" style="text-transform: unset; width: 100px"><i class="fa-solid fa-user-ninja"></i> Struggling</span>
                                                </div>
                                                <p class="mb-1 mt-2 text-dark text-sm">{{ activity.description }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center text-sm font-weight-bold">
                                            <div class="d-flex flex-column">
                                                <p class="mb-1 text-dark">{{ $filters.formatTime(activity.start) }}</p>
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
                                    </div>
                                </div>
                                <div v-else style="display: contents">
                                    <div class="d-flex align-items-center">
                                        <span v-if="activity.end" class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-check"></i></span>
                                        <span v-else class="btn btn-icon-only btn-rounded btn-outline-warning mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-spinner"></i></span>

                                        <span class="avatar avatar-md border-0 me-3" :title="activity.attendance.user.name">
                                            <img :src="activity.attendance.user.avatar ? '/storage/' + activity.attendance.user.avatar : '/assets/img/logo-sq.png'" class="h-100 border-radius-lg shadow-lg" style="width: 48px !important" />
                                        </span>

                                        <div class="d-flex flex-column">
                                            <div>
                                                <span class="text-sm font-weight-bold opacity-8">{{ activity.attendance.user.name }}</span>

                                                <span v-if="activity.struggle_text == 'Yes'" class="badge bg-gradient-danger ms-2" style="text-transform: unset; width: 100px"><i class="fa-solid fa-user-ninja"></i> Struggling</span>
                                            </div>
                                            <p class="mb-1 mt-2 text-dark text-sm">{{ activity.description }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center text-sm font-weight-bold">
                                        <div class="d-flex flex-column">
                                            <p class="mb-1 text-dark">{{ $filters.formatTime(activity.start) }}</p>
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
                                </div>
                            </li>
                        </ul>

                        <div v-else>
                            <div v-if="seletedUserNotFound && seletedUser" class="mt-10 mb-10 text-center opacity-7">
                                <h6><i class="fa-solid fa-user-large-slash"></i> {{ seletedUser }} hasn't done any activities yet</h6>
                            </div>
                            <div v-else class="mt-10 mb-10 text-center opacity-7">
                                <h6><i class="fa-solid fa-user-large-slash"></i> There are not yet activities today</h6>
                            </div>
                        </div>

                        <div v-if="activitiesData.length > 0 && seletedUserNotFound && seletedUser" class="mt-10 mb-10 text-center opacity-7">
                            <h6><i class="fa-solid fa-user-large-slash"></i> {{ seletedUser }} hasn't done any activities yet</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="userModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title opacity-6" id="userModalTitle" style="color: black !important">{{ userListTitle }}</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: black; font-size: 20px; padding-top: 0px">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <li v-for="user in users" :key="user.id" class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-md border-0 me-3" :title="user.name">
                                        <img :src="user.avatar ? '/storage/' + user.avatar : '/assets/img/logo-sq.png'" class="h-100 border-radius-lg shadow-lg" />
                                    </span>
                                    <div class="d-flex flex-column">
                                        <p class="mt-3 text-sm font-weight-bold opacity-5" style="color: black !important">{{ user.name }}</p>
                                        <span v-if="user.struggle_text == 'Yes'" class="badge bg-gradient-danger" style="text-transform: unset; width: 100px"><i class="fa-solid fa-user-ninja"></i> Struggling</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
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
import { ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Layout from "../../Components/Layout.vue";
import Echo from "laravel-echo";
import "axios";

const props = defineProps({
    workingRemote: Array,
    workingOnsite: Array,
    outOfOffice: Array,
    outSick: Array,
    notAvailable: Array,
    activities: Array,
    teammate: Array,
});

let workingRemoteData = ref(props.workingRemote);
let workingOnsiteData = ref(props.workingOnsite);
let outOfOfficeData = ref(props.outOfOffice);
let outSickData = ref(props.outSick);
let notAvailableData = ref(props.notAvailable);
let activitiesData = ref(props.activities);
let users = ref([]);
let userListTitle = ref(null);
let seletedUser = ref("all");
let seletedUserNotFound = ref(false);

window.Echo.channel("activity-channel").listen(".activity-event", (e) => {
    getData();
});

window.Echo.channel("status-channel").listen(".status-event", (e) => {
    getData();
});

const getData = () => {
    axios
        .get("/", {
            params: {
                data: "true",
            },
        })
        .then((response) => {
            workingRemoteData.value = response.data.workingRemote;
            workingOnsiteData.value = response.data.workingOnsite;
            outOfOfficeData.value = response.data.outOfOffice;
            outSickData.value = response.data.outSick;
            notAvailableData.value = response.data.notAvailable;
            activitiesData.value = response.data.activities;
            checkSeletedUser();
        });
};

const showUser = (status) => {
    if (status == "workingRemote") {
        users.value = workingRemoteData.value;
        userListTitle.value = "Working Remote";
    }

    if (status == "workingOnsite") {
        users.value = workingOnsiteData.value;
        userListTitle.value = "Working Onsite";
    }

    if (status == "outOfOffice") {
        users.value = outOfOfficeData.value;
        userListTitle.value = "Ouf of Office";
    }

    if (status == "outSick") {
        users.value = outSickData.value;
        userListTitle.value = "Out Sick";
    }

    if (status == "notAvailable") {
        users.value = notAvailableData.value;
        userListTitle.value = "Not Available";
    }
};

const checkSeletedUser = () => {
    seletedUserNotFound.value = false;

    if (seletedUser.value != "all") {
        let notFound = true;
        activitiesData.value.forEach((activity) => {
            if (activity.attendance.user.name == seletedUser.value) {
                notFound = false;
            }
        });

        if (notFound == false) {
            seletedUserNotFound.value = false;
        } else {
            seletedUserNotFound.value = true;
        }
    }
};
</script>
