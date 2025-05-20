// Ejecutando funciones
document.getElementById("btn__iniciar-sesion").addEventListener("click", iniciarSesion);
document.getElementById("btn__registrarse").addEventListener("click", register);

// Declarando variables
var formulario_login = document.querySelector(".formulario__login");
var formulario_register = document.querySelector(".formulario__register");
var contenedor_login_register = document.querySelector(".contenedor__login-register");
var caja_trasera_login = document.querySelector(".caja__trasera-login");
var caja_trasera_register = document.querySelector(".caja__trasera-register");

// FUNCIONES

function iniciarSesion(event) {
    event.preventDefault();
    if (window.innerWidth > 850) {
        formulario_login.style.opacity = "1";
        formulario_login.style.zIndex = "10";
        formulario_register.style.opacity = "0";
        formulario_register.style.zIndex = "1";
        contenedor_login_register.style.left = "10px";
        caja_trasera_register.style.opacity = "1";
        caja_trasera_login.style.opacity = "0";
    } else {
        formulario_login.style.display = "block";
        formulario_register.style.display = "none";
        contenedor_login_register.style.left = "0px";
        caja_trasera_register.style.display = "block";
        caja_trasera_login.style.display = "none";
        caja_trasera_login.style.opacity = "1";
    }
}

function register(event) {
    event.preventDefault();
    if (window.innerWidth > 850) {
        formulario_login.style.opacity = "0";
        formulario_login.style.zIndex = "1";
        formulario_register.style.opacity = "1";
        formulario_register.style.zIndex = "10";
        contenedor_login_register.style.left = "410px";
        caja_trasera_register.style.opacity = "0";
        caja_trasera_login.style.opacity = "1";
    } else {
        formulario_login.style.display = "none";
        formulario_register.style.display = "block";
        contenedor_login_register.style.left = "0px";
        caja_trasera_register.style.display = "none";
        caja_trasera_login.style.display = "block";
        caja_trasera_login.style.opacity = "1";
    }
}