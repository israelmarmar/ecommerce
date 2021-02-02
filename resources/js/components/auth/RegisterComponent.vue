<template>
  <div>
    <nav class="navbar navbar-light bg-light justify-content-end">
      <a class="nav-item nav-link" href="login">Login</a>
    </nav>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Register</div>

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
                    for="name"
                    class="col-md-4 col-form-label text-md-right"
                    >Name</label
                  >

                  <div class="col-md-6">
                    <input
                      id="name"
                      type="text"
                      class="form-control"
                      v-model="name"
                      name="name"
                      autocomplete="name"
                      autofocus
                    />
                  </div>
                </div>

                <div class="form-group row">
                  <label
                    for="email"
                    class="col-md-4 col-form-label text-md-right"
                    >E-Mail Address</label
                  >

                  <div class="col-md-6">
                    <input
                      id="email"
                      type="email"
                      class="form-control"
                      v-model="email"
                      name="email"
                      autocomplete="email"
                    />
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
                      autocomplete="new-password"
                    />
                  </div>
                </div>

                <div class="form-group row">
                  <label
                    for="password-confirm"
                    class="col-md-4 col-form-label text-md-right"
                    >Confirm Password</label
                  >

                  <div class="col-md-6">
                    <input
                      id="password-confirm"
                      type="password"
                      class="form-control"
                      v-model="password_confirmation"
                      name="password_confirmation"
                      autocomplete="new-password"
                    />
                  </div>
                </div>

                <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      Register
                    </button>
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
const urlRegister = baseURL + "auth/register/";
const urlProducts = "products";
const token = localStorage.getItem("token");

export default {
  data() {
    return {
      name: null,
      email: null,
      password: null,
      password_confirmation: null,
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

      if (!this.email) {
        this.errors.push("Email is required.");
      }
      if (!this.password) {
        this.errors.push("Password is required.");
      }
      if (!this.password_confirmation) {
        this.errors.push("Password confirmation is required.");
      }
      if (this.password != this.password_confirmation) {
        return this.errors.push("Passwords don't match.");
      }

      if (this.email && this.password) {

        const response = await fetch(urlRegister, {
          method: "POST",
          body: new URLSearchParams({
            name: this.name,
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
    },
  },
};
</script>
