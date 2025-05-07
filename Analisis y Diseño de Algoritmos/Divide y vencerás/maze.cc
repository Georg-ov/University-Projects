//Georg Usin, X8174555X
#include <unistd.h>
#include <iostream>
#include <fstream>
#include <sstream>
#include <vector>
#include <unordered_map>
#include <algorithm>
#include <iomanip>

using namespace std;

vector<vector<int>> matriz;
unordered_map<int, unordered_map<int, int>> memorizada;
vector<vector<int>> tablaMemo;
vector<vector<int>> tablaIter;

// Cargar la matriz desde un archivo
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

// Versión recursiva sin memoria
int maze_naive(int i, int j) {
    int n = matriz.size();
    int m = (n > 0) ? matriz[0].size() : 0;

    // Si estamos fuera de límites o en un obstáculo
    if (i < 0 || j < 0 || i >= n || j >= m || matriz[i][j] == 0)
        return 100000000;

    // Si ya llegamos al inicio
    if (i == 0 && j == 0)
        return 1;

    // Calculamos las tres opciones de movimiento (arriba, izquierda, diagonal)
    int arriba = maze_naive(i - 1, j);
    int izquierda = maze_naive(i, j - 1);
    int diagonal = maze_naive(i - 1, j - 1);

    // Elegimos el menor camino + 1 para esta posición
    return 1 + min({arriba, izquierda, diagonal});
}

int maze_memo(int i, int j) {
    int n = matriz.size();
    int m = matriz[0].size();

    // Si estamos fuera de límites o en un obstáculo
    if (i < 0 || j < 0 || i >= n || j >= m || matriz[i][j] == 0) {
        if (i >= 0 && j >= 0 && i < n && j < m) tablaMemo[i][j] = -2;  // Posición inválida
        return 100000000;
    }

    // Si ya llegamos al inicio
    if (i == 0 && j == 0) {
        tablaMemo[i][j] = 1;
        return 1;
    }

    // Si ya calculamos este valor antes
    if (memorizada.count(i) && memorizada[i].count(j)) {
        return memorizada[i][j];
    }

    // Calculamos las tres opciones de movimiento (arriba, izquierda, diagonal)
    int arriba = maze_memo(i - 1, j);
    int izquierda = maze_memo(i, j - 1);
    int diagonal = maze_memo(i - 1, j - 1);

    // Elegimos el menor camino + 1 para esta posición
    int resultado = 1 + min({arriba, izquierda, diagonal});
    memorizada[i][j] = resultado;

    // Actualizamos la tabla de visualización
    tablaMemo[i][j] = (resultado >= 100000000) ? -2 : resultado;

    return resultado;
}

int maze_it_matrix() {
    int n = matriz.size(), m = matriz[0].size();

    // Inicializar la matriz de distancias con valores grandes
    vector<vector<int>> dp(n, vector<int>(m, 100000000));
    tablaIter.assign(n, vector<int>(m, -1));

    // La casilla inicial tiene distancia 1
    if (matriz[0][0] == 1) {
        dp[0][0] = 1;
        tablaIter[0][0] = 1;
    }

    // Calcular distancias de manera iterativa (de arriba a abajo, izquierda a derecha)
    for (int i = 0; i < n; ++i) {
        for (int j = 0; j < m; ++j) {
            // Si es un obstáculo o ya es la casilla inicial, continuamos
            if (matriz[i][j] == 0) {
                tablaIter[i][j] = -2;  // Marcamos como obstáculo
                continue;
            }
            if (i == 0 && j == 0) continue;

            // Intentamos todos los posibles movimientos previos
            if (i > 0 && matriz[i-1][j] == 1)
                dp[i][j] = min(dp[i][j], 1 + dp[i-1][j]);
            if (j > 0 && matriz[i][j-1] == 1)
                dp[i][j] = min(dp[i][j], 1 + dp[i][j-1]);
            if (i > 0 && j > 0 && matriz[i-1][j-1] == 1)
                dp[i][j] = min(dp[i][j], 1 + dp[i-1][j-1]);

            // Actualizamos la tabla de visualización
            if (dp[i][j] < 100000000)
                tablaIter[i][j] = dp[i][j];
            else
                tablaIter[i][j] = -2;  // No hay camino hasta aquí
        }
    }

    return (dp[n-1][m-1] >= 100000000) ? 0 : dp[n-1][m-1];
}

// Versión iterativa con vector
int maze_it_vector() {
    int n = matriz.size();
    int m = (n > 0) ? matriz[0].size() : 0;
    if (n == 0 || m == 0 || matriz[0][0] == 0 || matriz[n - 1][m - 1] == 0)
        return 0;

    vector<int> actual(m, 100000000), anterior(m, 100000000);

    // Inicializamos la posición inicial
    actual[0] = matriz[0][0] == 1 ? 1 : 100000000;

    for (int i = 0; i < n; ++i) {
        swap(actual, anterior);  // Antes de procesar la fila i, actualizamos anterior

        for (int j = 0; j < m; ++j) {
            if (matriz[i][j] == 0) {
                actual[j] = 100000000;
            } else if (i == 0 && j == 0) {
                actual[j] = 1;
            } else {
                actual[j] = 100000000;
                // Verificamos movimientos previos posibles
                if (j > 0) actual[j] = min(actual[j], 1 + actual[j - 1]);
                if (i > 0) actual[j] = min(actual[j], 1 + anterior[j]);
                if (i > 0 && j > 0) actual[j] = min(actual[j], 1 + anterior[j - 1]);
            }
        }
    }

    return (actual[m-1] >= 100000000) ? 0 : actual[m-1];
}

// Recuperar el camino mínimo
vector<pair<int, int>> maze_parser() {
    vector<pair<int, int>> camino;
    int n = matriz.size(), m = (n > 0) ? matriz[0].size() : 0;
    if (n == 0 || m == 0 || matriz[0][0] == 0 || matriz[n - 1][m - 1] == 0)
        return camino;

    // Inicializar con valores grandes
    vector<vector<int>> dp(n, vector<int>(m, 100000000));
    vector<vector<pair<int, int>>> prev(n, vector<pair<int, int>>(m, {-1, -1}));

    // La casilla inicial tiene distancia 1
    dp[0][0] = 1;

    // Recorrido de arriba hacia abajo y de izquierda a derecha
    for (int i = 0; i < n; ++i) {
        for (int j = 0; j < m; ++j) {
            // Si la celda es un obstáculo o es la inicial, continuamos
            if (matriz[i][j] == 0) continue;
            if (i == 0 && j == 0) continue;

            // Revisamos todos los posibles movimientos previos
            if (i > 0 && matriz[i-1][j] == 1 && dp[i-1][j] + 1 < dp[i][j]) {
                dp[i][j] = dp[i-1][j] + 1;
                prev[i][j] = {i-1, j};
            }

            if (j > 0 && matriz[i][j-1] == 1 && dp[i][j-1] + 1 < dp[i][j]) {
                dp[i][j] = dp[i][j-1] + 1;
                prev[i][j] = {i, j-1};
            }

            if (i > 0 && j > 0 && matriz[i-1][j-1] == 1 && dp[i-1][j-1] + 1 < dp[i][j]) {
                dp[i][j] = dp[i-1][j-1] + 1;
                prev[i][j] = {i-1, j-1};
            }
        }
    }

    // Si no hay camino, retornamos vacío
    if (dp[n-1][m-1] >= 100000000) return camino;

    // Reconstruimos el camino desde el final hasta el inicio
    int x = n-1, y = m-1;
    while (x >= 0 && y >= 0) {
        camino.insert(camino.begin(), {x, y});
        if (x == 0 && y == 0) break;  // Llegamos al inicio
        auto [prev_x, prev_y] = prev[x][y];
        x = prev_x; y = prev_y;
    }

    return camino;
}

void imprimirTabla(const vector<vector<int>>& tabla, const string& titulo) {
    cout << titulo << ":\n";
    for (const auto& fila : tabla) {
        for (int val : fila) {
            if (val == -1)
                cout << setw(3) << '-';
            else if (val == -2)
                cout << setw(3) << 'X';
            else
                cout << setw(3) << val;
        }
        cout << '\n';
    }
}


// Imprimir matriz con camino para --p2D
void imprimirCamino2D(const vector<pair<int, int>>& camino) {
    unordered_map<int, unordered_map<int, bool>> esCamino;
    for (auto [x, y] : camino) esCamino[x][y] = true;

    for (int i = 0; i < matriz.size(); ++i) {
        for (int j = 0; j < matriz[0].size(); ++j) {
            if (esCamino[i][j]) cout << '*';
            else cout << matriz[i][j];
        }
        cout << '\n';
    }
}

int main(int argc, char* argv[]) {
    bool p2D = false, t = false, ignoreNaive = false;
    string nombre_archivo;

    for (int i = 1; i < argc; ++i) {
        string parametro = argv[i];
        if (parametro == "--ignore-naive") ignoreNaive = true;
        else if (parametro == "-t") t = true;
        else if (parametro == "--p2D") p2D = true;
        else if (parametro == "-f") {
            if (i + 1 < argc) {
                nombre_archivo = argv[i + 1];
                i++;
            } else {
                cerr << "ERROR: missing filename.\n";
                return -1;
            }
        } else {
            cerr << "ERROR: unknown option " << parametro << ".\n";
            return -1;
        }
    }

    if (!guardarMapa(nombre_archivo)) {
        cerr << "ERROR: can't open file: " << nombre_archivo << ".\n";
        return -1;
    }

    if (matriz[0][0] == 0 || matriz.back().back() == 0) {
        cout << "0 0 0 0\n";
        cout << "0\n";

        cout << "Memoization table:\n  X\n";
        cout << "Iterative table:\n  X\n";
        return 0;
    }

    tablaMemo.assign(matriz.size(), vector<int>(matriz[0].size(), -1));
    int iterativo = maze_it_matrix();
    int iterativo_espacio = maze_it_vector();

    // Cambio en la llamada: ahora pasamos coordenadas de la meta
    int memo = maze_memo(matriz.size() - 1, matriz[0].size() - 1);
    memo = (memo >= 100000000) ? 0 : memo;

    vector<pair<int, int>> camino = maze_parser();

    if (!ignoreNaive) {
        int naive = maze_naive(matriz.size() - 1, matriz[0].size() - 1);
        naive = (naive >= 100000000) ? 0 : naive;
        cout << naive << " " << memo << " " << iterativo << " " << iterativo_espacio << endl;
    } else {
        cout << "- " << memo << " " << iterativo << " " << iterativo_espacio << endl;
    }

    if (p2D) {
        if(iterativo == 0 && iterativo_espacio == 0 && memo == 0)
            cout << "0\n";

        else
            imprimirCamino2D(camino);
    }

    if (t) {
        imprimirTabla(tablaMemo, "Memoization table");
        imprimirTabla(tablaIter, "Iterative table");
    }

    return 0;
}
