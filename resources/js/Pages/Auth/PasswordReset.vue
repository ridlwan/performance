<template>
    <guest-layout>
        <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-lg-5">
                        <div class="card">
                            <div class="card-header pt-4 pb-4 text-center bg-primary">
                                <Link href="/">
                                    <span><img src="/images/hyper/logo.png" alt="logo" height="22" /></span>
                                </Link>
                            </div>

                            <div class="card-body p-4">
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center mt-0 fw-bold">Reset Password</h4>
                                    <p class="text-muted mb-4">Enter your new password.</p>
                                </div>

                                <div v-if="$page.props.flash.status" class="alert alert-success" role="alert">{{ $page.props.flash.status }}</div>

                                <form v-else @submit.prevent="form.post(route('password.store'))">
                                    <div class="mb-3">
                                        <label class="form-label">Email address</label>
                                        <input class="form-control" type="email" placeholder="Enter your email" :class="{ 'is-invalid': form.errors.email }" v-model="form.email" />
                                        <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <div class="input-group input-group-merge">
                                            <input :type="revealPassword ? 'text' : 'password'" class="form-control" placeholder="Enter your new password" v-model="form.password" :class="{ 'is-invalid': form.errors.password }" />
                                            <div class="input-group-text">
                                                <a href="javascript:void(0)" v-if="revealPassword" @click="revealPassword = false">
                                                    <i class="fa-solid fa-eye-slash"></i>
                                                </a>
                                                <a href="javascript:void(0)" v-else @click="revealPassword = true">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                            <div v-if="form.errors.password" class="invalid-feedback">{{ form.errors.password }}</div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <div class="input-group input-group-merge">
                                            <input :type="revealConfirmPassword ? 'text' : 'password'" class="form-control" placeholder="Enter your confirm password" v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.password_confirmation }" />
                                            <div class="input-group-text">
                                                <a href="javascript:void(0)" v-if="revealConfirmPassword" @click="revealConfirmPassword = false">
                                                    <i class="fa-solid fa-eye-slash"></i>
                                                </a>
                                                <a href="javascript:void(0)" v-else @click="revealConfirmPassword = true">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                            <div v-if="form.errors.password_confirmation" class="invalid-feedback">{{ form.errors.password_confirmation }}</div>
                                        </div>
                                    </div>

                                    <div class="mb-0 text-center">
                                        <button class="btn btn-primary" type="submit" :disabled="form.processing">Reset Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-muted">
                                    Back to <Link :href="route('login')" class="text-muted ms-1"><b>Log In</b></Link>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </guest-layout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    email: props.email,
    password: null,
    password_confirmation: null,
    token: props.token,
});

let revealPassword = ref(false);
let revealConfirmPassword = ref(false);
</script>
