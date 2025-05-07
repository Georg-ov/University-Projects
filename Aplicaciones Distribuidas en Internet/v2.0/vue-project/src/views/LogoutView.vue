<template>
    <div class="logout">
      <h1>¿Estás seguro de que deseas cerrar sesión?</h1>

      <!-- evento generado cuando se hace clic en el boton-->
      <button @click="handleLogout">Cerrar Sesión</button>
    </div>
  </template>
  
  <script>
  import { logoutUser } from "../app.js";
  
  //llama a logoutUser para cerrar sesion, luego muestra una alerta confirmando
  // el cierre desesion y redirige a login.
  export default {
    methods: {
      async handleLogout() {
        try {
          await logoutUser();
          alert("Sesión cerrada exitosamente");
          this.$router.push('/login');
          window.location.replace('/login'); // Reemplaza la página actual y recarga completamente
        } catch (error) {
          alert("Error al cerrar sesión: " + error.message);
        }
      }
    }
  };
  </script>
  
  <style scoped>
  /* Aquí puedes agregar estilos para personalizar tu vista de logout */
  .logout {
    text-align: center;
    margin-top: 50px;
  }
  button {
    padding: 10px 20px;
    margin-top: 20px;
  }
  </style>
  