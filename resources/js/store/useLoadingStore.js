// stores/useLoadingStore.js
import { defineStore } from 'pinia'

export const useLoadingStore = defineStore('loading', {
  state: () => ({
    isLoading: false,
    message: null,
    progress: 0
  }),
  actions: {
    start(message = 'Processing...') {
      this.isLoading = true
      this.message = message
      this.progress = 0
    },
    setProgress(value) {
      this.progress = value
    },
    stop() {
      this.isLoading = false
      this.message = null
      this.progress = 0
    }
  }
})
