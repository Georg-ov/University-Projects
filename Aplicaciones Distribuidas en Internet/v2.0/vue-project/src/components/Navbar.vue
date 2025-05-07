<template>
  <header class="navbar">
    <div class="wrapper">
      <nav>
        <router-link to="/" class="nav-link home-link">Home</router-link>

        <!-- Mostrar botones para cada categoría -->
        <div v-for="categoria in categorias" :key="categoria.id">
          <router-link :to="`/categoria/${categoria.id}`" class="nav-link home-link">
            {{ categoria.nombre }}
          </router-link>
        </div>

        <!-- Si el usuario no está autenticado, mostramos los enlaces Login y Register -->
        <template v-if="!userData">
          <router-link to="/login" class="nav-link">Login</router-link>
          <router-link to="/register" class="nav-link">Register</router-link>
        </template>

        <!-- Si el usuario está autenticado, mostramos el nombre del usuario -->
        <template v-if="userData">
          <button @click="goToProfile" class="user-button">{{ userData.username }}</button>
          <button @click="logout" class="logout-button" aria-label="Cerrar sesión">Cerrar sesión</button>
        </template>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'; // Importa useRouter para redirigir
import { obtenerCategorias } from '../app';  // Asegúrate de tener esta función

// Obtener el router
const router = useRouter();

// Computed para obtener los datos del usuario desde el localStorage al montar componente
const userData = computed(() => {
  const storedUser = localStorage.getItem('user');
  return storedUser ? JSON.parse(storedUser) : null; // Si hay datos, los parsea, sino devuelve null
});

// Almacena la lista de categorias obtenidas en la funcion obtenerCategorias
const categorias = ref([]);

// Obtener las categorías al montar el componente
onMounted(async () => {
  categorias.value = await obtenerCategorias();
});

// Redirige al usuario si hay algo en el userData utiliza el id del para redirigir a su pagina de perfil
const goToProfile = () => {
  if (userData.value) {
    // Redirige al perfil utilizando el id del usuario
    router.push(`/perfil/${userData.value.id}`);
  }
};

// elimina los daatos de ususario del localStorage y redirige al usuario a la pagina de login
const logout = () => {
  localStorage.removeItem('user'); // Elimina los datos de usuario del localStorage
  router.push('/login'); // Redirige al login después de cerrar sesión
};
</script>

<style scoped>
/* Estilos para el navbar */
.navbar {
  position: sticky;
  top: 0;
  background-color: #000;
  z-index: 1000;
  width: 100%;
  padding: 10px 0;
  display: flex;
  justify-content: center;
}

.wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  padding: 0 20px;
}

nav {
  display: flex;
  gap: 20px;
  font-size: 14px;
  font-weight: bold;
}

nav span {
  color: #007bff;
  font-weight: bold;
}

/* Estilo de los enlaces de navegación */
.nav-link {
  text-decoration: none;
  color: #fff;
  padding: 8px 15px;
  border-radius: 4px;
  transition: background-color 0.3s, color 0.3s;
}

.nav-link:hover {
  background-color: #f0f0f0;
  color: #007bff;
}

/* Estilo de los enlaces de categorías (en el navbar) */
.categoria-link {
  text-decoration: none;
  color: #fff;
  padding: 8px 15px;
  border-radius: 4px;
  transition: background-color 0.3s, color 0.3s;
}

.categoria-link:hover {
  background-color: #f0f0f0;
  color: #007bff;
}

/* Estilo de los botones */
button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

button:focus {
  outline: none;
}

/* Botón de Logout */
.logout-button {
  background-color: #ff5733;
}

.logout-button:hover {
  background-color: #b40202;
}

/* Botón de usuario */
.user-button {
  background-color: #6c5ce7;
  color: white;
  border: none;
  padding: 8px 15px;
  cursor: pointer;
  font-size: 1rem;
}

.user-button:hover {
  background-color: #5a4bbf;
}
</style>
