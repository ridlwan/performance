<template>
    <Layout>
        <template #heading>
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-white active" style="width: 300px">
                    <h6 class="font-weight-bolder text-white mb-0">Project</h6>
                </li>
            </ol>
        </template>

        <div class="row">
            <div class="col-lg-12 mb-lg-0">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" class="form-control" placeholder="Search" v-model="search" @change="filter" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" v-model="status" @change="filter">
                                    <option value="All">All Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Close">Close</option>
                                </select>
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
                            <div class="col-md-1">
                                <Link href="/projects/create" class="btn bg-gradient-primary">New</Link>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-3 px-0 pt-0 pb-2" style="min-height: 480px">
                        <div class="table-responsive p-3 pt-0 pb-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-xs text-dark p-2">Project Name</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Type</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Status</th>
                                        <th class="text-center text-uppercase text-xs text-dark">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="project in projects.data" :key="project.id">
                                        <td class="align-middle">
                                            <span class="text-secondary text-xs font-weight-bold">{{ project.name }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ project.type_text }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ project.status_text }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a v-if="project.status_text == 'Close'" href="javascript:;" class="text-success font-weight-bold" v-tooltip="'Open'" @click="open(project.id)"><i class="fa-solid fa-folder-open"></i></a>
                                            <a v-else href="javascript:;" class="text-warning font-weight-bold" v-tooltip="'Close'" @click="close(project.id)"><i class="fa-solid fa-folder-closed"></i></a>

                                            <Link :href="`/projects/${project.id}/edit`" class="text-primary font-weight-bold ms-2" v-tooltip="'Edit'"><i class="fa-solid fa-pen-to-square"></i></Link>
                                            <a v-if="project.assignments.length < 1" href="javascript:;" class="text-danger font-weight-bold ms-2" v-tooltip="'Delete'" @click="destroy(project.id)"><i class="fa-solid fa-trash-can"></i></a>
                                        </td>
                                    </tr>
                                    <tr v-if="projects.data.length < 1">
                                        <td colspan="4" class="align-middle text-center text-secondary">Data not found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <Pagination :links="projects.links" />
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Components/Layout.vue";
import Pagination from "../../Components/Pagination.vue";
import Swal from "sweetalert2";

const props = defineProps({
    projects: Object,
    filters: Object,
});

let activities = ref([]);
let search = ref(props.filters.search);
let status = ref(props.filters.status);
let paginate = ref(props.filters.paginate);

const reset = () => {
    search.value = null;
    status.value = "All";
    paginate.value = 10;

    filter();
};

const filter = () => {
    Inertia.get(
        "/projects",
        {
            search: search.value,
            status: status.value,
            paginate: paginate.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const open = (id) => {
    Swal.fire({
        title: "Are you sure? <br> <i class='fa-solid fa-folder-open'></i>",
        text: "Do you want to open this project?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonColor: "orange",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.get(`/projects/${id}/open`, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            });
        }
    });
};

const close = (id) => {
    Swal.fire({
        title: "Are you sure? <br> <i class='fa-solid fa-folder-closed'></i>",
        text: "Do you want to close this project?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonColor: "orange",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.get(`/projects/${id}/close`, {
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
            Inertia.delete(`/projects/${id}`);
        }
    });
};
</script>
