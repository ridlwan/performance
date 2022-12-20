<template>
    <layout>
        <template #heading>
            <li>
                <a class="no-pdd-right" href="javascript:void(0);"><strong>SP Document List</strong></a>
            </li>
        </template>

        <main class="main-content bgc-grey-100">
            <div id="mainContent">
                <div class="col-md-12">
                    <div class="bgc-white p-20 bd">
                        <div class="peers ai-c jc-sb fxw-nw">
                            <div class="peer">
                                <div class="btn-group" role="group">
                                    <h6 class="c-grey-900">Create SP Document</h6>
                                </div>
                            </div>
                            <div class="peer">
                                <div class="btn-group" role="group">
                                    <Link href="/documents" class="btn cur-p btn-info"><i class="fa-solid fa-left-long"></i> Back</Link>
                                </div>
                            </div>
                        </div>

                        <div class="mT-10">
                            <form @submit.prevent="form.post('/documents')">
                                <div class="row">
                                    <div class="mb-3 col-md-6"><label class="form-label">Shipping Order</label> <input type="text" class="form-control" /></div>
                                    <div class="mb-3 col-md-6"><label class="form-label">Aircraft Registration</label> <input type="text" class="form-control" /></div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Sender Nopeg</label> <input type="text" class="form-control" v-model="form.sender_nopeg" :class="{ 'is-invalid': form.errors.sender_nopeg }" />
                                        <div v-if="form.errors.sender_nopeg" class="invalid-feedback">{{ form.errors.sender_nopeg }}</div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Sender Name</label> <input type="text" class="form-control" v-model="form.sender_name" :class="{ 'is-invalid': form.errors.sender_name }" />
                                        <div v-if="form.errors.sender_name" class="invalid-feedback">{{ form.errors.sender_name }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Sender Unit</label> <input type="text" class="form-control" v-model="form.sender_unit" :class="{ 'is-invalid': form.errors.sender_unit }" />
                                        <div v-if="form.errors.sender_unit" class="invalid-feedback">{{ form.errors.sender_unit }}</div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label fw-500">Sender Date</label>
                                        <div class="timepicker-input input-icon mb-3">
                                            <div class="input-group">
                                                <div class="input-group-text bdwR-0"><i class="fa-solid fa-calendar-days"></i></div>
                                                <input type="date" class="form-control" v-model="form.sender_date" :class="{ 'is-invalid': form.errors.sender_date }" :style="{ 'border-color: #f44336 !important;': form.errors.sender_date }" />
                                                <div v-if="form.errors.sender_date" class="invalid-feedback">{{ form.errors.sender_date }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label class="form-label">Receiver Unit</label> <input type="text" class="form-control" v-model="form.receiver_unit" :class="{ 'is-invalid': form.errors.receiver_unit }" />
                                        <div v-if="form.errors.receiver_unit" class="invalid-feedback">{{ form.errors.receiver_unit }}</div>
                                    </div>
                                </div>
                                <div>
                                    <div class="peers ai-c jc-sb fxw-nw">
                                        <div class="peer">
                                            <div class="btn-group" role="group">
                                                <h6 class="c-grey-900">Document</h6>
                                            </div>
                                        </div>
                                        <div class="peer">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn cur-p btn-info btn-color" v-if="showDocument" @click="showDocument = false"><i class="fa-solid fa-chevron-up"></i> Hide</button>
                                                <button type="button" class="btn cur-p btn-info btn-color" v-else @click="showDocument = true"><i class="fa-solid fa-chevron-down"></i> Show</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div v-if="showDocument">
                                    <div class="row">
                                        <div class="col-md-6"><label class="form-label">Description</label></div>
                                        <div class="col-md-5"><label class="form-label">Remark</label></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" v-model="description" :class="{ 'is-invalid': descriptionError }" />
                                        </div>
                                        <div class="col-md-5"><input type="text" class="form-control" v-model="remark" :class="{ 'is-invalid': remarkError }" /></div>
                                        <div class="col-md-1">
                                            <button v-if="descriptionIndex" type="button" class="btn btn-success btn-color" @click="updateDescription">Update</button>
                                            <button v-else type="button" class="btn btn-primary btn-color" @click="addDescription">Submit</button>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <small class="text-danger" v-if="descriptionError">The description field is required.</small>
                                        </div>
                                        <div class="col-md-5">
                                            <small class="text-danger" v-if="remarkError">The remark field is required.</small>
                                        </div>
                                        <div class="col-md-1"></div>
                                    </div>
                                    <div>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Description</th>
                                                    <th>Remark</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(description, index) in form.descriptions" :key="index">
                                                    <th>{{ index + 1 }}</th>
                                                    <td>{{ description.description }}</td>
                                                    <td>{{ description.remark }}</td>
                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-sm cur-p btn-warning mR-5" @click="editDescription(index)" :disabled="descriptionIndex - 1 == index"><i class="fa-solid fa-pen-to-square"></i></button>
                                                            <button type="button" class="btn btn-sm btn-primary btn-color mL-5" @click="removeDescription(index)" :disabled="descriptionIndex - 1 == index"><i class="fa-solid fa-trash"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr v-if="form.descriptions.length < 1">
                                                    <td colspan="4" class="text-center">Data empty</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="text-center mb-1 mt-4">
                                    <button type="button" class="btn cur-p btn-secondary mR-5" @click="reset">Reset</button>
                                    <button type="submit" class="btn btn-primary btn-color mL-5" :disabled="form.processing">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </layout>
</template>

<script setup>
import { ref } from "vue";
import Layout from "../../Components/Layout.vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    title: String,
});

const form = useForm({
    sender_nopeg: null,
    sender_name: null,
    sender_unit: null,
    sender_date: null,
    receiver_unit: null,
    descriptions: [],
});

let showDocument = ref(true);
let description = ref(null);
let remark = ref(null);
let descriptionError = ref(false);
let remarkError = ref(false);
let descriptionIndex = ref(null);

const validateDescription = () => {
    if (description.value) {
        descriptionError.value = false;
    } else {
        descriptionError.value = true;
    }

    if (remark.value) {
        remarkError.value = false;
    } else {
        remarkError.value = true;
    }
};

const addDescription = () => {
    validateDescription();

    if (!descriptionError.value && !remarkError.value) {
        form.descriptions.push({
            description: description.value,
            remark: remark.value,
        });

        resetDescription();
    }
};

const editDescription = (index) => {
    resetDescription();

    descriptionIndex.value = index + 1;
    description.value = form.descriptions[index].description;
    remark.value = form.descriptions[index].remark;
};

const updateDescription = () => {
    validateDescription();

    if (!descriptionError.value && !remarkError.value) {
        form.descriptions[descriptionIndex.value - 1].description = description.value;
        form.descriptions[descriptionIndex.value - 1].remark = remark.value;
        descriptionIndex.value = null;

        resetDescription();
    }
};

const removeDescription = (index) => {
    form.descriptions.splice(index, 1);
};

const resetForm = () => {
    form.sender_nopeg = null;
    form.sender_name = null;
    form.sender_unit = null;
    form.sender_date = null;
    form.receiver_unit = null;
    form.descriptions = [];
    form.errors = [];
};

const resetDescription = () => {
    description.value = null;
    remark.value = null;
    descriptionError.value = false;
    remarkError.value = false;
};

const reset = () => {
    resetForm();
    resetDescription();
};
</script>
