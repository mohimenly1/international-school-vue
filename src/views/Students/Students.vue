<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Student Records</h6>
                                <el-button @click="dialogVisible = true" type="success" circle class="mr-5">
                                    <el-icon style="vertical-align: middle">
                                        <Document />
                                    </el-icon>
                                </el-button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <el-table :data="tableData" style="width: 100%">
                            <el-table-column type="selection" width="55" />
                            <el-table-column property="name" label="Name" width="120" />
                            <el-table-column property="date_of_birth" label="Date of Birth" width="120" />
                            <el-table-column property="address" label="Address" width="240" />
                            <el-table-column property="parent.first_name" label="Parent First Name" width="120" />
                            <el-table-column property="parent.last_name" label="Parent Last Name" width="120" />
                            <el-table-column property="class.name" label="Class" width="120" />
                            <el-table-column property="bus.number" label="Bus" width="120" />
                            <el-table-column label="Operations" width="280">
                                <template #default="scope">
                                    
                                    <el-button @click="showStudent(scope.row)" type="primary" size="mini">Show</el-button>
                                    <el-button @click="editStudent(scope.row)" type="warning" size="mini">Edit</el-button>
                                    <el-button @click="deleteStudent(scope.row.id)" type="danger" size="mini">Delete</el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </div>
        </div>

        <!-- dialog add a student -->
        <el-dialog v-model="dialogVisible" title="Add Student" width="800">
            <span>Please fill out the form below</span>

            <el-steps :active="activeStep" finish-status="success">
                <el-step title="Personal Details"></el-step>
                <el-step title="Parent Information"></el-step>
                <el-step title="Class Assignment"></el-step>
                <el-step title="Bus Assignment"></el-step>
            </el-steps>

            <div v-if="activeStep === 0">
                <el-form :model="form">
                    <el-form-item label="Name" :rules="[{ required: true, message: 'Please enter name', trigger: 'blur' }]">
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                    <el-form-item label="Date of Birth" :rules="[{ required: true, message: 'Please select date of birth', trigger: 'blur' }]">
                        <el-date-picker v-model="form.date_of_birth" type="date" placeholder="Pick a date"></el-date-picker>
                    </el-form-item>
                    <el-form-item label="Address" :rules="[{ required: true, message: 'Please enter address', trigger: 'blur' }]">
                        <el-input v-model="form.address"></el-input>
                    </el-form-item>
                </el-form>
            </div>

            <div v-if="activeStep === 1">
                <el-form :model="form">
                    <el-form-item label="Parent" :rules="[{ required: true, message: 'Please select parent', trigger: 'change' }]">
                        <el-select v-model="form.parent_id" placeholder="Select Parent">
                            <el-option v-for="parent in parents" :key="parent.id" :label="`${parent.first_name} ${parent.last_name}`" :value="parent.id"></el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </div>

            <div v-if="activeStep === 2">
                <el-form :model="form">
                    <el-form-item label="Class" :rules="[{ required: true, message: 'Please select class', trigger: 'change' }]">
                        <el-select v-model="form.class_id" placeholder="Select Class">
                            <el-option v-for="schoolClass in classes" :key="schoolClass.id" :label="schoolClass.name" :value="schoolClass.id"></el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </div>

            <div v-if="activeStep === 3">
                <el-form :model="form">
                    <el-form-item label="Bus" :rules="[{ required: true, message: 'Please select bus', trigger: 'change' }]">
                        <el-select v-model="form.bus_id" placeholder="Select Bus">
                            <el-option v-for="bus in buses" :key="bus.id" :value="bus.id" :label="bus.number"></el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </div>

            <div class="step-actions">
                <el-button v-if="activeStep > 0" @click="prevStep">Previous</el-button>
                <el-button v-if="activeStep < 3" type="primary" @click="nextStep">Next</el-button>
                <el-button v-if="activeStep === 3" type="success" @click="submitForm">Submit</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
import { Document } from '@element-plus/icons-vue'
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
});

export default {
    name: "Students",
    components: {
        Document
    },
    data() {
        return {
            activeStep: 0,
            dialogVisible: false,
            form: {
                name: '',
                date_of_birth: '',
                address: '',
                parent_id: '',
                class_id: '',
                bus_id: ''
            },
            tableData: [],
            parents: [],
            classes: [],
            buses: []
        };
    },
    methods: {
        nextStep() {
            this.activeStep++;
        },
        prevStep() {
            this.activeStep--;
        },
        async submitForm() {
            // Format the date_of_birth before submitting
            this.form.date_of_birth = this.form.date_of_birth.toISOString().split('T')[0];
            try {
                await api.post('/students', this.form);
                this.$message.success('Student added successfully');
                this.resetForm();
                this.fetchStudents();
            } catch (error) {
                this.$message.error('Failed to add student');
            }
        },
        async fetchStudents() {
            try {
                const response = await api.get('/students');
                this.tableData = response.data;
            } catch (error) {
                this.$message.error('Failed to fetch students');
            }
        },
        async fetchParents() {
            try {
                const response = await api.get('/parents');
                this.parents = response.data;
            } catch (error) {
                this.$message.error('Failed to fetch parents');
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
        async fetchBuses() {
            try {
                const response = await api.get('/buses');
                this.buses = response.data;
            } catch (error) {
                this.$message.error('Failed to fetch buses');
            }
        },
        resetForm() {
            this.form = {
                name: '',
                date_of_birth: '',
                address: '',
                parent_id: '',
                class_id: '',
                bus_id: ''
            };
            this.activeStep = 0;
            this.dialogVisible = false;
        }
    },
    mounted() {
        this.fetchStudents();
        this.fetchParents();
        this.fetchClasses();
        this.fetchBuses();
    }
};
</script>




  <style scoped>
.bg-header-table{
    background-image: linear-gradient(to top, #5f72bd 0%, #9b23ea 100%);
}

.step-actions {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
  }
</style>