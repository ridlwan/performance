<template>
    <layout>
        <template #heading>
            <li>
                <a class="no-pdd-right" href="javascript:void(0);"><strong>SP Document List</strong></a>
            </li>
        </template>

        <main class="main-content bgc-grey-100">
            <div id="mainContent">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="bgc-white bd bdrs-3 p-20 mB-20">
                                <div class="dataTables_wrapper">
                                    <div class="mB-50">
                                        <div class="dataTables_length">
                                            <button type="button" class="btn cur-p btn-outline-primary"><i class="fa-solid fa-eye"></i> Search & Filter</button>
                                        </div>
                                        <div class="dataTables_filter">
                                            <button type="button" class="btn cur-p btn-primary btn-color"><i class="fa-solid fa-plus"></i> Create SP Document</button>
                                        </div>
                                    </div>
                                    <table class="table table-striped table-bordered dataTable table-responsive" style="width: 100%" width="100%" cellspacing="0">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" style="width: 171px" aria-sort="ascending" aria-label="Name: activate to sort column descending">Name</th>
                                                <th class="sorting" style="width: 264px" aria-label="Position: activate to sort column ascending">Position</th>
                                                <th class="sorting" style="width: 125px" aria-label="Office: activate to sort column ascending">Office</th>
                                                <th class="sorting" style="width: 54px" aria-label="Age: activate to sort column ascending">Age</th>
                                                <th class="sorting" style="width: 116px" aria-label="Start date: activate to sort column ascending">Start date</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                                <th class="sorting" style="width: 99px" aria-label="Salary: activate to sort column ascending">Salary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Numquam, facilis adipisci quae iure aut odit excepturi voluptate, similique architecto animi neque quasi deleniti in eius perspiciatis sequi reprehenderit sapiente dolorem!</td>
                                                <td class="sorting_1">Airi Satou</td>
                                                <td class="sorting_1">Airi Satou</td>
                                                <td class="sorting_1">Airi Satou</td>
                                                <td class="sorting_1">Airi Satou</td>
                                                <td>Accountant</td>
                                                <td>Tokyo</td>
                                                <td>33</td>
                                                <td>2008/11/28</td>
                                                <td>$162,700</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="dataTables_length">
                                        <label
                                            >Show
                                            <select aria-controls="dataTable" class="">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                            entries</label
                                        >
                                    </div>
                                    <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                                        <a class="paginate_button previous disabled" aria-controls="dataTable" data-dt-idx="0" tabindex="0" id="dataTable_previous">Previous</a><span><a class="paginate_button current" aria-controls="dataTable" data-dt-idx="1" tabindex="0">1</a><a class="paginate_button" aria-controls="dataTable" data-dt-idx="2" tabindex="0">2</a><a class="paginate_button" aria-controls="dataTable" data-dt-idx="3" tabindex="0">3</a><a class="paginate_button" aria-controls="dataTable" data-dt-idx="4" tabindex="0">4</a><a class="paginate_button" aria-controls="dataTable" data-dt-idx="5" tabindex="0">5</a><a class="paginate_button" aria-controls="dataTable" data-dt-idx="6" tabindex="0">6</a></span
                                        ><a class="paginate_button next" aria-controls="dataTable" data-dt-idx="7" tabindex="0" id="dataTable_next">Next</a>
                                    </div>
                                </div>
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
