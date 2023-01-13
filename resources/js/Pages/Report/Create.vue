<template>
    <Layout>
        <template #heading>
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-white">
                    <Link href="/reports" class="opacity-5 text-white"><h6 class="font-weight-bolder text-white mb-0">Master Report</h6></Link>
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
                            <p class="text-uppercase text-sm">Progress</p>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label">Project</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label">Jira</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label">Development</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label">Testing</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label class="form-control-label">Overall</label>
                                    </div>
                                </div>
                            </div>
                            <div v-for="(progress, progress_index) in form.reportProgress" :key="progress_index" class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <select class="form-control" v-model="form.reportProgress[progress_index]['project_id']" :class="{ 'is-invalid': form.errors[`reportProgress.${progress_index}.project_id`] }" @change="checkJira(progress_index)" disabled>
                                            <option v-for="project in projects" :key="project.id" :value="project.id">
                                                {{ project.name }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors[`reportProgress.${progress_index}.project_id`]" class="invalid-feedback">{{ form.errors[`reportProgress.${progress_index}.project_id`] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <div class="input-group input-group-alternative mb-0" :class="{ 'is-invalid': form.errors[`reportProgress.${progress_index}.jira`] }">
                                            <input class="form-control" type="text" v-model="form.reportProgress[progress_index]['jira']" :class="{ 'is-invalid': form.errors[`reportProgress.${progress_index}.jira`] }" />
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div v-if="form.errors[`reportProgress.${progress_index}.jira`]" class="invalid-feedback">{{ form.errors[`reportProgress.${progress_index}.jira`] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <div class="input-group input-group-alternative mb-0" :class="{ 'is-invalid': form.errors[`reportProgress.${progress_index}.development`] }">
                                            <input class="form-control" type="text" v-model="form.reportProgress[progress_index]['development']" :class="{ 'is-invalid': form.errors[`reportProgress.${progress_index}.development`] }" />
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div v-if="form.errors[`reportProgress.${progress_index}.development`]" class="invalid-feedback">{{ form.errors[`reportProgress.${progress_index}.development`] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <div class="input-group input-group-alternative mb-0" :class="{ 'is-invalid': form.errors[`reportProgress.${progress_index}.testing`] }">
                                            <input class="form-control" type="text" v-model="form.reportProgress[progress_index]['testing']" :class="{ 'is-invalid': form.errors[`reportProgress.${progress_index}.testing`] }" />
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div v-if="form.errors[`reportProgress.${progress_index}.testing`]" class="invalid-feedback">{{ form.errors[`reportProgress.${progress_index}.testing`] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <div class="input-group input-group-alternative mb-0" :class="{ 'is-invalid': form.errors[`reportProgress.${progress_index}.overall`] }">
                                            <input class="form-control" type="text" v-model="form.reportProgress[progress_index]['overall']" :class="{ 'is-invalid': form.errors[`reportProgress.${progress_index}.overall`] }" />
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div v-if="form.errors[`reportProgress.${progress_index}.overall`]" class="invalid-feedback">{{ form.errors[`reportProgress.${progress_index}.overall`] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group mb-0">
                                        <button type="button" class="btn bg-gradient-danger btn-sm" @click="removeJira(progress_index)"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-sm bg-gradient-info" @click="addJira">Add</button>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "../../Components/Layout.vue";
import { onMounted } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";

const props = defineProps({
    projects: Array,
});

const form = useForm({
    name: null,
    start: null,
    end: null,
    reportProgress: [],
});

onMounted(() => {
    props.projects.forEach((project) => {
        form.reportProgress.push({
            project_id: project.id,
            jira: null,
            development: null,
            testing: null,
            overall: null,
        });
    });
});

const addJira = () => {
    form.progress.push({
        project_id: null,
        value: null,
    });
};

const removeJira = (index) => {
    form.reportProgress.splice(index, 1);
};

const checkJira = (progress_index) => {
    form.reportProgress.forEach((element, index) => {
        if (progress_index != index) {
            if (element["project_id"] == form.reportProgress[progress_index].project_id) {
                Swal.fire({
                    icon: "error",
                    title: "Duplicate Entry",
                    text: "Make sure to select another project list",
                    showConfirmButton: false,
                    timer: 3000,
                });

                form.reportProgress[progress_index].project_id = null;
            }
        }
    });
};
</script>
