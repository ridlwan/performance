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
                                    <p class="text-muted mb-4">Enter your email address and we'll send you an email with instructions to reset your password.</p>
                                </div>

                                <div v-if="$page.props.flash.status" class="alert alert-success" role="alert">{{ $page.props.flash.status }}</div>

                                <form v-else @submit.prevent="form.post('/forgot-password')">
                                    <div class="mb-3">
                                        <label class="form-label">Email address</label>
                                        <input class="form-control" type="email" placeholder="Enter your email" :class="{ 'is-invalid': form.errors.email }" v-model="form.email" />
                                        <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
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
import { reactive, ref } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";

const form = useForm({
    email: null,
});
</script>
