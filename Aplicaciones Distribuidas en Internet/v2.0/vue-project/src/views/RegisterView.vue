<template>
  <div class="register-container">
    <h2>Registrarse</h2>
    <form @submit.prevent="handleRegister">
      <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" v-model="email" required />
      </div>

      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" v-model="password" required />
      </div>

      <button type="submit" :disabled="isLoading">Registrarse</button>

      <p v-if="authStore.errorMessage" class="error-message">{{ authStore.errorMessage }}</p>
    </form>
    <p>¿Ya tienes cuenta? <router-link to="/login">Inicia sesión</router-link></p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth'; 
import { useRouter } from 'vue-router';  // Importar useRouter()

const authStore = useAuthStore();
const router = useRouter(); // Aquí obtenemos el router dentro de setup
const email = ref('');
const password = ref('');
const isLoading = ref(false);

const handleRegister = async () => {
  isLoading.value = true;
  try {
    await authStore.registerUser(email.value, password.value); // Registra el usuario
    router.push('/login'); // Redirige a la página de login después del registro
  } catch (error) {
    console.error(error);
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.register-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
}

.form-group {
  margin-bottom: 15px;
}

input {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
}

button:disabled {
  background-color: #9e9e9e;
  cursor: not-allowed;
}

button:hover {
  background-color: #45a049;
}

.error-message {
  color: red;
  margin-top: 10px;
}
</style>
