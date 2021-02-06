<template>
  <div>
    <nav class="navbar navbar-light bg-light justify-content-end">
      <a class="nav-item nav-link" v-if="token" @click="productsLink" href="#"
        >Products</a
      >
      <a class="nav-item nav-link" v-if="token" @click="logout" href="#"
        >Logout</a
      >
    </nav>
    <h1 id="heading">Ecommerce - Orders</h1>
    <div id="app" class="container">
      <h4 v-if="token">Orders</h4>
      <p v-if="token && (!orders || orders.length === 0)">Loading...</p>
      <div
        class="products_box"
        v-bind:key="index"
        v-for="(orderItem, index) of orders"
      >
        <div class="order_item">
          <div
            class="order_item"
            v-bind:key="index"
            v-for="(productItem, index) of orderItem.products"
          >
            <div class="row">
              <div class="col-4">
                <img
                  class="img-fluid"
                  v-bind:src="productItem.product.image"
                  alt="product-image"
                />
              </div>
              <div class="col-8">
                <h2 class="product_name">{{ productItem.product.name }}</h2>
                <h3>Quantity: {{ productItem.quantity }}</h3>
              </div>
            </div>
          </div>
          <h6>Buyer: {{ orderItem.purchase.buyer_email }}</h6>
          <h6>Status: {{ status[orderItem.purchase.status] }}</h6>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { loadStripe } from "@stripe/stripe-js";
const stripePromise = loadStripe(
  "pk_test_51IGpuIJMHnUuOtXFuebAUMSAgJiN98NLJ7ky9ukEkZA1rYdagyE4n3vumFmWUoqsfbNisO05Y82JPMEGAaqZaa1t00kOtvAyhd"
);
const baseURL = "http://127.0.0.1:8000/api/";
const urlLogin = "login";
const urlGetOrders = baseURL + "orders/";
const urlGetUser = baseURL + "auth/profile/";
const urlBuy = baseURL + "purchase/add";

export default {
  data() {
    return {
      orders: null,
      tempName: "",
      tempPrice: "",
      tempImage: "",
      tempQuantity: "",
      token: null,
      loading: false,
      status: ["unpaid", "processing", "paid"],
    };
  },
  methods: {
    getOrders: async function () {
      this.token = localStorage.getItem("token");
      let user;

      if (this.token) {
        user = await (
          await fetch(urlGetUser, {
            method: "GET",
            headers: {
              Authorization: `bearer ${this.token}`,
            },
          })
        ).json();

        const response = await fetch(urlGetOrders, {
          method: "GET",
          headers: {
            Authorization: `bearer ${this.token}`,
          },
        });
        if (response.ok) {
          this.orders = await response.json();
        } else {
          throw new Error("Unauthorized");
        }
      }
    },
    productsLink: function () {
      this.$router.push({ name: "Products" });
    },
    logout: function () {
      localStorage.removeItem("token");
      window.location.href = urlLogin;
    },
  },

  mounted: async function mounted() {
    this.getOrders();
  },
};
</script>

<style scoped>
html,
body {
  font-family: "Lato", "Arial", sans-serif;
  font-weight: 500;
}

.disabled {
  opacity: 0.5;
}

#heading {
  text-align: center;
  font-family: "Courgette", cursive;
  font-size: 100;
  color: rgb(218, 216, 212);
  background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6));
  background-attachment: fixed;
  background-size: cover;
  background-repeat: no-repeat;
  padding: 180px 0;
  margin-bottom: 20px;
}

img {
  border-radius: 10px;
  margin: 10px;
}

.quantity {
  margin: 10px;
  font-size: 15px;
  font-weight: bold;
}

.order_item {
  background-color: rgb(246, 241, 245);
  border-radius: 10px;
  margin: 20px 0;
  padding: 10px 10px;
}

.product_quantity {
  font-size: 20px;
}

.product_name {
  font-size: 40px;
}
.product_name:hover {
  color: rgb(43, 107, 226);
  font-size: 40px;
}

.product_price {
  font-size: 20px;
  font-weight: 500;
  color: red;
}

.product_price span {
  font-size: 15px;
}

.plusminus_btn {
  float: right;
  margin: 10px 10px 0 0;
}

.delete_btn {
  float: right;
  margin: 10px 10px 0 0;
  position: relative;
  top: 70%;
  left: 100px;
}

.buy_btn {
  margin-top: 68px;
}

.btn:focus {
  outline: none;
  box-shadow: none;
}
</style>