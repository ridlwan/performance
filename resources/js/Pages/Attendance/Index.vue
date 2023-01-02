<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">Attendance</h6>
        </template>

        <div class="row" v-if="status == 'Working Remotely' || status == 'Working Onsite'">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="text-uppercase text-sm text-dark">Your activity</p>
                            <button class="btn bg-gradient-danger ms-auto" @click="checkOut">Check Out <i class="fas fa-briefcase ms-1"></i></button>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <hr class="horizontal dark" />
                        <div id="activities" style="overflow: auto; height: 300px">
                            <div v-for="(activity, activity_index) in activities" :key="activity.id">
                                <div class="d-flex">
                                    <span class="mb-0 me-3 font-weight-bolder opacity-7" style="white-space: nowrap">{{ activity.start_time }}</span>
                                    <div>
                                        <h6 class="mb-0">
                                            <small class="font-weight-normal">{{ activity.description }}</small>
                                        </h6>
                                        <button v-if="activity_index == activities.length - 1 && activity.struggle_text == 'No' && !activity.end" type="button" @click="struggling(activity.id)" class="btn btn-sm btn-reddit btn-icon-only rounded-circle mb-0" data-bs-toggle="tooltip" title="I'm struggling here">
                                            <span class="btn-inner--icon"><i class="fa-solid fa-user-ninja"></i></span>
                                        </button>

                                        <span v-if="activity.struggle_text == 'Yes'" class="badge bg-gradient-danger" style="text-transform: unset"><i class="fa-solid fa-user-ninja"></i> I'm struggling here</span>
                                    </div>
                                </div>
                                <hr class="horizontal dark mb-4" />
                            </div>
                        </div>
                        <div class="row" v-if="addActivityForm">
                            <div v-if="activities.length < 1 || relogin" class="row" style="padding: 0; margin: 0">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <input class="form-control" type="text" placeholder="Write your activity" v-model="form.description" :class="{ 'is-invalid': form.errors.description }" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:;" class="btn btn-primary d-lg-block" @click="addActivity"><i class="fa-solid fa-play"></i>&nbsp; Start Activity</a>
                                </div>
                            </div>
                            <div v-else class="row" style="padding: 0; margin: 0">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input class="form-control" type="text" placeholder="Write your activity" v-model="form.description" :class="{ 'is-invalid': form.errors.description }" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <Datepicker time-picker class="form-control" type="time" id="timepicker" v-model="startTime" :class="{ 'is-invalid': form.errors.start }" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:;" class="btn btn-primary d-lg-block" @click="addActivity"><i class="fa-solid fa-play"></i>&nbsp; Start Activity</a>
                                </div>
                            </div>

                            <div class="text-center">
                                <a href="javascript:;" @click="closeActivityForm"
                                    ><span class="text-secondary">Hold on a second <i class="fa-solid fa-person-digging ms-1"></i></span
                                ></a>
                            </div>
                        </div>
                        <div v-else class="text-center">
                            <button type="submit" class="btn btn-success" @click="openActivityForm"><i class="fa-solid fa-plus"></i>&nbsp; Add Activity</button>
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
                            <img src="/assets/img/logo.png" alt="" class="img-fluid" />
                        </div>
                        <h6 class="text-center text-uppercase text-xs text-dark" v-html="quote"></h6>
                        <div v-if="status == 'Out of Office' || status == 'Out Sick' || outsideWorkingTime" class="row text-center mt-6 mb-2">
                            <span class="card-description opacity-7">Wish you all the best</span>
                        </div>
                        <div v-else>
                            <div class="row text-center">
                                <button @click="checkIn" class="btn btn-icon bg-gradient-primary d-lg-block mt-5 mb-3">
                                    Check In
                                    <i class="fas fa-laptop-code ms-1"></i>
                                </button>
                            </div>
                            <div v-if="!relogin">
                                <hr class="horizontal dark" />

                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="javascript:;" @click="outOfOffice" class="btn btn-icon bg-gradient-warning d-lg-block mt-3 mb-0">
                                            Out of Office
                                            <i class="fas fa-person-walking-luggage ms-1"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="javascript:;" @click="outSick" class="btn btn-icon bg-gradient-danger d-lg-block mt-3 mb-0">
                                            Out Sick
                                            <i class="fas fa-bed ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

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

const form = useForm({
    description: null,
    start: null,
});

let addActivityForm = ref(false);
let startTime = ref(null);
let outsideWorkingTime = ref(false);

const props = defineProps({
    quote: String,
    activities: Array,
    relogin: Boolean,
});

onMounted(() => {
    if ((status == "Working Remotely" || status == "Working Onsite") && props.activities.length > 0) {
        scrollDown();
    }

    let now = new Date();
    if (now.getHours() >= 23 || now.getHours() <= 1) {
        outsideWorkingTime.value = true;
    }
});

const status = computed(() => usePage().props.value.auth.user.status);

const startTimeData = computed(() => {
    if (startTime.value) {
        form.start = startTime.value.hours + ":" + startTime.value.minutes;
    } else {
        form.start = null;
    }
});

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
            definePosition();
        });
    } else {
        definePosition();
    }
};

const definePosition = () => {
    Swal.fire({
        title: "Define your position? <br> <i class='fa-solid fa-crosshairs'></i>",
        text: "Are you working onsite or remotely?",
        icon: "question",
        showDenyButton: true,
        showCancelButton: true,
        denyButtonColor: "#2ebd59",
        cancelButtonColor: "orange",
        confirmButtonText: "Onsite <i class='fa-solid fa-person-shelter'></i>",
        denyButtonText: "Remotely <i class='fa-solid fa-person-snowboarding'></i>",
        cancelButtonText: "Wait <i class='fa-regular fa-clock'></i>",
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.post("/attendance/check-in", {
                position: "onsite",
            });

            if (props.relogin) {
                alertRelogin();
            } else {
                alertLogin();
            }
        } else if (result.isDenied) {
            Inertia.post("/attendance/check-in", {
                position: "remotely",
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

const openActivityForm = () => {
    addActivityForm.value = true;
    form.errors = [];
    startTime.value = {
        hours: new Date().getHours(),
        minutes: new Date().getMinutes(),
    };
    form.start = startTime.value.hours + ":" + startTime.value.minutes;
};

const closeActivityForm = () => {
    addActivityForm.value = false;
};

const addActivity = () => {
    form.post("/attendance", {
        onSuccess: () => {
            addActivityForm.value = false;
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

const outOfOffice = () => {
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
                    Inertia.post("/attendance/out-of-office");
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
