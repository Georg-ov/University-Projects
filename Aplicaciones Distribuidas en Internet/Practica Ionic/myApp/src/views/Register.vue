<template>
  <ion-content>
    <div class="register-container">
      <ion-card>
        <ion-card-header>
          <ion-card-title>Registrarse</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <form @submit.prevent="handleRegister">
            <ion-item class="input-item">
              <ion-label position="floating" class="label-spacing">Correo Electrónico</ion-label>
              <ion-input v-model="email" type="email" required></ion-input>
            </ion-item>

            <ion-item class="input-item">
              <ion-label position="floating" class="label-spacing">Contraseña</ion-label>
              <ion-input v-model="password" type="password" required></ion-input>
            </ion-item>

            <ion-button expand="block" type="submit" :disabled="isLoading">
              Registrarse
            </ion-button>

            <ion-text color="danger" v-if="authStore.errorMessage" class="error-message">
              {{ authStore.errorMessage }}
            </ion-text>
          </form>
          <ion-text>
            ¿Ya tienes cuenta? 
            <router-link to="/login">Inicia sesión</router-link>
          </ion-text>
        </ion-card-content>
      </ion-card>
    </div>
  </ion-content>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

// Importación de componentes de Ionic
import { IonContent, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonItem, IonLabel, IonInput, IonButton, IonText } from '@ionic/vue';

const authStore = useAuthStore();
const router = useRouter();
const email = ref('');
const password = ref('');
const isLoading = ref(false);

const handleRegister = async () => {
  isLoading.value = true;

  // Depuración: Verificar el valor de `email`
  console.log('Valor de email:', email.value);

  // Verificación de si el correo está vacío
  if (!email.value.trim()) {
    authStore.setErrorMessage('El correo electrónico no puede estar vacío.');
    isLoading.value = false;
    return; // Evitar enviar el formulario si el correo está vacío
  }

  // Validación del correo electrónico antes de intentar el registro
  const isValidEmail = /^[^@]+@[^@]+\.[^@]+$/.test(email.value);

  if (!isValidEmail) {
    authStore.setErrorMessage('Correo electrónico no válido.');
    isLoading.value = false;
    return; // Evitar enviar el formulario si el correo no es válido
  }

  try {
    await authStore.registerUser(email.value, password.value);
    router.push('/login');
  } catch (error) {
    console.error(error);
    authStore.setErrorMessage('Error al registrar el usuario.');
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  padding: 16px;
}

ion-card {
  width: 100%;
  max-width: 400px;
}

.input-item {
  margin-bottom: 24px; /* Aumento la separación entre los campos */
}

.label-spacing {
  margin-bottom: 12px; /* Asegura que haya el mismo margen entre la etiqueta y el campo */
}

ion-button {
  margin-top: 16px;
}

.error-message {
  display: block;
  margin-top: 10px;
  color: red;
}

ion-text {
  margin-top: 16px;
}

router-link {
  text-decoration: none;
  color: var(--ion-text-color);
}
</style>
