<template>
    <div class="peers ai-s fxw-nw h-100vh">
        <div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style="background-image: url(https://colorlib.com/polygon/adminator/assets/static/images/bg.jpg)">
            <div class="pos-a centerXY">
                <div class="bgc-white bdrs-50p pos-r" style="width: 120px; height: 120px"><img class="pos-a centerXY" src="https://colorlib.com/polygon/adminator/assets/static/images/logo.png" alt="" /></div>
            </div>
        </div>
        <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style="min-width: 320px">
            <h4 class="fw-300 c-grey-900 mB-40">Login</h4>
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
