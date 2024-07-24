<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Teacher Types Records</h6>
                                <el-input
                                    v-model="searchQuery"
                                    placeholder="Search by Type"
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
                        <el-table :data="pagedTeacherTypes" style="width: 100%">
                            <el-table-column property="type" label="Type" width="800" />
                            <el-table-column label="Actions" width="320">
                                <template #default="scope">
                                    <el-button @click="deleteTeacherType(scope.row.id)" type="danger" size="mini">Delete</el-button>
                                    <el-button @click="editTeacherType(scope.row)" type="warning" size="mini">Edit</el-button>
                                </template>
                            </el-table-column>
                        </el-table>

                        <div class="d-flex justify-content-center mt-4">
                            <el-pagination
                                @current-change="handleCurrentChange"
                                :current-page="currentPage"
                                :page-size="pageSize"
                                :total="filteredTeacherTypes.length"
                                layout="prev, pager, next"
                                background
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog for adding a new teacher type -->
        <el-dialog v-model="dialogVisible" title="Add Teacher Type" width="800">
            <span>Please fill out the form below</span>

            <div class="teacher-type-form">
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label for="type">Type:</label>
                        <input type="text" id="type" v-model="form.type" required>
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
    name: "Teacher Type",
    components: {
        Document
    },
    data() {
        return {
            dialogVisible: false,
            searchQuery: '', // Search input model
            form: {
                type: '',
            },
            allTeacherTypes: [],
            currentPage: 1,
            pageSize: 10, // Number of records per page
            totalTeacherTypes: 0,
            isEditing: false,
            editingId: null,
        };
    },
    methods: {
        async submitForm() {
            if (this.isEditing) {
                try {
                    const response = await api.put(`/teacher-types/${this.editingId}`, this.form);
                    this.$message.success('Teacher type updated successfully');
                } catch (error) {
                    this.$message.error('Failed to update teacher type');
                }
                this.isEditing = false;
                this.editingId = null;
            } else {
                try {
                    const response = await api.post('/teacher-types', this.form);
                    this.$message.success('Teacher type added successfully');
                } catch (error) {
                    this.$message.error('Failed to add teacher type');
                }
            }
            this.resetForm();
            this.fetchTeacherTypes();
        },
        async fetchTeacherTypes() {
            try {
                const response = await api.get('/teacher-types');
                this.allTeacherTypes = response.data;
                this.totalTeacherTypes = this.allTeacherTypes.length;
            } catch (error) {
                this.$message.error('Failed to fetch teacher types');
            }
        },
        async deleteTeacherType(id) {
            try {
                await api.delete(`/teacher-types/${id}`);
                this.$message.success('Teacher type deleted successfully');
                this.fetchTeacherTypes();
            } catch (error) {
                this.$message.error('Failed to delete teacher type');
            }
        },
        editTeacherType(teacherType) {
            this.dialogVisible = true;
            this.isEditing = true;
            this.editingId = teacherType.id;
            this.form.type = teacherType.type;
        },
        resetForm() {
            this.form = {
                type: ''
            };
            this.dialogVisible = false;
        },
        handleCurrentChange(val) {
            this.currentPage = val;
        }
    },
    computed: {
        pagedTeacherTypes() {
            const startIndex = (this.currentPage - 1) * this.pageSize;
            const endIndex = startIndex + this.pageSize;
            return this.filteredTeacherTypes.slice(startIndex, endIndex);
        },
        filteredTeacherTypes() {
            const query = this.searchQuery.toLowerCase();
            return this.allTeacherTypes.filter(teacherType => {
                return (
                    teacherType.type.toLowerCase().includes(query)
                );
            });
        }
    },
    mounted() {
        this.fetchTeacherTypes();
    }
};
</script>

<style scoped>
.search-input {
    width: 300px;
    margin-right: 10px;
}
</style>
