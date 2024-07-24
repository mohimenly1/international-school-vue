<template>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
              <div class="d-flex justify-content-between">
                <h6 class="text-white text-capitalize ps-3">Manage Exams</h6>
                <el-input
                  style="width: 200px;"
                  v-model="searchQuery"
                  placeholder="Search exams..."
                  class="search-input"
                  @input="fetchExams"
                />
                <el-button @click="dialogVisible = true" type="success" circle>
                  <el-icon style="vertical-align: middle">
                    <Document />
                  </el-icon>
                </el-button>
              </div>
            </div>
            <div>
                <el-table :data="pagedExams" style="width: 100%">
                    <el-table-column property="name" label="Exam Name" width="120" />
                    <el-table-column property="description" label="Description" />
                    <el-table-column label="Class">
                        <template #default="scope">
                          <span v-if="scope.row.sections && scope.row.sections.length > 0 && scope.row.sections[0].class">
                            {{ scope.row.sections[0].class.name }}
                          </span>
                          <span v-else>N/A</span>
                        </template>
                      </el-table-column>
                      <el-table-column label="Section">
                        <template #default="scope">
                          <span v-if="scope.row.sections && scope.row.sections.length > 0">
                            {{ scope.row.sections[0].name }}
                          </span>
                          <span v-else>N/A</span>
                        </template>
                      </el-table-column>
                      <el-table-column label="Subjects">
                        <template #default="scope">
                          <ul v-if="scope.row.sections && scope.row.sections.length > 0 && scope.row.sections[0].subjects">
                            <li v-for="subject in scope.row.sections[0].subjects" :key="subject.id">
                              {{ subject.type }} - {{ subject.pivot.exam_date }}
                            </li>
                          </ul>
                          <span v-else>N/A</span>
                        </template>
                      </el-table-column>
                      
                    <el-table-column label="Actions" width="180">
                      <template #default="scope">
                        <el-button @click="editExam(scope.row)" type="warning" size="mini">Edit</el-button>
                        <el-button @click="deleteExam(scope.row.id)" type="danger" size="mini">Delete</el-button>
                      </template>
                    </el-table-column>
                  </el-table>
  
              <div class="d-flex justify-content-center mt-4">
                <el-pagination
                  @current-change="handleCurrentChange"
                  :current-page="currentPage"
                  :page-size="pageSize"
                  :total="totalExams"
                  layout="prev, pager, next"
                  background
                />
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <el-dialog v-model="dialogVisible" title="Add/Edit Exam" width="800">
        <el-form :model="form" ref="examForm">
          <el-form-item label="Exam Name" :rules="[{ required: true, message: 'Please enter exam name', trigger: 'blur' }]">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="Description">
            <el-input v-model="form.description"></el-input>
          </el-form-item>
          <el-form-item label="Class" :rules="[{ required: true, message: 'Please select a class', trigger: 'change' }]">
            <el-select v-model="form.class_id" @change="fetchSections" placeholder="Select class">
              <el-option v-for="classItem in classes" :key="classItem.id" :label="classItem.name" :value="classItem.id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Section" :rules="[{ required: true, message: 'Please select a section', trigger: 'change' }]">
            <el-select v-model="form.section_id" placeholder="Select section">
              <el-option v-for="section in sections" :key="section.id" :label="section.name" :value="section.id"></el-option>
            </el-select>
          </el-form-item>
  
          <el-form-item label="Subjects and Dates">
            <div v-for="(subject, index) in form.subjects" :key="index" class="subject-date">
              <el-select v-model="subject.id" placeholder="Select subject">
                <el-option v-for="subjectOption in subjects" :key="subjectOption.id" :label="subjectOption.type" :value="subjectOption.id"></el-option>
              </el-select>
              <el-date-picker v-model="subject.exam_date" type="date" placeholder="Pick a date"></el-date-picker>
              <el-button type="danger" icon="el-icon-minus" @click="removeSubject(index)"></el-button>
            </div>
            <el-button type="primary" icon="el-icon-plus" @click="addSubject">Add Subject</el-button>
          </el-form-item>
        </el-form>
  
        <div class="step-actions">
          <el-button type="primary" @click="submitForm">Submit</el-button>
        </div>
      </el-dialog>
    </div>
  </template>
  
  <script>
  import { Document } from '@element-plus/icons-vue';
  import axios from 'axios';
  
  const api = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
  });
  
  export default {
    name: "Exams",
    components: {
      Document
    },
    data() {
      return {
        dialogVisible: false,
        form: {
          name: '',
          description: '',
          class_id: '',
          section_id: '',
          subjects: []
        },
        exams: [],
        classes: [],
        sections: [],
        subjects: [],
        currentPage: 1,
        pageSize: 10,
        totalExams: 0,
        isEditing: false,
        editingId: null,
        searchQuery: ''
      };
    },
    computed: {
      filteredExams() {
        const query = this.searchQuery.toLowerCase();
        return this.exams.filter(exam => {
          return (
            exam.name.toLowerCase().includes(query) ||
            exam.description.toLowerCase().includes(query) ||
            exam.sections[0].class.name.toLowerCase().includes(query) ||
            exam.sections[0].name.toLowerCase().includes(query) ||
            exam.sections[0].subjects.some(subject => subject.type.toLowerCase().includes(query))
          );
        });
      },
      pagedExams() {
        const startIndex = (this.currentPage - 1) * this.pageSize;
        const endIndex = startIndex + this.pageSize;
        return this.filteredExams.slice(startIndex, endIndex);
      }
    },
    methods: {
      async fetchExams() {
        try {
          const response = await api.get('/exams', { params: { search: this.searchQuery } });
          this.exams = response.data.data; // Update based on pagination response
          this.totalExams = response.data.total; // Update based on pagination response
        } catch (error) {
          this.$message.error('Failed to fetch exams');
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
      async fetchSections() {
        try {
          const response = await api.get(`/sections`, { params: { class_id: this.form.class_id } });
          this.sections = response.data;
        } catch (error) {
          this.$message.error('Failed to fetch sections');
        }
      },
      async fetchSubjects() {
        try {
          const response = await api.get('/subjects');
          this.subjects = response.data;
        } catch (error) {
          this.$message.error('Failed to fetch subjects');
        }
      },
      async submitForm() {
        const payload = {
          ...this.form,
          subjects: this.form.subjects.map(subject => ({
            id: subject.id,
            exam_date: subject.exam_date
          }))
        };
  
        try {
          if (this.isEditing) {
            await api.put(`/exams/${this.editingId}`, payload);
            this.$message.success('Exam updated successfully');
          } else {
            await api.post('/exams', payload);
            this.$message.success('Exam added successfully');
          }
          this.resetForm();
          this.fetchExams();
        } catch (error) {
          this.$message.error('Failed to save exam');
        }
      },
      editExam(exam) {
        this.dialogVisible = true;
        this.isEditing = true;
        this.editingId = exam.id;
        this.form = {
          name: exam.name,
          description: exam.description,
          class_id: exam.sections[0].class_id,
          section_id: exam.sections[0].id,
          subjects: exam.sections[0].subjects.map(subject => ({
            id: subject.id,
            exam_date: subject.pivot.exam_date
          }))
        };
      },
      deleteExam(id) {
        this.$confirm('Are you sure you want to delete this exam?', 'Warning', {
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
          type: 'warning'
        }).then(async () => {
          try {
            await api.delete(`/exams/${id}`);
            this.$message.success('Exam deleted successfully');
            this.fetchExams();
          } catch (error) {
            this.$message.error('Failed to delete exam');
          }
        });
      },
      addSubject() {
        this.form.subjects.push({ id: '', exam_date: '' });
      },
      removeSubject(index) {
        this.form.subjects.splice(index, 1);
      },
      handleCurrentChange(val) {
        this.currentPage = val;
        this.fetchExams();
      },
      resetForm() {
        this.form = {
          name: '',
          description: '',
          class_id: '',
          section_id: '',
          subjects: []
        };
        this.dialogVisible = false;
        this.isEditing = false;
        this.editingId = null;
      }
    },
    mounted() {
      this.fetchExams();
      this.fetchClasses();
      this.fetchSubjects();
    }
  };
  </script>
  
  <style scoped>
  .bg-header-table {
    background-image: linear-gradient(to top, #5f72bd 0%, #9b23ea 100%);
  }
  
  .step-actions {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
  }
  
  .subject-date {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }
  </style>
  