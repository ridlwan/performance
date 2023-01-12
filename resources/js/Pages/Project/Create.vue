<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">Master Project</h6>
        </template>

        <div class="row" style="min-height: 670px">
            <div class="col-md-12">
                <div class="card">
                    <form @submit.prevent="form.post('/projects')">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h6 class="mb-0"><p class="text-uppercase text-sm">Create Project</p></h6>
                                </div>
                                <div class="col-6 text-end">
                                    <Link href="/projects" class="btn bg-gradient-secondary">Back</Link>
                                    <button type="submit" class="btn bg-gradient-success ms-3">Create</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Name</label>
                                        <input class="form-control" type="text" v-model="form.name" :class="{ 'is-invalid': form.errors.name }" />
                                        <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Status</label>
                                        <select class="form-control" v-model="form.status" :class="{ 'is-invalid': form.errors.status }">
                                            <option v-for="(status, index) in statuses" :key="status" :value="index">
                                                {{ status }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors.status" class="invalid-feedback">{{ form.errors.status }}</div>
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
    statuses: Array,
});

const form = useForm({
    name: null,
    status: null,
});
</script>
