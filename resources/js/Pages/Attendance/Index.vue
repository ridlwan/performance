<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">Attendance</h6>
        </template>

        <div class="row" v-if="userStatus == 'Working Remote' || userStatus == 'Working Onsite'">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="text-uppercase text-sm text-dark">Your activity</p>
                            <button class="btn bg-gradient-danger ms-auto" @click="checkOut" :disabled="disableCheckOut">Check Out <i class="fas fa-briefcase ms-1"></i></button>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <hr class="horizontal dark" />
                        <div id="activities" style="overflow: auto; height: 300px">
                            <div v-for="(activity, activityIndex) in activities" :key="activity.id">
                                <div class="d-flex">
                                    <span class="mb-0 me-3 font-weight-bolder opacity-7" style="white-space: nowrap">{{ activity.start_time }}</span>
                                    <div>
                                        <h6 class="mb-0">
                                            <small class="font-weight-normal"
                                                >{{ activity.description }} <a href="javascript:;" @click="openEditActivityForm(activity)"><i class="fas fa-pencil-alt text-primary me-3" aria-hidden="true"></i></a
                                            ></small>
                                        </h6>

                                        <span v-if="activity.project" class="badge bg-gradient-primary" style="text-transform: unset"><i class="fa-solid fa-check-to-slot"></i> {{ activity.project.name }}</span>
                                        <span v-else class="badge bg-gradient-primary" style="text-transform: unset"><i class="fa-solid fa-check-to-slot"></i> General</span>

                                        <span v-if="activity.struggle_text == 'Yes'" class="badge bg-gradient-danger ms-2" style="text-transform: unset"><i class="fa-solid fa-user-ninja"></i> I'm struggling here</span>

                                        <button v-if="activityIndex == activities.length - 1 && activity.struggle_text == 'No' && !activity.end" type="button" @click="struggling(activity.id)" class="btn btn-sm btn-reddit btn-icon-only rounded-circle mb-0 ms-2" data-bs-toggle="tooltip" title="I'm struggling here">
                                            <span class="btn-inner--icon"><i class="fa-solid fa-user-ninja"></i></span>
                                        </button>
                                    </div>
                                </div>
                                <hr class="horizontal dark mb-4" />
                            </div>
                        </div>
                        <div class="row" v-if="addActivityForm">
                            <div v-if="activities.length < 1 || relogin" class="row" style="padding: 0; margin: 0">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <multiselect
                                            v-model="form.description"
                                            :options="hints"
                                            :optionsHeight="30"
                                            openDirection="top" 
                                            placeholder="Write your activity or select from previous ones"
                                            tagPlaceholder="Use this new activity"
                                            :multiple="false"
                                            :searchable="true"
                                            :taggable="true"
                                            :closeOnSelect="true"
                                            :clearOnSelect="true"
                                            :preserveSearch="false"
                                            :preselectFirst="false"
                                            :showLabels="false"
                                            @tag="useNewActivity"
                                            :height="120"
                                            :class="{ 'is-invalid': form.errors.description }"
                                        ></multiselect>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control" v-model="form.project_id" :class="{ 'is-invalid': form.errors.project_id }">
                                            <option value="null">General</option>
                                            <option v-for="project in projectAssignment" :key="project.id" :value="project.id">
                                                {{ project.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary d-lg-block" @click="addActivity" :disabled="disableAddActivity"><i class="fa-solid fa-play"></i>&nbsp; Start Activity</button>
                                </div>
                            </div>
                            <div v-else class="row" style="padding: 0; margin: 0">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <multiselect
                                            v-model="form.description"
                                            :options="hints"
                                            :optionsHeight="30"
                                            openDirection="top" 
                                            placeholder="Write your activity or select from previous ones"
                                            tagPlaceholder="Use this new activity"
                                            :multiple="false"
                                            :searchable="true"
                                            :taggable="true"
                                            :closeOnSelect="true"
                                            :clearOnSelect="true"
                                            :preserveSearch="false"
                                            :preselectFirst="false"
                                            :showLabels="false"
                                            @tag="useNewActivity"
                                            :height="120"
                                            :class="{ 'is-invalid': form.errors.description }"
                                        ></multiselect>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control" v-model="form.project_id" :class="{ 'is-invalid': form.errors.project_id }">
                                            <option value="null">General</option>
                                            <option v-for="project in projectAssignment" :key="project.id" :value="project.id">
                                                {{ project.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <Datepicker time-picker class="form-control" type="time" id="timepicker" v-model="startTime" :class="{ 'is-invalid': form.errors.start }" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary d-lg-block" @click="addActivity" :disabled="disableAddActivity"><i class="fa-solid fa-play"></i>&nbsp; Start Activity</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="javascript:;" @click="closeActivityForm"
                                    ><span class="text-secondary">Hold on a second <i class="fa-solid fa-person-digging ms-1"></i></span
                                ></a>
                            </div>
                        </div>
                        <div class="row" v-else-if="editActivityForm">
                            <div class="row" style="padding: 0; margin: 0">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <multiselect
                                            v-model="form.description_updated"
                                            :options="hints"
                                            :optionsHeight="30"
                                            openDirection="top" 
                                            placeholder="Write your activity or select from previous ones"
                                            tagPlaceholder="Use this new activity"
                                            :multiple="false"
                                            :searchable="true"
                                            :taggable="true"
                                            :closeOnSelect="true"
                                            :clearOnSelect="true"
                                            :preserveSearch="false"
                                            :preselectFirst="false"
                                            :showLabels="false"
                                            @tag="useNewActivityUpdate"
                                            :height="120"
                                            :class="{ 'is-invalid': form.errors.description_updated }"
                                        ></multiselect>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-control" v-model="form.project_id_updated" :class="{ 'is-invalid': form.errors.project_id_updated }">
                                            <option value="null">General</option>
                                            <option v-for="project in projectAssignment" :key="project.id" :value="project.id">
                                                {{ project.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-warning d-lg-block" @click="updateActivity" :disabled="disableUpdateActivity"><i class="fa-solid fa-pen-to-square"></i>&nbsp; Update</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="javascript:;" @click="closeActivityForm"
                                    ><span class="text-secondary">Hold on a second <i class="fa-solid fa-person-digging ms-1"></i></span
                                ></a>
                            </div>
                        </div>
                        <div v-else class="text-center">
                            <button type="submit" class="btn btn-success" @click="openAddActivityForm"><i class="fa-solid fa-plus"></i>&nbsp; Add Activity</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-lg-5 col-md-5 mt-4 mt-sm-0">
                <div class="card move-on-hover">
                    <div class="card-body">
                        <div class="w-50 mx-auto mb-5">
                            <img src="/assets/img/logo.png" class="img-fluid" />
                        </div>
                        <h6 class="text-center text-uppercase text-xs text-dark" v-html="quote"></h6>
                        <div v-if="status == 'Out Office' || status == 'Out Sick' || outsideWorkingTime" class="row text-center mt-6 mb-2">
                            <span class="card-description opacity-7">Wish you all the best</span>
                        </div>
                        <div v-else>
                            <div class="row text-center">
                                <button @click="checkIn" class="btn btn-icon bg-gradient-primary d-lg-block mt-5 mb-3" :disabled="disableCheckIn">
                                    Check In
                                    <i class="fas fa-laptop-code ms-1"></i>
                                </button>
                            </div>
                            <div v-if="!relogin">
                                <hr class="horizontal dark d-none d-md-block" />

                                <div class="row">
                                    <div class="col-md-6 d-none d-md-block">
                                        <a href="javascript:;" @click="outOffice" class="btn btn-icon bg-gradient-warning d-lg-block mt-3 mb-0" :disabled="disableCheckIn">
                                            Out Office
                                            <i class="fas fa-person-walking-luggage ms-1"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-6 text-end d-none d-md-block">
                                        <a href="javascript:;" @click="outSick" class="btn btn-icon bg-gradient-danger d-lg-block mt-3 mb-0" :disabled="disableCheckIn">
                                            Out Sick
                                            <i class="fas fa-bed ms-1"></i>
                                        </a>
                                    </div>
                                    <button @click="outOffice" class="btn btn-icon bg-gradient-warning mb-0 d-md-none" :disabled="disableCheckIn">
                                        Out Office
                                        <i class="fas fa-person-walking-luggage ms-1"></i>
                                    </button>
                                    <button @click="outSick" class="btn btn-icon bg-gradient-danger mt-3 mb-0 d-md-none" :disabled="disableCheckIn">
                                        Out Sick
                                        <i class="fas fa-bed ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style>
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
    width: 115px;
}
</style>

<script setup>
import $ from "jquery";
import { Inertia } from "@inertiajs/inertia";
import { ref, onMounted, computed } from "vue";
import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Components/LayoutAttendance.vue";
import Swal from "sweetalert2";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Echo from "laravel-echo";
import "axios";
import Multiselect from 'vue-multiselect'

const form = useForm({
    description: null,
    description_updated: null,
    project_id: null,
    project_id_updated: null,
    start: null,
});

const props = defineProps({
    hints: Array,
    quote: String,
    activities: Array,
    relogin: Boolean,
    projects: Array,
});

let userStatus = ref(null);
let addActivityForm = ref(false);
let editActivityForm = ref(false);
let editActivityID = ref(null);
let startTime = ref(null);
let outsideWorkingTime = ref(false);
let projectAssignment = ref(props.projects);
let disableCheckIn = ref(false);
let disableCheckOut = ref(false);
let disableAddActivity = ref(false);
let disableUpdateActivity = ref(false);

const status = computed(() => usePage().props.value.auth.user.status);
const username = computed(() => usePage().props.value.auth.user.username);

onMounted(() => {
    userStatus.value = status.value;

    if ((status == "Working Remote" || status == "Working Onsite") && props.activities.length > 0) {
        scrollDown();
    }

    let now = new Date();
    if (now.getHours() >= 23 || now.getHours() <= 1) {
        outsideWorkingTime.value = true;
    }
});

window.Echo.channel("status-channel").listen(".status-event", (e) => {
    if (username.value == e.user) {
        axios.get("/attendance/user-status").then((response) => {
            userStatus.value = response.data;
        });
    }
});

window.Echo.channel("assignment-channel").listen(".assignment-event", (e) => {
    if (username.value == e.user) {
        axios.get("/attendance/assignment").then((response) => {
            form.project_id = null;
            form.project_id_updated = null;
            projectAssignment.value = response.data;
        });
    }
});

const useNewActivity = (newActivity) => {
    form.description = newActivity;
}

const useNewActivityUpdate = (newActivity) => {
    form.description_updated = newActivity;
}

const checkIn = () => {
    if (props.relogin) {
        Swal.fire({
            title: "Fight longer? <br> <i class='fa-solid fa-robot'></i>",
            text: "Do you want to re-check-in?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "orange",
            confirmButtonText: "Let's Go! <i class='fa-solid fa-person-skating'></i>",
            cancelButtonText: "Nope, Just kidding <i class='fa-solid fa-bed'></i>",
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                definePosition();
            }
        });
    } else {
        definePosition();
    }
};

const definePosition = () => {
    Swal.fire({
        title: "Define your position? <br> <i class='fa-solid fa-crosshairs'></i>",
        text: "Are you working onsite or remote?",
        icon: "question",
        showDenyButton: true,
        showCancelButton: true,
        denyButtonColor: "#2ebd59",
        cancelButtonColor: "orange",
        confirmButtonText: "Onsite <i class='fa-solid fa-person-shelter'></i>",
        denyButtonText: "Remote <i class='fa-solid fa-person-snowboarding'></i>",
        cancelButtonText: "Wait <i class='fa-regular fa-clock'></i>",
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            disableCheckIn.value = true;
            Inertia.post("/attendance/check-in", {
                position: "onsite",
            });

            if (props.relogin) {
                alertRelogin();
            } else {
                alertLogin();
            }
        } else if (result.isDenied) {
            disableCheckIn.value = true;
            Inertia.post("/attendance/check-in", {
                position: "remote",
            });

            if (props.relogin) {
                alertRelogin();
            } else {
                alertLogin();
            }
        }
    });
};

const alertLogin = () => {
    Swal.fire({
        title: "You're in! <br> <i class='fa-solid fa-user-secret'></i>",
        text: "Start the journey and enjoy your work",
        icon: "success",
        confirmButtonText: "Cool",
    });
};

const alertRelogin = () => {
    Swal.fire({
        title: "Welcome Back <br> <i class='fa-solid fa-user-secret'></i>",
        text: "Continue unfinished mission",
        icon: "success",
        confirmButtonText: "Sure",
    });
};

const openAddActivityForm = () => {
    addActivityForm.value = true;
    disableCheckOut.value = true;
    form.errors = [];
    startTime.value = {
        hours: new Date().getHours(),
        minutes: new Date().getMinutes(),
    };
    form.start = startTime.value.hours + ":" + startTime.value.minutes;
};

const addActivity = () => {
    disableAddActivity.value = true;
    form.start = startTime.value.hours + ":" + startTime.value.minutes;
    form.post("/attendance", {
        onSuccess: () => {
            addActivityForm.value = false;
            disableAddActivity.value = false;
            disableCheckOut.value = false;
            form.description = null;
            form.start = null;
            scrollDown();
            Swal.fire({
                icon: "success",
                title: "Activity added <br> <i class='fa-solid fa-user-astronaut'></i>",
                text: "Keep strong and continue your journey",
                showConfirmButton: false,
                timer: 3000,
            });
        },
    });
};

const openEditActivityForm = (activity) => {
    addActivityForm.value = false;
    editActivityForm.value = true;
    disableCheckOut.value = true;
    form.description_updated = activity.description;
    form.project_id_updated = activity.project_id;
    editActivityID.value = activity.id;
    form.errors = [];
};

const updateActivity = () => {
    disableUpdateActivity.value = true;
    form.post("/attendance/update/" + editActivityID.value, {
        onSuccess: () => {
            editActivityForm.value = false;
            disableUpdateActivity.value = false;
            disableCheckOut.value = false;
            form.description_updated = null;
            form.project_id_updated = null;
            editActivityID.value = null;
            scrollDown();
            Swal.fire({
                icon: "success",
                title: "Activity updated <br> <i class='fa-solid fa-pen-to-square'></i>",
                text: "Keep strong and continue your journey",
                showConfirmButton: false,
                timer: 3000,
            });
        },
    });
};

const closeActivityForm = () => {
    form.description = null;

    addActivityForm.value = false;
    editActivityForm.value = false;
    disableAddActivity.value = false;
    disableUpdateActivity.value = false;
    disableCheckOut.value = false;
};

const checkOut = () => {
    if (props.activities.length < 1 || props.relogin) {
        Swal.fire({
            title: "What are you doing? <br> <i class='fa-solid fa-person-through-window'></i>",
            text: "Please write down your activity before checking out",
            icon: "error",
            confirmButtonText: "Got it <i class='fa-solid fa-pen-to-square'></i>",
        });
    } else {
        Swal.fire({
            title: "Are you sure? <br> <i class='fa-solid fa-door-open'></i>",
            text: "Do you want to check out now?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "orange",
            confirmButtonText: "Yes, please! <i class='fa-solid fa-person-walking-dashed-line-arrow-right'></i>",
            cancelButtonText: "Not yet <i class='fa-solid fa-person-digging'></i>",
        }).then((result) => {
            if (result.isConfirmed) {
                disableCheckOut.value = true;
                Inertia.post("/attendance/check-out");
                Swal.fire({
                    title: "You're out! <br> <i class='fa-solid fa-child-reaching'></i>",
                    text: "Thanks for your great work today",
                    icon: "success",
                    confirmButtonText: "See ya <i class='fa-solid fa-hand'></i>",
                });
            }
        });
    }
};

const scrollDown = () => {
    $("#activities")
        .stop()
        .animate(
            {
                scrollTop: $("#activities")[0].scrollHeight,
            },
            800
        );
};

const struggling = (id) => {
    Swal.fire({
        title: "Whoops... <br> <i class='fa-solid fa-user-ninja'></i>",
        text: "Are you really struggling here?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "red",
        cancelButtonColor: "orange",
        confirmButtonText: "Definitely! <i class='fa-solid fa-face-dizzy'></i>",
        cancelButtonText: "Nope, it just needs more time <i class='fa-solid fa-person-digging'></i>",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.post("/attendance/struggle/" + id);
            Swal.fire({
                title: "It's okay <br> <i class='fa-solid fa-user-ninja'></i>",
                text: "Just take a deep breath. We know you will crush it",
                icon: "success",
                confirmButtonText: "Sure <i class='fa-solid fa-paw'></i>",
            });
        }
    });
};

const outOffice = () => {
    Swal.fire({
        title: "Hey, Yo... <br> <i class='fa-solid fa-person-walking-luggage'></i>",
        text: "Are you unable to work today?",
        icon: "question",
        showCancelButton: true,
        cancelButtonColor: "orange",
        confirmButtonText: "Yeap",
        cancelButtonText: "Nope, I'm going to work",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Don't forget <br> <i class='fa-solid fa-users'></i>",
                text: "Have you told your co-workers?",
                icon: "info",
                showCancelButton: true,
                cancelButtonColor: "orange",
                confirmButtonText: "I have",
                cancelButtonText: "Not yet",
            }).then((result) => {
                if (result.isConfirmed) {
                    disableCheckIn.value = true;
                    Inertia.post("/attendance/out-office");
                    Swal.fire({
                        title: "Okay <br> <i class='fa-solid fa-person-walking-luggage'></i>",
                        text: "Whatever your business, we hope everything is fine and see ya tomorrow",
                        icon: "success",
                        confirmButtonText: "See ya <i class='fa-solid fa-hand'></i>",
                    });
                }
            });
        }
    });
};

const outSick = () => {
    Swal.fire({
        title: "Whoops... <br> <i class='fa-regular fa-face-surprise'></i>",
        text: "Are you sick and unable to work today?",
        icon: "question",
        showCancelButton: true,
        cancelButtonColor: "orange",
        confirmButtonText: "Yes, I'm",
        cancelButtonText: "I'm fine and going to work",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Don't forget <br> <i class='fa-solid fa-users'></i>",
                text: "Have you told your co-workers?",
                icon: "info",
                showCancelButton: true,
                cancelButtonColor: "orange",
                confirmButtonText: "I have",
                cancelButtonText: "Not yet",
            }).then((result) => {
                if (result.isConfirmed) {
                    disableCheckIn.value = true;
                    Inertia.post("/attendance/out-sick");
                    Swal.fire({
                        title: "Okay <br> <i class='fa-solid fa-bed'></i>",
                        text: "Get well soon and see ya tomorrow",
                        icon: "success",
                        confirmButtonText: "See ya <i class='fa-solid fa-hand'></i>",
                    });
                }
            });
        }
    });
};
</script>
