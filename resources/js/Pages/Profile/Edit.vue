<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">Profile</h6>
        </template>

        <div class="row">
            <div class="col-lg-6 col-md-6 mt-4 mt-sm-0">
                <div class="card">
                    <div class="card-header pb-0"></div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Full Name</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': form.errors.name }" />
                                        <button class="btn btn-outline-primary mb-0 keep-radius" type="button" :disabled="form.processing" @click="updateName">Update</button>
                                        <span v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Email Address</label>
                                    <input class="form-control" type="email" v-model="user.email" disabled />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label">Avatar</label>
                                    <div class="input-group mb-3">
                                        <div class="avatar avatar-l position-relative" style="border: 1px solid #d2d6da">
                                            <img v-if="form.avatar" ref="avatarView" :src="'/storage/' + user.avatar" class="h-100 border-radius-lg shadow-sm image-inside-form" />
                                            <img v-else src="/assets/img/logo-sq.png" class="h-100 border-radius-lg shadow-sm image-inside-form" />
                                        </div>
                                        <input type="file" class="form-control" @change="attachAvatar" ref="avatarForm" style="padding-top: 12px; padding-left: 12px" :class="{ 'is-invalid': form.errors.avatar }" />
                                        <button class="btn btn-outline-primary mb-0 keep-radius" type="button" :disabled="form.processing" @click="updateAvatar">Update</button>
                                        <span v-if="form.errors.avatar" class="invalid-feedback">{{ form.errors.avatar }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Background</label>
                                    <div class="input-group mb-3">
                                        <div class="avatar avatar-l position-relative" style="border: 1px solid #d2d6da">
                                            <img src="/assets/img/logo-sq.png" class="h-100 border-radius-lg shadow-sm image-inside-form" />
                                        </div>
                                        <input type="file" class="form-control" style="padding-top: 12px; padding-left: 12px" :class="{ 'is-invalid': form.errors.background }" />
                                        <button class="btn btn-outline-primary mb-0 keep-radius" type="button" :disabled="form.processing" @click="updateBackground">Update</button>
                                        <span v-if="form.errors.background" class="invalid-feedback">{{ form.errors.background }}</span>
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
    avatar: null,
    background: null,
});

const props = defineProps({
    user: Object,
});

onMounted(() => {
    document.body.classList.add("virtual-reality");
    document.getElementById("navbar-main").classList.add("mt-3");
    document.getElementById("navbar-main").classList.add("mx-3");
    document.getElementById("navbar-main").classList.add("bg-primary");

    form.name = props.user.name;
    form.avatar = props.user.avatar;
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

const updateBackground = () => {};
</script>
