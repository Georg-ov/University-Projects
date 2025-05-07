// Importar Firebase
import { defineStore } from 'pinia';
import { initializeApp } from "firebase/app";
import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword, signOut, sendPasswordResetEmail, deleteUser } from "firebase/auth";
import { getFirestore, doc, setDoc, deleteDoc, collection, addDoc, getDoc, query, orderBy, getDocs, where, updateDoc, collectionGroup, writeBatch, startAfter, limit } from "firebase/firestore";

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
const auth = getAuth(app);
const db = getFirestore(app); // Inicializa Firestore

export { app, auth, db };

// Función simplificada para devolver un enlace fijo de donación
export const crearDonacionEnlace = async (username) => {
    return "https://default-donation-link.com";
};

//      MANEJO DE USUARIOS



export const registerUser = async (email, password) => {
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
        donaciones: null
      };
  
      // Guardar en Firestore
      const usuarioRef = doc(db, "usuarios", user.uid);
      await setDoc(usuarioRef, userData);
  
      // Crear enlace de donaciones
      const donacionEnlace = await crearDonacionEnlace(username);
      console.log(`Enlace de donación creado para el usuario: ${donacionEnlace}`);
  
      console.log("Usuario registrado con éxito:", user.email);
    } catch (error) {
      throw error; // Lanza el error para manejarlo en el componente
    }
  };
  


    export const loginUser = async (email, password) => {
        try {
            const userCredential = await signInWithEmailAndPassword(auth, email, password);
            const user = userCredential.user;
            console.log("El usuario", user.email, "ha iniciado sesión");

            // Obtener los datos del usuario desde Firestore
            const usuarioRef = doc(db, "usuarios", user.uid);
            const docSnap = await getDoc(usuarioRef);
            if (docSnap.exists()) {
                const userData = docSnap.data();

                // Guardar los datos del usuario en localStorage
                localStorage.setItem('user', JSON.stringify(userData));

                // Log a success message
                console.log("Datos de usuario guardados en localStorage");
            } else {
                console.error("No se encontraron datos de usuario en Firestore");
            }

        } catch (error) {
            console.error("Error al iniciar sesión:", error.message);
            throw error; // Lanza el error para manejarlo en el componente
        }
    };


    export const logoutUser = async () => {
        try {
            await signOut(auth);
            console.log("Sesión cerrada.");
    
            // Limpiar datos de localStorage
            localStorage.clear();
    
            // Limpiar el estado de autenticación (usando Pinia o cualquier otro sistema de manejo de estado)
            const authStore = useAuthStore();
            authStore.user = null;  // Limpiar usuario
            authStore.isAuthenticated = false;  // Cambiar estado de autenticación
            authStore.errorMessage = null;  // Limpiar posibles mensajes de error
    
            // Log a success message
            console.log("Estado de autenticación actualizado");
        } catch (error) {
            console.error("Error al cerrar sesión:", error.message);
        }
    };

// Funci�n para restablecer contrase�a
export const resetPassword = async (email) => {
    try {
        await sendPasswordResetEmail(auth, email);
        console.log("Se ha enviado un correo para restablecer la contrase�a a:", email);
    } catch (error) {
        console.error("Error al enviar el correo de restablecimiento:", error.message);
    }
};

// Funci�n para editar el perfil de un usuario
export const editProfile = async (newUsername, newDescription, newProfileImage) => {
    try {
        const user = auth.currentUser; // Obtener el usuario actualmente autenticado
        if (!user) {
            console.log("No tienes permiso para editar este perfil.");
            return;
        }

        const userId = user.uid; // Obtener el ID del usuario autenticado
        const userRef = doc(db, "usuarios", userId);

        // Verificar si el nuevo username ya est� en uso
        const usuariosRef = collection(db, "usuarios");
        const q = query(usuariosRef, where("username", "==", newUsername));
        const usuariosSnapshot = await getDocs(q);

        // Si el username ya est� en uso por otro usuario se cancela la edici�n.
        if (!usuariosSnapshot.empty && usuariosSnapshot.docs[0].id !== userId) {
            console.log("El nombre de usuario ya est� en uso por otro usuario.");
            return;
        }

        // Actualizar el documento del usuario
        await setDoc(userRef, {
            username: newUsername,
            description: newDescription,
            profileImage: newProfileImage
        }, { merge: true }); // merge: true asegura que no se sobrescriban otros campos

        console.log("Perfil actualizado con �xito.");
    } catch (error) {
        console.error("Error al actualizar el perfil:", error.message);
    }
};


// Función para mostrar el perfil del usuario
export const mostrarPerfilUsuario = async (userId) => {
    try {
        const userDoc = await getDoc(doc(db, "usuarios", userId));

        // Verifica si el documento existe
        if (!userDoc.exists()) {
            console.log(`El perfil del liberator con ID ${userId} no existe.`);
            return null;  // Retorna null si no existe el perfil
        }

        const userData = userDoc.data();

        // Obtener los posts del usuario
        const posts = await obtenerPostsDelUsuario(userId);

        // Estructura la información para mostrar en el frontend
        return {
            username: userData.username,
            descripcion: userData.descripcion || 'No disponible',
            valoracion: userData.valoracion || 0,
            fotoPerfil: userData.foto_perfil || '/foto.png',  // Imagen predeterminada si no tiene foto
            donaciones: userData.donaciones || 'No ha activado donaciones',
            posts: posts.map(post => ({
                titulo: post.titulo,
                valoracion: post.valoracion,
            }))
        };

    } catch (error) {
        console.error("Error al obtener el perfil del usuario:", error.message);
        return null;  // Retorna null en caso de error
    }
};

//funcion para obtener el usuario con la mejor valoracion de todos
export const obtenerMejorValoracion = async () => {
    try {
        // Consulta a la colección "usuarios" ordenada por el campo "valoracion" de mayor a menor
        const usuariosRef = collection(db, "usuarios");
        const q = query(usuariosRef, orderBy("valoracion", "desc"), limit(1));  // Obtener solo el primero (mejor valorado)

        // Ejecutar la consulta
        const querySnapshot = await getDocs(q);

        // Si la consulta devuelve resultados, extraer el primer usuario
        if (!querySnapshot.empty) {
            const usuario = querySnapshot.docs[0].data();  // El primer usuario (mejor valorado)
            console.log("Usuario con mejor valoración:", usuario);
            return usuario;  // Retornar el usuario encontrado
        } else {
            console.log("No se encontró ningún usuario.");
            return null;  // Si no hay usuarios en la base de datos
        }
    } catch (error) {
        console.error("Error al obtener el usuario con mejor valoración:", error.message);
        return null;  // Retorna null si hay un error
    }
};

//Funci�n auxiliar para obtener la lista de posts de un usuario
export const obtenerPostsDelUsuario = async (userId) => {
    try {
        // Hacer una consulta en todas las categor�as
        const categoriasSnapshot = await getDocs(collection(db, "categorias"));
        const posts = [];

        for (const categoriaDoc of categoriasSnapshot.docs) {
            const categoriaId = categoriaDoc.id;
            const postsRef = collection(db, "categorias", categoriaId, "posts");
            const q = query(postsRef, where("id_creador", "==", userId));
            const postsSnapshot = await getDocs(q);

            // Iterar sobre los posts encontrados
            postsSnapshot.forEach((doc) => {
                const postData = doc.data();
                posts.push({
                    id: doc.id,
                    titulo: postData.titulo,
                    valoracion: postData.valoracion || 0
                });
            });
        }

        return posts;
    } catch (error) {
        console.error("Error al obtener los posts del usuario:", error.message);
        return [];
    }
};

//funcion auxiliar para obtener toda las categorias de la base de datos y poder mostrarlas por pantalla en el navbar
export const obtenerCategorias = async () => {
    try {
        // Consulta a la colección "categorias"
        const categoriasSnapshot = await getDocs(collection(db, "categorias"));
        const categorias = [];

        // Iterar sobre los documentos de categorías encontrados
        categoriasSnapshot.forEach((doc) => {
            const categoriaData = doc.data();
            categorias.push({
                id: doc.id,  // ID de la categoría
                nombre: categoriaData.nombre,  // Nombre de la categoría
                descripcion: categoriaData.descripcion || "No disponible"  // Descripción de la categoría (si existe)
            });
        });

        return categorias;  // Retorna la lista de categorías
    } catch (error) {
        console.error("Error al obtener las categorías:", error.message);
        return [];  // Retorna un array vacío en caso de error
    }
};
// Funci�n para eliminar el usuario y sus posts, los comentarios no los borraremos.
export const eliminarUsuario = async () => {
    const auth = getAuth();
    const user = auth.currentUser;

    if (!user) {
        console.error("No hay un usuario autenticado.");
        return;
    }

    const userId = user.uid; // Obt�n el ID del usuario autenticado

    try {
        // Eliminar los posts antes de eliminar el perfil del usuario
        await eliminarPostsDelUsuario(userId);

        // Eliminar el documento del usuario en Firestore
        const userRef = doc(db, "usuarios", userId);
        await deleteDoc(userRef);
        console.log("Datos del usuario en Firestore eliminados.");

        // Eliminar el usuario en Firebase Authentication
        await user.delete();
        console.log("Usuario eliminado correctamente de Firebase Authentication.");

        localStorage.clear();

    } catch (error) {
        console.error("Error al eliminar el usuario:", error.message);
    }
}

// Funci�n auxiliar para eliminar los posts del usuario y los comentarios del post.
const eliminarPostsDelUsuario = async (userId) => {
    try {
        // Obtener todos los posts creados por el usuario
        const postsRef = collectionGroup(db, "posts");
        const q = query(postsRef, where("id_creador", "==", userId));
        const postsSnapshot = await getDocs(q);

        // Crear un batch para eliminar los posts y sus comentarios
        const batch = writeBatch(db);

        // Recorrer cada post encontrado
        for (const postDoc of postsSnapshot.docs) {
            const postRef = postDoc.ref;

            // Referencia a la subcolecci�n 'comentarios' del post
            const comentariosRef = collection(postRef, "comentarios");
            const comentariosSnapshot = await getDocs(comentariosRef);

            // Eliminar todos los comentarios del post
            comentariosSnapshot.docs.forEach((comentarioDoc) => {
                batch.delete(comentarioDoc.ref);
            });

            // Eliminar el post
            batch.delete(postRef);
        }

        await batch.commit();
        console.log("Posts del usuario y sus comentarios eliminados.");
    } catch (error) {
        console.error("Error al eliminar los posts del usuario:", error.message);
    }
};


//MANEJO DE PUBLICACIONES

// Funci�n para crear un nuevo post en una categor�a espec�fica
export const crearPost = async (categoria, postData) => {
    const user = auth.currentUser; // Obtener el usuario autenticado

    if (!user) {
        console.error("Solo los usuarios registrados pueden crear posts.");
        return;
    }

    try {
        // Referencia a la colecci�n 'categorias' y la subcolecci�n 'posts'
        const categoriaRef = doc(db, "categorias", categoria);
        const postsRef = collection(categoriaRef, "posts");

        // Obtener todos los posts para determinar el nuevo ID
        const postsSnapshot = await getDocs(postsRef);

        // Determinar el nuevo ID de post incrementando el m�s alto existente
        const nuevoIdPost = postsSnapshot.size > 0
            ? Math.max(...postsSnapshot.docs.map(doc => doc.data().id)) + 1
            : 1;

        // Agregar el ID del creador, la valoraci�n inicial de 0 y el ID generado al postData
        const postDataConIdCreador = {
            ...postData,
            id_creador: user.uid, // Agrega el ID del creador al objeto del post
            valoracion: 0, // Establecer la valoraci�n inicial del post a 0, ira cambiando cuando reciba valoraciones de los usuarios
            id: nuevoIdPost // Asignar el nuevo ID incremental
        };

        // Crear un nuevo documento dentro de la subcolecci�n 'posts' con el nuevo ID
        await setDoc(doc(postsRef, nuevoIdPost.toString()), postDataConIdCreador);

        console.log("Post creado exitosamente en la categor�a:", categoria);
    } catch (error) {
        console.error("Error al crear el post:", error.message);
    }
};

// Función para eliminar un post y sus comentarios
export const eliminarPost = async (categoria, postId) => {
    try {
        // Obtener el usuario autenticado
        const user = auth.currentUser;

        if (!user) {
            console.error("Debes estar autenticado para eliminar un post.");
            return;
        }

        // Referencia al documento del post
        const postRef = doc(db, "categorias", categoria, "posts", postId);

        // Obtener el post actual
        const postSnap = await getDoc(postRef);

        if (postSnap.exists()) {
            const post = postSnap.data();

            // Verificar que el usuario autenticado sea el propietario del post
            if (post.id_creador === user.uid) {
                // Referencia a la subcolección 'comentarios' del post
                const comentariosRef = collection(postRef, "comentarios");

                // Obtener todos los comentarios en la subcolección
                const comentariosSnapshot = await getDocs(comentariosRef);

                // Eliminar todos los comentarios
                const deletePromises = comentariosSnapshot.docs.map(doc => deleteDoc(doc.ref));
                await Promise.all(deletePromises); // Esperar a que se eliminen todos los comentarios

                // Eliminar el post
                await deleteDoc(postRef);
                console.log("Post y sus comentarios eliminados exitosamente.");
            } else {
                alert("No eres el creador de este post. No puedes eliminarlo.");
                console.error("No tienes permiso para eliminar este post.");
            }
        } else {
            console.error("El post no existe.");
        }
    } catch (error) {
        console.error("Error al eliminar el post:", error.message);
    }
};



// Funci�n para mostrar la vista detallada de un post y todos sus comentarios
export const mostrarDetallePost = async (categoria, postId) => {
    try {
        // Referencia al documento del post
        const postRef = doc(db, "categorias", categoria, "posts", postId);
        const postSnap = await getDoc(postRef);

        if (postSnap.exists()) {
            const postData = postSnap.data();

            // Mostrar informaci�n del post
            console.log(`T�tulo: ${postData.titulo}`);
            console.log(`Descripci�n: ${postData.descripcion || 'No disponible'}`);
            console.log(`Link de Descarga: ${postData.link_descarga || 'No disponible'}`);
            console.log(`Tutorial: ${postData.tutorial_instalacion || 'No disponible'}`);
            console.log(`Valoraci�n: ${postData.valoracion || 0}`);

            // Obtener y mostrar comentarios
            const comentarios = await obtenerComentariosDelPost(categoria, postId);
            console.log("Comentarios:");
            comentarios.forEach(comentario => {
                console.log(`- ${comentario.comentario} (Nombre del Usuario: ${comentario.nombre})`);
            });
        } else {
            console.log("El post no existe.");
        }
    } catch (error) {
        console.error("Error al mostrar el detalle del post:", error.message);
    }
};

// Funci�n auxiliar para obtener los comentarios de un post
const obtenerComentariosDelPost = async (categoria, postId) => {
    try {
        const postRef = doc(db, "categorias", categoria, "posts", postId);
        const postSnap = await getDoc(postRef);

        if (postSnap.exists()) {
            // Referencia a la subcolecci�n 'comentarios' del post
            const comentariosRef = collection(postRef, "comentarios");
            const comentariosSnapshot = await getDocs(comentariosRef);

            // Crear un array para almacenar los comentarios
            const comentarios = comentariosSnapshot.docs.map(doc => ({
                id_comentario: doc.data().id_comentario,
                id_creador: doc.data().id_creador,
                nombre: doc.data().nombre,
                comentario: doc.data().comentario,
                fecha: doc.data().fecha
            }));

            return comentarios; // Retorna los comentarios obtenidos
        } else {
            console.error("El post no existe.");
            return [];
        }
    } catch (error) {
        console.error("Error al obtener los comentarios del post:", error.message);
        return [];
    }
};


// Funci�n para valorar un post y recalcular su valoraci�n media
const valorarPost = async (categoria, postId, valoracion) => {
    try {
        console.log(`Valorando el post: ${postId} en la categor�a: ${categoria} con una valoraci�n de: ${valoracion}`);

        const user = auth.currentUser;

        if (!user) {
            console.error("Debes estar autenticado para valorar un post.");
            return;
        }

        const userId = user.uid; // Obtener el ID del usuario autenticado
        const postRef = doc(db, "categorias", categoria, "posts", postId);

        const postSnapshot = await getDoc(postRef);

        if (postSnapshot.exists()) {
            const postData = postSnapshot.data();

            // Revisar si el usuario ya valor� el post
            const valoracionesActuales = postData.valoraciones || [];
            const valoracionExistente = valoracionesActuales.find(v => v.id_usuario === userId);

            if (valoracionExistente) {
                // Actualizar la valoraci�n existente
                valoracionExistente.valoracion = valoracion;
            } else {
                // A�adir nueva valoraci�n
                valoracionesActuales.push({ id_usuario: userId, valoracion: valoracion });
            }

            // Calcular la nueva valoraci�n media del post
            const totalValoraciones = valoracionesActuales.length;
            const sumaValoraciones = valoracionesActuales.reduce((sum, val) => sum + val.valoracion, 0);
            const nuevaValoracionMedia = totalValoraciones > 0 ? (sumaValoraciones / totalValoraciones) : 0;

            // Actualizar el post con la nueva valoraci�n y lista de valoraciones
            await setDoc(postRef, {
                valoraciones: valoracionesActuales,
                valoracion: nuevaValoracionMedia
            }, { merge: true });

            console.log("Post valorado con �xito.");

            // Recalcular la valoraci�n del creador del post solo si el valorador no es el creador, para no inflarse la nota de sus propios posts.
            const idCreador = postData.id_creador; // Obtener el id del creador del post
            if (userId !== idCreador) {
                await recalcularValoracionUsuario(idCreador);
            }
        } else {
            console.log("El post no existe.");
        }
    } catch (error) {
        console.error("Error al valorar el post:", error.message);
    }
};

//Funcion auxiliar para recalcular la valoracion de un usuario
const recalcularValoracionUsuario = async (userId) => {
    try {
        // Obtener todas las categor�as
        const categoriasSnapshot = await getDocs(collection(db, "categorias"));
        let totalValoraciones = 0;
        let totalPosts = 0;

        // Iterar sobre cada categor�a
        for (const categoriaDoc of categoriasSnapshot.docs) {
            const categoriaId = categoriaDoc.id;
            const postsRef = collection(db, "categorias", categoriaId, "posts");
            const postsSnapshot = await getDocs(postsRef);

            // Iterar sobre cada post en la categor�a
            postsSnapshot.forEach((postDoc) => {
                const postData = postDoc.data();
                // Verificar si el post tiene valoraciones
                if (postData.valoracion) {
                    totalPosts++;
                    totalValoraciones += postData.valoracion; // Aseg�rate de manejar valores no definidos
                }
            });
        }

        console.log("Total de posts:", totalPosts);

        // Calcular la nueva valoraci�n media del usuario
        const nuevaValoracionUsuario = totalPosts > 0 ? (totalValoraciones / totalPosts) : 0;

        // Actualizar la valoraci�n del usuario en Firestore
        const userRef = doc(db, "usuarios", userId);
        await setDoc(userRef, {
            valoracion: nuevaValoracionUsuario
        }, { merge: true });

        console.log("Valoraci�n del usuario recalculada con �xito.");
    } catch (error) {
        console.error("Error al recalcular la valoraci�n del usuario:", error.message);
    }
};


//GESTION DE LOS COMENTARIOS

// Funci�n para comentar un post
const comentarPost = async (categoria, postId, comentario) => {
    try {
        const user = auth.currentUser; // Obtener el usuario autenticado
        if (!user) {
            console.error("Debes estar autenticado para comentar.");
            return;
        }

        // Referencia al documento del usuario en Firestore
        const userRef = doc(db, "usuarios", user.uid);
        const userSnapshot = await getDoc(userRef);

        // Obtener el username del documento del usuario
        const username = userSnapshot.exists() ? userSnapshot.data().username : user.email;

        const postRef = doc(db, "categorias", categoria, "posts", postId);
        const postSnapshot = await getDoc(postRef);

        if (postSnapshot.exists()) {
            // Referencia a la subcolecci�n 'comentarios' del post
            const comentariosRef = collection(postRef, "comentarios");

            // Obtener todos los comentarios para determinar el nuevo ID
            const comentariosSnapshot = await getDocs(comentariosRef);

            // Determinar el nuevo ID de comentario incrementando el m�s alto existente
            const nuevoIdComentario = comentariosSnapshot.size > 0
                ? Math.max(...comentariosSnapshot.docs.map(doc => doc.data().id_comentario)) + 1
                : 1;

            // Crear el objeto del nuevo comentario
            const nuevoComentario = {
                id_comentario: nuevoIdComentario,
                id_creador: user.uid, // Agregar el ID del creador
                nombre: username, // Usar el username obtenido
                comentario: comentario,
                fecha: new Date().toISOString()
            };

            // Crear un nuevo documento dentro de la subcolecci�n 'comentarios' con el nuevo ID
            await setDoc(doc(comentariosRef, nuevoIdComentario.toString()), nuevoComentario);

            console.log("Comentario a�adido con �xito.");
        } else {
            console.log("El post no existe.");
        }
    } catch (error) {
        console.error("Error al a�adir el comentario:", error.message);
    }
};


// Funci�n para editar un comentario
const editarComentario = async (categoria, postId, comentarioId, nuevoComentario) => {
    try {
        const user = auth.currentUser; // Obtener el usuario autenticado
        if (!user) {
            console.error("Debes estar autenticado para editar un comentario.");
            return;
        }

        const postRef = doc(db, "categorias", categoria, "posts", postId);
        const postSnapshot = await getDoc(postRef);

        if (postSnapshot.exists()) {
            // Referencia a la subcolecci�n 'comentarios' del post
            const comentarioRef = doc(postRef, "comentarios", comentarioId);
            const comentarioSnapshot = await getDoc(comentarioRef);

            if (comentarioSnapshot.exists()) {
                const comentarioExistente = comentarioSnapshot.data();

                // Verificar si el usuario es el creador del comentario
                if (comentarioExistente.id_creador === user.uid) {
                    // Actualizar el comentario
                    await setDoc(comentarioRef, {
                        comentario: nuevoComentario,
                        fecha_editado: new Date().toISOString(),
                        nombre: user.displayName || user.email // Actualiza el nombre
                    }, { merge: true });

                    console.log("Comentario editado con �xito.");
                } else {
                    console.log("No tienes permiso para editar este comentario.");
                }
            } else {
                console.log("Comentario no encontrado.");
            }
        } else {
            console.log("El post no existe.");
        }
    } catch (error) {
        console.error("Error al editar el comentario:", error.message);
    }
};

// Funci�n para eliminar un comentario
const eliminarComentario = async (categoria, postId, comentarioId) => {
    try {
        const user = auth.currentUser; // Obtener el usuario autenticado
        if (!user) {
            console.error("Debes estar autenticado para eliminar un comentario.");
            return;
        }

        const postRef = doc(db, "categorias", categoria, "posts", postId);
        const postSnapshot = await getDoc(postRef);

        if (postSnapshot.exists()) {
            // Referencia a la subcolecci�n 'comentarios' del post
            const comentarioRef = doc(postRef, "comentarios", comentarioId);
            const comentarioSnapshot = await getDoc(comentarioRef);

            if (comentarioSnapshot.exists()) {
                const comentarioExistente = comentarioSnapshot.data();

                // Verificar si el usuario es el creador del comentario
                if (comentarioExistente.id_creador === user.uid) {
                    // Eliminar el comentario
                    await deleteDoc(comentarioRef);
                    console.log("Comentario eliminado con �xito.");
                } else {
                    console.log("No tienes permiso para eliminar este comentario.");
                }
            } else {
                console.log("Comentario no encontrado.");
            }
        } else {
            console.log("El post no existe.");
        }
    } catch (error) {
        console.error("Error al eliminar el comentario:", error.message);
    }
};

//Funcion con p�ginacion para obtener una lista de todos los posts pertenecientes a una categoria.
export const obtenerPostsPorCategoria = async (categoria, limite = 10, ultimoPost = null) => {
    try {
        // Referencia a la colecci�n 'categorias' y la subcolecci�n 'posts'
        const categoriaRef = doc(db, "categorias", categoria);
        const postsRef = collection(categoriaRef, "posts");

        // Crear una consulta para obtener los posts ordenados por valoraciones (de mayor a menor)
        let q = query(postsRef, orderBy("valoracion", "desc"), limit(limite));

        // Si hay un �ltimo post, usarlo para la paginaci�n
        if (ultimoPost) {
            q = query(q, startAfter(ultimoPost));
        }

        // Ejecutar la consulta
        const querySnapshot = await getDocs(q);

        // Crear un array para almacenar los posts
        const posts = [];
        let lastPost = null;

        querySnapshot.forEach((doc) => {
            // Agregar solo el t�tulo y la valoraci�n al array
            const { titulo, valoracion } = doc.data();
            posts.push({ id: doc.id, titulo, valoracion });
            lastPost = doc; // Guardar el �ltimo post para la paginaci�n
        });

        console.log("Posts obtenidos con �xito:", posts);
        return { posts, lastPost }; // Retornar los posts obtenidos y el �ltimo post
    } catch (error) {
        console.error("Error al obtener los posts:", error.message);
    }
};