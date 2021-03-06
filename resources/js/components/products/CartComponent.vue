<template>
  <div>
    <nav class="navbar navbar-light bg-light justify-content-end">
      <a class="nav-item nav-link" v-if="token" @click="productsLink" href="#"
        >Products</a
      >
      <a class="nav-item nav-link" v-if="user && user.role == 'admin'" @click="ordersLink" href="#"
        >Orders</a
      >
      <a class="nav-item nav-link" v-if="user && user.role == 'buyer'" @click="purchasesLink" href="#"
        >Purchases</a
      >
      <a class="nav-item nav-link" v-if="token" @click="logout" href="#"
        >Logout</a
      >
    </nav>
    <h1 id="heading">Ecommerce - Cart</h1>
    <div id="app" class="container">
      <h4 v-if="token">My cart</h4>
      <p v-if="token && (!productItems || productItems.length === 0)">
        Loading...
      </p>
      <div
        class="products_box"
        v-bind:key="index"
        v-for="(productItem, index) of productItems"
      >
        <div class="product_item" v-if="productItem.quantity > 0">
          <div class="row">
            <div class="col-4">
              <img
                class="img-fluid"
                v-bind:src="productItem.product.image"
                alt="product-image"
              />
            </div>
            <div class="col-8">
              <div class="row plusminus_btn">
                <button
                  class="btn btn-primary"
                  @click="decrementProduct(index)"
                >
                  <span>-</span>
                </button>
                <div class="quantity">
                  {{ productItem.quantity }}
                </div>
                <button
                  class="btn btn-primary"
                  @click="incrementProduct(index)"
                >
                  <span>+</span>
                </button>
              </div>

              <button
                @click="deleteProduct(index)"
                class="btn btn-danger delete_btn"
              >
                <span>Delete</span>
              </button>
              <h2 class="product_name">{{ productItem.product.name }}</h2>
              <h3 class="product_price">
                <span>$</span>{{ productItem.product.price }}
              </h3>
            </div>
          </div>
        </div>
      </div>
      <button
        :class="['btn', 'btn-success', loading ? 'disabled' : null]"
        id="buy-button"
        @click="buy"
        :disabled="loading"
        v-if="productItems && productItems.length > 0"
      >
        <div class="spinner-border text-light" role="status" v-if="loading"></div>
          Buy Now
      </button>
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
const urlGetProduct = baseURL + "cart/";
const urlIncrement = urlGetProduct + "increment/";
const urlDecrement = urlGetProduct + "decrement/";
const urlDelete = urlGetProduct + "delete/";
const urlGetUser = baseURL + "auth/profile/";
const urlBuy = baseURL + "purchase/add";

export default {
  data() {
    return {
      productItems: null,
      tempName: "",
      tempPrice: "",
      tempImage: "",
      tempQuantity: "",
      token: null,
      loading: false,
      user: null
    };
  },
  methods: {
    buy: async function () {
      this.loading = true;
      const stripe = await stripePromise;
      const response = await fetch(urlBuy, {
        method: "POST",
        headers: {
              Authorization: `bearer ${this.token}`,
            },
      });
      const text = await response.text();
      console.log(text);
      const session = JSON.parse(text);
      // When the customer clicks on the button, redirect them to Checkout.
      const result = await stripe.redirectToCheckout({
        sessionId: session.id,
      });
      if (result.error) {
        console.log(error);
      }
    },
    getProducts: async function () {
      this.token = localStorage.getItem("token");

      if (this.token) {
        this.user = await (
          await fetch(urlGetUser, {
            method: "GET",
            headers: {
              Authorization: `bearer ${this.token}`,
            },
          })
        ).json();

        const response = await fetch(urlGetProduct, {
          method: "GET",
          headers: {
            Authorization: `bearer ${this.token}`,
          },
        });
        if (response.ok) {
          this.productItems = await response.json();
          this.productItems.forEach((productItem) => {
            productItem.product.price = productItem.product.price / 100;
          });
        } else {
          throw new Error("Unauthorized");
        }
      }
    },
    productsLink: function () {
      this.$router.push({ name: "Products" });
    },
    ordersLink: function () {
      this.$router.push({ name: "Orders" });
    },
    purchasesLink: function () {
      this.$router.push({ name: "Purchases" });
    },
    logout: function () {
      localStorage.removeItem("token");
      window.location.href = urlLogin;
    },
    incrementProduct: async function (index) {
      const response = await fetch(
        urlIncrement + `${this.productItems[index].id}`,
        {
          method: "GET",
          headers: {
            Authorization: `bearer ${this.token}`,
          },
        }
      );
      if (response.ok) {
        this.productItems[index].quantity =
          this.productItems[index].quantity + 1;
      } else {
        response.text().then((text) => console.log(text));
        alert("Something went wrong!");
      }
    },
    decrementProduct: async function (index) {
      const response = await fetch(
        urlDecrement + `${this.productItems[index].id}`,
        {
          method: "GET",
          headers: {
            Authorization: `bearer ${this.token}`,
          },
        }
      );
      if (response.ok) {
        this.productItems[index].quantity =
          this.productItems[index].quantity - 1;
      } else {
        response.text().then((text) => console.log(text));
        alert("Something went wrong!");
      }
    },
    deleteProduct: async function (index) {
      const response = await fetch(
        urlDelete + `${this.productItems[index].id}`,
        {
          method: "POST",
          headers: {
            Authorization: `bearer ${this.token}`,
          },
        }
      );
      if (response.ok) {
        this.productItems[index].quantity = 0;
      } else {
        response.text().then((text) => console.log(text));
        alert("Something went wrong!");
      }
    },
  },

  mounted: async function mounted() {
    this.getProducts();
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
  opacity:0.5;
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

.product_item {
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