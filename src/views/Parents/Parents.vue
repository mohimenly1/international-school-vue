<template>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-header-table shadow-success border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-around">
                                <h6 class="text-white text-capitalize ps-3">Parents Records</h6>
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
                            <el-table-column property="first_name" label="First Name" width="120" />
                            <el-table-column property="last_name" label="Last Name" width="120" />
                            <el-table-column property="phone_number_one" label="Phone Number One" width="120" />
                            <el-table-column property="phone_number_two" label="Phone Number Two" width="120" />
                            <el-table-column property="city" label="City" width="120" />
                            <el-table-column property="address" label="Address" />
                            <el-table-column label="Operations" width="280">
                                <template #default="scope">
                                    
                                    <el-button @click="showParent(scope.row)" type="primary" size="mini">Show</el-button>
                                    <el-button @click="editParent(scope.row)" type="warning" size="mini">Edit</el-button>
                                    <el-button @click="deleteParent(scope.row.id)" type="danger" size="mini">Delete</el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </div>
        </div>

        <!-- dialog add a parent -->
        <el-dialog v-model="dialogVisible" title="Add Parent" width="800">
            <span>Please fill out the form below</span>

            <el-form :model="form" ref="parentForm">
                <el-form-item label="First Name" :rules="[{ required: true, message: 'Please enter first name', trigger: 'blur' }]">
                    <el-input v-model="form.first_name"></el-input>
                </el-form-item>
                <el-form-item label="Last Name" :rules="[{ required: true, message: 'Please enter last name', trigger: 'blur' }]">
                    <el-input v-model="form.last_name"></el-input>
                </el-form-item>
                <el-form-item label="Phone Number One" :rules="[{ required: true, message: 'Please enter phone number one', trigger: 'blur' }]">
                    <el-input v-model="form.phone_number_one"></el-input>
                </el-form-item>
                <el-form-item label="Phone Number Two" :rules="[{ required: true, message: 'Please enter phone number two', trigger: 'blur' }]">
                    <el-input v-model="form.phone_number_two"></el-input>
                </el-form-item>
                <el-form-item label="City" :rules="[{ required: true, message: 'Please enter city', trigger: 'blur' }]">
                    <el-input v-model="form.city"></el-input>
                </el-form-item>
                <el-form-item label="Address" :rules="[{ required: true, message: 'Please enter address', trigger: 'blur' }]">
                    <el-input v-model="form.address"></el-input>
                </el-form-item>
                <el-form-item label="ID Image">
                    <el-upload
                        action=""
                        list-type="picture-card"
                        :file-list="form.id_image_list"
                        :on-preview="handlePreview"
                        :on-remove="handleRemove"
                        :before-upload="beforeUploadIDImage">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="Passport Image">
                    <el-upload
                        action=""
                        list-type="picture-card"
                        :file-list="form.passport_image_list"
                        :on-preview="handlePreview"
                        :on-remove="handleRemove"
                        :before-upload="beforeUploadPassportImage">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                </el-form-item>
            </el-form>

            <div class="step-actions">
                <el-button type="primary" @click="submitForm">Submit</el-button>
            </div>
        </el-dialog>

        <!-- dialog show parent -->
        <el-dialog v-model="showDialogVisible" title="Parent Details" width="800">
            <div v-if="selectedParent" class="parent-info">
                <div class="parent-details">
                    <div class="detail-item">
                        <strong>First Name:</strong> {{ selectedParent.first_name }}
                    </div>
                    <div class="detail-item">
                        <strong>Last Name:</strong> {{ selectedParent.last_name }}
                    </div>
                    <div class="detail-item">
                        <strong>Phone Number One:</strong> {{ selectedParent.phone_number_one }}
                    </div>
                    <div class="detail-item">
                        <strong>Phone Number Two:</strong> {{ selectedParent.phone_number_two }}
                    </div>
                    <div class="detail-item">
                        <strong>City:</strong> {{ selectedParent.city }}
                    </div>
                    <div class="detail-item">
                        <strong>Address:</strong> {{ selectedParent.address }}
                    </div>
                </div>
                <div class="parent-images">
                    <div class="image-item">
                        <strong>ID Image:</strong>
                        <img :src="getImageUrl(selectedParent.id_image)" alt="ID Image" class="responsive-image">
                    </div>
                    <div class="image-item">
                        <strong>Passport Image:</strong>
                        <img :src="getImageUrl(selectedParent.passport_image)" alt="Passport Image" class="responsive-image">
                    </div>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script>
import { Document } from '@element-plus/icons-vue'
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',
});

export default {
    name: "Parents",
    components: {
        Document
    },
    data() {
        return {
            dialogVisible: false,
            showDialogVisible: false,
            selectedParent: null,
            form: {
                user_id: null,
                first_name: '',
                last_name: '',
                phone_number_one: '',
                phone_number_two: '',
                city: '',
                address: '',
                id_image: '',
                id_image_list: [],
                passport_image: '',
                passport_image_list: [],
                images_info: null
            },
            tableData: []
        };
    },
    methods: {
        getImageUrl(imagePath) {
            return `http://localhost:8000/storage/${imagePath}`;
        },
        async submitForm() {
            const formData = new FormData();
            formData.append('user_id', this.form.user_id);
            formData.append('first_name', this.form.first_name);
            formData.append('last_name', this.form.last_name);
            formData.append('phone_number_one', this.form.phone_number_one);
            formData.append('phone_number_two', this.form.phone_number_two);
            formData.append('city', this.form.city);
            formData.append('address', this.form.address);
            formData.append('id_image', this.form.id_image);
            formData.append('passport_image', this.form.passport_image);

            try {
                await api.post('/parents', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.$message.success('Parent added successfully');
                this.resetForm();
                this.fetchParents();
            } catch (error) {
                this.$message.error('Failed to add parent');
            }
        },
        async fetchParents() {
            try {
                const response = await api.get('/parents');
                this.tableData = response.data;
            } catch (error) {
                this.$message.error('Failed to fetch parents');
            }
        },
        async showParent(parent) {
            try {
                const response = await api.get(`/parents/${parent.id}`);
                this.selectedParent = response.data;
                this.showDialogVisible = true;
            } catch (error) {
                this.$message.error('Failed to fetch parent details');
            }
        },
        async editParent(parent) {
            this.dialogVisible = true;
            this.form = { ...parent };
            // Handle file lists for images
            this.form.id_image_list = parent.id_image ? [{ name: parent.id_image, url: `http://localhost:8000/storage/${parent.id_image}` }] : [];
            this.form.passport_image_list = parent.passport_image ? [{ name: parent.passport_image, url: `http://localhost:8000/storage/${parent.passport_image}` }] : [];
        },
        async deleteParent(id) {
            try {
                await api.delete(`/parents/${id}`);
                this.$message.success('Parent deleted successfully');
                this.fetchParents();
            } catch (error) {
                this.$message.error('Failed to delete parent');
            }
        },
        resetForm() {
            this.form = {
                user_id: null,
                first_name: '',
                last_name: '',
                phone_number_one: '',
                phone_number_two: '',
                city: '',
                address: '',
                id_image: '',
                id_image_list: [],
                passport_image: '',
                passport_image_list: [],
                images_info: null
            };
            this.dialogVisible = false;
        },
        handlePreview(file) {
            window.open(file.url);
        },
        handleRemove(file, fileList) {
            console.log(file, fileList);
        },
        beforeUploadIDImage(file) {
            this.form.id_image = file;
            this.form.id_image_list = [file];
            return false; // Prevent auto upload
        },
        beforeUploadPassportImage(file) {
            this.form.passport_image = file;
            this.form.passport_image_list = [file];
            return false; // Prevent auto upload
        }
    },
    mounted() {
        this.fetchParents();
    }
};
</script>





  <style>
.bg-header-table{
    background-image: linear-gradient(to top, #5f72bd 0%, #9b23ea 100%);
}


.parent-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.parent-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.detail-item {
    padding: 10px;
    background-color: #f5f5f5;
    border-radius: 5px;
}

.parent-images {
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