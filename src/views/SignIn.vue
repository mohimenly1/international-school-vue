<template>
  <div
    class="page-header align-items-start min-vh-100"
    style="
      background-image: url('https://app.caledonian.ly/assets/header-school2.fb9ca148.jpg');
    "
  >
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
      <div class="row">
        <div class="col-lg-4 col-md-8 col-12 mx-auto">
          <div class="card z-index-0 fadeIn3 fadeInBottom">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div
                class="bg-gradient-successed shadow-success border-radius-lg py-3 pe-1"
              >
                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">
                  Sign in
                </h4>
              </div>
            </div>
            <div class="card-body">
              <form @submit.prevent="handleSubmit" role="form" class="text-start mt-3">
                <div class="mb-3">
                  <div class="form-control">
                    <input v-model="login"  type="text" required="">
                    <label>
                      <span style="transition-delay:0ms">L</span><span style="transition-delay:50ms">o</span><span style="transition-delay:100ms">g</span><span style="transition-delay:150ms">i</span><span style="transition-delay:200ms">n</span>
                    </label>
                </div>
                </div>
                <div class="mb-3">
                  <div class="form-control">
                    <input v-model="password" type="password" required="">
                    <label>
                        <span style="transition-delay:0ms">P</span><span style="transition-delay:50ms">a</span><span style="transition-delay:100ms">s</span><span style="transition-delay:150ms">s</span><span style="transition-delay:200ms">w</span><span style="transition-delay:200ms">o</span><span style="transition-delay:200ms">r</span><span style="transition-delay:200ms">d</span>
                    </label>
                </div>
                </div>
                <material-switch id="rememberMe" name="rememberMe">Remember me</material-switch>
                <div class="text-center">
                  <button class="my-4 mb-2 my-btn">Sign in</button>
                </div>
                <div v-if="error" class="alert alert-danger">{{ error }}</div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer class="footer position-absolute bottom-2 py-2 w-100">
      <div class="container">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-12 col-md-6 my-auto">
            <div class="copyright text-center text-sm text-white text-lg-start">
              © {{ new Date().getFullYear() }}, made with
              <i class="fa fa-heart" aria-hidden="true"></i> by
              <a
                href="https://caledonian.ly"
                class="font-weight-bold text-white"
                target="_blank"
                >Caledonian IT Team</a
              >
              for a better web.
            </div>
          </div>
          <div class="col-12 col-md-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="https://caledonian.ly" class="nav-link text-white" target="_blank">Creative Tim</a>
              </li>
              <li class="nav-item">
                <a href="https://caledonian.ly" class="nav-link text-white" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="https://caledonian.ly" class="nav-link text-white" target="_blank">Blog</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import axios from 'axios';
import MaterialSwitch from "@/components/MaterialSwitch.vue";
import { mapMutations } from "vuex";

export default {
  name: "sign-in",
  components: {
    MaterialSwitch,
  },
  data() {
    return {
      login: '',
      password: '',
      error: null,
    };
  },
  beforeMount() {
    this.toggleEveryDisplay();
    this.toggleHideConfig();
  },
  beforeUnmount() {
    this.toggleEveryDisplay();
    this.toggleHideConfig();
  },
  methods: {
    ...mapMutations(["toggleEveryDisplay", "toggleHideConfig", "SET_AUTH"]),
    async handleSubmit() {
      try {
        this.error = null;
        const response = await axios.post('http://127.0.0.1:8000/api/login', {
          login: this.login,
          password: this.password,
        });
        this.$router.push({ name: 'Dashboard' });
        const { token, user } = response.data;
        localStorage.setItem('access_token', token);
        localStorage.setItem('user_type', user.user_type);
        this.SET_AUTH({ token, userType: user.user_type });
        
      } catch (error) {
        this.error = error.response?.data?.message || 'Invalid login credentials';
      }
    },
  },
};
</script>

<style lang="css" scoped>
.bg-gradient-successed {
  background-image: radial-gradient(circle 382px at 50% 50.2%, rgba(73,76,212,1) 0.1%, rgba(3,1,50,1) 100.2%);
}

.my-btn {
  width: 30%;
  background-image: radial-gradient(circle 382px at 50% 50.2%, rgba(73,76,212,1) 0.1%, rgba(3,1,50,1) 100.2%);
  color: azure;
  border: none;
  border-radius: 10px;
}

.form-control {
  position: relative;
  margin: 20px 0 40px;
  width: 190px;
}

.form-control input {
  background-color: transparent;
  border: 0;
  border-bottom: 2px radial-gradient(circle 382px at 50% 50.2%, rgba(73,76,212,1) 0.1%, rgba(3,1,50,1) 100.2%) solid;
  display: block;
  width: 100%;
  padding: 15px 0;
  font-size: 18px;
  color: radial-gradient(circle 382px at 50% 50.2%, rgba(73,76,212,1) 0.1%, rgba(3,1,50,1) 100.2%);
}

.form-control input:focus,
.form-control input:valid {
  outline: 0;
  border-bottom-color: radial-gradient(circle 382px at 50% 50.2%, rgba(73,76,212,1) 0.1%, rgba(3,1,50,1) 100.2%);
}

.form-control label {
  position: absolute;
  top: 15px;
  left: 0;
  pointer-events: none;
}

.form-control label span {
  display: inline-block;
  font-size: 18px;
  min-width: 5px;
  color: radial-gradient(circle 382px at 50% 50.2%, rgba(73,76,212,1) 0.1%, rgba(3,1,50,1) 100.2%);
  transition: 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.form-control input:focus+label span,
.form-control input:valid+label span {
  color: radial-gradient(circle 382px at 50% 50.2%, rgba(73,76,212,1) 0.1%, rgba(3,1,50,1) 100.2%);
  transform: translateY(-30px);
}
</style>
