<template>
    <div>
        <h1>Manage Salaries</h1>
        <div>
            <label for="employee-id">Employee ID:</label>
            <input v-model="employeeId" type="number" id="employee-id">
        </div>
        <div>
            <label for="base-salary">Base Salary:</label>
            <input v-model="baseSalary" type="number" id="base-salary">
        </div>
        <div>
            <label for="hourly-rate">Hourly Rate:</label>
            <input v-model="hourlyRate" type="number" id="hourly-rate">
        </div>
        <button @click="setSalary">Set Salary</button>
        <div v-if="message">{{ message }}</div>
    </div>
</template>

<script>
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
});

export default {
    name: 'ManageSalaries',
    data() {
        return {
            employeeId: null,
            baseSalary: 0,
            hourlyRate: 0,
            message: ''
        };
    },
    methods: {
        async setSalary() {
            try {
                const response = await api.post(`/employees/${this.employeeId}/set-salary`, {
                    base_salary: this.baseSalary,
                    hourly_rate: this.hourlyRate
                });
                this.message = response.data.message;
            } catch (error) {
                console.error('Error setting salary:', error);
                this.message = 'Error setting salary.';
            }
        }
    }
};
</script>

<style scoped>
h1 {
    font-size: 24px;
    margin-bottom: 20px;
}
div {
    margin-bottom: 10px;
}
label {
    margin-right: 10px;
}
input {
    margin-right: 10px;
}
button {
    margin-right: 10px;
}
</style>
