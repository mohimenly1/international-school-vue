<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Employee Records</h6>
                                <el-input
                                style="width: 400px;"
                                    v-model="searchQuery"
                                    placeholder="Search by Name, Phone, or National ID"
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
                        <el-table :data="pagedEmployees" style="width: 100%">
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

                        <div class="d-flex justify-content-center mt-4">
                            <el-pagination
                                @current-change="handleCurrentChange"
                                :current-page="currentPage"
                                :page-size="pageSize"
                                :total="filteredEmployees.length"
                                layout="prev, pager, next"
                                background
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog for adding a new employee -->
        <el-dialog v-model="dialogVisible" title="Add Employee" width="800">
            <span>Please fill out the form below</span>

            <div class="employee-form">
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" v-model="form.name" required>
                    </div>

                    <div class="form-group">
                        <label for="department">Department:</label>
                        <select id="department" v-model="form.department_id" required>
                            <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="teacher-type">Teacher Type:</label>
                        <select id="teacher-type" v-model="form.teacher_type_id">
                            <option v-for="teacherType in teacherTypes" :key="teacherType.id" :value="teacherType.id">{{ teacherType.type }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="national-id">National ID:</label>
                        <input type="text" id="national-id" v-model="form.national_number" required>
                    </div>

                    <div class="form-group">
                        <label for="phone-number">Phone Number:</label>
                        <input type="text" id="phone-number" v-model="form.phone_number" required>
                    </div>

                    <div class="form-group">
                        <label for="second-phone-number">Second Phone Number:</label>
                        <input type="text" id="second-phone-number" v-model="form.phone_number_two">
                    </div>

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" v-model="form.address" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" v-model="form.pin" required>
                    </div>

                    <div class="form-group">
                        <label for="photos">Photos:</label>
                        <input type="file" id="photos" @change="handleFileUpload" multiple>
                    </div>

                    <div class="form-group">
                        <label for="years-of-experience">Years of Experience:</label>
                        <input type="number" id="years-of-experience" v-model="form.years_of_experience">
                    </div>

                    <div class="form-group">
                        <button type="submit">Submit</button>
                    </div>
                </form>
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
                    <div class="image-item" v-for="(photo, index) in parsedPhotos" :key="index">
                        <strong>Image {{ index + 1 }}:</strong>
                        <img :src="getImageUrl(photo)" alt="Employee Image" class="responsive-image">
                    </div>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script>
import { Document, Money } from '@element-plus/icons-vue';
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
                years_of_experience: null,
            },
            searchQuery: '', // Search input model
            allEmployees: [],
            tableData: [],
            currentPage: 1,
            pageSize: 10, // Number of records per page
            totalEmployees: 0,
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

            // Debug: Check photos_list before appending
            console.log('Photos List before appending:', this.form.photos_list);

            // Append each photo file to formData
            this.form.photos.forEach(photo => {
                formData.append('photos[]', photo);
            });

            // Log the formData content
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            this.$forceUpdate();

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
                this.allEmployees = response.data; // Assuming your API response has all employees
                this.totalEmployees = this.allEmployees.length; // Total count of employees
            } catch (error) {
                this.$message.error('Failed to fetch employees');
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
                this.fetchEmployees(); // Refresh employee list after deletion
            } catch (error) {
                this.$message.error('فشل في حذف الموظف');
            }
        },
        resetForm() {
            this.form = {
                name: '',
                user_id: '',
                department_id: '',
                teacher_type_id: '',
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
            window.open(file.url || URL.createObjectURL(file.raw));
        },
        handleRemove(file, fileList) {
            this.form.photos_list = fileList;
        },
        handleFileUpload(event) {
            const files = event.target.files;
            if (files) {
                this.form.photos = Array.from(files);
            }
        },
        beforeUploadPhotos(file) {
            this.form.photos_list.push(file);
            console.log('File added to photos_list:', file);
            return false; // Prevent auto upload
        },
        handleCurrentChange(val) {
            this.currentPage = val;
        }
    },
    computed: {
        pagedEmployees() {
            // Calculate the start and end index for pagination
            const startIndex = (this.currentPage - 1) * this.pageSize;
            const endIndex = startIndex + this.pageSize;
            // Return a slice of employees based on currentPage and pageSize
            return this.filteredEmployees.slice(startIndex, endIndex);
        },
        filteredEmployees() {
            const query = this.searchQuery.toLowerCase();
            return this.allEmployees.filter(employee => {
                return (
                    employee.name.toLowerCase().includes(query) ||
                    employee.phone_number.toLowerCase().includes(query) ||
                    employee.national_number.toLowerCase().includes(query)
                );
            });
        },
        parsedPhotos() {
            // Parse selectedEmployee.photos if it's a string
            if (this.selectedEmployee && typeof this.selectedEmployee.photos === 'string') {
                return JSON.parse(this.selectedEmployee.photos);
            }
            // If already an array, return as-is
            return this.selectedEmployee ? this.selectedEmployee.photos : [];
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


.employee-form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f0f0f0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
  }
  
  input[type="text"],
  input[type="password"],
  input[type="number"],
  select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    outline: none;
  }
  
  select {
    appearance: none;
  }
  

  
  input[type="file"] {
    margin-top: 5px;
    font-size: 14px;
  }
  
  .form-group:last-child {
    text-align: right; /* Adjust alignment for the submit button */
  }
  
</style>
