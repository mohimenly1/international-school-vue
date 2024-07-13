<template>
    <el-container>
        <el-header>
            <h1>إدارة الرواتب</h1>
        </el-header>
        <el-main>
            <el-form :model="form" label-width="120px">
                <el-form-item label="رقم الموظف">
                    <el-input v-model="form.employeeId" type="number"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="calculateAbsences">حساب الغيابات</el-button>
                </el-form-item>
                <el-form-item label="مبلغ الخصم">
                    <el-input v-model="form.deduction" type="number"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="adjustSalary">ضبط الراتب</el-button>
                </el-form-item>
            </el-form>
        </el-main>
    </el-container>
    <el-dialog v-model="dialogVisible" title="رسالة">
        <span>{{ message }}</span>

    </el-dialog>
</template>

<script>
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
});

export default {
    name: "ManageSalaries",
    data() {
        return {
            form: {
                employeeId: null,
                deduction: 0
            },
            message: '',
            dialogVisible: false
        };
    },
    methods: {
        async calculateAbsences() {
            try {
                const response = await api.post(`/employees/${this.form.employeeId}/calculate-absences`);
                this.message = response.data.message;
                this.dialogVisible = true;
            } catch (error) {
                console.error('Error calculating absences:', error);
                this.message = 'Error calculating absences.';
                this.dialogVisible = true;
            }
        },
        async adjustSalary() {
            try {
                const response = await api.post(`/employees/${this.form.employeeId}/adjust-salary`, {
                    deduction: this.form.deduction
                });
                this.message = response.data.message;
                this.dialogVisible = true;
            } catch (error) {
                console.error('Error adjusting salary:', error);
                this.message = 'Error adjusting salary.';
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
.dialog-footer {
    text-align: right;
}
</style>
