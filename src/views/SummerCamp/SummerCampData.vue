<template>
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div style="padding-right: 25px;" class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
            <div class="d-flex justify-content-around">
              <h6 class="text-white text-capitalize ps-3">Subscriber Records</h6>
              <el-input style="width: 200px;" v-model="searchQuery" placeholder="Search by name, school, etc." clearable @clear="handleClearSearch"></el-input>
            </div>
          </div>
          <div>
            <el-table :data="pagedSubscribers" style="width: 100%">
              <el-table-column type="selection" width="55" />
              <el-table-column property="student_name" label="Student Name" width="120" />
              <el-table-column property="age" label="Age" width="60" />
              <el-table-column property="school" label="School" width="120" />
              <el-table-column property="address" label="Address" />
              <el-table-column property="parent_phone" label="Parent Phone" width="120" />
              <el-table-column property="parent_email" label="Parent Email" width="180" />
              <el-table-column label="Actions" width="430">
                <template #default="scope">
                  <el-button @click="showSubscriber(scope.row)" type="primary" size="mini">View</el-button>
                  <el-button @click="printSubscriber(scope.row)" type="info" size="mini">Print</el-button>
                  <el-button @click="editSubscriber(scope.row)" type="warning" size="mini">Edit</el-button>
                  <el-button @click="deleteSubscriber(scope.row.id)" type="danger" size="mini">Delete</el-button>
                </template>
              </el-table-column>
            </el-table>

            <div class="d-flex justify-content-center mt-4">
              <el-pagination
                @current-change="handleCurrentChange"
                :current-page="currentPage"
                :page-size="pageSize"
                :total="totalSubscribers"
                layout="prev, pager, next"
                background
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dialog for showing subscriber details -->
    <el-dialog v-model="showDialogVisible" title="Subscriber Details" width="800">
      <div v-if="selectedSubscriber" class="subscriber-info">
        <div class="subscriber-details">
          <!-- Subscriber details here -->
          <div class="detail-item"><strong>Student Name:</strong> {{ selectedSubscriber.student_name }}</div>
          <div class="detail-item"><strong>Age:</strong> {{ selectedSubscriber.age }}</div>
          <div class="detail-item"><strong>School:</strong> {{ selectedSubscriber.school }}</div>
          <div class="detail-item"><strong>Address:</strong> {{ selectedSubscriber.address }}</div>
          <div class="detail-item"><strong>ZIP:</strong> {{ selectedSubscriber.zip }}</div>
          <div class="detail-item"><strong>Parent Phone:</strong> {{ selectedSubscriber.parent_phone }}</div>
          <div class="detail-item"><strong>Parent Email:</strong> {{ selectedSubscriber.parent_email }}</div>
          <div class="detail-item"><strong>First Guardian Name:</strong> {{ selectedSubscriber.first_guardian_name }}</div>
          <div class="detail-item"><strong>First Guardian Phone:</strong> {{ selectedSubscriber.first_guardian_phone }}</div>
          <div class="detail-item"><strong>Second Guardian Name:</strong> {{ selectedSubscriber.second_guardian_name }}</div>
          <div class="detail-item"><strong>Second Guardian Phone:</strong> {{ selectedSubscriber.second_guardian_phone }}</div>
          <div class="detail-item"><strong>Place of Work:</strong> {{ selectedSubscriber.place_of_work }}</div>
          <div class="detail-item"><strong>Emergency Contact:</strong> {{ selectedSubscriber.emergency_contact }}</div>
          <div class="detail-item"><strong>Pickup Person 1 Name:</strong> {{ selectedSubscriber.pickup_person_1_name }}</div>
          <div class="detail-item"><strong>Pickup Person 1 Phone:</strong> {{ selectedSubscriber.pickup_person_1_phone }}</div>
          <div class="detail-item"><strong>Pickup Person 2 Name:</strong> {{ selectedSubscriber.pickup_person_2_name }}</div>
          <div class="detail-item"><strong>Pickup Person 2 Phone:</strong> {{ selectedSubscriber.pickup_person_2_phone }}</div>
          <div class="detail-item"><strong>Subscription Fee:</strong> {{ selectedSubscriber.subscription_fee }}</div>
          <div class="detail-item"><strong>Paid:</strong> {{ selectedSubscriber.paid }}</div>
          <div class="detail-item"><strong>Remaining:</strong> {{ selectedSubscriber.remaining }}</div>
        </div>
      </div>
    </el-dialog>

    <!-- Dialog for adding/editing a subscriber -->
    <el-dialog v-model="editDialogVisible" title="Add/Edit Subscriber" width="800">
      <div class="subscriber-form">
        <form @submit.prevent="submitForm">
          <div class="form-group">
            <label for="student_name">Student Name:</label>
            <input type="text" id="student_name" v-model="form.student_name" required>
          </div>
          <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" id="age" v-model="form.age" required>
          </div>
          <div class="form-group">
            <label for="school">School:</label>
            <input type="text" id="school" v-model="form.school">
          </div>
          <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" v-model="form.address" required>
          </div>
          <div class="form-group">
            <label for="zip">ZIP:</label>
            <input type="text" id="zip" v-model="form.zip">
          </div>
          <div class="form-group">
            <label for="parent_phone">Parent Phone:</label>
            <input type="text" id="parent_phone" v-model="form.parent_phone" required>
          </div>
          <div class="form-group">
            <label for="parent_email">Parent Email:</label>
            <input type="email" id="parent_email" v-model="form.parent_email">
          </div>
          <div class="form-group">
            <label for="first_guardian_name">First Guardian Name:</label>
            <input type="text" id="first_guardian_name" v-model="form.first_guardian_name">
          </div>
          <div class="form-group">
            <label for="first_guardian_phone">First Guardian Phone:</label>
            <input type="text" id="first_guardian_phone" v-model="form.first_guardian_phone">
          </div>
          <div class="form-group">
            <label for="second_guardian_name">Second Guardian Name:</label>
            <input type="text" id="second_guardian_name" v-model="form.second_guardian_name">
          </div>
          <div class="form-group">
            <label for="second_guardian_phone">Second Guardian Phone:</label>
            <input type="text" id="second_guardian_phone" v-model="form.second_guardian_phone">
          </div>
          <div class="form-group">
            <label for="place_of_work">Place of Work:</label>
            <input type="text" id="place_of_work" v-model="form.place_of_work">
          </div>
          <div class="form-group">
            <label for="emergency_contact">Emergency Contact:</label>
            <input type="text" id="emergency_contact" v-model="form.emergency_contact">
          </div>
          <div class="form-group">
            <label for="pickup_person_1_name">Pickup Person 1 Name:</label>
            <input type="text" id="pickup_person_1_name" v-model="form.pickup_person_1_name">
          </div>
          <div class="form-group">
            <label for="pickup_person_1_phone">Pickup Person 1 Phone:</label>
            <input type="text" id="pickup_person_1_phone" v-model="form.pickup_person_1_phone">
          </div>
          <div class="form-group">
            <label for="pickup_person_2_name">Pickup Person 2 Name:</label>
            <input type="text" id="pickup_person_2_name" v-model="form.pickup_person_2_name">
          </div>
          <div class="form-group">
            <label for="pickup_person_2_phone">Pickup Person 2 Phone:</label>
            <input type="text" id="pickup_person_2_phone" v-model="form.pickup_person_2_phone">
          </div>
          <div class="form-group">
            <label for="subscription_fee">Subscription Fee:</label>
            <input type="number" id="subscription_fee" v-model="form.subscription_fee">
          </div>
          <div class="form-group">
            <label for="paid">Paid:</label>
            <input type="number" id="paid" v-model="form.paid">
          </div>
          <div class="form-group">
            <label for="remaining">Remaining:</label>
            <input type="number" id="remaining" v-model="form.remaining">
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
import axios from 'axios';
import logo from "@/assets/img/logo-school.png";
import MontserratRegular from "@/assets/fonts/Montserrat-Regular.ttf";
const api = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
});

export default {
  data() {
    return {
      tableData: [],
      searchQuery: '',
      showDialogVisible: false,
      editDialogVisible: false,
      selectedSubscriber: null,
      form: {
        student_name: '',
        age: null,
        school: '',
        address: '',
        zip: '',
        parent_phone: '',
        parent_email: '',
        first_guardian_name: '',
        first_guardian_phone: '',
        second_guardian_name: '',
        second_guardian_phone: '',
        place_of_work: '',
        emergency_contact: '',
        pickup_person_1_name: '',
        pickup_person_1_phone: '',
        pickup_person_2_name: '',
        pickup_person_2_phone: '',
        subscription_fee: null,
        paid: null,
        remaining: null,
      },
      isEditing: false,
      editingId: null,
      currentPage: 1,
      pageSize: 10,
      totalSubscribers: 0
    };
  },
  computed: {
    filteredData() {
      const query = this.searchQuery.toLowerCase();
      return this.tableData.filter(subscriber => 
        (subscriber.student_name && subscriber.student_name.toLowerCase().includes(query)) ||
        (subscriber.age && subscriber.age.toString().includes(query)) ||
        (subscriber.school && subscriber.school.toLowerCase().includes(query)) ||
        (subscriber.address && subscriber.address.toLowerCase().includes(query)) ||
        (subscriber.parent_phone && subscriber.parent_phone.toLowerCase().includes(query)) ||
        (subscriber.parent_email && subscriber.parent_email.toLowerCase().includes(query))
      );
    },
    pagedSubscribers() {
      const startIndex = (this.currentPage - 1) * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.filteredData.slice(startIndex, endIndex);
    }
  },
  methods: {
    fetchSubscribers() {
      api.get('/subscribers')
        .then(response => {
          this.tableData = response.data.data;
          this.totalSubscribers = response.data.total;
        })
        .catch(error => {
          console.error('Failed to fetch subscribers:', error);
        });
    },
    showSubscriber(subscriber) {
      this.selectedSubscriber = subscriber;
      this.showDialogVisible = true;
    },
    printSubscriber(subscriber) {
  const printWindow = window.open('', '_blank');
  if (printWindow) {
    const currentDate = new Date().toLocaleDateString('en-US');
    const currentTime = new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });

    const checkNull = (value) => (value === null || value === undefined ? 'لا يوجد بيانات' : value);

    // const fontURL = `${window.location.origin}/src/assets/fonts/PlaywritePE-Thin.ttf`;

    printWindow.document.write(`
      <html>
      <head>
        <title>Summer camp member</title>
        <style>
          @font-face {
            font-family: 'PlaywritePE-Thin';
            src: url('${MontserratRegular}') format('truetype');
          }
          body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: auto;
            padding: 20px;
          }
          .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
          }
          .header .left {
            flex: 1;
            text-align: left;
          }
          .header .center {
            flex: 1;
            text-align: center;
          }
          .header .center h2 {
            font-family: 'PlaywritePE-Thin', Arial, sans-serif;
          }
          .header .right {
            flex: 1;
            text-align: right;
          }
          .logo-container {
            text-align: left;
            margin-bottom: 10px;
          }
          .logo {
            max-width: 20%;
            height: auto;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
          }
          th, td {
            border: 2px solid black;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-family: 'MontserratRegular', sans-serif;
          }
          th {
            background-color: #f2f2f2;
          }
          .detail-item {
            margin-bottom: 8px;
            font-size: 14px;
          }
          .invoice-section {
            margin-top: 10px;
            border: 2px solid black;
            padding: 10px;
          }
          .respectful-statement {
            margin-top: 0px;
            font-size: 10px;
            text-align: center;
          }
          .signature-section {
            margin-top: 20px;
            text-align: right;
            font-size: 14px;
          }
        </style>
      </head>
      <body>
        <div class="logo-container">
          <img src="${logo}" alt="School Logo" class="logo">
        </div>
        <div class="header">
          <div class="left">
            <strong>Date:</strong> ${currentDate}<br>
            <strong>Time:</strong> ${currentTime}
          </div>
          <div class="center">
            <h2>Summer camp member</h2>
          </div>
          <div class="right">
           Caledonian International School
          </div>
        </div>
        <table>
          <tbody>
            <tr>
              <td><strong>Student Name</strong></td>
              <td>${checkNull(subscriber.student_name)}</td>
            </tr>
            <tr>
              <td><strong>Age</strong></td>
              <td>${checkNull(subscriber.age)}</td>
            </tr>
            <tr>
              <td><strong>Address</strong></td>
              <td>${checkNull(subscriber.address)}</td>
            </tr>
            <tr>
              <td><strong>Parent Phone</strong></td>
              <td>${checkNull(subscriber.parent_phone)}</td>
            </tr>
            <tr>
              <td><strong>Parent Email</strong></td>
              <td>${checkNull(subscriber.parent_email)}</td>
            </tr>
            <tr>
              <td><strong>First Guardian Name</strong></td>
              <td>${checkNull(subscriber.first_guardian_name)}</td>
            </tr>
            <tr>
              <td><strong>First Guardian Phone</strong></td>
              <td>${checkNull(subscriber.first_guardian_phone)}</td>
            </tr>
            <tr>
              <td><strong>Second Guardian Name</strong></td>
              <td>${checkNull(subscriber.second_guardian_name)}</td>
            </tr>
            <tr>
              <td><strong>Second Guardian Phone</strong></td>
              <td>${checkNull(subscriber.second_guardian_phone)}</td>
            </tr>
            <tr>
              <td><strong>Place of Work</strong></td>
              <td>${checkNull(subscriber.place_of_work)}</td>
            </tr>
            <tr>
              <td><strong>Emergency Contact</strong></td>
              <td>${checkNull(subscriber.emergency_contact)}</td>
            </tr>
            <tr>
              <td><strong>Pickup Person 1 Name</strong></td>
              <td>${checkNull(subscriber.pickup_person_1_name)}</td>
            </tr>
            <tr>
              <td><strong>Pickup Person 1 Phone</strong></td>
              <td>${checkNull(subscriber.pickup_person_1_phone)}</td>
            </tr>
            <tr>
              <td><strong>Pickup Person 2 Name</strong></td>
              <td>${checkNull(subscriber.pickup_person_2_name)}</td>
            </tr>
            <tr>
              <td><strong>Pickup Person 2 Phone</strong></td>
              <td>${checkNull(subscriber.pickup_person_2_phone)}</td>
            </tr>
          </tbody>
        </table>
        <div class="invoice-section">
          <p><strong>Subscription Fee:</strong> ${checkNull(subscriber.subscription_fee)}</p>
          <p><strong>Paid:</strong> ${checkNull(subscriber.paid)}</p>
          <p><strong>Remaining:</strong> ${checkNull(subscriber.remaining)}</p>
          <div class="signature-section">
            <p><strong>Parent's Signature:</strong> ...............</p>
          </div>
        </div>
        <div class="respectful-statement">
          <p style=" font-family: 'PlaywritePE-Thin', Arial, sans-serif;">Caledonian International School is delighted and is making every effort to provide the best educational services and activities.</p>
        </div>
      </body>
      </html>
    `);
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
  } else {
    alert('Pop-up blocked. Please allow pop-ups for this site to print.');
  }
},
    editSubscriber(subscriber) {
      this.editDialogVisible = true;
      this.isEditing = true;
      this.editingId = subscriber.id;
      this.form = { ...subscriber };
    },
    async deleteSubscriber(id) {
      try {
        await api.delete(`/subscribers/${id}`);
        this.$message.success('Subscriber deleted successfully');
        this.fetchSubscribers();
      } catch (error) {
        this.$message.error('Failed to delete subscriber');
      }
    },
    async submitForm() {
      const formData = { ...this.form };

      try {
        if (this.isEditing) {
          await api.put(`/subscribers/${this.editingId}`, formData);
          this.$message.success('Subscriber updated successfully');
        } else {
          await api.post('/subscribers', formData);
          this.$message.success('Subscriber added successfully');
        }
        this.resetForm();
        this.fetchSubscribers();
      } catch (error) {
        this.$message.error('Failed to save subscriber');
      }
    },
    handleClearSearch() {
      this.searchQuery = '';
    },
    resetForm() {
      this.form = {
        student_name: '',
        age: null,
        school: '',
        address: '',
        zip: '',
        parent_phone: '',
        parent_email: '',
        first_guardian_name: '',
        first_guardian_phone: '',
        second_guardian_name: '',
        second_guardian_phone: '',
        place_of_work: '',
        emergency_contact: '',
        pickup_person_1_name: '',
        pickup_person_1_phone: '',
        pickup_person_2_name: '',
        pickup_person_2_phone: '',
        subscription_fee: null,
        paid: null,
        remaining: null,
      };
      this.dialogVisible = false;
      this.isEditing = false;
      this.editingId = null;
    },
    handleCurrentChange(val) {
      this.currentPage = val;
    }
  },
  mounted() {
    this.fetchSubscribers();
  },
  watch: {
    searchQuery: 'fetchSubscribers'
  }
};
</script>

<style scoped>
.container-fluid {
  padding-top: 20px;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.table-container {
  margin-top: 20px;
}

.detail-item {
  margin-bottom: 10px;
}
</style>
