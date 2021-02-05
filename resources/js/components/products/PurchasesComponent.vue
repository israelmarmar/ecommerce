<template>
  <div>
    <nav class="navbar navbar-light bg-light justify-content-end">
      <a class="nav-item nav-link" v-if="token" @click="productsLink" href="#"
        >Products</a
      >
      <a class="nav-item nav-link" v-if="token" @click="cartLink" href="#"
        >Cart</a
      >
      <a class="nav-item nav-link" v-if="token" @click="logout" href="#"
        >Logout</a
      >
    </nav>
    <h1 id="heading">Ecommerce - Purchases</h1>
    <div id="app" class="container">
      <h4 v-if="token">My purchases</h4>
      <p v-if="token && (!purchaseItems || purchaseItems.length === 0)">
        Loading...
      </p>
      <div
        class="purchases_box"
        v-bind:key="index"
        v-for="(purchaseItem, index) of purchaseItems"
      >
        <div class="purchase_item">
          <div class="row">
            <div class="col-4">
              <h2 class="product_name">Purchase: ${{ purchaseItem.cart.total / 100 }}</h2>
              <p class="product_price">
                {{ status[purchaseItem.status] }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const baseURL = "http://127.0.0.1:8000/api/";
const urlGetPurchases = baseURL + "purchase/";
const urlLogin = "login";

export default {
  data() {
    return {
      purchaseItems: null,
      token: null,
      status: ["pending", "processing", "concluded"]
    };
  },
  methods: {
    productsLink: function () {
      this.$router.push({ name: "Products" });
    },
    logout: function () {
      localStorage.removeItem("token");
      window.location.href = urlLogin;
    },
    logout: function () {
      localStorage.removeItem("token");
      window.location.href = urlLogin;
    },
    cartLink: function () {
      this.$router.push({ name: "Cart" });
    },
  },
  mounted: async function mounted() {
      this.token = localStorage.getItem("token");
      let user;

      if (this.token) {
        const response = await fetch(urlGetPurchases, {
          method: "GET",
          headers: {
            Authorization: `bearer ${this.token}`,
          },
        });
        if (response.ok) {
          this.purchaseItems = await response.json();
        } else {
          response.text().then((text) => console.log(text));
          throw new Error("Unauthorized");
        }
      }
    },
};
</script>

<style scoped>
html,
body {
  font-family: "Lato", "Arial", sans-serif;
  font-weight: 500;
}

#buy-button {
  height: 36px;
  background: #0df705;
  border-radius: 4px 4px 4px 4px;
  color: white;
  border: 0;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0px 4px 5.5px 0px rgb(0 0 0 / 7%);
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

.purchase_item {
  background-color: rgb(246, 241, 245);
  border-radius: 10px;
  margin: 20px 0;
  padding: 10px 10px;
}

.purchase_quantity {
  font-size: 20px;
}

.purchase_name {
  font-size: 40px;
}
.purchase_name:hover {
  color: rgb(43, 107, 226);
  font-size: 40px;
}

.purchase_price {
  font-size: 20px;
  font-weight: 500;
  color: red;
}

.purchase_price span {
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