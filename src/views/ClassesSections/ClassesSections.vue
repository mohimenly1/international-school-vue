<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Class Sections</h6>
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
                        <el-table :data="pagedSections" style="width: 100%">
                            <el-table-column property="name" label="Section Name" width="180" />
                            <el-table-column property="class.name" label="Class" />
                            <el-table-column property="subjects" label="Subjects">
                                <template #default="scope">
                                    <ul>
                                        <li v-for="subject in scope.row.subjects" :key="subject.id">{{ subject.type }}</li>
                                    </ul>
                                </template>
                            </el-table-column>
                            <el-table-column label="Actions" width="320">
                                <template #default="scope">
                                    <el-button @click="editSection(scope.row)" type="warning" size="mini">Edit</el-button>
                                    <el-button @click="deleteSection(scope.row.id)" type="danger" size="mini">Delete</el-button>
                                </template>
                            </el-table-column>
                        </el-table>

                        <div class="d-flex justify-content-center mt-4">
                            <el-pagination
                                @current-change="handleCurrentChange"
                                :current-page="currentPage"
                                :page-size="pageSize"
                                :total="filteredSections.length"
                                layout="prev, pager, next"
                                background
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog for adding/editing a section -->
        <el-dialog v-model="dialogVisible" title="Add/Edit Section" width="800">
            <div class="section-form">
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label for="name">Section Name:</label>
                        <input type="text" id="name" v-model="form.name" required>
                    </div>

                    <div class="form-group">
                        <label for="class">Class:</label>
                        <el-select v-model="form.class_id" placeholder="Select Class" required>
                            <el-option v-for="classItem in classes" :key="classItem.id" :label="classItem.name" :value="classItem.id"></el-option>
                        </el-select>
                    </div>

                    <div class="form-group">
                        <label for="subjects">Subjects:</label>
                        <el-select v-model="form.subject_ids" multiple placeholder="Select Subjects">
                            <el-option v-for="subject in subjects" :key="subject.id" :label="subject.type" :value="subject.id"></el-option>
                        </el-select>
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
    name: "Sections",
    components: {
        Document
    },
    data() {
        return {
            dialogVisible: false,
            searchQuery: '', // Search input model
            form: {
                name: '',
                class_id: '',
                subject_ids: []
            },
            allSections: [],
            classes: [],
            subjects: [],
            currentPage: 1,
            pageSize: 10, // Number of records per page
            totalSections: 0,
            isEditing: false,
            editingId: null,
        };
    },
    methods: {
        async submitForm() {
            if (this.isEditing) {
                try {
                    await api.put(`/sections/${this.editingId}`, this.form);
                    this.$message.success('Section updated successfully');
                } catch (error) {
                    this.$message.error('Failed to update section');
                }
                this.isEditing = false;
                this.editingId = null;
            } else {
                try {
                    await api.post('/sections', this.form);
                    console.log(JSON.stringify(this.form))
                    this.$message.success('Section added successfully');
                } catch (error) {
                    this.$message.error('Failed to add section');
                }
            }
            this.resetForm();
            this.fetchSections();
        },
        async fetchSections() {
            try {
                const response = await api.get('/sections');
                this.allSections = response.data;
                this.totalSections = this.allSections.length;
            } catch (error) {
                this.$message.error('Failed to fetch sections');
            }
        },
        async fetchClasses() {
            try {
                const response = await api.get('/classes');
                this.classes = response.data;
            } catch (error) {
                this.$message.error('Failed to fetch classes');
            }
        },
        async fetchSubjects() {
            try {
                const response = await api.get('/subjects');
                this.subjects = response.data;
            } catch (error) {
                this.$message.error('Failed to fetch subjects');
            }
        },
        async deleteSection(id) {
            try {
                await api.delete(`/sections/${id}`);
                this.$message.success('Section deleted successfully');
                this.fetchSections();
            } catch (error) {
                this.$message.error('Failed to delete section');
            }
        },
        editSection(section) {
            this.form = {
                name: section.name,
                class_id: section.class.id,
                subject_ids: section.subjects.map(subject => subject.id)
            };
            this.dialogVisible = true;
            this.isEditing = true;
            this.editingId = section.id;
        },
        resetForm() {
            this.form = {
                name: '',
                class_id: '',
                subject_ids: []
            };
            this.dialogVisible = false;
        },
        handleCurrentChange(val) {
            this.currentPage = val;
        }
    },
    computed: {
        pagedSections() {
            const startIndex = (this.currentPage - 1) * this.pageSize;
            const endIndex = startIndex + this.pageSize;
            return this.filteredSections.slice(startIndex, endIndex);
        },
        filteredSections() {
            const query = this.searchQuery.toLowerCase();
            return this.allSections.filter(section => {
                return (
                    section.name.toLowerCase().includes(query)
                );
            });
        }
    },
    mounted() {
        this.fetchSections();
        this.fetchClasses();
        this.fetchSubjects();
    }
};
</script>

<style scoped>
.search-input {
    width: 300px;
    margin-right: 10px;
}
</style>
