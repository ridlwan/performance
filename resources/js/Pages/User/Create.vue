<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">Master Project</h6>
        </template>

        <div class="row" style="min-height: 670px">
            <div class="col-md-12">
                <div class="card">
                    <form @submit.prevent="form.post('/users')">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h6 class="mb-0"><p class="text-uppercase text-sm">Create Account</p></h6>
                                </div>
                                <div class="col-6 text-end">
                                    <Link href="/users" class="btn bg-gradient-secondary">Back</Link>
                                    <button type="submit" class="btn bg-gradient-success ms-3">Create</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Name</label>
                                        <input class="form-control" type="text" v-model="form.name" :class="{ 'is-invalid': form.errors.name }" />
                                        <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Position</label>
                                        <input class="form-control" type="text" v-model="form.position" :class="{ 'is-invalid': form.errors.position }" />
                                        <div v-if="form.errors.position" class="invalid-feedback">{{ form.errors.position }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Email</label>
                                        <input class="form-control" type="text" v-model="form.email" :class="{ 'is-invalid': form.errors.email }" />
                                        <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Password</label>
                                        <input class="form-control" type="text" v-model="form.password" :class="{ 'is-invalid': form.errors.password }" />
                                        <div v-if="form.errors.password" class="invalid-feedback">{{ form.errors.password }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Role</label>
                                        <select class="form-control" v-model="form.role" :class="{ 'is-invalid': form.errors.role }">
                                            <option v-for="role in roles" :key="role.id" :value="role.name">
                                                {{ role.name }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors.role" class="invalid-feedback">{{ form.errors.role }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Teammate</label>
                                        <select class="form-control" v-model="form.teammate" :class="{ 'is-invalid': form.errors.teammate }">
                                            <option v-for="(team, index) in teammate" :key="team" :value="index">
                                                {{ team }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors.teammate" class="invalid-feedback">{{ form.errors.teammate }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Reported</label>
                                        <select class="form-control" v-model="form.reported" :class="{ 'is-invalid': form.errors.reported }">
                                            <option v-for="(report, index) in reported" :key="report" :value="index">
                                                {{ report }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors.reported" class="invalid-feedback">{{ form.errors.reported }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "../../Components/Layout.vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    roles: Array,
    teammate: Array,
    reported: Array,
});

const form = useForm({
    name: null,
    email: null,
    position: null,
    password: null,
    role: null,
    teammate: null,
    reported: null,
});
</script>
