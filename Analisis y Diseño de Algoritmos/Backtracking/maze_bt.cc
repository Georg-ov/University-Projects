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

using namespace std;
using namespace std::chrono;

vector<vector<int>> matriz;
vector<vector<char>> visitado;
vector<vector<int>> mejor_distancia;

struct Estadisticas {
    int nodos_visitados = 0;
    int nodos_explorados = 0;
    int nodos_hoja = 0;
    int nodos_no_factibles = 0;
    int nodos_no_prometedores = 0;
};

Estadisticas estadisticas;
vector<pair<int, int>> mejor_camino;
int mejor_longitud = numeric_limits<int>::max();

const int dx[8] = {0, 1, 1, 1, 0, -1, -1, -1};
const int dy[8] = {1, 1, 0, -1, -1, -1, 0, 1};
const int dir_codes[8] = {3, 4, 5, 6, 7, 8, 1, 2};

// Longitud mínima teórica (distancia máxima entre diferencias x e y)
int distancia_minima(int x1, int y1, int x2, int y2) {
    int dx = abs(x2 - x1);
    int dy = abs(y2 - y1);
    return max(dx, dy);
}

bool guardarMapa(const string& nombre_archivo) {
    ifstream archivo(nombre_archivo);
    if (!archivo.is_open()) return false;

    matriz.clear();
    string linea;
    getline(archivo, linea);

    int n, m;
    istringstream dim(linea);
    dim >> n >> m;

    for (int i = 0; i < n; ++i) {
        getline(archivo, linea);
        istringstream ss(linea);
        vector<int> fila;
        int valor;
        while (ss >> valor) {
            fila.push_back(valor == 1 ? 1 : 0);
        }
        matriz.push_back(fila);
    }
    archivo.close();
    return true;
}

// Comprueba si el destino es alcanzable usando busqueda en anchura
bool es_alcanzable() {
    if (matriz.empty() || matriz[0].empty() || matriz[0][0] == 0 || matriz.back().back() == 0)
        return false;

    int n = matriz.size();
    int m = matriz[0].size();
    vector<vector<char>> visitado_bfs(n, vector<char>(m, 0));
    queue<pair<int, int>> cola;

    cola.push({0, 0});
    visitado_bfs[0][0] = 1;

    while (!cola.empty()) {
        auto [i, j] = cola.front();
        cola.pop();

        if (i == n - 1 && j == m - 1)
            return true;

        for (int dir = 0; dir < 8; ++dir) {
            int ni = i + dx[dir];
            int nj = j + dy[dir];

            if (ni >= 0 && ni < n && nj >= 0 && nj < m && matriz[ni][nj] == 1 && !visitado_bfs[ni][nj]) {
                visitado_bfs[ni][nj] = 1;
                cola.push({ni, nj});
            }
        }
    }
    return false;
}

// Función para inicializar el orden de direcciones según la posición del objetivo
void inicializar_orden_direcciones(int n, int m, vector<int>& orden_dirs) {

    int objetivo_x = n - 1;
    int objetivo_y = m - 1;

    // Inicializa el orden por defecto
    for (int i = 0; i < 8; ++i) {
        orden_dirs[i] = i;
    }

    // Ordena las direcciones según la proximidad al objetivo
    sort(orden_dirs.begin(), orden_dirs.end(), [objetivo_x, objetivo_y](int a, int b) {
        int a_x = 0 + dx[a];
        int a_y = 0 + dy[a];
        int b_x = 0 + dx[b];
        int b_y = 0 + dy[b];

        // Calcula qué dirección está más alineada con la dirección al objetivo
        int a_alineacion = (objetivo_x >= 0 ? a_x : -a_x) + (objetivo_y >= 0 ? a_y : -a_y);
        int b_alineacion = (objetivo_x >= 0 ? b_x : -b_x) + (objetivo_y >= 0 ? b_y : -b_y);

        return a_alineacion > b_alineacion;
    });
}

void maze_bt(int i, int j, int longitud, vector<pair<int, int>>& camino_actual, const vector<int>& orden_dirs) {
    int n = matriz.size();
    int m = matriz[0].size();

    estadisticas.nodos_visitados++;

    // Si ya encontramos un camino de longitud mínima posible, terminamos
    if (mejor_longitud == distancia_minima(0, 0, n-1, m-1) + 1) {
        return;
    }

    // Poda por mejor distancia conocida a esta celda
    if (mejor_distancia[i][j] <= longitud) {
        estadisticas.nodos_no_prometedores++;
        return;
    }

    // Poda por límite global
    if (longitud >= mejor_longitud) {
        estadisticas.nodos_no_prometedores++;
        return;
    }

    // Actualizamos la mejor distancia conocida a esta celda
    mejor_distancia[i][j] = longitud;

    // Llegamos a la meta
    if (i == n - 1 && j == m - 1) {
        estadisticas.nodos_hoja++;
        if (longitud < mejor_longitud) {
            mejor_longitud = longitud;
            mejor_camino = camino_actual;
        }
        return;
    }

    estadisticas.nodos_explorados++;

    for (int dir_idx = 0; dir_idx < 8; ++dir_idx) {
        int dir = orden_dirs[dir_idx];
        int ni = i + dx[dir];
        int nj = j + dy[dir];

        if (ni >= 0 && ni < n && nj >= 0 && nj < m && matriz[ni][nj] == 1 && !visitado[ni][nj]) {
            visitado[ni][nj] = 1;
            camino_actual.push_back({ni, nj});

            maze_bt(ni, nj, longitud + 1, camino_actual, orden_dirs);

            camino_actual.pop_back();
            visitado[ni][nj] = 0;
        } else if (ni >= 0 && ni < n && nj >= 0 && nj < m && matriz[ni][nj] == 0) {
            estadisticas.nodos_no_factibles++;
        }
    }
}

int maze_bt() {
    if (matriz.empty() || matriz[0].empty()) return 0;
    if (matriz[0][0] == 0 || matriz.back().back() == 0) return 0;

    // Verificamos primero si el destino es alcanzable
    if (!es_alcanzable()) return 0;

    int n = matriz.size();
    int m = matriz[0].size();

    // Inicializar el orden de direcciones según la posición del objetivo
    vector<int> orden_dirs(8);
    inicializar_orden_direcciones(n, m, orden_dirs);

    mejor_longitud = numeric_limits<int>::max();
    mejor_camino.clear();
    estadisticas = Estadisticas();
    visitado.assign(n, vector<char>(m, 0));
    mejor_distancia.assign(n, vector<int>(m, numeric_limits<int>::max()));

    vector<pair<int, int>> camino_actual;
    camino_actual.reserve(n * m);
    camino_actual.push_back({0, 0});
    visitado[0][0] = 1;

    maze_bt(0, 0, 1, camino_actual, orden_dirs);

    return mejor_longitud == numeric_limits<int>::max() ? 0 : mejor_longitud;
}

string obtenerSecuenciaMovimientos() {
    if (mejor_longitud == 0 || mejor_longitud == numeric_limits<int>::max()) return "<0>";
    if (mejor_longitud == 1) return "<>";

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

    unordered_map<int, unordered_map<int, bool>> esCamino;
    for (auto [x, y] : mejor_camino) esCamino[x][y] = true;

    for (int i = 0; i < matriz.size(); ++i) {
        for (int j = 0; j < matriz[0].size(); ++j) {
            if (esCamino[i][j]) cout << '*';
            else cout << matriz[i][j];
        }
        cout << '\n';
    }
}

void mostrarUso() {
    cerr << "Usage:\nmaze_bt [--p2D] [-p] -f file\n";
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
    int longitud = maze_bt();

    auto end = high_resolution_clock::now();
    duration<double, milli> tiempo_cpu = end - start;

    cout << longitud << '\n';
    cout << estadisticas.nodos_visitados << " "
    << estadisticas.nodos_explorados << " "
    << estadisticas.nodos_hoja << " "
    << estadisticas.nodos_no_factibles << " "
    << estadisticas.nodos_no_prometedores << '\n';
    cout << fixed << setprecision(3) << tiempo_cpu.count() << '\n';


    if (p2D) {
        if (longitud == 0) cout << "0\n";
        else imprimirCamino2D();
    }

    if (p) {
        cout << obtenerSecuenciaMovimientos() << '\n';
    }

    return 0;
}
