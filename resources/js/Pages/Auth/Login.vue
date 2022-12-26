<template>
    <div class="peers ai-s fxw-nw h-100vh">
        <div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style="background-image: url(/assets/login.jpeg)">
            <div class="pos-a centerXY"></div>
        </div>
        <div class="col-12 col-md-4 peer pX-50 pY-160 h-100 pos-r">
            <div class="text-center">
                <img src="/assets/logo.png" alt="logo" style="width: 300px" />
            </div>
            <h1 class="fw-300 mB-10 text-center" style="color: #006198">SP Document</h1>
            <h4 class="fw-300 mB-10 text-center">Please login your account</h4>
            <form @submit.prevent="form.post('/login')">
                <div class="mb-3">
                    <label class="text-normal text-dark form-label">Username</label><input type="text" class="form-control" v-model="form.email" :class="{ 'is-invalid': form.errors.email }" />
                    <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-normal text-dark form-label">Password</label><input type="password" class="form-control" v-model="form.password" :class="{ 'is-invalid': form.errors.password }" />
                    <div v-if="form.errors.password" class="invalid-feedback">{{ form.errors.password }}</div>
                </div>
                <div class="peers ai-c jc-sb fxw-nw">
                    <div class=""><input type="checkbox" id="inputCall1" />&nbsp;<label for="inputCall1" class="form-label">Remember Me</label></div>
                    <div class="peer"><button class="btn btn-primary btn-color" type="submit" :disabled="form.processing">Login</button></div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";

const form = useForm({
    email: null,
    password: null,
    remember: false,
});

let revealPassword = ref(false);
</script>
