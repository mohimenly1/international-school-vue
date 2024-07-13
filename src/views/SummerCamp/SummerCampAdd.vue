<template>
  <div class="container">
    <el-steps :active="activeStep" finish-status="success" align-center>
      <el-step title="Basic Information"></el-step>
      <el-step title="Parent/Guardian Information"></el-step>
      <el-step title="Emergency Contact & Pickup Persons"></el-step>
      <el-step title="Financial Invoice"></el-step>
    </el-steps>

    <el-form v-if="activeStep === 0" :model="formStep1" label-width="120px" class="form">
      <!-- Step 1: Basic Information -->
      <el-form-item label="Student Name">
        <el-input v-model="formStep1.student_name" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="Age">
        <el-input v-model.number="formStep1.age" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="School">
        <el-input v-model="formStep1.school" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="Address">
        <el-input v-model="formStep1.address" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="ZIP">
        <el-input v-model.number="formStep1.zip" class="custom-input"></el-input>
      </el-form-item>

      <el-button type="primary" @click="nextStep(1)">Next</el-button>
    </el-form>

    <el-form v-if="activeStep === 1" :model="formStep2" label-width="220px" class="form">
      <!-- Step 2: Parent/Guardian Information -->
      <el-form-item label="Parent's Phone">
        <el-input v-model="formStep2.parent_phone" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="Parent's Email">
        <el-input v-model="formStep2.parent_email" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="1st Parent/Guardian">
        <el-input v-model="formStep2.first_guardian_name" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="1st Parent/Guardian Phone">
        <el-input v-model="formStep2.first_guardian_phone" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="2nd Parent/Guardian">
        <el-input v-model="formStep2.second_guardian_name" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="2nd Parent/Guardian Phone">
        <el-input v-model="formStep2.second_guardian_phone" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="Place of Work">
        <el-input v-model="formStep2.place_of_work" class="custom-input"></el-input>
      </el-form-item>

      <el-button type="primary" @click="nextStep(2)">Next</el-button>
      <el-button @click="prevStep(0)">Previous</el-button>
    </el-form>

    <el-form v-if="activeStep === 2" :model="formStep3" label-width="150px" class="form">
      <!-- Step 3: Emergency Contact & Pickup Persons -->
      <el-form-item label="Emergency Contact">
        <el-input v-model="formStep3.emergency_contact" class="custom-input"></el-input>
      </el-form-item>

      <p>Name and phone number(s) of person(s) other than parents allowed to pick up your child</p>
      <el-form-item label="Name 1 - Phone">
        <el-input v-model="formStep3.pickup_person_1_name" class="custom-input" placeholder="Name"></el-input>
        <el-input v-model="formStep3.pickup_person_1_phone" class="custom-input" placeholder="Phone"></el-input>
      </el-form-item>
      <el-form-item label="Name 2 - Phone">
        <el-input v-model="formStep3.pickup_person_2_name" class="custom-input" placeholder="Name"></el-input>
        <el-input v-model="formStep3.pickup_person_2_phone" class="custom-input" placeholder="Phone"></el-input>
      </el-form-item>

      <el-button type="primary" @click="nextStep(3)">Next</el-button>
      <el-button @click="prevStep(1)">Previous</el-button>
    </el-form>

    <el-form v-if="activeStep === 3" :model="formStep4" label-width="150px" class="form">
      <!-- Step 4: Financial Invoice -->
      <el-form-item label="Subscription Fee">
        <el-input v-model.number="formStep4.subscription_fee" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="Paid">
        <el-input v-model.number="formStep4.paid" class="custom-input"></el-input>
      </el-form-item>
      <el-form-item label="Remaining">
        <el-input v-model.number="formStep4.remaining" class="custom-input"></el-input>
      </el-form-item>

      <el-button type="primary" @click="submitForm">Submit</el-button>
      <el-button @click="prevStep(2)">Previous</el-button>
    </el-form>
  </div>
</template>

<script>
import axios from 'axios';

const api = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
});

export default {
  data() {
    return {
      activeStep: 0,
      formStep1: {
        student_name: '',
        age: null,
        school: '',
        address: '',
        zip: null,
      },
      formStep2: {
        parent_phone: '',
        parent_email: '',
        first_guardian_name: '',
        first_guardian_phone: '',
        second_guardian_name: '',
        second_guardian_phone: '',
        place_of_work: '',
      },
      formStep3: {
        emergency_contact: '',
        pickup_person_1_name: '',
        pickup_person_1_phone: '',
        pickup_person_2_name: '',
        pickup_person_2_phone: '',
      },
      formStep4: {
        subscription_fee: null,
        paid: null,
        remaining: null,
      },
    };
  },
  methods: {
    nextStep(step) {
      this.activeStep = step;
    },
    prevStep(step) {
      this.activeStep = step;
    },
    async submitForm() {
      try {
        const response = await api.post('/subscribers', {
          ...this.formStep1,
          ...this.formStep2,
          ...this.formStep3,
          ...this.formStep4,
        });
        this.$message.success(response.data.message);
      } catch (error) {
        this.$message.error('Failed to add subscriber');
        console.error(error);
      }
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.form {
  margin-top: 20px;
}

.custom-input {
  width: 100%;
}

.el-form-item {
  margin-bottom: 20px;
}

.el-button {
  margin-right: 10px;
}
</style>
