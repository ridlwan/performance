<template>
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Reset Password</h4>
                                    <p class="mb-0">Enter your email address and we'll send you an email with instructions to reset your password</p>
                                </div>
                                <div class="card-body">
                                    <div v-if="$page.props.flash.status" class="alert alert-primary font-weight-bold text-sm text-white mt-4 mb-5">{{ $page.props.flash.status }}</div>
                                    <form v-else @submit.prevent="form.post('/forgot-password')">
                                        <div class="mb-3">
                                            <input type="email" class="form-control form-control-lg" placeholder="Email" v-model="form.email" :class="{ 'is-invalid': form.errors.email }" />
                                            <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" :disabled="form.processing" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        <Link href="/login" class="text-primary text-gradient font-weight-bold">Back to Sign In</Link>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg'); background-size: cover">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Attention is the new currency"</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<script setup>
import { onMounted } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";

onMounted(() => {
    document.body.classList.remove("g-sidenav-show");
    document.body.classList.remove("bg-gray-100");
});

const form = useForm({
    email: null,
});
</script>
