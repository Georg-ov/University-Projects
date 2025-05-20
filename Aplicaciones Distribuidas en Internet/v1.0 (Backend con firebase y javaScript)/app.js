// Importar Firebase
import { initializeApp } from "firebase/app";
import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword, signOut, sendPasswordResetEmail, deleteUser } from "firebase/auth";
import { getFirestore, doc, setDoc, deleteDoc, collection, addDoc, getDoc, query, orderBy, getDocs, where, updateDoc, collectionGroup, writeBatch, startAfter, limit } from "firebase/firestore";

// Importar Coinbase Commerce
import pkg from 'coinbase-commerce-node';
const { Client, resources } = pkg;
const { Charge } = resources; // Asegúrate de extraer Charge

// Inicializar Coinbase Commerce con la API Key
Client.init('c7d2fe3f-38fd-4955-aef2-f5f65fa1811a'); // Reemplaza con tu clave de API de Coinbase

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

//Hacemos uso de la API de CoinBase
const crearDonacionEnlace = async (username) => {
    try {
        // Buscamos el usuario por el nombre de usuario para obtener el userId
        const userQuery = await getDocs(query(collection(db, "usuarios"), where("username", "==", username)));
        if (userQuery.empty) {
            throw new Error("Usuario no encontrado");
        }

        const userDoc = userQuery.docs[0];
        const userId = userDoc.id; // Obtenemos el ID del usuario

        const chargeData = {
            name: `Donación para ${username}`,
            description: `Apoya a ${username} con una donación.`,
            local_price: {
                amount: "0.00",
                currency: "RUB",
            },
            pricing_type: "no_price",
            metadata: {
                userId: userId
            }
        };

        console.log("Datos del cargo:", chargeData); // Información de depuración

        // Crear el enlace de donación
        const charge = await Charge.create(chargeData);
        if (!charge || !charge.hosted_url) {
            throw new Error("Charge no se creó correctamente");
        }

        // Guardar el enlace de donación en Firestore para el usuario
        const userRef = doc(db, "usuarios", userId);
        await setDoc(userRef, {
            donaciones: charge.hosted_url
        }, { merge: true });

        console.log("Enlace de donación creado con éxito:", charge.hosted_url);
        return charge.hosted_url;
    } catch (error) {
        console.error("Error al crear el enlace de donación:", error.message);
        throw error;
    }
};

//      MANEJO DE USUARIOS

// Función para registrar un usuario
const registerUser = async (email, password) => {
    try {
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        // Generar nombre de usuario
        const username = `liberator_${user.uid}`; // Usa el UID de Firebase como base

        // Crear el objeto de usuario
        const userData = {
            id: user.uid, // Usar el UID de Firebase como ID del usuario
            username: username,
            valoracion: 0, // Valoración inicial, al principio será 0 y se irá modificando.
            email: user.email,
            profileImage: null,
            description: null, // Descripción inicial nula
            donaciones: null // Campo para almacenar el enlace de donaciones
        };

        // Guardar datos en Firestore usando el UID del usuario
        const usuarioRef = doc(db, "usuarios", user.uid); // Usa el UID directamente como ID
        await setDoc(usuarioRef, userData);

        // Crear enlace de donaciones para el usuario
        const donacionEnlace = await crearDonacionEnlace(username);
        console.log(`Enlace de donación creado para el usuario: ${donacionEnlace}`);

        console.log("Usuario registrado con éxito:", user.email);
    } catch (error) {
        console.error("Error al registrar:", error.message);
    }
};


// Función para iniciar sesión
const loginUser = async (email, password) => {
    try {
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;
        console.log("El usuario", user.email, "ha iniciado sesión");
    } catch (error) {
        console.error("Error al iniciar sesión:", error.message);
    }
};

// Función para cerrar sesión
const logoutUser = async () => {
    try {
        await signOut(auth);
        console.log("Sesión cerrada.");
    } catch (error) {
        console.error("Error al cerrar sesión:", error.message);
    }
};

// Función para restablecer contraseña
const resetPassword = async (email) => {
    try {
        await sendPasswordResetEmail(auth, email);
        console.log("Se ha enviado un correo para restablecer la contraseña a:", email);
    } catch (error) {
        console.error("Error al enviar el correo de restablecimiento:", error.message);
    }
};

// Función para editar el perfil de un usuario
const editProfile = async (newUsername, newDescription, newProfileImage) => {
    try {
        const user = auth.currentUser; // Obtener el usuario actualmente autenticado
        if (!user) {
            console.log("No tienes permiso para editar este perfil.");
            return;
        }

        const userId = user.uid; // Obtener el ID del usuario autenticado
        const userRef = doc(db, "usuarios", userId);

        // Verificar si el nuevo username ya está en uso
        const usuariosRef = collection(db, "usuarios");
        const q = query(usuariosRef, where("username", "==", newUsername));
        const usuariosSnapshot = await getDocs(q);

        // Si el username ya está en uso por otro usuario se cancela la edición.
        if (!usuariosSnapshot.empty && usuariosSnapshot.docs[0].id !== userId) {
            console.log("El nombre de usuario ya está en uso por otro usuario.");
            return;
        }

        // Actualizar el documento del usuario
        await setDoc(userRef, {
            username: newUsername,
            description: newDescription,
            profileImage: newProfileImage
        }, { merge: true }); // merge: true asegura que no se sobrescriban otros campos

        console.log("Perfil actualizado con éxito.");
    } catch (error) {
        console.error("Error al actualizar el perfil:", error.message);
    }
};


// Función para mostrar el perfil de un usuario, incluyendo el enlace de donaciones y todos sus posts
const mostrarPerfilUsuario = async (userId) => {
    try {
        const userDoc = await getDoc(doc(db, "usuarios", userId));

        // Verifica si el documento existe
        if (!userDoc.exists()) {
            console.log(`El perfil del liberator con ID ${userId} no existe.`);
            return;
        }

        const userData = userDoc.data();

        // Mostrar información del usuario
        console.log(`Nombre de usuario: ${userData.username}`);
        console.log(`Descripción: ${userData.descripcion || 'No disponible'}`);
        console.log(`Valoración: ${userData.valoracion || 0}`);

        if (userData.foto_perfil) {
            console.log(`Foto de perfil: ${userData.foto_perfil}`);
            // Aquí puedes mostrar la imagen de perfil en el frontend
        } else {
            console.log("Este liberator no tiene foto de perfil.");
        }

        if (userData.donaciones) {
            console.log(`El liberator ${userData.username} acepta donaciones en: ${userData.donaciones}`);
            // Aquí puedes mostrar el enlace de donaciones en el frontend
        } else {
            console.log("Este liberator no ha activado donaciones.");
        }

        // Obtener los posts del usuario
        const posts = await obtenerPostsDelUsuario(userId);
        console.log(`Posts de ${userData.username}:`); // Cambiado para mostrar el nombre del usuario
        posts.forEach(post => {
            console.log(`- ${post.titulo} - Valoración: ${post.valoracion}`);
        });

    } catch (error) {
        console.error("Error al obtener el perfil del usuario:", error.message);
    }
};

//Función auxiliar para obtener la lista de posts de un usuario
const obtenerPostsDelUsuario = async (userId) => {
    try {
        // Hacer una consulta en todas las categorías
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

// Función para eliminar el usuario y sus posts, los comentarios no los borraremos.
const eliminarUsuario = async () => {
    const auth = getAuth();
    const user = auth.currentUser;

    if (!user) {
        console.error("No hay un usuario autenticado.");
        return;
    }

    const userId = user.uid; // Obtén el ID del usuario autenticado

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

    } catch (error) {
        console.error("Error al eliminar el usuario:", error.message);
    }
}

// Función auxiliar para eliminar los posts del usuario y los comentarios del post.
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

            // Referencia a la subcolección 'comentarios' del post
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

// Función para crear un nuevo post en una categoría específica
const crearPost = async (categoria, postData) => {
    const user = auth.currentUser; // Obtener el usuario autenticado

    if (!user) {
        console.error("Solo los usuarios registrados pueden crear posts.");
        return;
    }

    try {
        // Referencia a la colección 'categorias' y la subcolección 'posts'
        const categoriaRef = doc(db, "categorias", categoria);
        const postsRef = collection(categoriaRef, "posts");

        // Obtener todos los posts para determinar el nuevo ID
        const postsSnapshot = await getDocs(postsRef);

        // Determinar el nuevo ID de post incrementando el más alto existente
        const nuevoIdPost = postsSnapshot.size > 0
            ? Math.max(...postsSnapshot.docs.map(doc => doc.data().id)) + 1
            : 1;

        // Agregar el ID del creador, la valoración inicial de 0 y el ID generado al postData
        const postDataConIdCreador = {
            ...postData,
            id_creador: user.uid, // Agrega el ID del creador al objeto del post
            valoracion: 0, // Establecer la valoración inicial del post a 0, ira cambiando cuando reciba valoraciones de los usuarios
            id: nuevoIdPost // Asignar el nuevo ID incremental
        };

        // Crear un nuevo documento dentro de la subcolección 'posts' con el nuevo ID
        await setDoc(doc(postsRef, nuevoIdPost.toString()), postDataConIdCreador);

        console.log("Post creado exitosamente en la categoría:", categoria);
    } catch (error) {
        console.error("Error al crear el post:", error.message);
    }
};

// Función para editar un post
const editarPost = async (categoria, postId, nuevosDatos) => {
    try {
        // Obtener el usuario autenticado
        const user = auth.currentUser;

        if (!user) {
            console.error("Debes estar autenticado para editar un post.");
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
                // Crear un objeto para los datos de actualización
                const updateData = {};

                // Solo agregar los campos que tienen un valor definido
                if (nuevosDatos.titulo) {
                    updateData.titulo = nuevosDatos.titulo;
                }
                if (nuevosDatos.link_descarga) {
                    updateData.link_descarga = nuevosDatos.link_descarga;
                }
                if (nuevosDatos.descripcion) {
                    updateData.descripcion = nuevosDatos.descripcion;
                }
                if (nuevosDatos.tutorial_instalacion) {
                    updateData.tutorial_instalacion = nuevosDatos.tutorial_instalacion;
                }

                // Actualizar el post solo con los campos que se han definido
                await updateDoc(postRef, updateData);

                console.log("Post actualizado exitosamente.");
            } else {
                console.error("No tienes permiso para editar este post.");
            }
        } else {
            console.error("El post no existe.");
        }
    } catch (error) {
        console.error("Error al editar el post:", error.message);
    }
};


// Función para eliminar un post y sus comentarios
const eliminarPost = async (categoria, postId) => {
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
                console.error("No tienes permiso para eliminar este post.");
            }
        } else {
            console.error("El post no existe.");
        }
    } catch (error) {
        console.error("Error al eliminar el post:", error.message);
    }
};


// Función para mostrar la vista detallada de un post y todos sus comentarios
const mostrarDetallePost = async (categoria, postId) => {
    try {
        // Referencia al documento del post
        const postRef = doc(db, "categorias", categoria, "posts", postId);
        const postSnap = await getDoc(postRef);

        if (postSnap.exists()) {
            const postData = postSnap.data();

            // Mostrar información del post
            console.log(`Título: ${postData.titulo}`);
            console.log(`Descripción: ${postData.descripcion || 'No disponible'}`);
            console.log(`Link de Descarga: ${postData.link_descarga || 'No disponible'}`);
            console.log(`Tutorial: ${postData.tutorial_instalacion || 'No disponible'}`);
            console.log(`Valoración: ${postData.valoracion || 0}`);

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

// Función auxiliar para obtener los comentarios de un post
const obtenerComentariosDelPost = async (categoria, postId) => {
    try {
        const postRef = doc(db, "categorias", categoria, "posts", postId);
        const postSnap = await getDoc(postRef);

        if (postSnap.exists()) {
            // Referencia a la subcolección 'comentarios' del post
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


// Función para valorar un post y recalcular su valoración media
const valorarPost = async (categoria, postId, valoracion) => {
    try {
        console.log(`Valorando el post: ${postId} en la categoría: ${categoria} con una valoración de: ${valoracion}`);

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

            // Revisar si el usuario ya valoró el post
            const valoracionesActuales = postData.valoraciones || [];
            const valoracionExistente = valoracionesActuales.find(v => v.id_usuario === userId);

            if (valoracionExistente) {
                // Actualizar la valoración existente
                valoracionExistente.valoracion = valoracion;
            } else {
                // Añadir nueva valoración
                valoracionesActuales.push({ id_usuario: userId, valoracion: valoracion });
            }

            // Calcular la nueva valoración media del post
            const totalValoraciones = valoracionesActuales.length;
            const sumaValoraciones = valoracionesActuales.reduce((sum, val) => sum + val.valoracion, 0);
            const nuevaValoracionMedia = totalValoraciones > 0 ? (sumaValoraciones / totalValoraciones) : 0;

            // Actualizar el post con la nueva valoración y lista de valoraciones
            await setDoc(postRef, {
                valoraciones: valoracionesActuales,
                valoracion: nuevaValoracionMedia
            }, { merge: true });

            console.log("Post valorado con éxito.");

            // Recalcular la valoración del creador del post solo si el valorador no es el creador, para no inflarse la nota de sus propios posts.
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
        // Obtener todas las categorías
        const categoriasSnapshot = await getDocs(collection(db, "categorias"));
        let totalValoraciones = 0;
        let totalPosts = 0;

        // Iterar sobre cada categoría
        for (const categoriaDoc of categoriasSnapshot.docs) {
            const categoriaId = categoriaDoc.id;
            const postsRef = collection(db, "categorias", categoriaId, "posts");
            const postsSnapshot = await getDocs(postsRef);

            // Iterar sobre cada post en la categoría
            postsSnapshot.forEach((postDoc) => {
                const postData = postDoc.data();
                // Verificar si el post tiene valoraciones
                if (postData.valoracion) {
                    totalPosts++;
                    totalValoraciones += postData.valoracion; // Asegúrate de manejar valores no definidos
                }
            });
        }

        console.log("Total de posts:", totalPosts);

        // Calcular la nueva valoración media del usuario
        const nuevaValoracionUsuario = totalPosts > 0 ? (totalValoraciones / totalPosts) : 0;

        // Actualizar la valoración del usuario en Firestore
        const userRef = doc(db, "usuarios", userId);
        await setDoc(userRef, {
            valoracion: nuevaValoracionUsuario
        }, { merge: true });

        console.log("Valoración del usuario recalculada con éxito.");
    } catch (error) {
        console.error("Error al recalcular la valoración del usuario:", error.message);
    }
};


//GESTION DE LOS COMENTARIOS

// Función para comentar un post
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
            // Referencia a la subcolección 'comentarios' del post
            const comentariosRef = collection(postRef, "comentarios");

            // Obtener todos los comentarios para determinar el nuevo ID
            const comentariosSnapshot = await getDocs(comentariosRef);

            // Determinar el nuevo ID de comentario incrementando el más alto existente
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

            // Crear un nuevo documento dentro de la subcolección 'comentarios' con el nuevo ID
            await setDoc(doc(comentariosRef, nuevoIdComentario.toString()), nuevoComentario);

            console.log("Comentario añadido con éxito.");
        } else {
            console.log("El post no existe.");
        }
    } catch (error) {
        console.error("Error al añadir el comentario:", error.message);
    }
};


// Función para editar un comentario
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
            // Referencia a la subcolección 'comentarios' del post
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

                    console.log("Comentario editado con éxito.");
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

// Función para eliminar un comentario
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
            // Referencia a la subcolección 'comentarios' del post
            const comentarioRef = doc(postRef, "comentarios", comentarioId);
            const comentarioSnapshot = await getDoc(comentarioRef);

            if (comentarioSnapshot.exists()) {
                const comentarioExistente = comentarioSnapshot.data();

                // Verificar si el usuario es el creador del comentario
                if (comentarioExistente.id_creador === user.uid) {
                    // Eliminar el comentario
                    await deleteDoc(comentarioRef);
                    console.log("Comentario eliminado con éxito.");
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

//Funcion con páginacion para obtener una lista de todos los posts pertenecientes a una categoria.
const obtenerPostsPorCategoria = async (categoria, limite = 10, ultimoPost = null) => {
    try {
        // Referencia a la colección 'categorias' y la subcolección 'posts'
        const categoriaRef = doc(db, "categorias", categoria);
        const postsRef = collection(categoriaRef, "posts");

        // Crear una consulta para obtener los posts ordenados por valoraciones (de mayor a menor)
        let q = query(postsRef, orderBy("valoracion", "desc"), limit(limite));

        // Si hay un último post, usarlo para la paginación
        if (ultimoPost) {
            q = query(q, startAfter(ultimoPost));
        }

        // Ejecutar la consulta
        const querySnapshot = await getDocs(q);

        // Crear un array para almacenar los posts
        const posts = [];
        let lastPost = null;

        querySnapshot.forEach((doc) => {
            // Agregar solo el título y la valoración al array
            const { titulo, valoracion } = doc.data();
            posts.push({ id: doc.id, titulo, valoracion });
            lastPost = doc; // Guardar el último post para la paginación
        });

        console.log("Posts obtenidos con éxito:", posts);
        return { posts, lastPost }; // Retornar los posts obtenidos y el último post
    } catch (error) {
        console.error("Error al obtener los posts:", error.message);
    }
};