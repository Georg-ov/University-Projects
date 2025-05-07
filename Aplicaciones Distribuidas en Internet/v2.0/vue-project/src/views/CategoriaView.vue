<template>
  <div class="categoria-detail">
    <h1 class="categoria-title">{{ categoria.nombre }}</h1>

    <div>
      <input 
        type="text" 
        v-model="searchQuery" 
        placeholder="Buscar posts..." 
        class="search-input"
      />
    </div>

    <div v-if="filteredPosts.length > 0">
      <ul>
        <li v-for="post in filteredPosts" :key="post.id">
          <p><strong>{{ post.titulo }}</strong> - Valoración: {{ post.valoracion }}</p>

          <!-- Botón para ver detalles del post, evento generado por clic -->
          <button @click="togglePostDetails(post.id)">
            {{ post.showDetails ? 'Ocultar Detalles' : 'Ver Detalles' }}
          </button>
          
          <!-- eventos generados por clic -->
          <button @click="editarPost(post)">Editar</button>
          <button @click="eliminarPost(post.id)">Eliminar</button>

          <!-- Detalles del post que se muestran solo si 'showDetails' es verdadero -->
          <div v-if="post.showDetails">
            <p><strong>Contenido:</strong> {{ post.contenido }}</p>
          </div>
        </li>
      </ul>
      <!-- evento generado por clic-->
      <button v-if="nextPost" @click="loadMorePosts">Cargar más</button>
    </div>

    <div v-else>
      <p>No hay posts para mostrar en esta categoría.</p>
    </div>

    <!-- evento generado por clic-->
    <button @click="mostrarFormulario = !mostrarFormulario" style="margin-top: 10px;">
      {{ mostrarFormulario ? 'Cancelar' : 'Crear nuevo post' }}
    </button>

    <div v-if="mostrarFormulario">
      <h3>Crear nuevo post</h3>
      <!-- Evento generado al enviar formulario de creacion -->
      <form @submit.prevent="crearNuevoPost">
        <div>
          <label for="titulo">Título</label>
          <input type="text" v-model="nuevoPost.titulo" id="titulo" required />
        </div>
        <div>
          <label for="contenido">Contenido</label>
          <textarea v-model="nuevoPost.contenido" id="contenido" required></textarea>
        </div>
        <button type="submit">Crear Post</button>
      </form>
    </div>

    <!-- Formulario de edición -->
    <div v-if="postEditando">
      <h3>Editar post</h3>
      <!-- Evento generado al enviar formulario de edicion-->
      <form @submit.prevent="actualizarPost">
        <div>
          <label for="tituloEdit">Título</label>
          <input type="text" v-model="postEditando.titulo" id="tituloEdit" required />
        </div>
        <div>
          <label for="contenidoEdit">Contenido</label>
          <textarea v-model="postEditando.contenido" id="contenidoEdit" required></textarea>
        </div>
        <button type="submit">Actualizar Post</button>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import { getFirestore, doc, getDoc, collection, query, orderBy, limit, getDocs, startAfter, setDoc, deleteDoc, updateDoc } from 'firebase/firestore';
import { auth } from '../app';

const db = getFirestore();

export default {
  setup() {
    const route = useRoute();
    const categoriaId = route.params.id;
    const categoria = ref({ nombre: '', descripcion: '' });
    const posts = ref([]);
    const nextPost = ref(null);
    const mostrarFormulario = ref(false);
    const nuevoPost = ref({ titulo: '', contenido: '' });
    const postEditando = ref(null); // Almacena el post que se está editando
    const searchQuery = ref('');

    const loadCategoryDetails = async () => {
      try {
        const categoriaRef = doc(db, 'categorias', String(categoriaId));
        const categoriaSnap = await getDoc(categoriaRef);
        if (categoriaSnap.exists()) {
          categoria.value = categoriaSnap.data();
        }

        const { posts: postsData, lastPost } = await obtenerPostsPorCategoria(categoriaId);
        posts.value = postsData.map(post => ({ ...post, showDetails: false })); // Inicializamos showDetails en false para no mostrar el forumlario antes de tiempo
        nextPost.value = lastPost;
      } catch (error) {
        console.error("Error al cargar los detalles de la categoría:", error);
      }
    };

    //script para mostrar mas posts por pantalla
    const loadMorePosts = async () => {
      try {
        if (nextPost.value) {
          const { posts: morePosts, lastPost } = await obtenerPostsPorCategoria(categoriaId, 10, nextPost.value);
          posts.value.push(...morePosts.map(post => ({ ...post, showDetails: false })));
          nextPost.value = lastPost;
        }
      } catch (error) {
        console.error("Error al cargar más posts:", error);
      }
    };

    //funcion que muestra o esconde los detalles de un post
    const togglePostDetails = (postId) => {
      const post = posts.value.find(p => p.id === postId);
      if (post) {
        post.showDetails = !post.showDetails; // Alternamos la visibilidad de los detalles
      }
    };

    //funcion para crear un nuevo post, llama a la funcion crearPost
    const crearNuevoPost = async () => {
      try {
        if (nuevoPost.value.titulo && nuevoPost.value.contenido) {
          await crearPost(categoriaId, nuevoPost.value);
          nuevoPost.value = { titulo: '', contenido: '' };
          mostrarFormulario.value = false;
          loadCategoryDetails();
        }
      } catch (error) {
        console.error("Error al crear el post:", error);
      }
    };

    //funcion para mostrar el formulario de edicion
    const editarPost = (post) => {
      postEditando.value = { ...post }; // Carga el post a editar en el formulario
      mostrarFormulario.value = false; // Cierra el formulario de creación
    };

    //funcion de edicion que escrive directamente en la base de datos
    const actualizarPost = async () => {
      try {
        if (postEditando.value.titulo && postEditando.value.contenido) {
          if (!postEditando.value.id) {
            console.error("ID del post no válido");
            return;
          }

          const postRef = doc(db, "categorias", String(categoriaId), "posts", String(postEditando.value.id));
          await updateDoc(postRef, {
            titulo: postEditando.value.titulo,
            contenido: postEditando.value.contenido,
          });

          postEditando.value = null; // Limpiar el post que se está editando
          loadCategoryDetails(); // Vuelve a cargar los posts con el post actualizado
        }
      } catch (error) {
        console.error("Error al actualizar el post:", error);
      }
    };

    //funcion que elimina un post directamente en la base de datos
    const eliminarPost = async (postId) => {
      try {
        const user = auth.currentUser;
        if (!user) {
          console.error("Debes estar autenticado para eliminar un post.");
          return;
        }

        const postRef = doc(db, "categorias", String(categoriaId), "posts", String(postId));
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

    onMounted(loadCategoryDetails);

    //funcion script para cargar los posts de una categoria
    const obtenerPostsPorCategoria = async (categoriaId, limitCount = 10, lastPost = null) => {
      const postsRef = collection(db, "categorias", String(categoriaId), "posts");
      let queryPosts = query(postsRef, orderBy("id"), limit(limitCount));
      if (lastPost) queryPosts = query(queryPosts, startAfter(lastPost));
      const postsSnapshot = await getDocs(queryPosts);
      const postsData = postsSnapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
      const lastVisible = postsSnapshot.docs[postsSnapshot.docs.length - 1];
      return { posts: postsData, lastPost: lastVisible || null };
    };

    const crearPost = async (categoria, postData) => {
      const user = auth.currentUser;
      if (!user) {
        console.error("Solo los usuarios registrados pueden crear posts.");
        return;
      }

      try {
        const categoriaRef = doc(db, "categorias", String(categoria));
        const postsRef = collection(categoriaRef, "posts");
        const postsSnapshot = await getDocs(postsRef);
        let nuevoIdPost = postsSnapshot.empty ? 1 : postsSnapshot.docs[0].data().id + 1;

        const postDataConIdCreador = {
          ...postData,
          id_creador: user.uid,
          valoracion: 0,
          id: nuevoIdPost
        };

        await setDoc(doc(postsRef, String(nuevoIdPost)), postDataConIdCreador);
        console.log("Post creado exitosamente.");
      } catch (error) {
        console.error("Error al crear el post:", error.message);
      }
    };

    const filteredPosts = computed(() => {
      return posts.value.filter(post =>
        post.titulo.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        post.contenido.toLowerCase().includes(searchQuery.value.toLowerCase())
      );
    });

    return {
      categoria,
      posts,
      nextPost,
      loadMorePosts,
      mostrarFormulario,
      nuevoPost,
      crearNuevoPost,
      searchQuery,
      filteredPosts,
      categoriaId,
      editarPost,
      eliminarPost,
      postEditando, // Se agrega al retorno
      actualizarPost,
      togglePostDetails, // Se agrega al retorno
    };
  },
};
</script>

<style scoped>

/* Estilos generales para la categoría */
.categoria-detail {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Títulos de las categorías */
.categoria-title {
  font-size: 2rem;
  color: #333; /* Color negro */
  margin-bottom: 20px;
}

/* Estilo de los botones */
button {
  background-color: #007bff;
  color: white; /* Color blanco para los textos de los botones */
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  margin-right: 10px;
}

/* Estilo para los textos dentro de los botones cuando se pasa el mouse */
button:hover {
  background-color: #0056b3;
}

/* Estilo para deshabilitar los botones */
button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

/* Estilo de los posts */
ul {
  list-style-type: none;
  padding: 0;
}

li {
  background-color: #fff;
  padding: 15px;
  margin-bottom: 10px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Color del contenido dentro del post */
.post-content {
  margin-top: 10px;
  font-size: 1rem;
  color: #444; /* Color negro para los textos */
}

/* Estilo de los formularios */
form {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Color de las etiquetas de formulario */
form label {
  display: block;
  font-size: 1rem;
  margin-bottom: 5px;
  color: #333; /* Color negro para las etiquetas */
}

/* Estilo de los inputs y textarea */
form input, form textarea {
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.categoria-detail button {
  color: white !important; /* Asegura que el texto de los botones sea blanco */
}


.categoria-detail, 
.categoria-detail * {
  color: #000000 !important; /* Sobrescribir color con prioridad */
}

/* Estilo para cuando el input o textarea están enfocados */
form input:focus, form textarea:focus {
  outline: none;
  border-color: #007bff;
}

/* Estilo para los botones del formulario */
form button {
  background-color: #28a745;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  color: white !important; /* Color blanco para los textos de los botones */
  font-size: 1rem;
}

/* Cambio de color del botón en hover */
form button:hover {
  background-color: #218838;
}




</style>
