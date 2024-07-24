<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Department Records</h6>
                                <el-input
                                    v-model="searchQuery"
                                    placeholder="Search by Name"
                                    class="search-input"
                                ></el-input>
                                <el-button @click="dialogVisible = true" type="success" circle class="mr-5">
                                    <el-icon style="vertical-align: middle">
                                        <Document />
                                    </el-icon>
                                </el-button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <el-table :data="pagedDepartments" style="width: 100%">
                            <el-table-column property="name" label="Name" width="710" />
                            <el-table-column label="Actions" width="320">
                                <template #default="scope">
                                    <el-button @click="deleteDepartment(scope.row.id)" type="danger" size="mini">Delete</el-button>
                                    <el-button @click="editDepartment(scope.row)" type="warning" size="mini">Edit</el-button>
                                </template>
                            </el-table-column>
                        </el-table>

                        <div class="d-flex justify-content-center mt-4">
                            <el-pagination
                                @current-change="handleCurrentChange"
                                :current-page="currentPage"
                                :page-size="pageSize"
                                :total="filteredDepartments.length"
                                layout="prev, pager, next"
                                background
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog for adding a new department -->
        <el-dialog v-model="dialogVisible" title="Add Department" width="800">
            <span>Please fill out the form below</span>

            <div class="department-form">
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" v-model="form.name" required>
                    </div>

                    <div class="form-group">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </el-dialog>
    </div>
</template>

<script>
import { Document } from '@element-plus/icons-vue';
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
});

export default {
    name: "Departments",
    components: {
        Document
    },
    data() {
        return {
            dialogVisible: false,
            searchQuery: '', // Search input model
            form: {
                name: '',
            },
            allDepartments: [],
            currentPage: 1,
            pageSize: 10, // Number of records per page
            totalDepartments: 0,
            isEditing: false,
            editingId: null,
        };
    },
    methods: {
        async submitForm() {
            if (this.isEditing) {
                try {
                    const response = await api.put(`/departments/${this.editingId}`, this.form);
                    this.$message.success('Department updated successfully');
                } catch (error) {
                    this.$message.error('Failed to update department');
                }
                this.isEditing = false;
                this.editingId = null;
            } else {
                try {
                    const response = await api.post('/departments', this.form);
                    this.$message.success('Department added successfully');
                } catch (error) {
                    this.$message.error('Failed to add department');
                }
            }
            this.resetForm();
            this.fetchDepartments();
        },
        async fetchDepartments() {
            try {
                const response = await api.get('/departments');
                this.allDepartments = response.data;
                this.totalDepartments = this.allDepartments.length;
            } catch (error) {
                this.$message.error('Failed to fetch departments');
            }
        },
        async deleteDepartment(id) {
            try {
                await api.delete(`/departments/${id}`);
                this.$message.success('Department deleted successfully');
                this.fetchDepartments();
            } catch (error) {
                this.$message.error('Failed to delete department');
            }
        },
        editDepartment(department) {
            this.dialogVisible = true;
            this.isEditing = true;
            this.editingId = department.id;
            this.form.name = department.name;
        },
        resetForm() {
            this.form = {
                name: ''
            };
            this.dialogVisible = false;
        },
        handleCurrentChange(val) {
            this.currentPage = val;
        }
    },
    computed: {
        pagedDepartments() {
            const startIndex = (this.currentPage - 1) * this.pageSize;
            const endIndex = startIndex + this.pageSize;
            return this.filteredDepartments.slice(startIndex, endIndex);
        },
        filteredDepartments() {
            const query = this.searchQuery.toLowerCase();
            return this.allDepartments.filter(department => {
                return (
                    department.name.toLowerCase().includes(query)
                );
            });
        }
    },
    mounted() {
        this.fetchDepartments();
    }
};
</script>

<style scoped>
.search-input {
    width: 300px;
    margin-right: 10px;
}
</style>
