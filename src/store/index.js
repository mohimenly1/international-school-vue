// store/index.js
import { createStore } from "vuex";
import { login, logout, register } from '@/api';

export default createStore({
  state: {
    hideConfigButton: false,
    isPinned: true,
    showConfig: false,
    sidebarType: "bg-gradient-dark",
    isRTL: false,
    color: "success",
    isAuthenticated: !!localStorage.getItem('access_token'),
    userType: localStorage.getItem('user_type') || '', 
    token: localStorage.getItem('access_token') || '',
    isNavFixed: false,
    isAbsolute: false,
    showNavs: true,
    showSidenav: true,
    showNavbar: true,
    showFooter: true,
    showMain: true,
    isDarkMode: false,
    navbarFixed:
      "position-sticky blur shadow-blur left-auto top-1 z-index-sticky px-0 mx-4",
    absolute: "position-absolute px-4 mx-0 w-100 z-index-2",
  },
  mutations: {
    toggleConfigurator(state) {
      state.showConfig = !state.showConfig;
    },
    navbarMinimize(state) {
      const sidenav_show = document.querySelector(".g-sidenav-show");

      if (sidenav_show.classList.contains("g-sidenav-pinned")) {
        sidenav_show.classList.remove("g-sidenav-pinned");
        state.isPinned = true;
      } else {
        sidenav_show.classList.add("g-sidenav-pinned");
        state.isPinned = false;
      }
    },
    navbarFixed(state) {
      state.isNavFixed = !state.isNavFixed;
    },
    toggleEveryDisplay(state) {
      state.showNavbar = !state.showNavbar;
      state.showSidenav = !state.showSidenav;
      state.showFooter = !state.showFooter;
    },
    toggleHideConfig(state) {
      state.hideConfigButton = !state.hideConfigButton;
    },
    color(state, payload) {
      state.color = payload;
    },
    SET_AUTH(state, {token,user}) {
      state.isAuthenticated = true;
      state.token = token;
      localStorage.setItem('access_token', token);
      localStorage.setItem('user_type', user.user_type);
      state.showSidenav = true;
      state.showNavbar = true;
      state.showConfig = true;
      state.showFooter = false;
    },
    LOGOUT(state) {
      state.isAuthenticated = false;
      state.token = '';
      localStorage.removeItem('access_token');
      localStorage.removeItem('user_type');
      state.showSidenav = false;
      state.showConfig = false;
      state.showNavbar = false;
      state.showFooter = false;
    },
    HIDE_ALL(state) {
      state.showNavs = false;
      state.showSidenav = false;
      state.showNavbar = false;
      state.showFooter = false;
      state.showMain = false;
    },
    SHOW_ALL(state) {
      state.showNavs = true;
      state.showSidenav = true;
      state.showNavbar = true;
      state.showFooter = true;
      state.showMain = true;
    }
  },
  actions: {
    setColor({ commit }, payload) {
      commit("color", payload);
    },
    async login({ commit }, credentials) {
      const { access_token,userType } = await login(credentials.login, credentials.password);
      commit('SET_AUTH', access_token, userType);
    },
    async register(_, userData) { // Removed 'commit' as it's not used
      await register(userData);
    },
    async logout({ commit }) {
      await logout();
      commit('LOGOUT');
    },
    initializeAuth({ commit }) {
      const token = localStorage.getItem('access_token');
      const userType = localStorage.getItem('user_type'); // Get userType from localStorage
      if (token && userType) {
        commit('SET_AUTH', { token, user: { user_type: userType } }); // Initialize with user_type
      }
    },
    hideAllElements({ commit }) {
      commit('HIDE_ALL');
    },
    showAllElements({ commit }) {
      commit('SHOW_ALL');
    }
  },
  getters: {
    userType: state => state.userType,
  },
});
