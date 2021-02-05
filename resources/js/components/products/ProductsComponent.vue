<template>
  <div>
    <nav class="navbar navbar-light bg-light justify-content-end">
      <a class="nav-item nav-link" v-if="token" @click="cartLink" href="#"
        >Cart</a
      >
      <a class="nav-item nav-link" v-if="token" @click="purchasesLink" href="#"
        >Purchases</a
      >
      <a class="nav-item nav-link" v-if="token" @click="logout" href="#"
        >Logout</a
      >
    </nav>
    <h1 id="heading">Ecommerce</h1>
    <div id="app" class="container">
      <button
        type="button"
        @click="addProductButtonPressed"
        v-if="token"
        class="btn btn-success add-btn"
      >
        Add a new Product
      </button>
      <form v-show="showAddForm">
        <div class="form-group">
          <label>Name</label>
          <input
            type="text"
            class="form-control"
            placeholder="Enter name"
            required
            v-model="tempName"
          />
        </div>
        <div class="form-group">
          <label>Quantity</label>
          <input
            type="number"
            class="form-control"
            placeholder="Enter quantity"
            required
            v-model="tempQuantity"
          />
        </div>
        <div class="form-group">
          <label>Image</label>
          <input
            type="url"
            class="form-control"
            placeholder="Enter image"
            required
            v-model="tempImage"
          />
        </div>
        <div class="form-group">
          <label>Price</label>
          <input
            type="number"
            class="form-control"
            placeholder="Enter price"
            required
            v-model="tempPrice"
          />
        </div>
        <button @click="addProduct" type="button" class="btn btn-primary">
          Submit
        </button>
      </form>
      <h4 v-if="token">My products</h4>
      <p v-if="token && (!products || products.length === 0)">
        Your catalog is empty
      </p>
      <div
        class="products_box"
        v-bind:key="index"
        v-for="(product, index) of products"
      >
        <div class="product_item">
          <div class="row">
            <div class="col-4">
              <img
                class="img-fluid"
                v-bind:src="product.image"
                alt="product-image"
              />
            </div>
            <div class="col-8">
              <button
                @click="editButtonPressed(index)"
                class="btn btn-primary edit_btn"
              >
                <span v-if="!product.showEditForm">Edit</span>
                <span v-else>Cancel</span>
              </button>
              <button
                @click="deleteProduct(index)"
                class="btn btn-danger delete_btn"
              >
                <span>Delete</span>
              </button>
              <h2 class="product_name">{{ product.name }}</h2>
              <h3 class="product_quantity">Quantity: {{ product.quantity }}</h3>
              <h3 class="product_price"><span>$</span>{{ product.price }}</h3>
            </div>
          </div>
          <div>
            <form v-show="product.showEditForm">
              <div class="form-group">
                <label>Name</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Enter name"
                  required
                  v-model="tempName"
                />
              </div>
              <div class="form-group">
                <label>Quantity</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Enter quantity"
                  required
                  v-model="tempQuantity"
                />
              </div>
              <div class="form-group">
                <label>Image</label>
                <input
                  type="url"
                  class="form-control"
                  placeholder="Enter image"
                  required
                  v-model="tempImage"
                />
              </div>
              <div class="form-group">
                <label>Price</label>
                <input
                  type="number"
                  class="form-control"
                  placeholder="Enter price"
                  required
                  v-model="tempPrice"
                />
              </div>
              <button
                @click="editProduct(index)"
                type="button"
                class="btn btn-primary"
              >
                Submit
              </button>
            </form>
          </div>
        </div>
      </div>

      <h4>All products</h4>
      <p v-if="!allProducts || allProducts.length === 0">
        There are no products
      </p>
      <div
        class="products_box"
        v-bind:key="index"
        v-for="(product, index) of allProducts"
      >
        <div class="product_item">
          <div class="row">
            <div class="col-4">
              <img
                class="img-fluid"
                v-bind:src="product.image"
                alt="product-image"
              />
            </div>
            <div class="col-8">
              <h2 class="product_name">{{ product.name }}</h2>
              <h3 class="product_quantity">Quantity: {{ product.quantity }}</h3>
              <h3 class="product_price"><span>$</span>{{ product.price }}</h3>
              <button
                type="button"
                class="buy_btn btn btn-lg btn-outline-success"
                @click="addToCart(index)"
                v-if="!product.addedToCart"
              >
                Add to cart
              </button>
              <button
                type="button"
                class="buy_btn btn btn-lg btn-outline-success"
                v-else
              >
                <img :src="checkImg" />Added to chart
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const baseURL = "http://127.0.0.1:8000/api/";
const urlLogin = "login";
const urlGetProduct = baseURL + "product/";
const urlGetCart = baseURL + "cart/";
const urlGetUser = baseURL + "auth/profile/";
const urlGetAllProduct = baseURL + "allproduct/";
const urlUpdateProduct = baseURL + "product/update/";
const urlDeleteProduct = baseURL + "product/delete/";
const urlAddProduct = baseURL + "product/add";
const urlAddToCart = baseURL + "cart/add";

export default {
  data() {
    return {
      products: null,
      allProducts: null,
      showAddForm: false,
      tempName: "",
      tempPrice: "",
      tempImage: "",
      tempQuantity: "",
      token: null,
      cart: null,
      checkImg: require("../../../../public/img/check.svg").default,
    };
  },
  methods: {
    cartLink: function () {
      this.$router.push({ name: "Cart" });
    },
    logout: function () {
      localStorage.removeItem("token");
      window.location.href = urlLogin;
    },
    purchasesLink: function () {
      this.$router.push({ name: "Purchases" });
    },
    resetInputFields: function () {
      this.tempName = "";
      this.tempPrice = "";
      this.tempImage = "";
      this.tempQuantity = "";
    },
    addProductButtonPressed: function () {
      this.showAddForm = !this.showAddForm;
      this.resetInputFields();
    },
    addProduct: async function () {
      const newProduct = {
        name: this.tempName,
        price: this.tempPrice,
        image: this.tempImage,
        quantity: this.tempQuantity,
      };
      const response = await fetch(urlAddProduct, {
        method: "POST",
        body: JSON.stringify(newProduct),
        headers: {
          "Content-Type": "application/json",
          Authorization: `bearer ${this.token}`,
        },
      });

      if (response.ok) {
        response.text().then((text) => console.log(text));
        this.showAddForm = false;
        this.products.push(newProduct);
        alert("Product Added Successfully!");
      } else {
        response.text().then((text) => console.log(text));
        alert("Something went wrong!");
      }
    },
    addToCart: async function (index) {
      const response = await fetch(urlAddToCart, {
        method: "POST",
        headers: {
          Authorization: `bearer ${this.token}`,
        },
        body: new URLSearchParams({
          product_id: this.allProducts[index].id,
        }),
      });

      if (response.ok) {
        response.text().then((text) => console.log(text));
        const newProduct = {
          id: this.allProducts[index].id,
          name: this.allProducts[index].name,
          price: this.allProducts[index].price,
          image: this.allProducts[index].image,
          quantity: this.allProducts[index].quantity,
          addedToCart: true,
        };
        let products = [...this.allProducts];
        products[index] = newProduct;
        this.allProducts = products;
      } else {
        response.text().then((text) => console.log(text));
        alert("Something went wrong!");
      }
    },
    editButtonPressed: function (index) {
      const curProduct = this.products[index];
      curProduct.showEditForm = !curProduct.showEditForm;
      this.tempName = curProduct.name;
      this.tempPrice = curProduct.price;
      this.tempImage = curProduct.image;
      this.tempQuantity = curProduct.quantity;
    },
    editProduct: async function (index) {
      this.products[index].showEditForm = false;
      const newProduct = {
        id: this.products[index].id,
        name: this.tempName,
        price: this.tempPrice,
        image: this.tempImage,
        quantity: this.tempQuantity,
      };
      this.products[index] = newProduct;
      this.resetInputFields();
      const url = urlUpdateProduct + newProduct.id;
      await fetch(url, {
        method: "POST",
        body: JSON.stringify(newProduct),
        headers: {
          "Content-Type": "application/json",
          Authorization: `bearer ${this.token}`,
        },
      })
        .then((res) => {
          res.text().then((text) => console.log(text));
          alert("Product Updated Successfully!");
        })
        .catch((err) => console.log(err));
    },
    deleteProduct: async function (index) {
      const url = urlDeleteProduct + this.products[index].id;
      if (confirm("do you really want to remove this product?")) {
        await fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `bearer ${this.token}`,
          },
        })
          .then((res) => {
            res.text().then((text) => console.log(text));
            this.products.splice(index, 1);
          })
          .catch((err) => console.log(err));
      }
    },
  },

  mounted: async function mounted() {
    this.token = localStorage.getItem("token");
    let user;

    if (this.token) {
      const userResponse = await fetch(urlGetUser, {
        method: "GET",
        headers: {
          Authorization: `bearer ${this.token}`,
        },
      });

      if (userResponse.ok) {
        user = await userResponse.json();
      } else {
        userResponse.text().then((text) => console.log(text));
        localStorage.removeItem("token");
        window.location.href = urlLogin;
      }

      const response = await fetch(urlGetProduct, {
        method: "GET",
        headers: {
          Authorization: `bearer ${this.token}`,
        },
      });
      if (response.ok) {
        this.products = await response.json();
        this.products.forEach((product) => {
          product.showEditForm = false;
          product.price = product.price / 100;
        });
      } else {
        response.text().then((text) => console.log(text));
        throw new Error("Unauthorized");
      }
    }

    const responseCart = await fetch(urlGetCart, {
      method: "GET",
      headers: {
        Authorization: `bearer ${this.token}`,
      },
    });
    if (responseCart.ok) {
      this.cart = await responseCart.json();
      this.cart.forEach((productItem) => {
        productItem.product.price = productItem.product.price / 100;
      });
    } else {
      throw new Error("Unauthorized");
    }

    const responseAllProduct = await fetch(urlGetAllProduct, {
      method: "GET",
      headers: {
        Authorization: `bearer ${this.token}`,
      },
    });
    if (responseAllProduct.ok) {
      this.allProducts = await responseAllProduct.json();
      if (user)
        this.allProducts = this.allProducts.filter(
          (product) => product.user_id != user.id
        );
      this.allProducts.forEach((product) => {
        const addedToCart = this.cart.find(
          (productItem) => productItem.product_id === product.id
        );
        product.addedToCart = addedToCart !== undefined;
        product.price = product.price / 100;
      });
    } else {
      throw new Error("Unauthorized");
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

.edit_btn {
  float: right;
  margin: 10px 10px 0 0;
}

.delete_btn {
  float: right;
  margin: 10px 10px 0 0;
  position: relative;
  top: 70%;
  left: 60px;
}

.buy_btn {
  margin-top: 68px;
}

.btn:focus {
  outline: none;
  box-shadow: none;
}
</style>