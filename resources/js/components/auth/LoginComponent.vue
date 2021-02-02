<template>
  <div>
    <nav class="navbar navbar-light bg-light justify-content-end">
      <a class="nav-item nav-link" href="register">Register</a>
    </nav>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Login</div>

            <div class="card-body">
              <form @submit="checkForm" v-on:submit.prevent>
                <div v-for="error in errors" v-bind:key="error">
                  <div class="alert alert-danger" role="alert">
                    {{ error }}
                  </div>
                  <br />
                </div>
                <div class="form-group row">
                  <label
                    for="email"
                    class="col-md-4 col-form-label text-md-right"
                    >E-Mail</label
                  >

                  <div class="col-md-6">
                    <input
                      id="email"
                      type="email"
                      class="form-control"
                      v-model="email"
                      name="email"
                      autocomplete="email"
                      autofocus
                    />
                    <span class="invalid-feedback" role="alert">
                      <strong></strong>
                    </span>
                  </div>
                </div>

                <div class="form-group row">
                  <label
                    for="password"
                    class="col-md-4 col-form-label text-md-right"
                    >Password</label
                  >

                  <div class="col-md-6">
                    <input
                      id="password"
                      type="password"
                      class="form-control"
                      v-model="password"
                      name="password"
                      autocomplete="current-password"
                    />

                    <span class="invalid-feedback" role="alert">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="form-group row mb-0">
                  <div class="col-md-8 offset-md-4">
                    <input
                      type="submit"
                      class="btn btn-primary"
                      value="Login"
                    />
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const baseURL = "http://127.0.0.1:8000/api/";
const urlProducts = "products";
const urlLogin = baseURL + "auth/login/";
const token = localStorage.getItem("token");

export default {
  data() {
    return {
      email: null,
      password: null,
      errors: [],
    };
  },
  mounted() {
    if (token) return this.$router.push({ name: "Products" });

    console.log("Component mounted.");
  },
  methods: {
    checkForm: async function (e) {
      this.errors = [];
      if (this.email && this.password) {

        const response = await fetch(urlLogin, {
          method: "POST",
          body: new URLSearchParams({
            email: this.email,
            password: this.password,
          }),
        });

        console.log(response);

        if (response.ok) {
          const data = await response.json();
          console.log(data.access_token);
          localStorage.setItem("token", data.access_token);
          this.$router.go(urlProducts);
        } else {
          this.errors.push("Email or password incorrect.");
        }
      }

      if (!this.email) {
        this.errors.push("Email is required.");
      }
      if (!this.password) {
        this.errors.push("Password is required.");
      }
    },
  },
};
</script>
