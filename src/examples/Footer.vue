<template>
  <footer class="py-3 footer">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        <div class="mb-4 col-lg-12 mb-lg-0">
          <div
            class="text-sm text-center copyright text-muted"
            :class="$store.state.isRTL ? 'text-lg-center' : 'text-lg-center'"
          >
            ©
            {{ new Date().getFullYear() }}, made with
            <i class="fa fa-heart"></i> by
            <a
              href="https://www.creative-tim.com"
              class="font-weight-bold"
              target="_blank"
              >Caledonian IT Team</a
            >
            for a better web.
          </div>
        </div>
        
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "app-footer",
};
</script>
