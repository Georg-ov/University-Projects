<template>
  <div>
    <!-- Navbar siempre visible -->
    <Navbar />
    <!-- Vista principal donde se renderizan las rutas -->
    <RouterView />
  </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue'; // Asegúrate de que la ruta sea correcta
</script>

<style scoped>
/* Aquí puedes agregar los estilos globales si es necesario */

/* Asegura que el fondo de la página ocupe todo el alto disponible */
body {
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
}

/* Si quieres que todo el contenido esté centrado en la pantalla */
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
