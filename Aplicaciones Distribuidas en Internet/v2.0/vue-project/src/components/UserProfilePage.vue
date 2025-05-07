<template>
    <div class="user-profile">
      <h1>Perfil de {{ user.username }}</h1>
      <p>Email: {{ user.email }}</p>
      <p>Valoración: {{ user.valoracion }}</p>
      <img :src="user.profileImage" alt="Foto de perfil" />

    </div>
  </template>
  
  <script setup>
  import { onMounted, ref } from 'vue';
  import { useRoute } from 'vue-router';
  import { getFirestore, doc, getDoc } from 'firebase/firestore';
  import { useAuthStore } from '../stores/auth';
  
  const route = useRoute();
  const userId = route.params.userId; // Obtener el ID del usuario desde la ruta
  const user = ref({});
  
  const db = getFirestore();
  
  onMounted(async () => {
    // Obtener los detalles del usuario desde Firestore
    const userDoc = await getDoc(doc(db, 'usuarios', userId));
    if (userDoc.exists()) {
      user.value = userDoc.data();
    }
  });
  </script>
  
  <style scoped>
  /* Estilos para el perfil del usuario */
  .user-profile {
    max-width: 600px;
    margin: 50px auto;
    text-align: center;
  }
  </style>
  