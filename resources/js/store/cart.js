// stores/counter.js
import { defineStore } from 'pinia'
import { toRef } from 'vue'

export const useCartStore = defineStore('cart', {
  state: () => ({ cart: [], }),
  getters: {
    totalAmount: (state) => {
        let totalValue = toRef(0.0);
        state.cart.forEach((obj, index) => {
            totalValue += parseFloat(obj.price)
        })
        return totalValue
    }
  },

  actions: {
    removeProductFromCart (prodId) {
        this.cart.forEach((product, index) => {
            if(product.id === prodId) this.cart.splice(index, 1)
        })
    }
  },
  persist: true
})
