//GEORG USIN, X8174555X
#include <unistd.h>
#include <iostream>
#include <fstream>
#include <sstream>
#include <vector>
#include <unordered_map>
#include <algorithm>
#include <iomanip>
#include <chrono>
#include <limits>
#include <queue>
#include <stack>
#include <memory>
#include <functional>

using namespace std;
using namespace std::chrono;

vector<vector<int>> matriz;
int n_filas, n_cols;

struct Estadisticas {
    int nodos_visitados = 0;
    int nodos_explorados = 0;
    int nodos_hoja = 0;
    int nodos_no_factibles = 0;
    int nodos_no_prometedores = 0;
    int nodos_prometedores_descartados = 0;
    int mejor_solucion_desde_hoja = 0;
    int mejor_solucion_desde_cota = 0;
    int nodos_anadidos_lista_vivos = 0;
};

Estadisticas estadisticas;

// Estructura para representar el estado visitado de forma linealizada
struct EstadoVisitado {
    vector<bool> visitado;

    EstadoVisitado(int n, int m) : visitado(n * m, false) {}

    bool operator[](pair<int, int> pos) const {
        return visitado[pos.first * n_cols + pos.second];
    }

    void marcar(int x, int y) {
        visitado[x * n_cols + y] = true;
    }

    bool esta_visitado(int x, int y) const {
        return visitado[x * n_cols + y];
    }
};

struct Nodo {
    int x, y;
    int longitud;
    shared_ptr<EstadoVisitado> visitado;
    shared_ptr<Nodo> padre; // Para reconstruir el camino

    // Para la cola de prioridad (menor longitud + heurística tiene prioridad)
    int cota_inferior() const {
        int dx = abs((n_filas-1) - x);
        int dy = abs((n_cols-1) - y);
        return longitud + max(dx, dy); //Distancia de Chebyshev
    }

    bool operator>(const Nodo& otro) const {
        return cota_inferior() > otro.cota_inferior();
    }

    // Constructor
    Nodo(int _x, int _y, int _longitud, shared_ptr<EstadoVisitado> _visitado, shared_ptr<Nodo> _padre = nullptr)
    : x(_x), y(_y), longitud(_longitud), visitado(_visitado), padre(_padre) {}
};

const int dx[8] = {0, 1, 1, 1, 0, -1, -1, -1};
const int dy[8] = {1, 1, 0, -1, -1, -1, 0, 1};
const int dir_codes[8] = {3, 4, 5, 6, 7, 8, 1, 2};

bool guardarMapa(const string& nombre_archivo) {
    ifstream archivo(nombre_archivo);
    if (!archivo.is_open()) return false;

    matriz.clear();
    string linea;
    getline(archivo, linea);

    istringstream dim(linea);
    dim >> n_filas >> n_cols;

    matriz.reserve(n_filas);
    for (int i = 0; i < n_filas; ++i) {
        getline(archivo, linea);
        istringstream ss(linea);
        vector<int> fila;
        fila.reserve(n_cols);
        int valor;
        while (ss >> valor) {
            fila.push_back(valor == 1 ? 1 : 0);
        }
        matriz.push_back(move(fila));
    }
    archivo.close();
    return true;
}

// Comprueba si el destino es alcanzable usando busqueda en anchura
bool es_alcanzable() {
    if (matriz.empty() || matriz[0].empty() || matriz[0][0] == 0 || matriz[n_filas-1][n_cols-1] == 0)
        return false;

    vector<bool> visitado_bfs(n_filas * n_cols, false);
    queue<pair<int, int>> cola;

    cola.push({0, 0});
    visitado_bfs[0] = true;

    while (!cola.empty()) {
        auto [i, j] = cola.front();
        cola.pop();

        if (i == n_filas - 1 && j == n_cols - 1)
            return true;

        for (int dir = 0; dir < 8; ++dir) {
            int ni = i + dx[dir];
            int nj = j + dy[dir];

            if (ni >= 0 && ni < n_filas && nj >= 0 && nj < n_cols &&
                matriz[ni][nj] == 1 && !visitado_bfs[ni * n_cols + nj]) {
                visitado_bfs[ni * n_cols + nj] = true;
            cola.push({ni, nj});
                }
        }
    }
    return false;
}

// Función optimizada para obtener orden de direcciones hacia el objetivo
void inicializar_orden_direcciones(vector<int>& orden_dirs) {
    int objetivo_x = n_filas - 1;
    int objetivo_y = n_cols - 1;

    // Inicializa el orden por defecto
    for (int i = 0; i < 8; ++i) {
        orden_dirs[i] = i;
    }

    // Ordena las direcciones según la proximidad al objetivo usando heurística simple
    sort(orden_dirs.begin(), orden_dirs.end(), [objetivo_x, objetivo_y](int a, int b) {
        // Calcula qué dirección está más alineada con la dirección al objetivo
        int a_alineacion = (objetivo_x > 0 ? dx[a] : -dx[a]) + (objetivo_y > 0 ? dy[a] : -dy[a]);
        int b_alineacion = (objetivo_x > 0 ? dx[b] : -dx[b]) + (objetivo_y > 0 ? dy[b] : -dy[b]);
        return a_alineacion > b_alineacion;
    });
}

shared_ptr<Nodo> mejor_nodo_solucion;

// Cota pesimista inicial
int mejor_longitud = n_filas * n_cols + 1;

// Función para reconstruir el camino desde el nodo solución
vector<pair<int, int>> reconstruir_camino(shared_ptr<Nodo> nodo_final) {
    vector<pair<int, int>> camino;
    shared_ptr<Nodo> actual = nodo_final;

    while (actual != nullptr) {
        camino.push_back({actual->x, actual->y});
        actual = actual->padre;
    }

    reverse(camino.begin(), camino.end());
    return camino;
}

int maze_bb() {
    if (matriz.empty() || matriz[0].empty()) return 0;
    if (matriz[0][0] == 0 || matriz[n_filas-1][n_cols-1] == 0) return 0;

    // Verificamos primero si el destino es alcanzable
    if (!es_alcanzable()) return 0;

    // Inicializar el orden de direcciones según la posición del objetivo
    vector<int> orden_dirs(8);
    inicializar_orden_direcciones(orden_dirs);

    mejor_longitud = n_filas * n_cols + 1;
    mejor_nodo_solucion.reset();
    estadisticas = Estadisticas();

    //ESTRUCTURAS DE DATOS SEGÚN LA ESTRATEGIA

    // VERSIÓN LC
    auto comparador = [](const shared_ptr<Nodo>& a, const shared_ptr<Nodo>& b) {
        return *a > *b;
    };
    priority_queue<shared_ptr<Nodo>, vector<shared_ptr<Nodo>>, decltype(comparador)> cola_prioridad(comparador);


    // VERSIÓN FIFO
    // queue<shared_ptr<Nodo>> cola_fifo;

    //VERSIÓN LIFO
    //stack<shared_ptr<Nodo>> pila_lifo;

    // Nodo inicial
    auto visitado_inicial = make_shared<EstadoVisitado>(n_filas, n_cols);
    visitado_inicial->marcar(0, 0);
    auto inicial = make_shared<Nodo>(0, 0, 1, visitado_inicial);

    // Agregar nodo inicial según la estrategia
    cola_prioridad.push(inicial);           // LC
    // cola_fifo.push(inicial);             // FIFO
    // pila_lifo.push(inicial);             // LIFO

    estadisticas.nodos_anadidos_lista_vivos++;

    // Tabla para evitar estados repetidos (memoización)
    vector<int> mejor_distancia(n_filas * n_cols, numeric_limits<int>::max());
    mejor_distancia[0] = 1;

    // BUCLE PRINCIPAL
    while (!cola_prioridad.empty()) {      // LC
        //while (!cola_fifo.empty()) {         // FIFO
        //while (!pila_lifo.empty()) {         // LIFO

        // Obtener siguiente nodo según la estrategia
        auto actual = cola_prioridad.top(); cola_prioridad.pop();  // LC
        // auto actual = cola_fifo.front(); cola_fifo.pop();       // FIFO
        // auto actual = pila_lifo.top(); pila_lifo.pop();         // LIFO

        estadisticas.nodos_visitados++;

        // Poda: si ya tenemos una solución mejor
        if (actual->longitud >= mejor_longitud) {
            estadisticas.nodos_no_prometedores++;
            continue;
        }

        // Poda: si ya visitamos este estado con mejor longitud
        int pos_actual = actual->x * n_cols + actual->y;
        if (mejor_distancia[pos_actual] < actual->longitud) {
            estadisticas.nodos_no_prometedores++;
            continue;
        }

        // Si llegamos al objetivo
        if (actual->x == n_filas - 1 && actual->y == n_cols - 1) {
            estadisticas.nodos_hoja++;
            if (actual->longitud < mejor_longitud) {
                mejor_longitud = actual->longitud;
                mejor_nodo_solucion = actual;
                estadisticas.mejor_solucion_desde_hoja++;
            }
            continue;
        }

        estadisticas.nodos_explorados++;

        // EXPANSIÓN DE NODOS HIJOS

        // Orden de expansión según la estrategia:
        int inicio = 0, fin = 8, incremento = 1;           // LC y FIFO (orden normal)
        // int inicio = 7, fin = -1, incremento = -1;      // LIFO (orden inverso)

        for (int dir_idx = inicio; dir_idx != fin; dir_idx += incremento) {
            int dir = orden_dirs[dir_idx];
            int ni = actual->x + dx[dir];
            int nj = actual->y + dy[dir];

            // Verificar límites y factibilidad
            if (ni < 0 || ni >= n_filas || nj < 0 || nj >= n_cols) {
                continue;
            }

            // Verificar que la celda sea transitable
            if (matriz[ni][nj] == 0) {
                estadisticas.nodos_no_factibles++;
                continue;
            }

            // Verificar que no haya sido visitada previamente
            if (actual->visitado->esta_visitado(ni, nj)) {
                continue;
            }

            // Crear nuevo estado visitado (copia optimizada)
            auto nuevo_visitado = make_shared<EstadoVisitado>(*actual->visitado);
            nuevo_visitado->marcar(ni, nj);

            // Crear nuevo nodo
            auto nuevo_nodo = make_shared<Nodo>(ni, nj, actual->longitud + 1, nuevo_visitado, actual);

            // Poda por cota inferior
            if (nuevo_nodo->cota_inferior() >= mejor_longitud) {
                estadisticas.nodos_no_prometedores++;
                continue;
            }

            // Poda por mejor distancia conocida
            int pos_nueva = ni * n_cols + nj;
            if (mejor_distancia[pos_nueva] <= nuevo_nodo->longitud) {
                estadisticas.nodos_prometedores_descartados++;
                continue;
            }

            mejor_distancia[pos_nueva] = nuevo_nodo->longitud;

            // Agregar nuevo nodo según la estrategia
            cola_prioridad.push(nuevo_nodo);         // LC
            //cola_fifo.push(nuevo_nodo);           // FIFO
            //pila_lifo.push(nuevo_nodo);           // LIFO

            estadisticas.nodos_anadidos_lista_vivos++;
        }
    }

    return mejor_longitud == numeric_limits<int>::max() ? 0 : mejor_longitud;
}


string obtenerSecuenciaMovimientos() {
    if (mejor_longitud == 0 || mejor_longitud == numeric_limits<int>::max()) return "<0>";
    if (mejor_longitud == 1) return "<>";

    vector<pair<int, int>> mejor_camino = reconstruir_camino(mejor_nodo_solucion);

    string secuencia = "<";
    for (size_t i = 0; i < mejor_camino.size() - 1; ++i) {
        int x1 = mejor_camino[i].first;
        int y1 = mejor_camino[i].second;
        int x2 = mejor_camino[i + 1].first;
        int y2 = mejor_camino[i + 1].second;

        int dx_actual = x2 - x1;
        int dy_actual = y2 - y1;
        for (int d = 0; d < 8; ++d) {
            if (dx_actual == dx[d] && dy_actual == dy[d]) {
                secuencia += to_string(dir_codes[d]);
                break;
            }
        }
    }
    secuencia += ">";
    return secuencia;
}

void imprimirCamino2D() {
    if (mejor_longitud == 0 || mejor_longitud == numeric_limits<int>::max()) return;

    vector<pair<int, int>> mejor_camino = reconstruir_camino(mejor_nodo_solucion);

    unordered_map<int, unordered_map<int, bool>> esCamino;
    for (auto [x, y] : mejor_camino) esCamino[x][y] = true;

    for (int i = 0; i < n_filas; ++i) {
        for (int j = 0; j < n_cols; ++j) {
            if (esCamino[i][j]) cout << '*';
            else cout << matriz[i][j];
        }
        cout << '\n';
    }
}

void mostrarUso() {
    cerr << "Usage:\nmaze_bb [--p2D] [-p] -f file\n";
}

int main(int argc, char* argv[]) {
    bool p2D = false;
    bool p = false;
    string nombre_archivo;
    bool archivoProporcionado = false;

    for (int i = 1; i < argc; ++i) {
        string parametro = argv[i];
        if (parametro == "--p2D") p2D = true;
        else if (parametro == "-p") p = true;
        else if (parametro == "-f") {
            if (i + 1 < argc) {
                nombre_archivo = argv[++i];
                archivoProporcionado = true;
            } else {
                cerr << "ERROR: missing filename.\n";
                mostrarUso();
                return -1;
            }
        } else {
            cerr << "ERROR: unknown option " << parametro << ".\n";
            mostrarUso();
            return -1;
        }
    }

    if (!archivoProporcionado) {
        mostrarUso();
        return -1;
    }

    if (!guardarMapa(nombre_archivo)) {
        cerr << "ERROR: can't open file: " << nombre_archivo << ".\n";
        mostrarUso();
        return -1;
    }

    auto start = high_resolution_clock::now();
    int longitud = maze_bb();

    auto end = high_resolution_clock::now();
    duration<double, milli> tiempo_cpu = end - start;

    cout << longitud << '\n';
    cout << estadisticas.nodos_visitados << " "
    << estadisticas.nodos_explorados << " "
    << estadisticas.nodos_hoja << " "
    << estadisticas.nodos_no_factibles << " "
    << estadisticas.nodos_no_prometedores << " "
    << estadisticas.nodos_prometedores_descartados << " "
    << estadisticas.mejor_solucion_desde_hoja << " "
    << estadisticas.mejor_solucion_desde_cota << endl;
    // << "Nodos Vivos: " << estadisticas.nodos_anadidos_lista_vivos << '\n';
    cout << fixed << setprecision(3) << tiempo_cpu.count() << '\n';

    if (p2D) {
        if (longitud == 0) cout << "0\n";
        else imprimirCamino2D();
    }

    if (p) {
        if (longitud == 0)
            cout << "<0>" << '\n';
        else
            cout << obtenerSecuenciaMovimientos() << '\n';
    }

    return 0;
}
