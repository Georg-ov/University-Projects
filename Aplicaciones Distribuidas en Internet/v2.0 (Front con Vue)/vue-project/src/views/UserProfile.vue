<template>
  <div class="user-profile">
    <!-- Información del usuario a la izquierda -->
    <div class="profile-left">
      <div v-if="user">
        <h2>{{ user.username }}'s Profile</h2>
        


        <!-- Información del perfil -->
        <div class="profile-info">
          <div class="user-photo">
            <img :src="user.fotoPerfil || '/resources/foto.png'" alt="Foto de perfil" />
          </div>
          <p><strong>Valoración:</strong> {{ user.valoracion }}</p>
          <p><strong>Descripción:</strong> {{ user.descripcion }}</p>
          <p><strong>Donaciones:</strong> <a :href="user.donaciones" target="_blank">{{ user.donaciones }}</a></p>
        </div>

        <!-- Botón eliminar cuenta -->
        <button class="delete-account" @click="confirmarEliminacion">Eliminar cuenta</button>
      </div>

      <div v-else>
        <p>Cargando perfil...</p>
      </div>
    </div>

    <!-- Información de los posts a la derecha -->
    <div class="profile-right">
      <div v-if="posts.length > 0">
        <h3>Posts</h3>
        <ul>
          <li v-for="post in posts" :key="post.id">
            <p><strong>{{ post.titulo }}</strong> - Valoración: {{ post.valoracion }}</p>
          </li>
        </ul>
      </div>
      <div v-else>
        <p>No hay posts para mostrar.</p> 
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { mostrarPerfilUsuario } from '../app';  // Asegúrate de tener este servicio
import { eliminarUsuario } from '../app';  // Asegúrate de tener esta función
import { obtenerPostsDelUsuario } from '../app'; // Importa la función para obtener posts

export default {
  props: {
    userId: {
      type: String,
      required: true,
    },
  },

  setup(props) {
    const user = ref(null);
    const posts = ref([]);
    const router = useRouter();

    // Obtener el perfil del usuario al montar el componente
    onMounted(async () => {
      const perfil = await mostrarPerfilUsuario(props.userId);
      user.value = perfil;

      // Obtener los posts del usuario
      posts.value = await obtenerPostsDelUsuario(props.userId);
    });

    // Función para confirmar la eliminación
    const confirmarEliminacion = () => {
      if (confirm("¿Estás seguro de que deseas eliminar tu cuenta?")) {
        eliminarUsuario().then(() => {
          router.push('/');  // Redirige al home después de eliminar la cuenta
        });
      }
    };

    return {
      user,
      posts,
      confirmarEliminacion,
    };
  },
};
</script>

<style scoped>
.user-profile {
  display: flex;
  justify-content: space-between;
  padding: 20px;
  max-width: 1200px;
  margin: auto;
}

.profile-left {
  flex: 1;
  padding-right: 20px;
}

.profile-right {
  flex: 1;
}

.profile-info {
  margin-bottom: 20px;
}

.profile-img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
}

h3 {
  margin-top: 20px;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  margin-bottom: 10px;
}

a {
  color: #007bff;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

button {
  background-color: red;
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  margin-top: 20px;
}

.user-photo img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
}

button:hover {
  background-color: darkred;
}
</style>
