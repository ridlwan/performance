<template>
    <Layout>
        <template #heading>
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-white active" style="width: 300px">
                    <h6 class="font-weight-bolder text-white mb-0">Monthly Report</h6>
                </li>
            </ol>
        </template>

        <div class="row">
            <div class="col-lg-12 mb-lg-0">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div :class="permissions.includes('manage-report') ? 'col-md-8' : 'col-md-9'">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control" placeholder="Search" v-model="search" @change="filter" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" v-model="paginate" @change="filter">
                                    <option value="10">Show 10 data</option>
                                    <option value="25">Show 25 data</option>
                                    <option value="50">Show 50 data</option>
                                    <option value="100">Show 100 data</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn bg-gradient-secondary" @click="reset">Reset</button>
                            </div>
                            <div v-if="permissions.includes('manage-report')" class="col-md-1">
                                <Link href="/reports/create" class="btn bg-gradient-primary">New</Link>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-3 px-0 pt-0 pb-2" style="min-height: 480px">
                        <div class="table-responsive p-3 pt-0 pb-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xs text-dark p-2">Name</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Start</th>
                                        <th class="text-center text-uppercase text-xs text-dark">End</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Mandays</th>
                                        <th v-if="permissions.includes('manage-report')" class="text-center text-uppercase text-xs text-dark">Published</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="report in reports.data" :key="report.id">
                                        <td class="align-middle">
                                            <span class="text-secondary text-xs font-weight-bold">{{ report.name }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $filters.formatDate(report.start) }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $filters.formatDate(report.end) }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ report.mandays }}</span>
                                        </td>
                                        <td v-if="permissions.includes('manage-report')" class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ report.published_text }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <Link :href="`/reports/${report.id}`" class="text-info font-weight-bold" v-tooltip="'Show'"><i class="fa-solid fa-eye"></i></Link>
                                            <span v-if="permissions.includes('manage-report')">
                                                <a v-if="report.published_text == 'No'" href="javascript:;" class="text-success font-weight-bold ms-2" v-tooltip="'Publish'" @click="publish(report.id)"><i class="fa-solid fa-cloud-arrow-up"></i></a>
                                                <a v-else href="javascript:;" class="text-warning font-weight-bold ms-2" v-tooltip="'Unpublish'" @click="unpublish(report.id)"><i class="fa-solid fa-cloud-arrow-down"></i></a>
                                                <Link v-if="report.published_text == 'No'" :href="`/reports/${report.id}/edit`" class="text-primary font-weight-bold ms-2" v-tooltip="'Edit'"><i class="fa-solid fa-pen-to-square"></i></Link>
                                                <a v-if="report.published_text == 'No'" href="javascript:;" class="text-danger font-weight-bold ms-2" v-tooltip="'Delete'" @click="destroy(report.id)"><i class="fa-solid fa-trash-can"></i></a>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="reports.data.length < 1">
                                        <td colspan="5" class="align-middle text-center text-secondary">Data not found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <Pagination :links="reports.links" />
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { usePage, Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Components/Layout.vue";
import Pagination from "../../Components/Pagination.vue";
import Swal from "sweetalert2";

const permissions = computed(() => usePage().props.value.auth.user.permissions);

const props = defineProps({
    reports: Object,
    filters: Object,
});

let activities = ref([]);
let search = ref(props.filters.search);
let paginate = ref(props.filters.paginate);

const reset = () => {
    search.value = null;
    paginate.value = 10;

    filter();
};

const filter = () => {
    Inertia.get(
        "/reports",
        {
            search: search.value,
            paginate: paginate.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const publish = (id) => {
    Swal.fire({
        title: "Are you sure? <br> <i class='fa-solid fa-cloud-arrow-up'></i>",
        text: "Do you want to publish this report?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonColor: "orange",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.get(`/reports/${id}/publish`, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        }
    });
};

const unpublish = (id) => {
    Swal.fire({
        title: "Are you sure? <br> <i class='fa-solid fa-cloud-arrow-down'></i>",
        text: "Do you want to unpublish this report?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonColor: "orange",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.get(`/reports/${id}/unpublish`, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        }
    });
};

const destroy = (id) => {
    Swal.fire({
        title: "Are you sure? <br> <i class='fa-solid fa-trash-can'></i>",
        text: "You won't be able to revert this",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "red",
        cancelButtonColor: "orange",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(`/reports/${id}`);
        }
    });
};
</script>
