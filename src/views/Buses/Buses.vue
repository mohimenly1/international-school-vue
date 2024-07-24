<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Bus Records</h6>
                                <el-input
                                    v-model="searchQuery"
                                    placeholder="Search by Number or Model"
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
                        <el-table :data="pagedBuses" style="width: 100%">
                            <el-table-column property="number" label="Number" width="180" />
                            <el-table-column property="model" label="Model" />
                            <el-table-column property="capacity" label="Capacity" width="420" />
                            <el-table-column label="Actions" width="320">
                                <template #default="scope">
                                    <el-button @click="editBus(scope.row)" type="warning" size="mini">Edit</el-button>
                                    <el-button @click="deleteBus(scope.row.id)" type="danger" size="mini">Delete</el-button>
                                </template>
                            </el-table-column>
                        </el-table>

                        <div class="d-flex justify-content-center mt-4">
                            <el-pagination
                                @current-change="handleCurrentChange"
                                :current-page="currentPage"
                                :page-size="pageSize"
                                :total="filteredBuses.length"
                                layout="prev, pager, next"
                                background
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog for adding a new bus -->
        <el-dialog v-model="dialogVisible" title="Add Bus" width="800">
            <span>Please fill out the form below</span>

            <div class="bus-form">
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label for="number">Number:</label>
                        <input type="text" id="number" v-model="form.number" required>
                    </div>

                    <div class="form-group">
                        <label for="model">Model:</label>
                        <input type="text" id="model" v-model="form.model" required>
                    </div>

                    <div class="form-group">
                        <label for="capacity">Capacity:</label>
                        <input type="number" id="capacity" v-model="form.capacity" required>
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
    name: "Buses",
    components: {
        Document
    },
    data() {
        return {
            dialogVisible: false,
            searchQuery: '', // Search input model
            form: {
                number: '',
                model: '',
                capacity: null,
            },
            allBuses: [],
            currentPage: 1,
            pageSize: 10, // Number of records per page
            totalBuses: 0,
            isEditing: false,
            editingId: null,
        };
    },
    methods: {
        async submitForm() {
            if (this.isEditing) {
                try {
                    const response = await api.put(`/buses/${this.editingId}`, this.form);
                    this.$message.success('Bus updated successfully');
                } catch (error) {
                    this.$message.error('Failed to update bus');
                }
                this.isEditing = false;
                this.editingId = null;
            } else {
                try {
                    const response = await api.post('/buses', this.form);
                    this.$message.success('Bus added successfully');
                } catch (error) {
                    this.$message.error('Failed to add bus');
                }
            }
            this.resetForm();
            this.fetchBuses();
        },
        async fetchBuses() {
            try {
                const response = await api.get('/buses');
                this.allBuses = response.data;
                this.totalBuses = this.allBuses.length;
            } catch (error) {
                this.$message.error('Failed to fetch buses');
            }
        },
        async deleteBus(id) {
            try {
                await api.delete(`/buses/${id}`);
                this.$message.success('Bus deleted successfully');
                this.fetchBuses();
            } catch (error) {
                this.$message.error('Failed to delete bus');
            }
        },
        editBus(bus) {
            this.dialogVisible = true;
            this.isEditing = true;
            this.editingId = bus.id;
            this.form.number = bus.number;
            this.form.model = bus.model;
            this.form.capacity = bus.capacity;
        },
        resetForm() {
            this.form = {
                number: '',
                model: '',
                capacity: null
            };
            this.dialogVisible = false;
        },
        handleCurrentChange(val) {
            this.currentPage = val;
        }
    },
    computed: {
        pagedBuses() {
            const startIndex = (this.currentPage - 1) * this.pageSize;
            const endIndex = startIndex + this.pageSize;
            return this.filteredBuses.slice(startIndex, endIndex);
        },
        filteredBuses() {
            const query = this.searchQuery.toLowerCase();
            return this.allBuses.filter(bus => {
                return (
                    bus.number.toLowerCase().includes(query) ||
                    bus.model.toLowerCase().includes(query)
                );
            });
        }
    },
    mounted() {
        this.fetchBuses();
    }
};
</script>

<style scoped>
.search-input {
    width: 300px;
    margin-right: 10px;
}
</style>
