<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-info text-white text-center">
            <h4 class="mb-0">Login</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="login">
              <div class="form-group">
                <label for="email">Email</label>
                <input
                  v-model="email"
                  type="email"
                  id="email"
                  class="form-control"
                  placeholder="Enter your email"
                  required
                />
              </div>

              <div class="form-group">
                <label for="password">Password</label>
                <input
                  v-model="password"
                  type="password"
                  id="password"
                  class="form-control"
                  placeholder="Enter your password"
                  required
                />
              </div>

              <button type="submit" class="btn btn-info btn-block mt-3">
                Login
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const email = ref('');
  const password = ref('');
  const router = useRouter();
  
  const login = async () => {
    const { data } = await axios.post('/api/admin/login', { email: email.value, password: password.value });
    localStorage.setItem('token', data.token);
    localStorage.setItem('role', 'admin');
        axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
        router.push({ name: 'Dashboard' })
    };
  </script>
  