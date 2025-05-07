<template>
  <ion-app>
    <ion-router-outlet :key="$route.fullPath"></ion-router-outlet>
  </ion-app>
</template>


<script>
import { IonApp, IonRouterOutlet } from '@ionic/vue';
import { getMessaging, getToken, onMessage } from "firebase/messaging";
import { initializeApp } from "firebase/app";

export default {
  components: {
    IonApp,
    IonRouterOutlet,
  },
};

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


// Get registration token. Initially this makes a network call, once retrieved
// subsequent calls to getToken will return from cache.
const messaging = getMessaging();
onMessage(messaging, (payload) => {
  console.log('Message received. ', payload);
  // ...
});

getToken(messaging, { vapidKey: 'BMQ8lFoaj4meJA-AwYOQFSPPPUtTEyZWaogo7_-S77hqvypN-RajyPn6Xn2f3fAdFWxwg1O7lKWly5jHd5f1fCo' }).then((currentToken) => {
  if (currentToken) {
    // Send the token to your server and update the UI if necessary
    console.log("El Token es:" ,currentToken)
    // ...
  } else {
    // Show permission request UI
    console.log('No registration token available. Request permission to generate one.');
    // ...
  }
}).catch((err) => {
  console.log('An error occurred while retrieving token. ', err);
  // ...
});
</script>
