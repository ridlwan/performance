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
                    <div class="bgc-white bd bdrs-3 p-20 mB-20">
                        <div class="dataTables_wrapper">
                            <div class="mB-50">
                                <div class="dataTables_length">
                                    <button type="button" class="btn cur-p btn-outline-primary"><i class="fa-solid fa-eye"></i> Search & Filter</button>
                                </div>
                                <div class="dataTables_filter">
                                    <Link href="/documents/create" class="btn cur-p btn-primary btn-color"><i class="fa-solid fa-plus"></i> Create SP Document</Link>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered dataTable table-responsive" style="width: 100%" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Shipping No.</th>
                                        <th>Aircraft Registration</th>
                                        <th>Sender Nopeg</th>
                                        <th>Sender Name</th>
                                        <th>Sender Unit</th>
                                        <th>Sender Date</th>
                                        <th>Receiver Nopeg</th>
                                        <th>Receiver Name</th>
                                        <th>Receiver Unit</th>
                                        <th>Receiver Date</th>
                                        <th>Description</th>
                                        <th>Remark</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="document in documents.data" :key="document.id">
                                        <td>No.</td>
                                        <td>Shipping No.</td>
                                        <td>Aircraft Registration</td>
                                        <td>{{ document.sender_nopeg }}</td>
                                        <td>{{ document.sender_name }}</td>
                                        <td>{{ document.sender_unit }}</td>
                                        <td>{{ document.sender_date }}</td>
                                        <td>{{ document.receiver_nopeg }}</td>
                                        <td>{{ document.receiver_name }}</td>
                                        <td>{{ document.receiver_unit }}</td>
                                        <td>{{ document.receiver_date }}</td>
                                        <td>Description</td>
                                        <td>Remark</td>
                                        <td>Status</td>
                                        <td>Action</td>
                                    </tr>
                                    <tr v-if="documents.data.length < 1">
                                        <td colspan="15" class="text-center">Data not found</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="dataTables_length">
                                <label
                                    >Show
                                    <select aria-controls="dataTable" v-model="pagination" @change="list">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    entries</label
                                >
                            </div>

                            <Pagination :links="documents.links" />
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </layout>
</template>

<script setup>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Components/Layout.vue";
import Pagination from "../../Components/Pagination.vue";

const props = defineProps({
    documents: Object,
    filters: Object,
});

let search = ref(props.filters.search);
let pagination = ref(props.filters.pagination);
let order = ref(props.filters.order);
let by = ref(props.filters.by);

const list = () => {
    Inertia.get(
        "/documents",
        { search: search.value, pagination: pagination.value, order: order.value, by: by.value },
        {
            preserveState: true,
            replace: true,
        }
    );
};
</script>
