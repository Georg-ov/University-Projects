import { createSignal } from "solid-js";

const Lista = () => {
    const [recados, setRecados] = createSignal([]);
    const [newRecado, setNewRecado] = createSignal("");
    const [newFechaLimite, setNewFechaLimite] = createSignal("");
    const [editRecado, setEditRecado] = createSignal(null);
    const [showDetails, setShowDetails] = createSignal(null); // Estado para controlar la visibilidad de los detalles

    // Crear un nuevo recado
    const handleCreate = () => {
        if (newRecado() && newFechaLimite()) {
            setRecados([
                ...recados(),
                { id: Date.now(), text: newRecado(), fechaLimite: newFechaLimite() }
            ]);
            setNewRecado("");
            setNewFechaLimite("");
        }
    };

    // Eliminar un recado
    const handleDelete = (id) => {
        setRecados(recados().filter((recado) => recado.id !== id));
    };

    // Editar un recado
    const handleEdit = (id) => {
        const recado = recados().find((recado) => recado.id === id);
        setEditRecado({ ...recado }); // Hacer una copia del recado para no modificar el estado directamente
    };

    // Guardar el recado editado
    const handleSaveEdit = () => {
        setRecados(
            recados().map((recado) =>
                recado.id === editRecado().id
                    ? { ...recado, text: editRecado().text, fechaLimite: editRecado().fechaLimite }
                    : recado
            )
        );
        setEditRecado(null);
    };

    // Toggle para mostrar/ocultar detalles
    const handleToggleDetails = (id) => {
        setShowDetails(showDetails() === id ? null : id);
    };

    return (
        <div class="container">
            <h2>Siguiente Territorio a Conquistar</h2>

            {/* Agregar un nuevo territorio */}
            <input
                type="text"
                placeholder="Nuevo Territorio"
                value={newRecado()}
                onInput={(e) => setNewRecado(e.target.value)}
                class="input"
            />
            <input
                type="text"
                placeholder="Pais"
                value={newFechaLimite()}
                onInput={(e) => setNewFechaLimite(e.target.value)}
                class="input"
            />
            <button onClick={handleCreate} class="button">Agregar</button>

            {/* Mostrar los territorios */}
            <ul>
                {recados().map((recado) => (
                    <li key={recado.id} class="territory">
                        {editRecado()?.id === recado.id ? (
                            <div>
                                <input
                                    type="text"
                                    value={editRecado().text}
                                    onInput={(e) => setEditRecado({ ...editRecado(), text: e.target.value })}
                                    class="input"
                                />
                                <input
                                    type="text"
                                    value={editRecado().fechaLimite}
                                    onInput={(e) => setEditRecado({ ...editRecado(), fechaLimite: e.target.value })}
                                    class="input"
                                />
                                <button onClick={handleSaveEdit} class="button">Guardar</button>
                            </div>
                        ) : (
                            <div>
                                {recado.text}
                                <div class="button-container">
                                    <button onClick={() => handleEdit(recado.id)} class="button">Editar</button>
                                    <button onClick={() => handleDelete(recado.id)} class="button">Eliminar</button>
                                    <button onClick={() => handleToggleDetails(recado.id)} class="button">
                                        {showDetails() === recado.id ? "Ocultar Detalles" : "Ver Detalles"}
                                    </button>
                                </div>

                                {/* Mostrar detalles */}
                                {showDetails() === recado.id && (
                                    <div class="details">
                                        <p><strong>Pais:</strong> {recado.fechaLimite}</p>
                                    </div>
                                )}
                            </div>
                        )}
                    </li>
                ))}
            </ul>

            {/* CSS Interno */}
            <style jsx>{`
                /* Estilos generales */
                .container {
                    font-family: Arial, sans-serif;
                    background-color: #fff6d1; /* Amarillo claro */
                    margin: 0;
                    padding: 20px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    max-width: 600px;
                    margin: auto;
                    border-radius: 8px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }

                h2 {
                    text-align: center;
                    color: #c8102e; /* Rojo */
                    font-size: 2.5rem;
                    margin-bottom: 20px;
                    font-weight: bold;
                }

                .input {
                    width: calc(100% - 20px);
                    padding: 10px;
                    margin: 10px 0;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    font-size: 1rem;
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

                .territory {
                    background-color: #ffcd00; /* Amarillo */
                    padding: 15px;
                    margin: 10px 0;
                    border-radius: 6px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    transition: background-color 0.3s ease;
                }

                .territory:hover {
                    background-color: #f8b800; /* Amarillo más oscuro */
                }

                .details {
                    margin-top: 10px;
                    padding: 5px;
                    background-color: #f8b800;
                    border-radius: 4px;
                }

                .button-container {
                    display: flex;
                    gap: 10px; /* Espacio entre los botones */
                }
            `}</style>
        </div>
    );
};

export default Lista;
