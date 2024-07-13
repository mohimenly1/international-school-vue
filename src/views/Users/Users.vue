<template>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-around">
                  <h6 class="text-white text-capitalize ps-3">Users Records</h6>
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
                <el-table-column property="phone" label="Phone" width="120" />
                <el-table-column property="username" label="Username" width="120" />
                <el-table-column property="email" label="Email" width="180" />
                <el-table-column property="user_type" label="User Type" width="120" />
                <el-table-column property="address" label="Address" />
              </el-table>
            </div>
          </div>
        </div>
      </div>
  
      <!-- dialog add a user -->
      <el-dialog v-model="dialogVisible" title="Add User" width="800">
        <span>Please fill out the form below</span>
  
        <el-form :model="form" ref="userForm">
          <el-form-item label="Name" :rules="[{ required: true, message: 'Please enter name', trigger: 'blur' }]">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="Phone" :rules="[{ required: true, message: 'Please enter phone', trigger: 'blur' }]">
            <el-input v-model="form.phone"></el-input>
          </el-form-item>
          <el-form-item label="Username" :rules="[{ required: true, message: 'Please enter username', trigger: 'blur' }]">
            <el-input v-model="form.username"></el-input>
          </el-form-item>
          <el-form-item label="Email" :rules="[{ required: true, message: 'Please enter email', trigger: 'blur' }]">
            <el-input v-model="form.email"></el-input>
          </el-form-item>
          <el-form-item label="Address" :rules="[{ required: true, message: 'Please enter address', trigger: 'blur' }]">
            <el-input v-model="form.address"></el-input>
          </el-form-item>
          <el-form-item label="Password" :rules="[{ required: true, message: 'Please enter password', trigger: 'blur' }]">
            <el-input v-model="form.password" type="password"></el-input>
          </el-form-item>
          <el-form-item label="Confirm Password" :rules="[{ required: true, message: 'Please confirm password', trigger: 'blur' }]">
            <el-input v-model="form.password_confirmation" type="password"></el-input>
          </el-form-item>
          <el-form-item label="User Type" :rules="[{ required: true, message: 'Please select user type', trigger: 'change' }]">
            <el-select v-model="form.user_type" placeholder="Select User Type">
              <el-option label="Admin" value="admin"></el-option>
              <el-option label="Teacher" value="teacher"></el-option>
              <el-option label="Staff" value="staff"></el-option>
              <el-option label="Student" value="student"></el-option>
              <el-option label="Parent" value="parent"></el-option>
              <el-option label="Driver" value="driver"></el-option>
            </el-select>
          </el-form-item>
        </el-form>
  
        <div class="step-actions">
          <el-button type="primary" @click="submitForm">Submit</el-button>
        </div>
      </el-dialog>
    </div>
  </template>
  
  <script>
  import { Document } from '@element-plus/icons-vue'
  import { mapActions } from "vuex";
  import axios from 'axios';
  const api = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',

});
  export default {
    name: "Users",
    components: {
      Document
    },
    data() {
      return {
        dialogVisible: false,
        form: {
          name: '',
          phone: '',
          username: '',
          email: '',
          address: '',
          password: '',
          password_confirmation: '',
          user_type: ''
        },
        tableData: [] // here we should get all users data
      };
    },
    methods: {
      ...mapActions(['register']),
      async submitForm() {
        this.$refs.userForm.validate(async (valid) => {
          if (valid) {
            try {
              await this.register(this.form);
              this.$message.success('User added successfully');
              this.resetForm();
              this.fetchUsers();
            } catch (error) {
              this.$message.error('Failed to add user');
            }
          }
        });
      },
      async fetchUsers() {
        try {
          const response = await api.get('/users');
          this.tableData = response.data;
        } catch (error) {
          this.$message.error('Failed to fetch users');
        }
      },
      resetForm() {
        this.form = {
          name: '',
          phone: '',
          username: '',
          email: '',
          address: '',
          password: '',
          password_confirmation: '',
          user_type: ''
        };
        this.dialogVisible = false;
      }
    },
    mounted() {
      this.fetchUsers();
    }
  };
  </script>
  