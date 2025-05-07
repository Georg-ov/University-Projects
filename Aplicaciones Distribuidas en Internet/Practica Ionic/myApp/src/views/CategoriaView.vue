<template>
  <ion-page>
    <ion-header translucent>
      <ion-toolbar>
        <ion-title>{{ categoria.nombre }}</ion-title>
        <ion-buttons slot="end">
          <ion-button @click="$router.push('/categoria/TV')">TV</ion-button>
          <ion-button @click="$router.push('/categoria/juegos')">Juegos</ion-button>
          <ion-button @click="$router.push('/categoria/literatura')">Literatura</ion-button>
          
          <!-- Botón de logout -->
          <ion-button @click="logout" class="logout-button">Logout</ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <ion-content class="ion-padding">
      <!-- Lista de posts -->
      <ion-list v-if="posts.length > 0">
        <ion-item-group>
          <ion-item v-for="post in posts" :key="post.id">
            <ion-label>
              <h2>{{ post.titulo }}</h2>

              <ion-item v-if="post && post.showDetails">
                <ion-label>
                  <p><strong>Contenido:</strong> {{ post.contenido }}</p>
                </ion-label>
              </ion-item>
            </ion-label>
            <ion-buttons slot="end">
              <ion-button color="primary" @click="togglePostDetails(post.id)">
                {{ post.showDetails ? 'Ocultar Detalles' : 'Ver Detalles' }}
              </ion-button>
              <ion-button color="warning" @click="editarPost(post)">Editar</ion-button>
              <ion-button color="danger" @click="eliminarPost(post.id)">Eliminar</ion-button>
            </ion-buttons>
          </ion-item>
        </ion-item-group>
      </ion-list>
      
      <!-- Mensaje cuando no hay posts -->
      <ion-label v-else>No hay posts para mostrar en esta categoría.</ion-label>

      <!-- Botón para cargar más posts -->
      <ion-button expand="block" v-if="nextPost" @click="loadMorePosts">
        Cargar más
      </ion-button>

      <!-- Botón para alternar el formulario de creación -->
      <ion-button expand="block" @click="mostrarFormulario = !mostrarFormulario" color="success">
        {{ mostrarFormulario ? 'Cancelar' : 'Crear nuevo post' }}
      </ion-button>

      <!-- Formulario de creación -->
      <ion-card v-if="mostrarFormulario">
        <ion-card-header>
          <ion-card-title>Crear nuevo post</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-item>
            <ion-label position="stacked">Título</ion-label>
            <ion-input 
              :value="nuevoPost.titulo" 
              @ionChange="nuevoPost.titulo = $event.detail.value" 
              required>
            </ion-input>
          </ion-item>
          <ion-item>
            <ion-label position="stacked">Contenido</ion-label>
            <ion-textarea 
              :value="nuevoPost.contenido" 
              @ionChange="nuevoPost.contenido = $event.detail.value" 
              required>
            </ion-textarea>
          </ion-item>
          <ion-button expand="block" @click="crearNuevoPost">Crear Post</ion-button>
        </ion-card-content>
      </ion-card>

      <!-- Formulario de edición -->
      <ion-card v-if="postEditando">
        <ion-card-header>
          <ion-card-title>Editar post</ion-card-title>
        </ion-card-header>
        <ion-card-content>
          <ion-item>
            <ion-label position="stacked">Título</ion-label>
            <ion-input
              :value="postEditando.titulo" 
              @ionInput="postEditando.titulo = $event.detail.value"
              required>
            </ion-input>
          </ion-item>
          <ion-item>
            <ion-label position="stacked">Contenido</ion-label>
            <ion-textarea
              :value="postEditando.contenido" 
              @ionInput="postEditando.contenido = $event.detail.value"
              required>
            </ion-textarea>
          </ion-item>
          <ion-button expand="block" @click="actualizarPost">Actualizar Post</ion-button>
        </ion-card-content>
      </ion-card>
    </ion-content>
  </ion-page>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { auth } from '../app'; 
import { signOut } from 'firebase/auth'; 
import {
  getFirestore,
  doc,
  getDoc,
  collection,
  query,
  orderBy,
  limit,
  getDocs,
  startAfter,
  setDoc,
  deleteDoc,
  updateDoc
} from 'firebase/firestore';

const db = getFirestore();

export default {
  setup() {
    const route = useRoute();
    const router = useRouter();
    const categoriaId = ref(route.params.id);
    const categoria = ref({ nombre: '', descripcion: '' });
    const posts = ref([]);
    const nextPost = ref(null);
    const mostrarFormulario = ref(false);
    const nuevoPost = ref({ titulo: '', contenido: '' });
    const postEditando = ref(null);

    const logout = async () => {
      try {
        await signOut(auth); 
        localStorage.removeItem('user');
        router.replace('/login'); 
      } catch (error) {
        console.error('Error al cerrar sesión:', error.message);
        alert('Hubo un error al cerrar sesión. Por favor, inténtalo de nuevo.');
      }
    };

    const loadCategoryDetails = async () => {
      try {
        const categoriaRef = doc(db, 'categorias', String(categoriaId.value));
        const categoriaSnap = await getDoc(categoriaRef);
        if (categoriaSnap.exists()) {
          categoria.value = categoriaSnap.data();
        }
        const { posts: postsData, lastPost } = await obtenerPostsPorCategoria(categoriaId.value);
        posts.value = postsData.map(post => ({ ...post, showDetails: false }));
        nextPost.value = lastPost;
      } catch (error) {
        console.error("Error al cargar los detalles de la categoría:", error);
      }
    };

    const loadMorePosts = async () => {
      try {
        if (nextPost.value) {
          const { posts: morePosts, lastPost } = await obtenerPostsPorCategoria(categoriaId.value, 10, nextPost.value);
          posts.value.push(...morePosts.map(post => ({ ...post, showDetails: false })));
          nextPost.value = lastPost;
        }
      } catch (error) {
        console.error("Error al cargar más posts:", error);
      }
    };

    const togglePostDetails = (postId) => {
      const post = posts.value.find(p => p.id === postId);
      if (post) {
        post.showDetails = !post.showDetails;
      }
    };

    const crearNuevoPost = async () => {
      try {
        if (nuevoPost.value.titulo && nuevoPost.value.contenido) {
          await crearPost(categoriaId.value, nuevoPost.value);
          nuevoPost.value = { titulo: '', contenido: '' };
          mostrarFormulario.value = false;
          loadCategoryDetails();
        }
      } catch (error) {
        console.error("Error al crear el post:", error);
      }
    };

    const editarPost = (post) => {
      postEditando.value = { ...post };
      mostrarFormulario.value = false;
    };

    const actualizarPost = async () => {
      try {
        if (postEditando.value.titulo && postEditando.value.contenido) {
          const postRef = doc(db, "categorias", String(categoriaId.value), "posts", String(postEditando.value.id));
          await updateDoc(postRef, {
            titulo: postEditando.value.titulo,
            contenido: postEditando.value.contenido,
          });
          postEditando.value = null;
          loadCategoryDetails();
        }
      } catch (error) {
        console.error("Error al actualizar el post:", error);
      }
    };

    const eliminarPost = async (postId) => {
      try {
        const user = auth.currentUser;
        if (!user) {
          console.error("Debes estar autenticado para eliminar un post.");
          return;
        }

        const postRef = doc(db, "categorias", String(categoriaId.value), "posts", String(postId));
        const postSnap = await getDoc(postRef);

        if (postSnap.exists()) {
          const post = postSnap.data();
          if (post.id_creador === user.uid) {
            await deleteDoc(postRef);
            loadCategoryDetails();
          } else {
            alert("No eres el creador de este post. No puedes eliminarlo.");
          }
        }
      } catch (error) {
        console.error("Error al eliminar el post:", error.message);
      }
    };

    const obtenerPostsPorCategoria = async (categoriaId, limitCount = 10, lastPost = null) => {
      const postsRef = collection(db, "categorias", String(categoriaId), "posts");
      let queryPosts = query(postsRef, orderBy("id"), limit(limitCount));
      if (lastPost) queryPosts = query(queryPosts, startAfter(lastPost));
      const postsSnapshot = await getDocs(queryPosts);
      const postsData = postsSnapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
      const lastVisible = postsSnapshot.docs[postsSnapshot.docs.length - 1];
      return { posts: postsData, lastPost: lastVisible || null };
    };

    const crearPost = async (categoriaId, postData) => {
      const user = auth.currentUser;
      if (!user) {
        console.error("Solo los usuarios registrados pueden crear posts.");
        return;
      }

      try {
        const categoriaRef = doc(db, "categorias", String(categoriaId));
        const postsRef = collection(categoriaRef, "posts");
        const postsSnapshot = await getDocs(postsRef);
        let nuevoIdPost = postsSnapshot.empty ? 1 : postsSnapshot.docs[0].data().id + 1;

        const postDataConIdCreador = {
          ...postData,
          id_creador: user.uid,
          valoracion: 0,
          id: nuevoIdPost,
        };

        await setDoc(doc(postsRef, String(nuevoIdPost)), postDataConIdCreador);
      } catch (error) {
        console.error("Error al crear el post:", error.message);
      }
    };

    watch(
      () => route.params.id,
      (newId) => {
        categoriaId.value = newId;
        loadCategoryDetails();
      }
    );

    onMounted(loadCategoryDetails);

    return {
      categoria,
      posts,
      nextPost,
      loadMorePosts,
      mostrarFormulario,
      nuevoPost,
      crearNuevoPost,
      categoriaId,
      editarPost,
      eliminarPost,
      postEditando,
      actualizarPost,
      togglePostDetails,
      logout,
    };
  },
};
</script>

<style>
/* CSS para el botón de logout */
ion-button.logout-button {
  background-color: #ff3b30; 
  color: white;
  font-weight: bold;
  border-radius: 5px;
  padding: 10px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

ion-button.logout-button:hover {
  background-color: #ff2a1d;
}

/* CSS para el título */
ion-title {
  font-size: 24px;
  font-weight: bold;
  color: #4a90e2;
  text-transform: uppercase;
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>
