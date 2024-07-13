<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Employee Records</h6>
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
                            <el-table-column property="phone_number" label="Phone Number" width="120" />
                            <el-table-column property="phone_number_two" label="Second Phone Number" width="120" />
                            <el-table-column property="address" label="Address" />
                            <el-table-column label="Actions" width="290">
                                <template #default="scope">
                                    <el-button @click="showEmployee(scope.row)" type="primary" size="mini">View</el-button>
                                    <el-button @click="editEmployee(scope.row)" type="warning" size="mini">Edit</el-button>
                                    <el-button @click="deleteEmployee(scope.row.id)" type="danger" size="mini">Delete</el-button>
                                    <el-button @click="openSalaryDialog(scope.row)" type="success" circle class="mr-5">
                                        <el-icon style="vertical-align: middle">
                                            <Money />
                                        </el-icon>
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog for adding a new employee -->
        <el-dialog v-model="dialogVisible" title="Add Employee" width="800">
            <span>Please fill out the form below</span>

            <el-form :model="form" ref="employeeForm">
                <el-form-item label="Name" :rules="[{ required: true, message: 'Please enter name', trigger: 'blur' }]">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="User ID">
                    <el-input v-model="form.user_id" type="number"></el-input>
                </el-form-item>
                <el-form-item label="Department">
                    <el-select v-model="form.department_id" placeholder="Select department">
                        <el-option
                            v-for="department in departments"
                            :key="department.id"
                            :label="department.name"
                            :value="department.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Teacher Type">
                    <el-select v-model="form.teacher_type_id" placeholder="Select teacher type" clearable>
                        <el-option
                            v-for="teacherType in teacherTypes"
                            :key="teacherType.id"
                            :label="teacherType.type"
                            :value="teacherType.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="National ID" :rules="[{ required: true, message: 'Please enter national ID', trigger: 'blur' }]">
                    <el-input v-model="form.national_number"></el-input>
                </el-form-item>
                <el-form-item label="Phone Number" :rules="[{ required: true, message: 'Please enter phone number', trigger: 'blur' }]">
                    <el-input v-model="form.phone_number"></el-input>
                </el-form-item>
                <el-form-item label="Second Phone Number">
                    <el-input v-model="form.phone_number_two"></el-input>
                </el-form-item>
                <el-form-item label="Address" :rules="[{ required: true, message: 'Please enter address', trigger: 'blur' }]">
                    <el-input v-model="form.address"></el-input>
                </el-form-item>
                <el-form-item label="Password" :rules="[{ required: true, message: 'Please enter password', trigger: 'blur' }]">
                    <el-input v-model="form.pin"></el-input>
                </el-form-item>
                <el-form-item label="Photos">
                    <el-upload
                        action=""
                        list-type="picture-card"
                        :file-list="form.photos_list"
                        :on-preview="handlePreview"
                        :on-remove="handleRemove"
                        :before-upload="beforeUploadPhotos">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="Years of Experience">
                    <el-input v-model="form.years_of_experience" type="number"></el-input>
                </el-form-item>
            </el-form>

            <div class="step-actions">
                <el-button type="primary" @click="submitForm">Submit</el-button>
            </div>
        </el-dialog>

        <!-- Dialog for showing employee details and salary -->
        <el-dialog v-model="salaryDialogVisible" title="Employee Details and Salary Invoice" width="800">
            <div v-if="selectedEmployee && selectedSalary">
                <el-card>
                    <h3>Employee Details</h3>
                    <p><strong>Employee ID:</strong> {{ selectedEmployee.id }}</p>
                    <p><strong>Department:</strong> {{ selectedEmployee.department.name }}</p>
                    <p><strong>Teacher Type:</strong> {{ selectedEmployee.teacher_type.type }}</p>
                </el-card>
                <el-card>
                    <h3>Salary Invoice</h3>
                    <p><strong>Employee ID:</strong> {{ selectedSalary.employee_id }}</p>
                    <p><strong>Base Salary:</strong> {{ selectedSalary.base_salary }}</p>
                    <p><strong>Hourly Rate:</strong> {{ selectedSalary.hourly_rate }}</p>
                    <p><strong>Total Salary:</strong> {{ selectedSalary.total_salary }}</p>
                </el-card>
            </div>
        </el-dialog>


        <el-dialog v-model="showDialogVisible" title="تفاصيل الموظف" width="800">
            <div v-if="selectedEmployee" class="employee-info">
                <div class="employee-details">
                    <div class="detail-item">
                        <strong>الاسم:</strong> {{ selectedEmployee.name }}
                    </div>
                    <div class="detail-item">
                        <strong>رقم المستخدم:</strong> {{ selectedEmployee.user_id }}
                    </div>
                    <div class="detail-item">
                        <strong>رقم القسم:</strong> {{ selectedEmployee.department ? selectedEmployee.department.name : 'N/A' }}
                    </div>
                    <div class="detail-item">
                        <strong>نوع المعلم:</strong> {{ selectedEmployee.teacherType ? selectedEmployee.teacherType.type : 'N/A' }}
                    </div>
                    <div class="detail-item">
                        <strong>الرقم الوطني:</strong> {{ selectedEmployee.national_number }}
                    </div>
                    <div class="detail-item">
                        <strong>رقم الهاتف:</strong> {{ selectedEmployee.phone_number }}
                    </div>
                    <div class="detail-item">
                        <strong>رقم الهاتف الثاني:</strong> {{ selectedEmployee.phone_number_two }}
                    </div>
                    <div class="detail-item">
                        <strong>العنوان:</strong> {{ selectedEmployee.address }}
                    </div>
                    <div class="detail-item">
                        <strong>الرمز السري:</strong> {{ selectedEmployee.pin }}
                    </div>
                </div>
                <div class="employee-images">
                    <div class="image-item" v-for="(photo, index) in JSON.parse(selectedEmployee.photos || '[]')" :key="index">
                        <strong>صورة {{ index + 1 }}:</strong>
                        <img :src="getImageUrl(photo)" alt="Employee Image" class="responsive-image">
                    </div>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script>
import { Document,  Money} from '@element-plus/icons-vue'
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
});

export default {
    name: "Employees",
    components: {
        Document,
        Money
        
    },
    data() {
        return {
            dialogVisible: false,
            showDialogVisible: false,
            salaryDialogVisible: false,
            selectedEmployee: null,
            selectedSalary: null,
            form: {
                name: '',
                user_id: null,
                department_id: null,
                teacher_type_id: '',
                national_number: '',
                phone_number: '',
                phone_number_two: '',
                address: '',
                pin: '',
                photos: [],
                photos_list: [],
                years_of_experience: null,
            },
            tableData: [],
            departments: [],
            teacherTypes: [],
            message: ''
        };
    },
    methods: {
        getImageUrl(imagePath) {
            return `http://localhost:8000/storage/${imagePath}`;
        },
        async submitForm() {
            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('user_id', this.form.user_id);
            formData.append('department_id', this.form.department_id);
            formData.append('teacher_type_id', this.form.teacher_type_id);
            formData.append('national_number', this.form.national_number);
            formData.append('phone_number', this.form.phone_number);
            formData.append('phone_number_two', this.form.phone_number_two);
            formData.append('address', this.form.address);
            formData.append('years_of_experience', this.form.years_of_experience);
            formData.append('pin', this.form.pin);
            this.form.photos_list.forEach((photo,) => {
                formData.append('photos[]', photo.raw);
            });

            try {
                await api.post('/employees', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.$message.success('تمت إضافة الموظف بنجاح');
                this.resetForm();
                this.fetchEmployees();
            } catch (error) {
                this.$message.error('فشل في إضافة الموظف');
            }
        },
        async fetchEmployees() {
            try {
                const response = await api.get('/employees');
                this.tableData = response.data;
            } catch (error) {
                this.$message.error('فشل في جلب سجلات الموظفين');
            }
        },
        async fetchDepartmentsAndTeacherTypes() {
            try {
                const response = await api.get('/departments-teacher-types');
                this.departments = response.data.departments;
                this.teacherTypes = response.data.teacherTypes;
            } catch (error) {
                this.$message.error('فشل في جلب الأقسام وأنواع المعلمين');
            }
        },
        async showEmployee(employee) {
            try {
                const response = await api.get(`/employees/${employee.id}`);
                this.selectedEmployee = response.data;
                this.showDialogVisible = true;
            } catch (error) {
                this.$message.error('فشل في جلب تفاصيل الموظف');
            }
        },
        async openSalaryDialog(employee) {
    try {
        const response = await api.get(`/employees/salary/${employee.id}`);
        this.selectedEmployee = response.data.employee;
        this.selectedSalary = response.data.salary;
        this.salaryDialogVisible = true;
    } catch (error) {
        console.error('Error fetching employee details:', error);
        this.message = 'Error fetching employee details.';
        this.dialogVisible = true;
    }
},
        async editEmployee(employee) {
            this.dialogVisible = true;
            this.form = { ...employee };
            // Handle file lists for images
            this.form.photos_list = JSON.parse(employee.photos || '[]').map(photo => ({
                name: photo,
                url: `http://localhost:8000/storage/${photo}`
            }));
        },
        async deleteEmployee(id) {
            try {
                await api.delete(`/employees/${id}`);
                this.$message.success('تم حذف الموظف بنجاح');
                this.fetchEmployees();
            } catch (error) {
                this.$message.error('فشل في حذف الموظف');
            }
        },
        resetForm() {
            this.form = {
                name: '',
                user_id: null,
                department_id: null,
                teacher_type_id: null,
                national_number: '',
                phone_number: '',
                phone_number_two: '',
                address: '',
                pin: '',
                photos: [],
                photos_list: [],
                years_of_experience: null,
            };
            this.dialogVisible = false;
        },
        handlePreview(file) {
            window.open(file.url);
        },
        handleRemove(file, fileList) {
            this.form.photos_list = fileList;
        },
        beforeUploadPhotos(file) {
            this.form.photos_list.push(file);
            return false; // Prevent auto upload
        }
    },
    mounted() {
        this.fetchEmployees();
        this.fetchDepartmentsAndTeacherTypes();
    }
};
</script>

<style scoped>
.bg-header-table {
    background-image: linear-gradient(to top, #5f72bd 0%, #9b23ea 100%);
}

.employee-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.employee-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.detail-item {
    padding: 10px;
    background-color: #f5f5f5;
    border-radius: 5px;
}

.employee-images {
    display: flex;
    gap: 20px;
    justify-content: space-between;
}

.image-item {
    flex: 1;
    text-align: center;
}

.responsive-image {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
}
</style>
