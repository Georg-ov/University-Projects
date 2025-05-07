import matplotlib.pyplot as plt
import numpy as np
from tensorflow.keras.datasets import cifar10
from tensorflow.keras.utils import to_categorical
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Conv2D, MaxPooling2D, Flatten, Dense, Dropout, BatchNormalization
from tensorflow.keras.optimizers import Adam, SGD
from tensorflow.keras.callbacks import LambdaCallback
from tensorflow.keras.callbacks import EarlyStopping
from sklearn.metrics import confusion_matrix, ConfusionMatrixDisplay
import time
from tensorflow.keras.preprocessing.image import load_img, img_to_array
import os

# Rutas y categorías
directorio = './dataset_propio'
categorias = ['Avion', 'Automovil', 'Barco', 'Gato', 'Perro', 'Ciervo', 'Caballo', 'Rana', 'Pajaro', 'Camion']

# Dimensiones para CIFAR-10
dimensiones = (32, 32)
num_clases = len(categorias)

#Funcion para procesar el dataset propio
def cargar_imagenes():
    X = []
    Y = []

    for i, categoria in enumerate(categorias):
        path = os.path.join(directorio, categoria)
        imagenes = os.listdir(path)
        
        for img_nombre in imagenes:
            img_path = os.path.join(path, img_nombre)
            
            # Cargar imagen, redimensionar y convertir a array
            img = load_img(img_path, target_size=dimensiones)
            img_array = img_to_array(img) / 255.0
            
            X.append(img_array)
            Y.append(i) 

    X = np.array(X)
    Y = to_categorical(Y, num_classes=num_clases)
    
    return X, Y

def cargarCifar10():
    
    (X_train, Y_train), (X_test, Y_test) = cifar10.load_data()
    X_train = X_train.astype('float32') / 255.0
    X_test = X_test.astype('float32') / 255.0
    X_train = X_train.reshape(X_train.shape[0], -1)
    X_test = X_test.reshape(X_test.shape[0], -1)
    Y_train = to_categorical(Y_train, num_classes=10)
    Y_test = to_categorical(Y_test, num_classes=10)
    return X_train, Y_train, X_test, Y_test

def cargar_Cifar10_CNN():
    (X_train, Y_train), (X_test, Y_test) = cifar10.load_data()
    X_train = X_train.astype('float32') / 255.0
    X_test = X_test.astype('float32') / 255.0
    Y_train = to_categorical(Y_train, 10)
    Y_test = to_categorical(Y_test, 10)
    return X_train, Y_train, X_test, Y_test

def entrenarMLP(X_train, Y_train, X_test, Y_test):
    
    modelo = Sequential([
        Dense(32, activation='sigmoid', input_shape=(X_train.shape[1],)),
        Dense(10, activation='softmax')
    ])

    modelo.compile(optimizer=Adam(),
                   loss='categorical_crossentropy',
                   metrics=['accuracy'])

    modelo.summary()

    historial = modelo.fit(X_train, Y_train,
                           epochs=20, batch_size=32,
                           validation_split=0.1,
                           verbose=1)

    resultados = modelo.evaluate(X_test, Y_test, verbose=0)
    print(f"Pérdida en test: {resultados[0]:.4f}")
    print(f"Tasa de acierto en test: {resultados[1]:.4f}")

    return modelo, historial, resultados


def entrenarConDeteccion(X_train, Y_train, X_test, Y_test):
    
    # Definir el modelo
    modelo = Sequential([
        Dense(32, activation='sigmoid', input_shape=(X_train.shape[1],)),
        Dense(10, activation='softmax')
    ])

    modelo.compile(optimizer=Adam(),
                   loss='categorical_crossentropy',
                   metrics=['accuracy'])

    # Configurar EarlyStopping basado en val_loss y val_accuracy
    #detendrá el entrenamiento si val_loss no mejora tras 5 épocas
    early_stop_loss = EarlyStopping(
        monitor='val_loss', patience=5, restore_best_weights=True, verbose=1
    )
    #detendrá el entrenamiento si val_accuaracy no mejora tras 5 épocas
    early_stop_acc = EarlyStopping(
        monitor='val_accuracy', patience=5, restore_best_weights=True, verbose=1
    )

    # Entrenar el modelo con múltiples callbacks
    historial = modelo.fit(
        X_train, Y_train,
        epochs=100, batch_size=32,
        validation_split=0.1,
        verbose=1,
        callbacks=[early_stop_loss, early_stop_acc]
    )

    # Evaluar en el conjunto de prueba
    resultados = modelo.evaluate(X_test, Y_test, verbose=0)
    print(f"Pérdida en test: {resultados[0]:.4f}")
    print(f"Tasa de acierto en test: {resultados[1]:.4f}")

    return modelo, historial, resultados


def graficarHistorial(historial):
    
    # Extraer las métricas del historial
    acc = historial.history['accuracy']
    val_acc = historial.history['val_accuracy']
    loss = historial.history['loss']
    val_loss = historial.history['val_loss']

    # Número de épocas
    epochs = range(1, len(acc) + 1)

    # Crear el lienzo y los ejes
    fig, ax1 = plt.subplots(figsize=(10, 6))

    # Eje izquierdo para precisión
    ax1.set_xlabel('Épocas')
    ax1.set_ylabel('Precisión', color='tab:blue')
    ax1.plot(epochs, acc, label='Precisión de Entrenamiento', color='tab:blue', linestyle='--')
    ax1.plot(epochs, val_acc, label='Precisión de Validación', color='tab:blue')
    ax1.tick_params(axis='y', labelcolor='tab:blue')
    ax1.legend(loc='upper left')

    # Eje derecho para pérdida
    ax2 = ax1.twinx()  # Crear un segundo eje Y que comparta el mismo eje X
    ax2.set_ylabel('Pérdida', color='tab:red')
    ax2.plot(epochs, loss, label='Pérdida de Entrenamiento', color='tab:red', linestyle='--')
    ax2.plot(epochs, val_loss, label='Pérdida de Validación', color='tab:red')
    ax2.tick_params(axis='y', labelcolor='tab:red')
    ax2.legend(loc='upper right')

    # Título y mostrar gráfica
    plt.title('Evolución del Entrenamiento')
    plt.show()


# Función para graficar la matriz de confusión
def graficarMatrizConfusion(Y_test, predicciones):
    """
    Genera y muestra la matriz de confusión de las predicciones.
    """
    Y_test_clases = np.argmax(Y_test, axis=1)
    pred_clases = np.argmax(predicciones, axis=1)

    matriz = confusion_matrix(Y_test_clases, pred_clases)

    disp = ConfusionMatrixDisplay(confusion_matrix=matriz, display_labels=range(10))
    disp.plot(cmap=plt.cm.Blues)
    plt.title('Matriz de Confusión')
    plt.show()
    

def experimentoBatchSize(X_train, Y_train, X_test, Y_test, batch_sizes):
    
    resultados = []

    for batch_size in batch_sizes:
        print(f"\nEntrenando con batch_size={batch_size}")

        # Definir el modelo
        modelo = Sequential([
            Dense(32, activation='sigmoid', input_shape=(X_train.shape[1],)),
            Dense(10, activation='softmax')
        ])

        modelo.compile(optimizer=Adam(),
                       loss='categorical_crossentropy',
                       metrics=['accuracy'])

        # Configurar EarlyStopping basado en val_loss y val_accuracy
        early_stop_loss = EarlyStopping(
            monitor='val_loss', patience=5, restore_best_weights=True, verbose=1
        )
        early_stop_acc = EarlyStopping(
            monitor='val_accuracy', patience=5, restore_best_weights=True, verbose=1
        )

        # Medir tiempo de entrenamiento
        inicio = time.time()
        historial = modelo.fit(
            X_train, Y_train,
            epochs=100,  # Se establece un número máximo alto para permitir EarlyStopping
            batch_size=batch_size,
            validation_split=0.1,
            verbose=1,
            callbacks=[early_stop_loss, early_stop_acc]
        )
        fin = time.time()

        # Evaluar el modelo
        resultados_test = modelo.evaluate(X_test, Y_test, verbose=0)
        tiempo_entrenamiento = fin - inicio

        print(f"Tiempo de entrenamiento: {tiempo_entrenamiento:.2f} segundos")
        print(f"Pérdida en test: {resultados_test[0]:.4f}")
        print(f"Precisión en test: {resultados_test[1]:.4f}")

        resultados.append({
            'batch_size': batch_size,
            'test_accuracy': resultados_test[1],
            'train_time': tiempo_entrenamiento
        })

    return resultados



def graficarResultadosBatchSize(resultados):
    
    # Extraer datos
    batch_sizes = [r['batch_size'] for r in resultados]
    accuracies = [r['test_accuracy'] for r in resultados]
    tiempos = [r['train_time'] for r in resultados]

    x = np.arange(len(batch_sizes))  # Índices de batch_sizes
    ancho = 0.4  # Ancho de las barras

    fig, ax1 = plt.subplots(figsize=(12, 6))

    # Primer eje Y: Precisión
    ax1.bar(x - ancho/2, accuracies, width=ancho, label='Precisión (Test)', color='skyblue')
    ax1.set_xlabel('Batch Size')
    ax1.set_ylabel('Precisión', color='skyblue')
    ax1.tick_params(axis='y', labelcolor='skyblue')
    ax1.set_xticks(x)
    ax1.set_xticklabels(batch_sizes)
    ax1.set_ylim(0.35, 0.5)  # Ajuste del rango para precisión

    # Segundo eje Y: Tiempo
    ax2 = ax1.twinx()
    ax2.bar(x + ancho/2, tiempos, width=ancho, label='Tiempo de Entrenamiento (s)', color='salmon')
    ax2.set_ylabel('Tiempo de Entrenamiento (s)', color='salmon')
    ax2.tick_params(axis='y', labelcolor='salmon')

    # Leyendas independientes
    ax1.legend(loc='upper left')
    ax2.legend(loc='upper right')

    # Título y ajustes finales
    plt.title('Resultados en función de Batch Size')
    plt.tight_layout()
    plt.show()



def experimentoFuncionesActivacion(X_train, Y_train, X_test, Y_test, activaciones):
    
    resultados = []

    for activacion in activaciones:
        print(f"\nEntrenando con activación={activacion}")

        # Definir el modelo
        modelo = Sequential([
            Dense(32, activation=activacion, input_shape=(X_train.shape[1],)),
            Dense(10, activation='softmax')
        ])

        modelo.compile(optimizer=Adam(),
                       loss='categorical_crossentropy',
                       metrics=['accuracy'])

        # Configurar EarlyStopping basado en val_loss y val_accuracy
        early_stop_loss = EarlyStopping(
            monitor='val_loss', patience=5, restore_best_weights=True, verbose=1
        )
        early_stop_acc = EarlyStopping(
            monitor='val_accuracy', patience=5, restore_best_weights=True, verbose=1
        )

        # Medir tiempo de entrenamiento
        inicio = time.time()
        historial = modelo.fit(
            X_train, Y_train,
            epochs=100,  # Establecemos un número máximo alto para permitir EarlyStopping
            batch_size=128,
            validation_split=0.1,
            verbose=1,
            callbacks=[early_stop_loss, early_stop_acc]
        )
        fin = time.time()

        # Evaluar el modelo
        resultados_test = modelo.evaluate(X_test, Y_test, verbose=0)
        tiempo_entrenamiento = fin - inicio

        print(f"Tiempo de entrenamiento: {tiempo_entrenamiento:.2f} segundos")
        print(f"Pérdida en test: {resultados_test[0]:.4f}")
        print(f"Precisión en test: {resultados_test[1]:.4f}")

        resultados.append({
            'activation': activacion,
            'test_accuracy': resultados_test[1],
            'train_time': tiempo_entrenamiento
        })

    return resultados


def graficarResultadosActivacion(resultados):
    
    # Extraer datos
    activaciones = [r['activation'] for r in resultados]
    accuracies = [r['test_accuracy'] for r in resultados]
    tiempos = [r['train_time'] for r in resultados]

    # Verificar si algún valor de precisión está fuera del rango [0.3, 0.5]
    min_acc = min(accuracies)
    max_acc = max(accuracies)
    rango_precision = (0.3, 0.5) if (min_acc >= 0.3 and max_acc <= 0.5) else (min_acc - 0.02, max_acc + 0.02)

    x = np.arange(len(activaciones))  # Índices de las funciones de activación
    ancho = 0.4  # Ancho de las barras

    fig, ax1 = plt.subplots(figsize=(12, 6))

    # Primer eje Y: Precisión
    ax1.bar(x - ancho/2, accuracies, width=ancho, label='Precisión (Test)', color='skyblue')
    ax1.set_xlabel('Función de Activación')
    ax1.set_ylabel('Precisión', color='skyblue')
    ax1.tick_params(axis='y', labelcolor='skyblue')
    ax1.set_xticks(x)
    ax1.set_xticklabels(activaciones)
    ax1.set_ylim(rango_precision)  # Ajuste dinámico del rango

    # Segundo eje Y: Tiempo
    ax2 = ax1.twinx()
    ax2.bar(x + ancho/2, tiempos, width=ancho, label='Tiempo de Entrenamiento (s)', color='salmon')
    ax2.set_ylabel('Tiempo de Entrenamiento (s)', color='salmon')
    ax2.tick_params(axis='y', labelcolor='salmon')

    # Leyendas independientes
    ax1.legend(loc='upper left')
    ax2.legend(loc='upper right')

    # Título y ajustes finales
    plt.title('Resultados en función de la Función de Activación')
    plt.tight_layout()
    plt.show()



def experimentoNeurona(X_train, Y_train, X_test, Y_test, neuronas_list):
    
    resultados = []

    for neuronas in neuronas_list:
        print(f"\nEntrenando con {neuronas} neuronas en la capa oculta")

        # Definir el modelo con un número variable de neuronas y LeakyReLU
        modelo = Sequential([
            Dense(neuronas, input_shape=(X_train.shape[1],)),
            LeakyReLU(),  # LeakyReLU activación en la capa oculta
            Dense(10, activation='softmax')  # Capa de salida con Softmax
        ])

        modelo.compile(optimizer=Adam(),
                       loss='categorical_crossentropy',
                       metrics=['accuracy'])

        # Configurar EarlyStopping basado en val_loss y val_accuracy
        early_stop_loss = EarlyStopping(
            monitor='val_loss', patience=5, restore_best_weights=True, verbose=1
        )
        early_stop_acc = EarlyStopping(
            monitor='val_accuracy', patience=5, restore_best_weights=True, verbose=1
        )

        # Medir tiempo de entrenamiento
        inicio = time.time()
        historial = modelo.fit(
            X_train, Y_train,
            epochs=100,  # Establecemos un número máximo alto para permitir EarlyStopping
            batch_size=128,
            validation_split=0.1,
            verbose=1,
            callbacks=[early_stop_loss, early_stop_acc]
        )
        fin = time.time()

        # Evaluar en el conjunto de prueba
        resultados_test = modelo.evaluate(X_test, Y_test, verbose=0)
        tiempo_entrenamiento = fin - inicio

        print(f"Tiempo de entrenamiento: {tiempo_entrenamiento:.2f} segundos")
        print(f"Pérdida en test: {resultados_test[0]:.4f}")
        print(f"Precisión en test: {resultados_test[1]:.4f}")

        resultados.append({
            'neuronas': neuronas,
            'test_accuracy': resultados_test[1],
            'train_time': tiempo_entrenamiento
        })

    return resultados



def graficarResultadosNeurona(resultados):
    
    # Extraer datos
    neuronas = [r['neuronas'] for r in resultados]
    accuracies = [r['test_accuracy'] for r in resultados]
    tiempos = [r['train_time'] for r in resultados]

    # Verificar si algún valor de precisión está fuera del rango [0.3, 0.5]
    min_acc = min(accuracies)
    max_acc = max(accuracies)
    rango_precision = (0.40, 0.5) if (min_acc >= 0.3 and max_acc <= 0.5) else (min_acc - 0.02, max_acc + 0.02)

    x = np.arange(len(neuronas))  # Índices de las neuronas
    ancho = 0.4  # Ancho de las barras

    fig, ax1 = plt.subplots(figsize=(12, 6))

    # Primer eje Y: Precisión
    ax1.bar(x - ancho/2, accuracies, width=ancho, label='Precisión (Test)', color='skyblue')
    ax1.set_xlabel('Número de Neuronas')
    ax1.set_ylabel('Precisión', color='skyblue')
    ax1.tick_params(axis='y', labelcolor='skyblue')
    ax1.set_xticks(x)
    ax1.set_xticklabels(neuronas)
    ax1.set_ylim(rango_precision)  # Ajuste dinámico del rango para precisión

    # Segundo eje Y: Tiempo
    ax2 = ax1.twinx()
    ax2.bar(x + ancho/2, tiempos, width=ancho, label='Tiempo de Entrenamiento (s)', color='salmon')
    ax2.set_ylabel('Tiempo de Entrenamiento (s)', color='salmon')
    ax2.tick_params(axis='y', labelcolor='salmon')

    # Leyendas independientes
    ax1.legend(loc='upper left')
    ax2.legend(loc='upper right')

    # Título y ajustes finales
    plt.title('Resultados en función del Número de Neuronas')
    plt.tight_layout()
    plt.show()

    

def optimizarMLP(X_train, Y_train, X_test, Y_test, num_capas_list, neuronas=128):
    
    resultados = []

    for num_capas in num_capas_list:
        print(f"\nEntrenando con {num_capas} capas y {neuronas} neuronas por capa")

        # Crear el modelo con un número variable de capas
        modelo = Sequential()

        # Añadir la primera capa
        modelo.add(Dense(neuronas, input_shape=(X_train.shape[1],)))
        modelo.add(LeakyReLU())  # LeakyReLU activación en la capa oculta

        # Añadir capas ocultas adicionales
        for _ in range(num_capas - 1):
            modelo.add(Dense(neuronas))
            modelo.add(LeakyReLU())

        # Capa de salida
        modelo.add(Dense(10, activation='softmax'))  # Para clasificación multiclase

        modelo.compile(optimizer=Adam(),
                       loss='categorical_crossentropy',
                       metrics=['accuracy'])

        # Configurar EarlyStopping basado en val_loss y val_accuracy
        early_stop_loss = EarlyStopping(
            monitor='val_loss', patience=5, restore_best_weights=True, verbose=1
        )
        early_stop_acc = EarlyStopping(
            monitor='val_accuracy', patience=5, restore_best_weights=True, verbose=1
        )

        # Medir tiempo de entrenamiento
        inicio = time.time()
        historial = modelo.fit(
            X_train, Y_train,
            epochs=100,  # Establecemos un número máximo alto para permitir EarlyStopping
            batch_size=128,
            validation_split=0.1,
            verbose=1,
            callbacks=[early_stop_loss, early_stop_acc]
        )
        fin = time.time()

        # Evaluar el modelo
        resultados_test = modelo.evaluate(X_test, Y_test, verbose=0)
        tiempo_entrenamiento = fin - inicio

        print(f"Tiempo de entrenamiento: {tiempo_entrenamiento:.2f} segundos")
        print(f"Pérdida en test: {resultados_test[0]:.4f}")
        print(f"Precisión en test: {resultados_test[1]:.4f}")

        resultados.append({
            'num_capas': num_capas,
            'neuronas': neuronas,
            'test_accuracy': resultados_test[1],
            'train_time': tiempo_entrenamiento
        })

    return resultados


def graficarResultadosOptimizado(resultados):
    
    num_capas = [r['num_capas'] for r in resultados]
    accuracies = [r['test_accuracy'] for r in resultados]
    tiempos = [r['train_time'] for r in resultados]

    # Verificar si algún valor de precisión está fuera del rango [0.3, 0.5]
    min_acc = min(accuracies)
    max_acc = max(accuracies)
    rango_precision = (0.40, 0.5) if (min_acc >= 0.3 and max_acc <= 0.5) else (min_acc - 0.02, max_acc + 0.02)

    x = np.arange(len(resultados))  # Índices de las configuraciones
    ancho = 0.4  # Ancho de las barras

    fig, ax1 = plt.subplots(figsize=(12, 6))

    # Primer eje Y: Precisión
    ax1.bar(x - ancho/2, accuracies, width=ancho, label='Precisión (Test)', color='skyblue')
    ax1.set_xlabel('Configuración de Capas y Neuronas')
    ax1.set_ylabel('Precisión', color='skyblue')
    ax1.tick_params(axis='y', labelcolor='skyblue')
    ax1.set_xticks(x)
    ax1.set_xticklabels([f'{r["num_capas"]} capas\n{r["neuronas"]} neuronas' for r in resultados], rotation=45)
    ax1.set_ylim(rango_precision)  # Ajuste dinámico del rango para precisión

    # Segundo eje Y: Tiempo
    ax2 = ax1.twinx()
    ax2.bar(x + ancho/2, tiempos, width=ancho, label='Tiempo de Entrenamiento (s)', color='salmon')
    ax2.set_ylabel('Tiempo de Entrenamiento (s)', color='salmon')
    ax2.tick_params(axis='y', labelcolor='salmon')

    # Leyendas independientes
    ax1.legend(loc='upper left')
    ax2.legend(loc='upper right')

    # Título y ajustes finales
    plt.title('Resultados en función de las Capas y Neuronas')
    plt.tight_layout()
    plt.show()




def construirCNN(maxpooling=False):

    model = Sequential()
    
    # Primera capa Conv2D
    model.add(Conv2D(16, (3, 3), activation='relu', input_shape=(32, 32, 3)))
    if maxpooling:
        model.add(MaxPooling2D(pool_size=(2, 2)))
    
    # Segunda capa Conv2D
    model.add(Conv2D(32, (3, 3), activation='relu'))
    if maxpooling:
        model.add(MaxPooling2D(pool_size=(2, 2)))
    
    # Aplanar y añadir capa Dense para clasificación
    model.add(Flatten())
    model.add(Dense(10, activation='softmax'))
    
    # Compilar modelo
    model.compile(optimizer=Adam(),
                  loss='categorical_crossentropy',
                  metrics=['accuracy'])
    return model


def entrenarCNN(model, X_train, Y_train, X_test, Y_test, epochs=100, batch_size=128):

    # Configurar EarlyStopping basado en val_loss y val_accuracy
    early_stop_loss = EarlyStopping(
        monitor='val_loss', patience=5, restore_best_weights=True, verbose=1
    )
    early_stop_acc = EarlyStopping(
        monitor='val_accuracy', patience=5, restore_best_weights=True, verbose=1
    )

    # Medir tiempo de entrenamiento
    inicio = time.time()
    history = model.fit(
        X_train, Y_train,
        epochs=epochs,
        batch_size=batch_size,
        validation_split=0.1,
        verbose=1,
        callbacks=[early_stop_loss, early_stop_acc]
    )
    fin = time.time()
    
    tiempo = fin - inicio
    resultados = model.evaluate(X_test, Y_test, verbose=0)
    
    print(f"Tiempo de entrenamiento: {tiempo:.2f} segundos")
    print(f"Pérdida en test: {resultados[0]:.4f}")
    print(f"Precisión en test: {resultados[1]:.4f}")
    return history, resultados, tiempo


def graficarHistorialCNN(history, titulo):

    # Extraer las métricas del historial
    acc = history.history['accuracy']
    val_acc = history.history['val_accuracy']
    loss = history.history['loss']
    val_loss = history.history['val_loss']

    # Número de épocas
    epochs = range(1, len(acc) + 1)

    # Crear el lienzo y los ejes
    fig, ax1 = plt.subplots(figsize=(12, 6))

    # Eje izquierdo para precisión
    ax1.set_xlabel('Épocas')
    ax1.set_ylabel('Precisión', color='tab:blue')
    ax1.plot(epochs, acc, label='Precisión de Entrenamiento', color='tab:blue', linestyle='--')
    ax1.plot(epochs, val_acc, label='Precisión de Validación', color='tab:blue')
    ax1.tick_params(axis='y', labelcolor='tab:blue')
    ax1.legend(loc='upper left')

    # Eje derecho para pérdida
    ax2 = ax1.twinx()  # Crear un segundo eje Y que comparta el mismo eje X
    ax2.set_ylabel('Pérdida', color='tab:red')
    ax2.plot(epochs, loss, label='Pérdida de Entrenamiento', color='tab:red', linestyle='--')
    ax2.plot(epochs, val_loss, label='Pérdida de Validación', color='tab:red')
    ax2.tick_params(axis='y', labelcolor='tab:red')
    ax2.legend(loc='upper right')

    # Título y mostrar gráfica
    plt.title(f'{titulo} - Evolución del Entrenamiento')
    plt.tight_layout()
    plt.show()

def construirCNNKernel(kernel_size=(3, 3)):
    """
    Construye una CNN con MaxPooling2D después de cada Conv2D.
    Args:
        kernel_size (tuple): Tamaño del filtro en las capas Conv2D.
    Returns:
        model: Modelo CNN construido.
    """
    model = Sequential()
    
    # Primera capa Conv2D
    model.add(Conv2D(16, kernel_size, activation='relu', input_shape=(32, 32, 3)))
    model.add(MaxPooling2D(pool_size=(2, 2)))  # MaxPooling2D

    # Segunda capa Conv2D
    model.add(Conv2D(32, kernel_size, activation='relu'))
    model.add(MaxPooling2D(pool_size=(2, 2)))  # MaxPooling2D

    # Aplanar y añadir capa Dense para clasificación
    model.add(Flatten())
    model.add(Dense(10, activation='softmax'))
    
    # Compilar modelo
    model.compile(optimizer=Adam(),
                  loss='categorical_crossentropy',
                  metrics=['accuracy'])
    return model



def experimentoKernelSize(X_train, Y_train, X_test, Y_test, kernel_sizes):
    """
    Prueba diferentes tamaños de kernel y registra el mejor val_accuracy y tiempo promedio por época.
    Incluye EarlyStopping para evitar sobreentrenamiento.
    Args:
        X_train, Y_train: Datos de entrenamiento.
        X_test, Y_test: Datos de prueba.
        kernel_sizes (list): Lista de tamaños de kernel a probar.
    Returns:
        resultados: Lista de resultados con el mejor val_accuracy y tiempo promedio para cada kernel_size.
    """
    resultados = []

    for kernel_size in kernel_sizes:
        print(f"\nEntrenando con kernel_size={kernel_size}")

        # Construir el modelo
        modelo = construirCNNKernel(kernel_size=kernel_size)

        # Configurar EarlyStopping
        early_stop = EarlyStopping(
            monitor='val_accuracy',  # También puedes monitorear 'val_loss'
            patience=5,             # Detener después de 5 épocas sin mejora
            restore_best_weights=True,
            verbose=1
        )

        # Medir tiempo total de entrenamiento
        inicio = time.time()
        history = modelo.fit(
            X_train, Y_train,
            epochs=100,
            batch_size=128,
            validation_split=0.1,
            verbose=1,
            callbacks=[early_stop]  # EarlyStopping incluido
        )
        fin = time.time()

        # Calcular tiempo promedio por época
        tiempo_total = fin - inicio
        tiempo_promedio = tiempo_total / len(history.epoch)

        # Obtener el mejor val_accuracy del historial
        mejor_val_accuracy = max(history.history['val_accuracy'])

        print(f"Tiempo total de entrenamiento: {tiempo_total:.2f} segundos")
        print(f"Tiempo promedio por época: {tiempo_promedio:.2f} segundos")
        print(f"Mejor Val Accuracy: {mejor_val_accuracy:.4f}")

        resultados.append({
            'kernel_size': kernel_size,
            'val_accuracy': mejor_val_accuracy,
            'train_time_avg': tiempo_promedio
        })

    return resultados



def graficarResultadosKernelSize(resultados):
    """
    Grafica los resultados de val_accuracy y tiempo promedio por época para diferentes tamaños de kernel.
    Incluye dos ejes Y: uno para val_accuracy y otro para el tiempo promedio por época.
    Args:
        resultados: Lista de resultados del experimento.
        Cada elemento debe incluir 'kernel_size', 'val_accuracy', y 'train_time_avg'.
    """
    kernel_sizes = [str(r['kernel_size']) for r in resultados]
    val_accuracies = [r['val_accuracy'] for r in resultados]
    tiempos_medios = [r['train_time_avg'] for r in resultados]

    x = range(len(kernel_sizes))
    ancho_barra = 0.4

    fig, ax1 = plt.subplots(figsize=(10, 6))

    # Barras de val_accuracy (eje izquierdo)
    ax1.bar(
        [p - ancho_barra / 2 for p in x], 
        val_accuracies, 
        width=ancho_barra, 
        label='Val Accuracy', 
        color='skyblue'
    )
    ax1.set_ylabel('Val Accuracy', color='blue')
    ax1.set_ylim(0.6, 0.75)
    ax1.set_xlabel('Tamaño del Kernel')
    ax1.set_xticks(x)
    ax1.set_xticklabels(kernel_sizes)
    ax1.tick_params(axis='y', labelcolor='blue')

    # Barras de tiempo promedio por época (eje derecho)
    ax2 = ax1.twinx()
    ax2.bar(
        [p + ancho_barra / 2 for p in x], 
        tiempos_medios, 
        width=ancho_barra, 
        label='Tiempo Promedio por Época', 
        color='salmon'
    )
    ax2.set_ylabel('Tiempo Promedio por Época (s)', color='red')
    ax2.tick_params(axis='y', labelcolor='red')

    # Leyendas combinadas
    ax1.legend(loc='upper left')
    ax2.legend(loc='upper right')

    plt.title('Resultados en función del Tamaño del Kernel')
    plt.tight_layout()
    plt.show()
    
    # Crear diferentes arquitecturas de CNN
def crear_modelo(tipo='simple'):
    modelo = Sequential()

    if tipo == 'simple':
        modelo.add(Conv2D(32, (3, 3), activation='relu', input_shape=(32, 32, 3)))
        modelo.add(Conv2D(64, (3, 3), activation='relu'))
        modelo.add(Flatten())
    
    elif tipo == 'maxpooling':
        modelo.add(Conv2D(32, (3, 3), activation='relu', input_shape=(32, 32, 3)))
        modelo.add(MaxPooling2D(pool_size=(2, 2)))
        modelo.add(Conv2D(64, (3, 3), activation='relu'))
        modelo.add(MaxPooling2D(pool_size=(2, 2)))
        modelo.add(Flatten())

    elif tipo == 'batchnorm_dropout':
        modelo.add(Conv2D(32, (3, 3), activation='relu', input_shape=(32, 32, 3)))
        modelo.add(BatchNormalization())
        modelo.add(Conv2D(64, (3, 3), activation='relu'))
        modelo.add(BatchNormalization())
        modelo.add(MaxPooling2D(pool_size=(2, 2)))
        modelo.add(Dropout(0.3))
        modelo.add(Flatten())
    
    elif tipo == 'profundo':
        modelo.add(Conv2D(32, (3, 3), activation='relu', input_shape=(32, 32, 3)))
        modelo.add(Conv2D(64, (3, 3), activation='relu'))
        modelo.add(MaxPooling2D(pool_size=(2, 2)))
        modelo.add(Conv2D(128, (3, 3), activation='relu'))
        modelo.add(Conv2D(128, (3, 3), activation='relu'))
        modelo.add(MaxPooling2D(pool_size=(2, 2)))
        modelo.add(Dropout(0.4))
        modelo.add(Flatten())

    modelo.add(Dense(128, activation='relu'))
    modelo.add(Dense(10, activation='softmax'))

    modelo.compile(optimizer=Adam(),
                   loss='categorical_crossentropy',
                   metrics=['accuracy'])
    return modelo

def entrenar_y_evaluar(modelo, X_train, Y_train, X_test, Y_test, epochs=100):
    tiempos_por_epoca = []

    # Callback para registrar el tiempo por época
    def registrar_tiempos(epoch, logs):
        tiempos_por_epoca.append(logs.get('time', 0))

    # Configurar EarlyStopping basado en val_loss y val_accuracy
    early_stop_loss = EarlyStopping(monitor='val_loss', patience=5, restore_best_weights=True, verbose=1)
    early_stop_acc = EarlyStopping(monitor='val_accuracy', patience=5, restore_best_weights=True, verbose=1)
    
    # Entrenamiento con EarlyStopping y callback para tiempos
    inicio = time.time()  # Iniciar el contador de tiempo
    historia = modelo.fit(X_train, Y_train,
                          epochs=epochs,  # Se establece un número máximo de 100 épocas
                          batch_size=128,
                          validation_split=0.1,
                          verbose=1,
                          callbacks=[early_stop_loss, early_stop_acc, LambdaCallback(on_epoch_end=registrar_tiempos)])

    fin = time.time()  # Fin del contador de tiempo
    resultados = modelo.evaluate(X_test, Y_test, verbose=0)
    
    # Calcular tiempo medio por época
    tiempo_medio = (fin - inicio) / len(historia.history['val_accuracy'])
    
    # Promediar la precisión de validación
    val_accuracy_medio = sum(historia.history['val_accuracy']) / len(historia.history['val_accuracy'])

    return historia, resultados, tiempo_medio, val_accuracy_medio


def graficar_tiempos_y_precisiones(historiales, nombres, tiempos_por_epoca_list, val_accuracy_list):
    # Definir el número de modelos
    num_modelos = len(nombres)

    # Crear una figura y dos ejes
    fig, ax1 = plt.subplots(figsize=(12, 6))

    # Establecer el ancho de las barras
    ancho_barras = 0.35

    # Crear las posiciones para las barras
    indices = np.arange(num_modelos)

    # Graficar las barras de precisión de validación
    ax1.bar(indices - ancho_barras / 2, val_accuracy_list, ancho_barras, label='Precisión de Validación Promedio', color='skyblue')

    # Etiquetas del primer eje (Precisión de validación)
    ax1.set_xlabel('Modelos')
    ax1.set_ylabel('Precisión de Validación Promedio')
    ax1.set_xticks(indices)
    ax1.set_xticklabels(nombres)
    ax1.tick_params(axis='y', labelcolor='blue')
    ax1.set_ylim([0, 1])  # Asegurarse de que la precisión de validación esté entre 0 y 1

    # Crear el segundo eje Y para el tiempo medio por época
    ax2 = ax1.twinx()

    # Graficar las barras del tiempo medio por época
    ax2.bar(indices + ancho_barras / 2, tiempos_por_epoca_list, ancho_barras, label='Tiempo Medio por Época (s)', color='orange')

    # Etiquetas del segundo eje (Tiempo medio por época)
    ax2.set_ylabel('Tiempo Medio por Época (s)', color='orange')
    ax2.tick_params(axis='y', labelcolor='orange')

    # Leyenda y título
    ax1.legend(loc='upper left', bbox_to_anchor=(0, 1))
    ax2.legend(loc='upper left', bbox_to_anchor=(0, 0.95))
    plt.title('Comparación de Modelos CNN: Precisión de Validación y Tiempo Medio por Época')

    # Mostrar el gráfico
    plt.tight_layout()
    plt.show()
    
# Función para entrenar el modelo
def entrenar_modelo_parametrizado(X_train, Y_train, X_test, Y_test, capas_ocultas, activacion, optimizador, lr, batch_size, kernel_size, epochs=100):
    # Crear el modelo
    modelo = Sequential()

    # Añadir capas Conv2D
    modelo.add(Conv2D(32, kernel_size, activation='relu', input_shape=(32, 32, 3)))
    modelo.add(MaxPooling2D(pool_size=(2, 2)))
    modelo.add(Conv2D(64, kernel_size, activation='relu'))
    modelo.add(MaxPooling2D(pool_size=(2, 2)))
    modelo.add(Flatten())

    # Añadir capas ocultas
    for unidades in capas_ocultas:
        modelo.add(Dense(unidades, activation=activacion))

    # Añadir capa de salida
    modelo.add(Dense(10, activation='softmax'))

    # Configurar optimizador
    if optimizador == 'adam':
        opt = Adam(learning_rate=lr)
    elif optimizador == 'sgd':
        opt = SGD(learning_rate=lr)

    modelo.compile(optimizer=opt, loss='categorical_crossentropy', metrics=['accuracy'])

    # Configurar EarlyStopping
    early_stop = EarlyStopping(monitor='val_loss', patience=5, restore_best_weights=True, verbose=1)

    # Entrenar el modelo
    inicio = time.time()
    historia = modelo.fit(
        X_train, Y_train,
        epochs=epochs,
        batch_size=batch_size,
        validation_split=0.1,
        callbacks=[early_stop],
        verbose=1
    )
    fin = time.time()

    # Calcular tiempo medio por época
    tiempo_total = fin - inicio
    tiempo_medio_por_epoca = tiempo_total / len(historia.history['val_accuracy'])

    # Obtener la mejor val_accuracy
    mejor_val_accuracy = max(historia.history['val_accuracy'])

    print(f"\nResultados del entrenamiento:")
    print(f"Mejor Val Accuracy: {mejor_val_accuracy:.4f}")
    print(f"Tiempo medio por época: {tiempo_medio_por_epoca:.2f} segundos")

    # Obtener las predicciones sobre el conjunto de prueba
    predicciones = modelo.predict(X_test)

    # Mostrar la matriz de confusión
    graficarMatrizConfusion(Y_test, predicciones)

    return {
        'val_accuracy': mejor_val_accuracy,
        'train_time_avg': tiempo_medio_por_epoca
    }


if __name__ == "__main__":
    # Cargar datos
    X_train, Y_train, X_test, Y_test = cargarCifar10()

    """
    #TAREA A
    # Entrenamiento estándar
    print("Entrenamiento estándar:")
    modelo1, historial1, resultados1 = entrenarMLP(X_train, Y_train, X_test, Y_test)
    graficarHistorial(historial1)
    
    # Predicciones y matriz de confusión
    predicciones = modelo2.predict(X_test)
    graficarMatrizConfusion(Y_test, predicciones)
    """
    
    """
    #TAREA B
    # Entrenamiento con detección de sobreentrenamiento
    print("\nEntrenamiento con detección de sobreentrenamiento:")
    modelo2, historial2, resultados2 = entrenarConDeteccion(X_train, Y_train, X_test, Y_test)
    graficarHistorial(historial2)
    """
    
    """
    #TAREA C
    #Probar distintos batch_size
    batch_sizes = [8, 16, 32, 64, 128, 256]
    resultados = experimentoBatchSize(X_train, Y_train, X_test, Y_test, batch_sizes)
    graficarResultadosBatchSize(resultados)

    mejor = max(resultados, key=lambda x: x['test_accuracy'])
    print(f"\nMejor batch_size: {mejor['batch_size']} con precisión en test: {mejor['test_accuracy']:.4f} y tiempo: {mejor['train_time']:.2f} segundos")
    """
    """
    #TAREA D
    # Probar distintas funciones de activación
    activaciones = ['relu', 'tanh', 'leaky_relu', 'sigmoid']  # Elegir las funciones de activación a probar
    resultados_activacion = experimentoFuncionesActivacion(X_train, Y_train, X_test, Y_test, activaciones)
    graficarResultadosActivacion(resultados_activacion)

    mejor = max(resultados_activacion, key=lambda x: x['test_accuracy'])
    print(f"\nMejor función de activación: {mejor['activation']} con precisión en test: {mejor['test_accuracy']:.4f} y tiempo: {mejor['train_time']:.2f} segundos")
    """
    
    """
    #TAREA E
    # Número de neuronas a probar
    neuronas_list = [32, 64, 128, 256, 512, 1024, 2048, 4096]
    resultados_neurona = experimentoNeurona(X_train, Y_train, X_test, Y_test, neuronas_list)
    graficarResultadosNeurona(resultados_neurona)
    
    mejor = max(resultados_neurona, key=lambda x: x['test_accuracy'])
    print(f"\nMejor número de neuronas: {mejor['neuronas']} con precisiÃ³n en test: {mejor['test_accuracy']:.4f} y tiempo: {mejor['train_time']:.2f} segundos")
    """
    
    """
    #TAREA F
    # Número de capas ocultas a probar
    num_capas_list = [1, 2, 3, 4, 5, 6, 7]  # Probar con 1, 2, 3, 4 y 5 capas ocultas
    resultados_optimizado = optimizarMLP(X_train, Y_train, X_test, Y_test, num_capas_list)
    graficarResultadosOptimizado(resultados_optimizado)

    mejor = max(resultados_optimizado, key=lambda x: x['test_accuracy'])
    print(f"\nMejor configuración: {mejor['num_capas']} capas y {mejor['neuronas']} neuronas con precisión en test: {mejor['test_accuracy']:.4f} y tiempo: {mejor['train_time']:.2f} segundos")
    """    
    
    """
    #TAREA G
    # Cargar y preprocesar los datos para CNN
    (X_train, Y_train), (X_test, Y_test) = cifar10.load_data()
    X_train = X_train.astype('float32') / 255.0
    X_test = X_test.astype('float32') / 255.0
    Y_train = to_categorical(Y_train, 10)
    Y_test = to_categorical(Y_test, 10)
    
    # Modelo básico (sin MaxPooling2D)
    print("\nModelo básico (sin MaxPooling2D):")
    modelo_basico = construirCNN(maxpooling=False)
    history_basico, resultados_basico, tiempo_basico = entrenarCNN(modelo_basico, X_train, Y_train, X_test, Y_test, epochs=100)
    graficarHistorialCNN(history_basico, "CNN Básico")
    
    # Modelo con MaxPooling2D
    print("\nModelo con MaxPooling2D:")
    modelo_maxpool = construirCNN(maxpooling=True)
    history_maxpool, resultados_maxpool, tiempo_maxpool = entrenarCNN(modelo_maxpool, X_train, Y_train, X_test, Y_test, epochs=100)
    graficarHistorialCNN(history_maxpool, "CNN con MaxPooling2D")
    
    # Comparación de resultados
    print("\nResultados Comparativos:")
    print(f"Modelo Básico - Precisión en test: {resultados_basico[1]:.4f}, Tiempo: {tiempo_basico:.2f} segundos")
    print(f"Modelo con MaxPooling2D - Precisión en test: {resultados_maxpool[1]:.4f}, Tiempo: {tiempo_maxpool:.2f} segundos")
    """
    
    """
    # Tarea H
    # Cargar y preprocesar los datos
    (X_train, Y_train), (X_test, Y_test) = cifar10.load_data()
    X_train = X_train.astype('float32') / 255.0
    X_test = X_test.astype('float32') / 255.0
    Y_train = to_categorical(Y_train, 10)
    Y_test = to_categorical(Y_test, 10)

    # Tamaños de kernel a probar
    kernel_sizes = [(3, 3), (5, 5), (7, 7), (9, 9)]

    # Realizar el experimento
    resultados_kernel = experimentoKernelSize(X_train, Y_train, X_test, Y_test, kernel_sizes)

    # Graficar los resultados
    graficarResultadosKernelSize(resultados_kernel)
    """
    
    """
    # Tarea I
    # Cargar los datos
    X_train, Y_train, X_test, Y_test = cargar_Cifar10_CNN()

    # Definir los modelos y entrenarlos
    modelos = ['simple', 'maxpooling', 'batchnorm_dropout', 'profundo']
    nombres_modelos = modelos
    historiales = []
    resultados = []
    tiempos_por_epoca_list = []
    val_accuracy_list = []

    for tipo in modelos:
        modelo = crear_modelo(tipo)
        print(f'Entrenando modelo {tipo}')
        historia, resultado, tiempo_medio, val_accuracy_medio = entrenar_y_evaluar(modelo, X_train, Y_train, X_test, Y_test, epochs=100)
        historiales.append(historia)
        resultados.append(resultado)
        tiempos_por_epoca_list.append(tiempo_medio)
        val_accuracy_list.append(val_accuracy_medio)
        print(f'{tipo} - Precisión en test: {resultado[1]:.4f}, Tiempo Medio por Época: {tiempo_medio:.2f} segundos, Val Accuracy Medio: {val_accuracy_medio:.4f}')

    # Graficar los resultados
    graficar_tiempos_y_precisiones(historiales, nombres_modelos, tiempos_por_epoca_list, val_accuracy_list)
    """
    
    #Tarea K
    X, Y = cargar_imagenes()

    # Dividir el dataset en entrenamiento y prueba
    from sklearn.model_selection import train_test_split
    X_train, X_test, Y_train, Y_test = train_test_split(X, Y, test_size=0.2, random_state=42)

    # Entrenar el modelo usando los datos personalizados
    resultados = entrenar_modelo_parametrizado(
        X_train, Y_train, X_test, Y_test,
        capas_ocultas=(128, 64),
        activacion='leaky_relu',
        optimizador='adam',
        lr=0.001,
        batch_size=128,
        kernel_size=(3, 3),
        epochs=100
    )

