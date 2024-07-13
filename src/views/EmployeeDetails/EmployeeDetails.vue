<template>
    <el-container>
        <el-header>
            <h1>تفاصيل الموظف وفاتورة الراتب</h1>
        </el-header>
        <el-main>
            <el-form :model="form" label-width="120px">
                <el-form-item label="رقم الموظف">
                    <el-input v-model="form.employeeId" type="number"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="fetchEmployeeDetails">عرض التفاصيل</el-button>
                </el-form-item>
            </el-form>
            <div v-if="employee">
                <el-card>
                    <h3>تفاصيل الموظف</h3>
                    <p><strong>رقم الموظف:</strong> {{ employee.id }}</p>
                    <p><strong>رقم القسم:</strong> {{ employee.department_id }}</p>
                    <p><strong>نوع المعلم:</strong> {{ employee.teacher_type_id }}</p>
                </el-card>
                <el-card>
                    <h3>فاتورة الراتب</h3>
                    <p><strong>رقم الموظف:</strong> {{ salary.employee_id }}</p>
                    <p><strong>الراتب الأساسي:</strong> {{ salary.base_salary }}</p>
                    <p><strong>سعر الساعة:</strong> {{ salary.hourly_rate }}</p>
                    <p><strong>الراتب الإجمالي:</strong> {{ salary.total_salary }}</p>
                </el-card>
            </div>
        </el-main>
    </el-container>
</template>

<script>
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
});

export default {
    name: "EmployeeDetails",
    data() {
        return {
            form: {
                employeeId: null
            },
            employee: null,
            salary: null,
            message: '',
            dialogVisible: false
        };
    },
    methods: {
        async fetchEmployeeDetails() {
            try {
                const employeeResponse = await api.get(`/employees/${this.form.employeeId}`);
                const salaryResponse = await api.get(`/salaries/${this.form.employeeId}`);
                this.employee = employeeResponse.data;
                this.salary = salaryResponse.data;
            } catch (error) {
                console.error('Error fetching employee details:', error);
                this.message = 'Error fetching employee details.';
                this.dialogVisible = true;
            }
        }
    }
};
</script>

<style scoped>
h1 {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
}
.el-form {
    max-width: 600px;
    margin: 0 auto;
}
.el-card {
    margin-top: 20px;
}
.dialog-footer {
    text-align: right;
}
</style>
