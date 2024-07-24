<!--
=========================================================
* Vue Material Dashboard 2 - v3.1.0
=========================================================

* Product Page: https://creative-tim.com/product/vue-material-dashboard-2
* Copyright 2023 Creative Tim (https://www.creative-tim.com)

Coded by www.creative-tim.com

 =========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<template>
  <sidenav
    :custom_class="color"
    :class="[isRTL ? 'fixed-end' : 'fixed-start']"
    v-if="showSidenav"
  />
  <main
    class="main-content position-relative max-height-vh-100 h-100 overflow-x-hidden"
  >
    <!-- nav -->
    <navbar
      :class="[isNavFixed ? navbarFixed : '', isAbsolute ? absolute : '']"
      :color="isAbsolute ? 'text-white opacity-8' : ''"
      :minNav="navbarMinimize"
      v-if="showNavbar"
    />
    <router-view />
    <app-footer v-show="showFooter" />
    <configurator
      :toggle="toggleConfigurator"
      :class="[showConfig ? 'show' : '', hideConfigButton ? 'd-none' : '']"
    />
  </main>
</template>
<script>

import Sidenav from "./examples/Sidenav";
import Configurator from "@/examples/Configurator.vue";
import Navbar from "@/examples/Navbars/Navbar.vue";
import AppFooter from "@/examples/Footer.vue";
import { mapMutations, mapState } from "vuex";

export default {
  name: "App",
  components: {
    Sidenav,
    Configurator,
    Navbar,
    AppFooter,
  },
  methods: {
    ...mapMutations(["toggleConfigurator", "navbarMinimize"]),
  },
  computed: {
    ...mapState([
      "isRTL",
      "color",
      "isAbsolute",
      "isNavFixed",
      "navbarFixed",
      "absolute",
      "showSidenav",
      "showNavbar",
      "showFooter",
      "showConfig",
      "hideConfigButton",
    ]),
  },
  // beforeMount() {
  //   this.$store.state.isTransparent = "bg-transparent";

  //   const sidenav = document.getElementsByClassName("g-sidenav-show")[0];

  //   if (window.innerWidth > 1200) {
  //     sidenav.classList.add("g-sidenav-pinned");
  //   }

  //   this.$store.state.isRTL = true;
  //   document.querySelector("html").setAttribute("lang", "ar");
  //   document.querySelector("html").setAttribute("dir", "rtl");
  //   document.querySelector("#app").classList.add("rtl");
  // },


  // beforeUnmount() {
  //   this.$store.state.isRTL = false;
  //   document.querySelector("html").removeAttribute("lang");
  //   document.querySelector("html").removeAttribute("dir");
  //   document.querySelector("#app").classList.remove("rtl");
  // },
};
</script>
<style>
.bg-aside{

  background-image: linear-gradient(to top, #cd9cf2 0%, #f6f3ff 100%);

}

.navbar-vertical .navbar-nav .nav-link{

  background: -webkit-linear-gradient(#ff5b5b, #02006b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.text-logo{
  background: -webkit-linear-gradient(#b4a0ff, #02006b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>