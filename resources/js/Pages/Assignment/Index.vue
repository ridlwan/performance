<template>
    <Layout>
        <template #heading>
            <ol
                class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5"
            >
                <li
                    class="breadcrumb-item text-white active"
                    style="width: 300px"
                >
                    <h6 class="font-weight-bolder text-white mb-0">
                        Assignment
                    </h6>
                </li>
            </ol>
        </template>

        <div class="row" style="min-height: 670px">
            <div class="col-lg-12">
                <!-- <div
                class="col-6"
                v-for="(user, userIndex) in users"
                :key="userIndex"
            > -->
                <div
                    class="card mb-2"
                    v-for="(user, userIndex) in users"
                    :key="userIndex"
                >
                    <div class="card-body pt-0">
                        <div class="row mt-4">
                            <div class="row">
                                <div class="col-lg-10 col-xs-10">
                                    <h6 class="mb-0 text-sm">
                                        {{ user.name }} - {{ user.position }}
                                    </h6>
                                    <p class="text-xs text-secondary mt-2 mb-2">
                                        {{ user.email }}
                                    </p>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <a
                                                href="#"
                                                class="badge rounded-pill text-bg-primary me-1 mt-1 text-white"
                                                v-for="(
                                                    assignment, assignmentIndex
                                                ) in user.assignments"
                                                :key="assignmentIndex"
                                                @click="destroy(assignment.id)"
                                            >
                                                {{ assignment.project.name }}
                                                <i
                                                    class="fa-solid fa-trash ms-1"
                                                ></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <a
                                        href="#"
                                        class="position-absolute top-0 end-0 me-3 mt-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#projectModal"
                                        @click="
                                            openProjectModal(user.id, userIndex)
                                        "
                                        ><i class="fa-solid fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="modal fade"
            id="projectModal"
            role="dialog"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6
                            class="modal-title opacity-7"
                            style="color: black !important"
                        >
                            Add Project
                        </h6>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                            style="
                                color: black;
                                font-size: 20px;
                                padding-top: 0px;
                            "
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="addProject">
                        <div class="modal-body">
                            <div class="form-group mb-0">
                                <select
                                    class="form-control"
                                    v-model="form.project_id"
                                    :class="{
                                        'is-invalid': form.errors.project_id,
                                    }"
                                >
                                    <option
                                        v-for="project in projects"
                                        :key="project.id"
                                        :value="project.id"
                                    >
                                        {{ project.name }}
                                    </option>
                                </select>
                                <div
                                    v-if="form.errors.project_id"
                                    class="invalid-feedback"
                                >
                                    {{ form.errors.project_id }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="mt-0 mb-0 btn bg-gradient-default"
                                data-bs-dismiss="modal"
                                ref="closeProjectModal"
                            >
                                Close
                            </button>
                            <button
                                type="button"
                                class="mt-0 mb-0 btn bg-gradient-primary"
                                @click="addProject"
                            >
                                Assign
                            </button>
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

const closeProjectModal = ref(null);

const addProject = () => {
    if (
        !props.users[userIndex.value].assignments.find(
            (data) => data.project_id === form.project_id
        )
    ) {
        form.post("/assignments", {
            preserveScroll: true,
            onSuccess: () => {
                closeProjectModal.value.click();
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
