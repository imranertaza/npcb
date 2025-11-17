import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useNestedStore = defineStore('nested', () => {
  const elements = ref([
    { id: 1, name: 'Item A', elements: [{ id: 11, name: 'Sub A1', elements: [] }] },
    { id: 2, name: 'Item B', elements: [] }
  ])
  const updateElements = (value) => { elements.value = value }
  return { elements, updateElements }
})
