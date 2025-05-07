// Georg Usin, X8174555X
#include <unistd.h>
#include <iostream>
#include <fstream>
#include <sstream>
#include <vector>
#include <string>

using namespace std;

vector<vector<int>> matriz;
vector<vector<bool>> caminoTomado;

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

    caminoTomado.assign(matriz.size(), vector<bool>(matriz[0].size(), false));
    archivo.close();
    return true;
}

// Implementación del método voraz
int maze_greedy() {
    int i = 0, j = 0;
    int pasos = 1;

    int n = matriz.size();
    int m = matriz[0].size();

    caminoTomado[i][j] = true;

    while (!(i == n - 1 && j == m - 1)) {
        bool moved = false;

        // Intentar mover en orden: diagonal, derecha, abajo
        if (i + 1 < n && j + 1 < m && matriz[i + 1][j + 1] == 1) {
            i++;
            j++;
            moved = true;
        } else if (j + 1 < m && matriz[i][j + 1] == 1) {
            j++;
            moved = true;
        } else if (i + 1 < n && matriz[i + 1][j] == 1) {
            i++;
            moved = true;
        }

        if (!moved)
            return 0;

        caminoTomado[i][j] = true;
        pasos++;
    }

    return pasos;
}

void imprimirCamino2D() {
    for (int i = 0; i < matriz.size(); ++i) {
        for (int j = 0; j < matriz[0].size(); ++j) {
            if (caminoTomado[i][j])
                cout << '*';
            else
                cout << matriz[i][j];
        }
        cout << '\n';
    }
}

void mostrarUso() {
    cerr << "Usage:\nmaze_greedy [--p2D] -f file\n";
}

int main(int argc, char* argv[]) {
    bool p2D = false;
    string nombre_archivo;
    bool archivoProporcionado = false;

    for (int i = 1; i < argc; ++i) {
        string parametro = argv[i];

        if (parametro == "--p2D") {
            p2D = true;
        } else if (parametro == "-f") {
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

    if (matriz[0][0] == 0 || matriz.back().back() == 0) {
        cout << "0\n";
        if (p2D)
            imprimirCamino2D();
        return 0;
    }

    int longitud = maze_greedy();
    cout << longitud << '\n';

    if (p2D)
        imprimirCamino2D();

    return 0;
}
