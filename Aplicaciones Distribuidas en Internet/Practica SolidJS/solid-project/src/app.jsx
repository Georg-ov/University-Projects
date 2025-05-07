import { createSignal } from "solid-js";
import Register from './components/Register';
import Login from './components/Login';
import Lista from './components/Lista';

const App = () => {
    const [currentView, setCurrentView] = createSignal("register"); // Empezamos con "register"

    return (
        <div class="app-container">
            <h1 class="main-title">Conquistar el Mundo</h1>

            {/* Renderizamos la vista correspondiente */}
            {currentView() === "login" && <Login setCurrentView={setCurrentView} />}
            {currentView() === "register" && <Register setCurrentView={setCurrentView} />}
            {currentView() === "lista" && <Lista />}

            <style jsx>{`
                /* Estilos generales */
                .app-container {
                    font-family: Arial, sans-serif;
                    background-color: #fff6d1; /* Amarillo claro */
                    margin: 0;
                    padding: 20px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    max-width: 800px;
                    margin: auto;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }

                .main-title {
                    text-align: center;
                    color: #c8102e; /* Rojo */
                    font-size: 3.5rem;
                    font-family: 'Georgia', serif; /* Fuente elegante */
                    font-weight: bold;
                    margin-bottom: 30px;
                    text-transform: uppercase; /* Todo en mayúsculas */
                    letter-spacing: 3px; /* Espaciado entre letras */
                    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Sombra de texto */
                    background: linear-gradient(90deg, #c8102e, #ff4500); /* Gradiente de color */
                    -webkit-background-clip: text; /* Clip del fondo al texto */
                    -webkit-text-fill-color: transparent; /* Solo mostrar el gradiente */
                }

                .button {
                    padding: 10px 20px;
                    background-color: #c8102e; /* Rojo */
                    color: white;
                    border: none;
                    border-radius: 4px;
                    font-size: 1rem;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    margin: 5px 0;
                }

                .button:hover {
                    background-color: #a30f1f; /* Rojo más oscuro */
                }
            `}</style>
        </div>
    );
};

export default App;
