<template>
    <layout>
        <template #heading>
            <li>
                <a class="no-pdd-right" href="javascript:void(0);"><strong>SP Document List</strong></a>
            </li>
        </template>

        <main class="main-content bgc-grey-100">
            <div id="mainContent">
                <div class="row gap-20 masonry pos-r" style="position: relative; height: 1137px">
                    <div class="masonry-sizer col-md-12"></div>
                    <div class="masonry-item col-md-12">
                        <div class="bgc-white p-20 bd">
                            <div class="peers ai-c jc-sb fxw-nw">
                                <div class="peer">
                                    <div class="btn-group" role="group">
                                        <h6 class="c-grey-900">Create SP Document</h6>
                                    </div>
                                </div>
                                <div class="peer">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn cur-p btn-outline-primary"><i class="fa-solid fa-left-long"></i> Back</button>
                                    </div>
                                </div>
                            </div>

                            <div class="mT-10">
                                <form>
                                    <div class="row">
                                        <div class="mb-3 col-md-6"><label class="form-label" for="inputEmail4">Email</label> <input type="email" class="form-control" id="inputEmail4" placeholder="Email" /></div>
                                        <div class="mb-3 col-md-6"><label class="form-label" for="inputPassword4">Password</label> <input type="password" class="form-control" id="inputPassword4" placeholder="Password" /></div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label fw-500">Birthdate</label>
                                            <div class="timepicker-input input-icon mb-3">
                                                <div class="input-group">
                                                    <div class="input-group-text bgc-white bd bdwR-0"><i class="fa-solid fa-calendar-days"></i></div>
                                                    <input type="text" class="form-control bdc-grey-200 start-date" placeholder="Datepicker" data-provide="datepicker" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label fw-500">Birthdate</label>
                                            <div class="timepicker-input input-icon mb-3">
                                                <div class="input-group">
                                                    <div class="input-group-text bgc-white bd bdwR-0"><i class="fa-solid fa-calendar-days"></i></div>
                                                    <input type="text" class="form-control bdc-grey-200 start-date" placeholder="Datepicker" data-provide="datepicker" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="peers ai-c jc-sb fxw-nw">
                                            <div class="peer">
                                                <div class="btn-group" role="group">
                                                    <h6 class="c-grey-900">Create SP Document</h6>
                                                </div>
                                            </div>
                                            <div class="peer">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn cur-p btn-outline-primary"><i class="fa-solid fa-chevron-down"></i> Hide / Show</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr />

                                    <div class="row">
                                        <div class="col-md-6"><label class="form-label" for="inputEmail4">Email</label></div>
                                        <div class="col-md-5"><label class="form-label" for="inputPassword4">Password</label></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="email" class="form-control" id="inputEmail4" placeholder="Email" />
                                        </div>
                                        <div class="col-md-5"><input type="password" class="form-control" id="inputPassword4" placeholder="Password" /></div>
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-primary btn-color">Submit</button>
                                        </div>
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
                                                <tr>
                                                    <th>1</th>
                                                    <td>Mark</td>
                                                    <td>Otto</td>
                                                    <td>
                                                        <div class="text-center">
                                                            <button type="button" class="btn btn-sm cur-p btn-outline-primary mR-5"><i class="fa-solid fa-pen-to-square"></i></button>
                                                            <button type="submit" class="btn btn-sm btn-primary btn-color mL-5"><i class="fa-solid fa-trash"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mb-1 mt-4">
                                        <button type="button" class="btn cur-p btn-outline-primary mR-5">Reset</button>
                                        <button type="submit" class="btn btn-primary btn-color mL-5">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </layout>
</template>

<script setup>
import Layout from "../../Components/Layout.vue";

const props = defineProps({
    title: String,
});
</script>
