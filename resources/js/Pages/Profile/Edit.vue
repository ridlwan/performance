<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">Profile</h6>
        </template>

        <div class="row">
            <div class="col-lg-6 col-md-6 mt-4 mt-sm-0 mb-3">
                <div class="card">
                    <div class="card-header pb-0"></div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label"
                                        >Name</label
                                    >
                                    <div class="input-group mb-3">
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.name"
                                            :class="{
                                                'is-invalid': form.errors.name,
                                            }"
                                        />
                                        <button
                                            class="btn btn-outline-primary mb-0 keep-radius"
                                            type="button"
                                            :disabled="form.processing"
                                            @click="updateName"
                                        >
                                            Update
                                        </button>
                                        <span
                                            v-if="form.errors.name"
                                            class="invalid-feedback"
                                            >{{ form.errors.name }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label"
                                        >Email Address</label
                                    >
                                    <input
                                        class="form-control"
                                        type="email"
                                        v-model="user.email"
                                        disabled
                                    />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label"
                                        >Password</label
                                    >
                                    <div class="input-group mb-3">
                                        <input
                                            type="password"
                                            class="form-control"
                                            v-model="form.password"
                                            :class="{
                                                'is-invalid':
                                                    form.errors.password,
                                            }"
                                        />
                                        <button
                                            class="btn btn-outline-primary mb-0 keep-radius"
                                            type="button"
                                            :disabled="form.processing"
                                            @click="updatePassword"
                                        >
                                            Update
                                        </button>
                                        <span
                                            v-if="form.errors.password"
                                            class="invalid-feedback"
                                            >{{ form.errors.password }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label"
                                        >Avatar</label
                                    >
                                    <div class="input-group mb-3">
                                        <div
                                            class="avatar avatar-l position-relative"
                                            style="border: 1px solid #d2d6da"
                                        >
                                            <img
                                                v-if="form.avatar"
                                                ref="avatarView"
                                                :src="'/storage/' + user.avatar"
                                                class="h-100 border-radius-lg image-inside-form"
                                            />
                                            <img
                                                v-else
                                                src="/assets/img/logo-sq.png"
                                                class="h-100 border-radius-lg image-inside-form"
                                            />
                                        </div>
                                        <input
                                            type="file"
                                            class="form-control"
                                            @change="attachAvatar"
                                            ref="avatarForm"
                                            style="
                                                padding-top: 12px;
                                                padding-left: 12px;
                                            "
                                            :class="{
                                                'is-invalid':
                                                    form.errors.avatar,
                                            }"
                                        />
                                        <button
                                            class="btn btn-outline-primary mb-0 keep-radius"
                                            type="button"
                                            :disabled="form.processing"
                                            @click="updateAvatar"
                                        >
                                            Update
                                        </button>
                                        <span
                                            v-if="form.errors.avatar"
                                            class="invalid-feedback"
                                            >{{ form.errors.avatar }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label"
                                        >Background</label
                                    >
                                    <div class="input-group mb-3">
                                        <div
                                            class="avatar avatar-l position-relative"
                                            style="border: 1px solid #d2d6da"
                                        >
                                            <img
                                                v-if="form.background"
                                                ref="backgroundView"
                                                :src="
                                                    '/storage/' +
                                                    user.background
                                                "
                                                class="h-100 border-radius-lg image-inside-form"
                                            />
                                            <img
                                                v-else
                                                src="/assets/img/logo-sq.png"
                                                class="h-100 border-radius-lg image-inside-form"
                                            />
                                        </div>
                                        <input
                                            type="file"
                                            class="form-control"
                                            @change="attachBackground"
                                            ref="backgroundForm"
                                            style="
                                                padding-top: 12px;
                                                padding-left: 12px;
                                            "
                                            :class="{
                                                'is-invalid':
                                                    form.errors.background,
                                            }"
                                        />
                                        <button
                                            class="btn btn-outline-primary mb-0 keep-radius"
                                            type="button"
                                            :disabled="form.processing"
                                            @click="updateBackground"
                                        >
                                            Update
                                        </button>
                                        <span
                                            v-if="form.errors.background"
                                            class="invalid-feedback"
                                            >{{ form.errors.background }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div
                                    class="my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3"
                                >
                                    <div
                                        class="nav-wrapper position-relative center justify-content-center"
                                    >
                                        <ul class="nav nav-pills nav-fill p-1">
                                            <li class="nav-item">
                                                <a
                                                    v-if="
                                                        form.darkmode == 'Yes'
                                                    "
                                                    class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center"
                                                    href="javascript:;"
                                                    @click="
                                                        updateDarkMode('No')
                                                    "
                                                >
                                                    <i
                                                        class="fa-solid fa-sun"
                                                    ></i>
                                                    <span class="ms-2"
                                                        >Light Mode</span
                                                    >
                                                </a>
                                                <a
                                                    v-else
                                                    class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center"
                                                    href="javascript:;"
                                                >
                                                    <i
                                                        class="fa-solid fa-sun"
                                                    ></i>
                                                    <span class="ms-2"
                                                        >Light Mode</span
                                                    >
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a
                                                    v-if="form.darkmode == 'No'"
                                                    class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center active"
                                                    href="javascript:;"
                                                    @click="
                                                        updateDarkMode('Yes')
                                                    "
                                                >
                                                    <i
                                                        class="fa-solid fa-moon"
                                                    ></i>
                                                    <span class="ms-2"
                                                        >Dark Mode</span
                                                    >
                                                </a>
                                                <a
                                                    v-else
                                                    class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center active"
                                                    href="javascript:;"
                                                >
                                                    <i
                                                        class="fa-solid fa-moon"
                                                    ></i>
                                                    <span class="ms-2"
                                                        >Dark Mode</span
                                                    >
                                                </a>
                                            </li>
                                            <div
                                                class="moving-tab position-absolute nav-link"
                                                style="
                                                    padding: 0px;
                                                    transition: all 0.5s ease 0s;
                                                    width: 49%;
                                                "
                                                :style="
                                                    form.darkmode == 'Yes'
                                                        ? 'transform: translate3d(100%, 0px, 0px)'
                                                        : 'transform: unset'
                                                "
                                            >
                                                <a
                                                    class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center active"
                                                    href="javascript:;"
                                                    >&nbsp;</a
                                                >
                                            </div>
                                        </ul>
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
.form-control:hover:not(:disabled):not([readonly])::file-selector-button {
    background-color: unset;
}

.keep-radius {
    border-top-right-radius: 8px !important;
    border-bottom-right-radius: 8px !important;
}

.image-inside-form {
    border-radius: 10px;
    border-top-right-radius: 0px;
    border-bottom-right-radius: 0px;
}
</style>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useForm, usePage, Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Components/LayoutAttendance.vue";
import Swal from "sweetalert2";

const form = useForm({
    name: null,
    password: null,
    avatar: null,
    background: null,
    darkmode: null,
});

const props = defineProps({
    user: Object,
});

onMounted(() => {
    form.name = props.user.name;
    form.avatar = props.user.avatar;
    form.background = props.user.background;
    form.darkmode = props.user.dark_mode_text;
});

const updateName = () => {
    form.post("/profile", {
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Name changed <br> <i class='fa-solid fa-address-card'></i>",
                text: "Your profile was successfully updated",
                showConfirmButton: false,
                timer: 3000,
            });
        },
    });
};

const updatePassword = () => {
    if (form.password) {
        Swal.fire({
            title: "Are you sure? <br> <i class='fa-solid fa-key'></i>",
            text: "Do you want to update your password?",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: "orange",
            confirmButtonText: "Yes, please!",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                form.post("/profile/password", {
                    onSuccess: () => {
                        Swal.fire({
                            icon: "success",
                            title: "Password changed <br> <i class='fa-solid fa-key'></i>",
                            text: "Your profile was successfully updated",
                            showConfirmButton: false,
                            timer: 3000,
                        });
                    },
                });
            }
        });
    } else {
        form.errors.password = "The password field is required.";
    }
};

const avatarForm = ref(null);
const avatarView = ref(null);

const attachAvatar = () => {
    form.avatar = avatarForm.value.files[0];
    let reader = new FileReader();
    reader.addEventListener(
        "load",
        function () {
            avatarView.value.src = reader.result;
        }.bind(this),
        false
    );

    reader.readAsDataURL(form.avatar);
};

const updateAvatar = () => {
    form.post("/profile/avatar", {
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Avatar changed <br> <i class='fa-solid fa-address-card'></i>",
                text: "Your profile was successfully updated",
                showConfirmButton: false,
                timer: 3000,
            });
        },
    });
};

const backgroundForm = ref(null);
const backgroundView = ref(null);

const attachBackground = () => {
    form.background = backgroundForm.value.files[0];
    let reader = new FileReader();
    reader.addEventListener(
        "load",
        function () {
            backgroundView.value.src = reader.result;
        }.bind(this),
        false
    );

    reader.readAsDataURL(form.background);
};

const updateBackground = () => {
    form.post("/profile/background", {
        onSuccess: () => {
            Swal.fire({
                title: "Background changed <br> <i class='fa-regular fa-image'></i>",
                text: "Your page needs to be refreshed to show the latest background",
                icon: "success",
                confirmButtonText: "Okay",
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        },
    });
};

const updateDarkMode = (choice) => {
    if (choice == "Yes") {
        form.darkmode = "Yes";
    } else {
        form.darkmode = "No";
    }

    form.post("/profile/darkmode", {
        onSuccess: () => {
            Swal.fire({
                title: "View mode changed <br> <i class='fa-solid fa-circle-half-stroke'></i>",
                text: "Your page needs to be refreshed to show the latest view mode",
                icon: "success",
                confirmButtonText: "Okay",
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        },
    });
};
</script>
