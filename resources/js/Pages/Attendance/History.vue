<template>
    <Layout>
        <template #heading>
            <h6 class="font-weight-bolder text-white mb-0">History</h6>
        </template>

        <div class="card">
            <div class="card-body pb-2">
                <div class="table-responsive" style="max-height: 500px; overflow: auto">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-xs text-dark">Date</th>
                                <th class="text-center text-uppercase text-xs text-dark">Start</th>
                                <th class="text-center text-uppercase text-xs text-dark">End</th>
                                <th class="text-center text-uppercase text-xs text-dark">Duration</th>
                                <th class="text-center text-uppercase text-xs text-dark">Status</th>
                                <th class="text-center text-uppercase text-xs text-dark">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="attendance in attendances.data" :key="attendance.id">
                                <td class="align-middle text-center">
                                    <span class="text-sm text-secondary">{{ $filters.formatDate(attendance.created_at) }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-sm text-secondary">{{ $filters.formatTime(attendance.start) }}</span>
                                </td>
                                <td v-if="attendance.end" class="align-middle text-center">
                                    <span class="text-sm text-secondary">{{ $filters.formatTime(attendance.end) }}</span>
                                </td>
                                <td v-else class="align-middle text-center">
                                    <span>-</span>
                                </td>
                                <td v-if="attendance.duration" class="align-middle text-center">
                                    <span v-if="attendance.duration > 60" class="text-sm text-secondary"
                                        >{{ Math.floor(attendance.duration / 60) }} hours
                                        <span v-if="attendance.duration - Math.floor(attendance.duration / 60) * 60 > 0"> {{ attendance.duration - Math.floor(attendance.duration / 60) * 60 }} minutes </span>
                                    </span>
                                    <span v-else class="text-sm text-secondary">{{ attendance.duration }} minutes</span>
                                </td>
                                <td v-else class="align-middle text-center">
                                    <span>-</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-sm text-secondary">{{ attendance.status_text }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <a v-if="attendance.activities.length > 0" href="javascript:;" @click="showActivities(attendance)" data-bs-toggle="modal" data-bs-target="#activityModal" class="text-primary font-weight-bold" title="See details"><i class="fa-solid fa-list-check"></i></a>
                                </td>
                            </tr>
                            <tr v-if="attendances.data.length < 1">
                                <td colspan="6" class="align-middle text-center text-secondary">History not found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Pagination :links="attendances.links" />

                <div class="modal fade" id="activityModal" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title" id="activityModalTitle">Activity List</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color: black; font-size: 20px; padding-top: 0px">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group">
                                    <li v-for="activity in activities" :key="activity.id" class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <span v-if="activity.end" class="btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-check"></i></span>
                                            <span v-else class="btn btn-icon-only btn-rounded btn-outline-warning mb-0 me-3 d-flex align-items-center justify-content-center"><i class="fa-solid fa-spinner"></i></span>
                                            <div class="d-flex flex-column">
                                                <p class="mb-1 text-dark">{{ activity.description }}</p>
                                                <span v-if="activity.struggle_text == 'Yes'" class="badge bg-gradient-danger" style="text-transform: unset; width: 100px"><i class="fa-solid fa-user-ninja"></i> Struggled</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center text-sm font-weight-bold">
                                            <div class="d-flex flex-column">
                                                <p class="mb-1 text-dark text-gradient">{{ $filters.formatTime(activity.start) }}</p>
                                                <span v-if="activity.duration">
                                                    <span v-if="activity.duration > 60" class="text-xs text-secondary" style="white-space: nowrap"
                                                        >{{ Math.floor(activity.duration / 60) }} hours
                                                        <span v-if="activity.duration - Math.floor(activity.duration / 60) * 60 > 0">
                                                            <br />
                                                            {{ activity.duration - Math.floor(activity.duration / 60) * 60 }} minutes
                                                        </span>
                                                    </span>

                                                    <span v-else class="text-xs text-secondary" style="white-space: nowrap">{{ activity.duration }} minutes</span>
                                                </span>
                                                <span v-else class="text-xs text-warning" style="white-space: nowrap">In Progress</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn bg-gradient-default" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card"></div>
    </Layout>
</template>

<script setup>
import Pagination from "../../Components/Pagination.vue";
import $ from "jquery";
import { Inertia } from "@inertiajs/inertia";
import { ref, onMounted, computed } from "vue";
import { usePage, Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Components/LayoutAttendance.vue";
import Swal from "sweetalert2";

const props = defineProps({
    attendances: Array,
});

let activities = ref([]);

onMounted(() => {
    document.body.classList.add("virtual-reality");
    document.getElementById("navbar-main").classList.add("mt-3");
    document.getElementById("navbar-main").classList.add("mx-3");
    document.getElementById("navbar-main").classList.add("bg-primary");
});

const showActivities = (attendance) => {
    activities.value = attendance.activities;
};
</script>
