<template>
  <ion-content>
    <div class="login">
      <ion-card>
        <ion-card-header>
          <ion-card-title>Iniciar Sesión</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <!-- Evento generado al enviar el formulario -->
          <form @submit.prevent="handleLogin">
            <ion-item class="input-item">
              <ion-label position="floating" class="label-spacing">Correo Electrónico</ion-label>
              <ion-input v-model="email" type="email" required></ion-input>
            </ion-item>
            <ion-item class="input-item">
              <ion-label position="floating" class="label-spacing">Contraseña</ion-label>
              <ion-input v-model="password" type="password" required></ion-input>
            </ion-item>
            <ion-button expand="block" type="submit">Entrar</ion-button>
          </form>
          <!-- Evento generado al hacer clic en el botón -->
          <ion-button expand="block" fill="clear" @click="handleResetPassword">
            ¿Olvidaste tu contraseña?
          </ion-button>
          
          <!-- Texto y enlace para redirigir a la página de registro -->
          <ion-text>
            ¿No tienes cuenta? 
            <router-link to="/register" class="create-account-link">
              Crea tu cuenta aquí
            </router-link>
          </ion-text>
        </ion-card-content>
      </ion-card>
    </div>
  </ion-content>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { loginUser, resetPassword as resetPasswordFunction } from '../app.js';

// Importación de componentes de Ionic
import { IonContent, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonItem, IonLabel, IonInput, IonButton, IonText } from '@ionic/vue';

const email = ref('');
const password = ref('');
const router = useRouter();

const handleLogin = async () => {
  try {
    await loginUser(email.value, password.value);
    const user = JSON.parse(localStorage.getItem('user'));
    if (user && user.id) {
      router.push(`/categoria/TV`);
    } else {
      throw new Error("Usuario no encontrado en localStorage");
    }
  } catch (error) {
    alert("Error al iniciar sesión: " + error.message);
  }
};

const handleResetPassword = async () => {
  try {
    await resetPasswordFunction(email.value);
    alert("Correo de restablecimiento enviado.");
  } catch (error) {
    alert("Error: " + error.message);
  }
};
</script>

<style scoped>

.login {
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
  margin-bottom: 32px;
}

.label-spacing {
  margin-bottom: 16px;
}

ion-button {
  margin-top: 16px;
}

.create-account-link {
  text-decoration: none;
  color: var(--ion-text-color);
  font-weight: bold;
  margin-left: 5px;
}
</style>
