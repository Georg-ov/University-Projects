import { auth } from "../firebase-config";  // Importar la instancia de auth
import { createSignal } from "solid-js";
import { createUserWithEmailAndPassword } from "firebase/auth";  // Importar las funciones de Firebase

const Register = ({ setCurrentView }) => { // Recibe setCurrentView como prop
    const [email, setEmail] = createSignal("");
    const [password, setPassword] = createSignal("");
    const [message, setMessage] = createSignal("");

    const handleRegister = async () => {
        try {
            const userCredential = await createUserWithEmailAndPassword(auth, email(), password());
            setMessage(`Usuario registrado: ${userCredential.user.email}`);
            setCurrentView("login");  // Redirigir al login después de registrar
        } catch (error) {
            setMessage(`Error: ${error.message}`);
        }
    };

    return (
        <div class="register-container">
            <h2>Registro</h2>
            <input
                type="email"
                placeholder="Correo electronico"
                value={email()}
                onInput={(e) => setEmail(e.target.value)}
                class="input-field"
            />
            <input
                type="password"
                placeholder="Contrasena"
                value={password()}
                onInput={(e) => setPassword(e.target.value)}
                class="input-field"
            />
            <button onClick={handleRegister} class="button">Registrar</button>
            <p class="message">{message()}</p>

            <style jsx>{`
                .register-container {
                    font-family: Arial, sans-serif;
                    background-color: #fff6d1; /* Amarillo claro */
                    padding: 20px;
                    max-width: 400px;
                    margin: auto;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    text-align: center;
                }

                h2 {
                    color: #c8102e; /* Rojo */
                    font-size: 2rem;
                    margin-bottom: 20px;
                    font-weight: bold;
                }

                .input-field {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 10px;
                    border: 1px solid #c8102e; /* Rojo */
                    border-radius: 4px;
                    font-size: 1rem;
                    box-sizing: border-box;
                }

                .button {
                    padding: 12px 20px;
                    background-color: #c8102e; /* Rojo */
                    color: white;
                    border: none;
                    border-radius: 4px;
                    font-size: 1rem;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    width: 100%;
                }

                .button:hover {
                    background-color: #a30f1f; /* Rojo más oscuro */
                }

                .message {
                    margin-top: 15px;
                    font-size: 1rem;
                    color: #a30f1f; /* Rojo más oscuro */
                }
            `}</style>
        </div>
    );
};

export default Register;
