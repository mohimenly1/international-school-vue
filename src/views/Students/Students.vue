<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Student Records</h6>
                                <el-input
                                    v-model="searchQuery"
                                    placeholder="Search by Name or Bus Number"
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
                        <el-table :data="pagedStudents" style="width: 100%">
                            <el-table-column type="selection" width="55" />
                            <el-table-column property="name" label="Name" width="120" />
                            <el-table-column property="date_of_birth" label="Date of Birth" width="120" />
                            <el-table-column property="address" label="Address" width="240" />
                            <el-table-column property="parent.first_name" label="Parent First Name" width="120" />
                            <el-table-column property="parent.last_name" label="Parent Last Name" width="120" />
                            <el-table-column property="class.name" label="Class" width="120" />
                            <el-table-column property="bus.number" label="Bus" width="120" />
                            <el-table-column label="Operations" width="300">
                                <template #default="scope">
                                  <el-button @click="showStudent(scope.row)" type="primary" size="mini">Show</el-button>
                                  <el-button @click="editStudent(scope.row)" type="warning" size="mini">Edit</el-button>
                                  <el-button @click="deleteStudent(scope.row.id)" type="danger" size="mini">Delete</el-button>
                                  <el-button @click="printStudent(scope.row)" type="info" circle size="large">
                                    <el-icon style="vertical-align: middle">
                                      <Printer />
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
                                :total="filteredStudents.length"
                                layout="prev, pager, next"
                                background
                            />
                        </div>
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
                <el-step title="Subscription Fees"></el-step>
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

            <div v-if="activeStep === 4">
              
                <el-form :model="form">
                    <el-form-item label="Registration Fees">
                        <el-select v-model="form.registration_fee" placeholder="Select Registration Fee">
                          <el-option v-for="fee in subscriptionFees.registration" :key="fee.id" :label="fee.sub_category" :value="fee.id"></el-option>
                        </el-select>
                      </el-form-item>
                      <el-form-item label="Books">
                        <el-select v-model="form.books_fee" placeholder="Select Books Fee">
                          <el-option v-for="fee in subscriptionFees.books" :key="fee.id" :label="fee.sub_category" :value="fee.id"></el-option>
                        </el-select>
                      </el-form-item>
                      <el-form-item label="Uniform">
                        <el-select v-model="form.uniform_fee" placeholder="Select Uniform Fee">
                          <el-option v-for="fee in subscriptionFees.uniform" :key="fee.id" :label="fee.sub_category" :value="fee.id"></el-option>
                        </el-select>
                      </el-form-item>
                      <el-form-item label="Tuition Fees">
                        <el-select v-model="form.tuition_fee" placeholder="Select Tuition Fee">
                          <el-option v-for="fee in subscriptionFees.tuition" :key="fee.id" :label="fee.sub_category" :value="fee.id"></el-option>
                        </el-select>
                      </el-form-item>
                      <el-form-item label="Transportation and Food">
                        <el-select v-model="form.transportation_food_fee" placeholder="Select Transportation and Food Fee">
                          <el-option v-for="fee in subscriptionFees.transportation_food" :key="fee.id" :label="fee.sub_category" :value="fee.id"></el-option>
                        </el-select>
                      </el-form-item>
                      
                </el-form>
            </div>

            <div class="step-actions">
                <el-button v-if="activeStep > 0" @click="prevStep">Previous</el-button>
                <!-- <el-button v-if="activeStep < 3" type="primary" @click="nextStep">Next</el-button> -->
                <el-button v-if="activeStep < 4" type="primary" @click="nextStep">Next</el-button>
                <el-button v-if="activeStep === 4" type="success" @click="submitForm">Submit</el-button>
            </div>
        </el-dialog>

        <!-- dialog show student details -->
        <el-dialog v-model="showDialogVisible" title="Student Details" width="800">
            <div v-if="selectedStudent">
                <p><strong>Name:</strong> {{ selectedStudent.name }}</p>
                <p><strong>Date of Birth:</strong> {{ selectedStudent.date_of_birth }}</p>
                <p><strong>Address:</strong> {{ selectedStudent.address }}</p>
                <p><strong>Parent:</strong> {{ selectedStudent.parent?.first_name ?? 'N/A' }} {{ selectedStudent.parent?.last_name ?? '' }}</p>
                <p><strong>Class:</strong> {{ selectedStudent.class?.name ?? 'N/A' }}</p>
                <p><strong>Bus:</strong> {{ selectedStudent.bus?.number ?? 'N/A' }}</p>
            </div>
        </el-dialog>
    </div>
</template>

<script>
import { Document, Printer } from '@element-plus/icons-vue';
import axios from 'axios';
import logo from "@/assets/img/logo-school.png";

const api = axios.create({
        baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
    });

const currentDate = new Date().toLocaleDateString('en-US');
const currentTime = new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
const titleInvoiceStudent = "Caledonian International School";

export default {
  name: "Students",
  components: {
    Document,
    Printer,
  },
  data() {
    return {
      activeStep: 0,
      dialogVisible: false,
      showDialogVisible: false,
      form: {
        id: null,
        name: '',
        date_of_birth: '',
        address: '',
        parent_id: '',
        class_id: '',
        registration_fee: '',
        bus_id: '',
        books_fee: '',
        uniform_fee: '',
        transportation_food_fee: '',
        tuition_fee: ''
      },
      selectedStudent: null,
      searchQuery: '',
      tableData: [],
      subscriptionFees: {
        registration: [],
        books: [],
        uniform: [],
        transportation_food: [],
        tuition:[],
      },
      subscriptionFeesData: [],
      parents: [],
      classes: [],
      buses: [],
      currentPage: 1,
      pageSize: 10
    };
  },
  methods: {
    async fetchSubscriptionFees() {
      try {
        const response = await api.get('/subscription-fees');
        const fees = response.data;

        this.subscriptionFees = {
          registration: fees.filter(fee => fee.category === 'Registration Fees'),
          books: fees.filter(fee => fee.category === 'Books'),
          uniform: fees.filter(fee => fee.category === 'Uniform'),
          transportation_food: fees.filter(fee => fee.category === 'Transportation and Food'),
          tuition: fees.filter(fee => fee.category === 'Tuition Fees')
        };
      } catch (error) {
        this.$message.error('Failed to fetch subscription fees');
      }
    },
    async submitForm() {
      // Convert date_of_birth to ISO string if it's a Date object
      if (this.form.date_of_birth instanceof Date) {
        this.form.date_of_birth = this.form.date_of_birth.toISOString().split('T')[0];
      }

      try {
        let response;
        if (this.form.id) {
          // Update existing student
          response = await api.put(`/students/${this.form.id}`, this.form);
          this.$message.success('Student updated successfully');
        } else {
          // Create new student
          response = await api.post('/students', this.form);
          this.$message.success('Student added successfully');
        }

        const studentId = response.data.id;

        // Prepare subscription fees payload
        const subscriptionFeesPayload = [
          {
            subscription_fee_id: this.form.registration_fee,
            amount: this.subscriptionFees.registration.find(fee => fee.id === this.form.registration_fee).amount
          },
          {
            subscription_fee_id: this.form.books_fee,
            amount: this.subscriptionFees.books.find(fee => fee.id === this.form.books_fee).amount
          },
          {
            subscription_fee_id: this.form.uniform_fee,
            amount: this.subscriptionFees.uniform.find(fee => fee.id === this.form.uniform_fee).amount
          },
          {
            subscription_fee_id: this.form.transportation_food_fee,
            amount: this.subscriptionFees.transportation_food.find(fee => fee.id === this.form.transportation_food_fee).amount
          },
          {
            subscription_fee_id: this.form.tuition_fee,
            amount: this.subscriptionFees.tuition.find(fee => fee.id === this.form.tuition_fee).amount
          }
        ];

        // Save subscription fees in the pivot table
        await api.post(`/students/${studentId}/subscription-fees`, { subscriptionFees: subscriptionFeesPayload });

        this.resetForm();
        this.fetchStudents();
      } catch (error) {
        this.$message.error('Failed to save student');
      }
    },
    async printStudent(student) {
  try {
    const response = await api.get(`/students/${student.id}`);
    const studentDetails = response.data;

    // Open a new page with the invoice
    const invoiceUrl = `/invoice.html?id=${student.id}`;
    window.open(invoiceUrl, '_blank');
  } catch (error) {
    console.error('Error fetching student details for printing:', error);
    this.$message.error('Failed to fetch student details for printing');
  }
},
    nextStep() {
      this.activeStep++;
    },
    prevStep() {
      this.activeStep--;
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
    async deleteStudent(id) {
      try {
        await api.delete(`/students/${id}`);
        this.$message.success('Student deleted successfully');
        this.fetchStudents();
      } catch (error) {
        this.$message.error('Failed to delete student');
      }
    },
    async showStudent(student) {
      try {
        const response = await api.get(`/students/${student.id}`);
        this.selectedStudent = response.data;
        this.showDialogVisible = true;
      } catch (error) {
        this.$message.error('Failed to fetch student details');
      }
    },
    editStudent(student) {
      this.form = { ...student, date_of_birth: new Date(student.date_of_birth) };
      this.dialogVisible = true;
    },
    resetForm() {
      this.form = {
        id: null,
        name: '',
        date_of_birth: '',
        address: '',
        parent_id: '',
        class_id: '',
        bus_id: ''
      };
      this.activeStep = 0;
      this.dialogVisible = false;
    },
    handleCurrentChange(val) {
      this.currentPage = val;
    },
    handleSearch() {
      this.fetchStudents();
    }
  },
  computed: {
    pagedStudents() {
      const startIndex = (this.currentPage - 1) * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.filteredStudents.slice(startIndex, endIndex);
    },
    filteredStudents() {
      const query = this.searchQuery.toLowerCase();
      return this.tableData.filter(student => {
        return (
          student.name.toLowerCase().includes(query) ||
          (student.bus && student.bus.number.toLowerCase().includes(query))
        );
      });
    }
  },
  mounted() {
    this.fetchStudents();
    this.fetchParents();
    this.fetchClasses();
    this.fetchBuses();
    this.fetchSubscriptionFees();
  },
  watch: {
    searchQuery: 'handleSearch'
  }
};
</script>


<style scoped>
.bg-header-table {
    background-image: linear-gradient(to top, #5f72bd 0%, #9b23ea 100%);
}

.search-input {
    width: 300px;
    margin-right: 10px;
}

.step-actions {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
}
</style>
