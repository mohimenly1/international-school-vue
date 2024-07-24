<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Classes Records</h6>
                                <el-input
                                    v-model="searchQuery"
                                    placeholder="Search by Name or Description"
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
                        <el-table :data="pagedClasses" style="width: 100%">
                            <el-table-column property="name" label="Name" width="180" />
                            <el-table-column property="description" label="Description" />
                            <el-table-column label="Actions" width="120">
                                <template #default="scope">
                                    <el-button @click="deleteClass(scope.row.id)" type="danger" size="mini">Delete</el-button>
                                </template>
                            </el-table-column>
                        </el-table>

                        <div class="d-flex justify-content-center mt-4">
                            <el-pagination
                                @current-change="handleCurrentChange"
                                :current-page="currentPage"
                                :page-size="pageSize"
                                :total="filteredClasses.length"
                                layout="prev, pager, next"
                                background
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog for adding a new class -->
        <el-dialog v-model="dialogVisible" title="Add Class" width="800">
            <span>Please fill out the form below</span>

            <div class="class-form">
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" v-model="form.name" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <input type="text" id="description" v-model="form.description" required>
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
    name: "Classes",
    components: {
        Document
    },
    data() {
        return {
            dialogVisible: false,
            searchQuery: '', // Search input model
            form: {
                name: '',
                description: ''
            },
            allClasses: [],
            currentPage: 1,
            pageSize: 10, // Number of records per page
            totalClasses: 0
        };
    },
    methods: {
        async submitForm() {
            try {
                const response = await api.post('/classes', this.form);
                this.$message.success('Class added successfully');
                this.resetForm();
                this.fetchClasses();
            } catch (error) {
                this.$message.error('Failed to add class');
            }
        },
        async fetchClasses() {
            try {
                const response = await api.get('/classes');
                this.allClasses = response.data;
                this.totalClasses = this.allClasses.length;
            } catch (error) {
                this.$message.error('Failed to fetch classes');
            }
        },
        async deleteClass(id) {
            try {
                await api.delete(`/classes/${id}`);
                this.$message.success('Class deleted successfully');
                this.fetchClasses();
            } catch (error) {
                this.$message.error('Failed to delete class');
            }
        },
        resetForm() {
            this.form = {
                name: '',
                description: ''
            };
            this.dialogVisible = false;
        },
        handleCurrentChange(val) {
            this.currentPage = val;
        }
    },
    computed: {
        pagedClasses() {
            const startIndex = (this.currentPage - 1) * this.pageSize;
            const endIndex = startIndex + this.pageSize;
            return this.filteredClasses.slice(startIndex, endIndex);
        },
        filteredClasses() {
            const query = this.searchQuery.toLowerCase();
            return this.allClasses.filter(classItem => {
                return (
                    classItem.name.toLowerCase().includes(query) ||
                    classItem.description.toLowerCase().includes(query)
                );
            });
        }
    },
    mounted() {
        this.fetchClasses();
    }
};
</script>

<style scoped>
.search-input {
    width: 300px;
    margin-right: 10px;
}
</style>
