<template>
    <div class="container text-center mt-5">
      <div class="card shadow p-4">
        <h1 class="display-1 text-danger">401</h1>
        <h2>Unauthorized</h2>
        <p class="lead">You do not have permission to access this page.</p>
        <router-link to="/" class="btn btn-primary mt-3">Go to Home</router-link>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Unauthorized',
  };
  </script>
  
  <style scoped>
  .card {
    max-width: 500px;
    margin: auto;
  }
  </style>