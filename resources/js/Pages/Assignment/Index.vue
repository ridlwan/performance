<template>
    <Layout>
        <template #heading>
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-white active" style="width: 300px">
                    <h6 class="font-weight-bolder text-white mb-0">Assignment</h6>
                </li>
            </ol>
        </template>

        <div class="row" style="min-height: 670px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body pt-0">
                        <div class="row mt-4 mb-2">
                            <div class="table-responsive p-3 pt-0 pb-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-xs text-dark">Personel</th>
                                            <th class="text-center text-uppercase text-xs text-dark">Role</th>
                                            <th class="text-center text-uppercase text-xs text-dark">Project Assignment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(user, userIndex) in users" :key="userIndex">
                                            <td class="align-middle">
                                                <div class="d-flex px-3">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ user.name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ user.email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ user.position }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <button v-for="(assignment, assignmentIndex) in user.assignments" :key="assignmentIndex" class="btn btn-sm bg-gradient-primary mb-0 p-2 ms-2" type="button" @click="destroy(assignment.id)">
                                                    <span class="btn-inner--text">{{ assignment.project.name }}</span>
                                                    <i class="fa-solid fa-xmark ms-2"></i>
                                                </button>
                                                <button class="btn btn-sm bg-gradient-success mb-0 p-3 pt-2 pb-2 ms-2" type="button" data-bs-toggle="modal" data-bs-target="#projectModal" @click="openProjectModal(user.id, userIndex)">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="users.length < 1">
                                            <td colspan="4" class="align-middle text-center text-secondary">Data not found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="projectModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title opacity-7" style="color: black !important">Add Project</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: black; font-size: 20px; padding-top: 0px">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="addProject">
                        <div class="modal-body">
                            <div class="form-group mb-0">
                                <select class="form-control" v-model="form.project_id" :class="{ 'is-invalid': form.errors.project_id }">
                                    <option v-for="project in projects" :key="project.id" :value="project.id">
                                        {{ project.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.project_id" class="invalid-feedback">{{ form.errors.project_id }}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="mt-0 mb-0 btn bg-gradient-default" data-bs-dismiss="modal" id="closeProjectModal">Close</button>
                            <button type="button" class="mt-0 mb-0 btn bg-gradient-primary" @click="addProject">Assign</button>
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
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    users: Array,
    projects: Array,
});

const form = useForm({
    user_id: null,
    project_id: null,
});

let userIndex = ref(null);

const openProjectModal = (id, index) => {
    form.user_id = id;
    userIndex.value = index;
};

const addProject = () => {
    if (!props.users[userIndex.value].assignments.find((data) => data.project_id === form.project_id)) {
        form.post("/assignments", {
            preserveScroll: true,
            onSuccess: () => {
                document.getElementById("closeProjectModal").click();
                reset();
            },
        });
    } else {
        Swal.fire({
            icon: "error",
            title: "Duplicate Entry",
            text: "Make sure to select another project list",
            showConfirmButton: false,
            timer: 3000,
        });
    }
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
            Inertia.delete(`/assignments/${id}`);
        }
    });
};

const reset = () => {
    form.user_id = null;
    form.project_id = null;
};
</script>
