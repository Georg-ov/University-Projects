<script setup>
import { ref, onMounted } from 'vue'
import { obtenerMejorValoracion } from '../app' // Importa la función correctamente

const mejorValorado = ref(null)

// Función para obtener el usuario mejor valorado
const getMejorValorado = async () => {
  mejorValorado.value = await obtenerMejorValoracion()
}

onMounted(() => {
  getMejorValorado() // Llama a la función al montar el componente
})
</script>

<template>
  <div class="home">
    <h1>El usuario mejor valorado es...</h1>

    <!-- Mostrar los detalles del usuario mejor valorado -->
    <div v-if="mejorValorado" class="user-info">
      <div class="user-rating">
        <p>{{ mejorValorado.valoracion }}</p>
      </div>
      
      <div class="user-photo">
        <img :src="mejorValorado.profileImage || '/foto.png'" alt="Foto de perfil" />
      </div>
      
      <div class="user-name">
        <p>{{ mejorValorado.username }}</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.home {
  display: flex;
  flex-direction: column;
  justify-content: center; /* Centra los elementos verticalmente */
  align-items: center; /* Centra los elementos horizontalmente */
  height: 100%; /* Asegura que ocupe el 100% de la altura disponible */
  width: 160%;  /* Asegura que ocupe el 100% del ancho */
}

.user-info {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  margin-top: 20px;
}

.user-rating, .user-name {
  flex: 1;
}

.user-rating p, .user-name p {
  font-size: 1.5rem;
  font-weight: bold;
}

.user-photo img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
}

h1 {
  font-size: 2rem;
  margin-bottom: 20px;
}
</style>
