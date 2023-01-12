<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">Master Report</h6>
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-control-label">Start</label>
                                        <input class="form-control" type="date" v-model="form.start" :class="{ 'is-invalid': form.errors.start }" />
                                        <div v-if="form.errors.start" class="invalid-feedback">{{ form.errors.start }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-control-label">End</label>
                                        <input class="form-control" type="date" v-model="form.end" :class="{ 'is-invalid': form.errors.end }" />
                                        <div v-if="form.errors.end" class="invalid-feedback">{{ form.errors.end }}</div>
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark" />
                            <p class="text-uppercase text-sm">Jira Planning</p>
                            <div v-for="(jira, jira_index) in form.jira" :key="jira_index" class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label">Project</label>
                                        <select class="form-control" v-model="form.jira[jira_index]['project_id']" :class="{ 'is-invalid': form.errors[`jira.${jira_index}.project_id`] }" @change="checkJira(jira_index)">
                                            <option v-for="project in projects" :key="project.id" :value="project.id">
                                                {{ project.name }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors[`jira.${jira_index}.project_id`]" class="invalid-feedback">{{ form.errors[`jira.${jira_index}.project_id`] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label">Percentage</label>
                                        <input class="form-control" type="text" v-model="form.jira[jira_index]['value']" :class="{ 'is-invalid': form.errors[`jira.${jira_index}.value`] }" />
                                        <div v-if="form.errors[`jira.${jira_index}.value`]" class="invalid-feedback">{{ form.errors[`jira.${jira_index}.value`] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2 pt-4">
                                    <div class="form-group mb-0 pt-2 mt-1">
                                        <button type="button" class="btn btn-sm bg-gradient-danger" @click="removeJira(jira_index)">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-sm bg-gradient-info" @click="addJira">Add</button>
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
import Swal from "sweetalert2";

const props = defineProps({
    projects: Array,
});

const form = useForm({
    name: null,
    start: null,
    end: null,
    jira: [
        {
            project_id: null,
            value: null,
        },
    ],
});

const addJira = () => {
    form.jira.push({
        project_id: null,
        value: null,
    });
};

const removeJira = (index) => {
    form.jira.splice(index, 1);
};

const checkJira = (jira_index) => {
    form.jira.forEach((element, index) => {
        if (jira_index != index) {
            if (element["project_id"] == form.jira[jira_index].project_id) {
                Swal.fire({
                    icon: "error",
                    title: "Duplicate Entry",
                    text: "Make sure to select another project list",
                    showConfirmButton: false,
                    timer: 3000,
                });

                form.jira[jira_index].project_id = null;
            }
        }
    });
};
</script>
