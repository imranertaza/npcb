<template>
    <div class="input-group" :class="sizeClass" :style="{ width }">
      <input
        v-model="query"
        @keyup.enter="emitSearch"
        type="text"
        class="form-control"
        :placeholder="placeholder"
      />
      <div class="input-group-append">
        <button :class="['btn', btnClass, btnSize]" @click="emitSearch">
          <i class="fas fa-search"></i> {{ btnText }}
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  
  const props = defineProps({
    width: { type: String, default: '250px' },        // dynamic width
    btnClass: { type: String, default: 'btn-default' }, // button style class
    btnSize: { type: String, default: '' },             // e.g. 'btn-sm', 'btn-lg'
    btnText: { type: String, default: 'Search' },       // button text
    size: { type: String, default: '' },              // input-group size: sm, lg, etc.
    placeholder: { type: String, default: 'Search...' } // input placeholder
  })
  
  const query = ref('')
  const emit = defineEmits(['search'])
  
  const emitSearch = () => {
    emit('search', query.value)
  }
  
  const sizeClass = computed(() => {
    return props.size ? `input-group-${props.size}` : ''
  })
  </script>
  