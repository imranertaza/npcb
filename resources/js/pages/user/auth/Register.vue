<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Register</h4>
          </div>
          <div class="card-body">
            <form @submit.prevent="register">
              <div class="form-group">
                <label for="name">Name</label>
                <input v-model="name" type="text" id="name" class="form-control" placeholder="Enter your name" required />
              </div>

              <div class="form-group">
                <label for="email">Email</label>
                <input v-model="email" type="email" id="email" class="form-control" placeholder="Enter your email" required />
              </div>

              <div class="form-group">
                <label for="password">Password</label>
                <input v-model="password" type="password" id="password" class="form-control" placeholder="Enter password" required />
              </div>

              <div class="form-group">
                <label for="confirm">Confirm Password</label>
                <input v-model="confirm" type="password" id="confirm" class="form-control" placeholder="Confirm password" required />
              </div>

              <button type="submit" class="btn btn-primary btn-block mt-3">Register</button>
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
  
  const name = ref('');
  const email = ref('');
  const password = ref('');
  const confirm = ref('');
  const router = useRouter();
  
  const register = async () => {
    const { data } = await axios.post('/api/user/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: confirm.value
    });
    localStorage.setItem('token', data.token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
    router.push('/profile');
  };
  </script>
  