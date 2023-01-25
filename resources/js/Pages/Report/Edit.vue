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
                    <form @submit.prevent="form.put(`/reports/${report.id}`)">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h6 class="mb-0"><p class="text-uppercase text-sm">Edit Report</p></h6>
                                </div>
                                <div class="col-6 text-end">
                                    <Link href="/reports" class="btn bg-gradient-secondary">Back</Link>
                                    <button type="submit" class="btn bg-gradient-info ms-3">Update</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-8">
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
                            <hr class="horizontal dark mt-3" />
                            <p class="text-uppercase text-sm">Resource In Charge</p>
                            <div class="row mb-3">
                                <div class="table-responsive p-3 pt-0 pb-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-xs text-dark text-center">No</th>
                                                <th class="text-uppercase text-xs text-dark">Personel</th>
                                                <th class="text-center text-uppercase text-xs text-dark">Role</th>
                                                <th class="text-center text-uppercase text-xs text-dark">Project Assignment</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(responsibility, responsibilityIndex) in form.responsibilities" :key="responsibilityIndex">
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">{{ responsibilityIndex + 1 }}</span>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex px-3">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ responsibility.user.name }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ responsibility.user.email }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-secondary text-xs font-weight-bold">{{ responsibility.user.position }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button v-for="(assignment, assignmentIndex) in responsibility.assignments" :key="assignmentIndex" class="btn btn-sm bg-gradient-primary mb-0 p-2 ms-2" type="button" @click="removeProject(responsibilityIndex, assignmentIndex)">
                                                        <span class="btn-inner--text">{{ assignment.project.name }}</span>
                                                        <i class="fa-solid fa-xmark ms-2"></i>
                                                    </button>
                                                    <button class="btn btn-sm bg-gradient-success mb-0 p-3 pt-2 pb-2 ms-2" type="button" data-bs-toggle="modal" data-bs-target="#projectModal" @click="openProjectModal(responsibilityIndex)">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr v-if="form.responsibilities.length < 1">
                                                <td colspan="4" class="align-middle text-center text-secondary">Data not found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="projectModal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title opacity-7" style="color: black !important">Add Project</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: black; font-size: 20px; padding-top: 0px">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label class="form-control-label">Project</label>
                            <select class="form-control" v-model="responsibilityProjectId">
                                <option v-for="project in allProjects" :key="project.id" :value="project.id">
                                    {{ project.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mt-0 mb-0 btn bg-gradient-default" data-bs-dismiss="modal" id="closeProjectModal">Close</button>
                        <button type="button" class="mt-0 mb-0 btn bg-gradient-success" @click="addProject">Add</button>
                    </div>
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
import moment from "moment";

const props = defineProps({
    report: Object,
    projects: Array,
    allProjects: Array,
});

const form = useForm({
    name: props.report.name,
    start: moment(props.report.start).format("Y-MM-DD"),
    end: moment(props.report.end).format("Y-MM-DD"),
    waiting_for_support: props.report.support.waiting_for_support,
    waiting_for_customer: props.report.support.waiting_for_customer,
    waiting_for_partner: props.report.support.waiting_for_partner,
    escalated: props.report.support.escalated,
    pending: props.report.support.pending,
    in_progress: props.report.support.in_progress,
    resolved: props.report.support.resolved,
    completed: props.report.support.completed,
    closed: props.report.support.closed,
    canceled: props.report.support.canceled,
    sla: props.report.support.sla,
    responsibilities: [],
});

let userResponsibilityIndex = ref(null);
let responsibilityProjectId = ref(null);

onMounted(() => {
    props.report.responsibilities.forEach((responsibility) => {
        let assignments = [];
        responsibility.assignments.forEach((assignment) => {
            assignments.push({
                project: assignment.project,
                project_id: assignment.project_id,
            });
        });

        form.responsibilities.push({
            user: responsibility.user,
            assignments: assignments,
        });
    });

    if (props.allProjects.length > 0) {
        responsibilityProjectId.value = props.allProjects[0].id;
    }
});

const openProjectModal = (index) => {
    userResponsibilityIndex.value = index;
};

const addProject = () => {
    if (!form.responsibilities[userResponsibilityIndex.value].assignments.find((data) => data.project_id === responsibilityProjectId.value)) {
        form.responsibilities[userResponsibilityIndex.value].assignments.push({
            project: props.allProjects.find((data) => data.id === responsibilityProjectId.value),
            project_id: responsibilityProjectId.value,
        });

        document.getElementById("closeProjectModal").click();
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

const removeProject = (responsibilityIndex, assignmentIndex) => {
    form.responsibilities[responsibilityIndex].assignments.splice(assignmentIndex, 1);
};
</script>
