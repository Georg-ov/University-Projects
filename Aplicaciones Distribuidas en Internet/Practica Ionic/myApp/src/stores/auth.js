import { defineStore } from 'pinia';
import { initializeApp } from 'firebase/app';
import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword, signOut, sendPasswordResetEmail, deleteUser } from 'firebase/auth';
import { getFirestore, doc, setDoc, getDoc } from 'firebase/firestore';

// Tu configuración de Firebase
const firebaseConfig = {
  apiKey: 'AIzaSyAzrxAhJama6vSlhrk4rHTlRzsnBq8kJqg',
  authDomain: 'freeworld-2b532.firebaseapp.com',
  projectId: 'freeworld-2b532',
  storageBucket: 'freeworld-2b532.appspot.com',
  messagingSenderId: '170141483478',
  appId: '1:170141483478:web:ebd30f95859894fac12477',
};

//complemento para poder registrarnos correctamente, como se puede observar se ha simplificado hasta la saciedad la funcion crearDonacionEnlace porque la API de coinbase daba muchos errores.
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db = getFirestore(app); // Inicializa Firestore

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null, // Cargar desde localStorage
    isAuthenticated: JSON.parse(localStorage.getItem('user')) ? true : false, // Verificar si hay usuario
    errorMessage: null,
  }),

  actions: {
    // Función para devolver un enlace fijo de donación
    async crearDonacionEnlace(username) {
      return "https://default-donation-link.com";
    },

    // Acción para registrar usuario
    async registerUser(email, password) {
      try {
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        // Crear el objeto de usuario
        const username = `liberator_${user.uid}`;
        const userData = {
          id: user.uid,
          username: username,
          valoracion: 0,
          email: user.email,
          profileImage: '/src/resources/foto.png',
          description: null,
          donaciones: null,
        };

        // Guardar en Firestore
        const usuarioRef = doc(db, 'usuarios', user.uid);
        await setDoc(usuarioRef, userData);

        // Crear enlace de donaciones
        const donacionEnlace = await this.crearDonacionEnlace(username);
        console.log(`Enlace de donación creado para el usuario: ${donacionEnlace}`);

        console.log('Usuario registrado con éxito:', user.email);

        // Almacenar en el estado y en el localStorage
        this.user = userData;
        this.isAuthenticated = true;
        localStorage.setItem('user', JSON.stringify(this.user));
      } catch (error) {
        this.setErrorMessage(error.message || 'Hubo un error al registrar el usuario.');
        console.error('Error al registrar el usuario:', error);
        throw error;
      }
    },

    // Función para establecer el mensaje de error
    setErrorMessage(message) {
      this.errorMessage = message;
    },
  },
});
