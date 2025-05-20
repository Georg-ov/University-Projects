<template>
    <div class="login">
      <h1>Iniciar Sesión</h1>
      <!-- evento generado al enviar el formulario-->
      <form @submit.prevent="handleLogin">
        <input v-model="email" type="email" placeholder="Correo electrónico" required />
        <input v-model="password" type="password" placeholder="Contraseña" required />
        <button type="submit">Entrar</button>
      </form>
      <!-- evento generado al hacer clic en el boton-->
      <button @click="handleResetPassword">¿Olvidaste tu contraseña?</button>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router'; // Importar useRouter
  import { loginUser, resetPassword as resetPasswordFunction } from '../app.js'; // Importar correctamente resetPassword
   
  //almacena el correo electronico ingresado en el formulario
  const email = ref('');

  //almacena la contraseña ingresada en el formulario
  const password = ref('');

  //router que permite navegacion entre paginas
  const router = useRouter(); // Obtener el router
  
  
  //llama a la funcion loginUser para autenticar al usuario con el correo y la contraseña
  // proporcionados en el formulario, si es exitoso redirige a su vista de perfil, sino, muestra error
  const handleLogin = async () => {
    try {
      await loginUser(email.value, password.value); // Llamada a la función de login
      
      // Verificar si el usuario está en localStorage
      const user = JSON.parse(localStorage.getItem('user'));
      if (user && user.id) {
        // Redirigir al perfil del usuario
        router.push(`/perfil/${user.id}`);
        
      } else {
        throw new Error("Usuario no encontrado en localStorage");
      }
    } catch (error) {
      alert("Error al iniciar sesión: " + error.message); // Mostrar error si ocurre
    }
  };
  
  //llama a la funcion resetPasswordFunction para enviar un correo para restablecer la contraseña
  const handleResetPassword = async () => {
    try {
      await resetPasswordFunction(email.value); // Llamada para restablecer la contraseña
      alert("Correo de restablecimiento enviado.");
    } catch (error) {
      alert("Error: " + error.message); // Mostrar mensaje de error si ocurre
    }
  };
  </script>
  
  <style scoped>
  /* Estilos para la vista de login */
  .login {
    max-width: 400px;
    margin: 50px auto;
    text-align: center;
  }
  input {
    display: block;
    margin: 10px auto;
    padding: 10px;
    width: 80%;
  }
  button {
    margin: 10px auto;
    padding: 10px 20px;
  }
  </style>
  