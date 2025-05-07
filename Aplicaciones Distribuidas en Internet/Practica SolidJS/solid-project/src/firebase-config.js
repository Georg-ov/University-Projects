// Importar Firebase
import { initializeApp } from "firebase/app";
import { getAuth } from "firebase/auth";  // Importar el servicio de autenticación

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyAzrxAhJama6vSlhrk4rHTlRzsnBq8kJqg",
    authDomain: "freeworld-2b532.firebaseapp.com",
    projectId: "freeworld-2b532",
    storageBucket: "freeworld-2b532.appspot.com",
    messagingSenderId: "170141483478",
    appId: "1:170141483478:web:ebd30f95859894fac12477"
};

// Inicializar Firebase
const app = initializeApp(firebaseConfig);

// Obtener el servicio de autenticación
const auth = getAuth(app);

// Exportar la instancia de Firebase y la de auth
export { app, auth };
