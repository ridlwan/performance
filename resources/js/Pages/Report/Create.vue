<template>
    <Layout>
        <template #heading>
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-white">
                    <Link href="/reports" class="opacity-5 text-white"><h6 class="font-weight-bolder text-white mb-0">Monthly Report</h6></Link>
                </li>
                <li class="breadcrumb-item text-white active" style="width: 300px">
                    <h6 class="font-weight-bolder text-white mb-0">Create Report</h6>
                </li>
            </ol>
        </template>

        <div class="row" style="min-height: 670px">
            <div class="col-md-12">
                <div class="card">
                    <form @submit.prevent="form.post('/reports')">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h6 class="mb-0"><p class="text-uppercase text-sm">Create Report</p></h6>
                                </div>
                                <div class="col-6 text-end">
                                    <Link href="/reports" class="btn bg-gradient-secondary">Back</Link>
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Start</label>
                                        <input class="form-control" type="date" v-model="form.start" :class="{ 'is-invalid': form.errors.start }" />
                                        <div v-if="form.errors.start" class="invalid-feedback">{{ form.errors.start }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">End</label>
                                        <input class="form-control" type="date" v-model="form.end" :class="{ 'is-invalid': form.errors.end }" />
                                        <div v-if="form.errors.end" class="invalid-feedback">{{ form.errors.end }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Mandays</label>
                                        <input class="form-control" type="text" v-model="form.mandays" :class="{ 'is-invalid': form.errors.mandays }" />
                                        <div v-if="form.errors.mandays" class="invalid-feedback">{{ form.errors.mandays }}</div>
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark" />
                            <p class="text-uppercase text-sm">Support</p>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Waiting For Support</label>
                                        <input class="form-control" type="text" v-model="form.waiting_for_support" :class="{ 'is-invalid': form.errors.waiting_for_support }" />
                                        <div v-if="form.errors.waiting_for_support" class="invalid-feedback">{{ form.errors.waiting_for_support }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Waiting For Customer</label>
                                        <input class="form-control" type="text" v-model="form.waiting_for_customer" :class="{ 'is-invalid': form.errors.waiting_for_customer }" />
                                        <div v-if="form.errors.waiting_for_customer" class="invalid-feedback">{{ form.errors.waiting_for_customer }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Waiting For Partner</label>
                                        <input class="form-control" type="text" v-model="form.waiting_for_partner" :class="{ 'is-invalid': form.errors.waiting_for_partner }" />
                                        <div v-if="form.errors.waiting_for_partner" class="invalid-feedback">{{ form.errors.waiting_for_partner }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Escalated</label>
                                        <input class="form-control" type="text" v-model="form.escalated" :class="{ 'is-invalid': form.errors.escalated }" />
                                        <div v-if="form.errors.escalated" class="invalid-feedback">{{ form.errors.escalated }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Pending</label>
                                        <input class="form-control" type="text" v-model="form.pending" :class="{ 'is-invalid': form.errors.pending }" />
                                        <div v-if="form.errors.pending" class="invalid-feedback">{{ form.errors.pending }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">In Progress</label>
                                        <input class="form-control" type="text" v-model="form.in_progress" :class="{ 'is-invalid': form.errors.in_progress }" />
                                        <div v-if="form.errors.in_progress" class="invalid-feedback">{{ form.errors.in_progress }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Resolved</label>
                                        <input class="form-control" type="text" v-model="form.resolved" :class="{ 'is-invalid': form.errors.resolved }" />
                                        <div v-if="form.errors.resolved" class="invalid-feedback">{{ form.errors.resolved }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Completed</label>
                                        <input class="form-control" type="text" v-model="form.completed" :class="{ 'is-invalid': form.errors.completed }" />
                                        <div v-if="form.errors.completed" class="invalid-feedback">{{ form.errors.completed }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Closed</label>
                                        <input class="form-control" type="text" v-model="form.closed" :class="{ 'is-invalid': form.errors.closed }" />
                                        <div v-if="form.errors.closed" class="invalid-feedback">{{ form.errors.closed }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-control-label">Canceled</label>
                                        <input class="form-control" type="text" v-model="form.canceled" :class="{ 'is-invalid': form.errors.canceled }" />
                                        <div v-if="form.errors.canceled" class="invalid-feedback">{{ form.errors.canceled }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label">SLA</label>
                                        <div class="input-group input-group-alternative mb-0" :class="{ 'is-invalid': form.errors.sla }">
                                            <input class="form-control" type="text" v-model="form.sla" :class="{ 'is-invalid': form.errors.sla }" />
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div v-if="form.errors.sla" class="invalid-feedback">{{ form.errors.sla }}</div>
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
import { ref, onMounted } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";

const props = defineProps({
    projects: Array,
    users: Array,
});

const form = useForm({
    name: null,
    start: null,
    end: null,
    mandays: null,
    waiting_for_support: null,
    waiting_for_customer: null,
    waiting_for_partner: null,
    escalated: null,
    pending: null,
    in_progress: null,
    resolved: null,
    completed: null,
    closed: null,
    canceled: null,
    sla: null,
});
</script>
