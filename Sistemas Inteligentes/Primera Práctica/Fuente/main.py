import sys, pygame, time, math
from casilla import *
from mapa import *
from pygame.locals import *


MARGEN=5
MARGEN_INFERIOR=60
TAM=30
NEGRO=(0,0,0)
HIERBA=(250, 180, 160)
MURO=(30, 70, 140)
AGUA=(173, 216, 230) 
ROCA=(110, 75, 48)
AMARILLO=(255, 255, 0) 

# ---------------------------------------------------------------------
# Funciones
# ---------------------------------------------------------------------

# Devuelve si una casilla del mapa se puede seleccionar como destino o como origen
def bueno(mapi, pos):
    res= False
    
    if mapi.getCelda(pos.getFila(),pos.getCol())==0 or mapi.getCelda(pos.getFila(),pos.getCol())==4 or mapi.getCelda(pos.getFila(),pos.getCol())==5:
       res=True
    
    return res
    
# Devuelve si una posición de la ventana corresponde al mapa
def esMapa(mapi, posicion):
    res=False     
    
    if posicion[0] > MARGEN and posicion[0] < mapi.getAncho()*(TAM+MARGEN)+MARGEN and \
    posicion[1] > MARGEN and posicion[1] < mapi.getAlto()*(TAM+MARGEN)+MARGEN:
        res= True       
    
    return res
    
#Devuelve si se ha pulsado algún botón
def pulsaBoton(mapi, posicion):
    res=-1
    
    if posicion[0] > (mapi.getAncho()*(TAM+MARGEN)+MARGEN)//2-65 and posicion[0] < (mapi.getAncho()*(TAM+MARGEN)+MARGEN)//2-15 and \
       posicion[1] > mapi.getAlto()*(TAM+MARGEN)+MARGEN+10 and posicion[1] < MARGEN_INFERIOR+mapi.getAlto()*(TAM+MARGEN)+MARGEN:
        res=1 #boton A* pulsado
    elif posicion[0] > (mapi.getAncho()*(TAM+MARGEN)+MARGEN)//2+15 and posicion[0] < (mapi.getAncho()*(TAM+MARGEN)+MARGEN)//2+65 and \
       posicion[1] > mapi.getAlto()*(TAM+MARGEN)+MARGEN+10 and posicion[1] < MARGEN_INFERIOR+mapi.getAlto()*(TAM+MARGEN)+MARGEN:
        res=2 #boton A*e pulsado

    
    return res
   
# Construye la matriz para guardar el camino
# Utilizar para mostrar el camino que calcula A*
def inic(mapi):    
    cam=[]
    for i in range(mapi.alto):        
        cam.append([])
        for j in range(mapi.ancho):            
            cam[i].append('.')
    
    return cam

# Funcion auxiliar para calcular las calorias
def calcularCalorias(casilla, mapi):
    aux = casilla
    calorias = 0
    
    while aux.padre is not None:
        if mapi.getCelda(aux.getFila(), aux.getCol()) == 0:
            calorias += 2
        elif mapi.getCelda(aux.getFila(), aux.getCol()) == 4:
            calorias += 4
        elif mapi.getCelda(aux.getFila(), aux.getCol()) == 5:
            calorias += 6
        aux = aux.padre
    
    return calorias

def heuristica(pos1, pos2):
    
    #Manhattan, NO ADMISIBLE!!!
    #return abs(pos1.getFila() - pos2.getFila()) + abs(pos1.getCol() - pos2.getCol())
    
    #Euclides
    #return math.sqrt((pos1.getFila() - pos2.getFila())**2 + (pos1.getCol() - pos2.getCol())**2)
    
    #Octile LA MEJOR
    dx = abs(pos1.getFila() - pos2.getFila())
    dy = abs(pos1.getCol() - pos2.getCol())
    return max(dx, dy) + (1.5 - 1) * min(dx, dy)
    
    #Chebyshev
    #return max(abs(pos1.getFila() - pos2.getFila()), abs(pos1.getCol() - pos2.getCol()))

def casillasAdyacentes(n, mapi):
    adyacentes = []
    fila = n.getFila()
    col = n.getCol()
     
    direcciones = [
        (-1, 0, 1),    # Arriba
        (1, 0, 1),     # Abajo
        (0, -1, 1),    # Izquierda
        (0, 1, 1),     # Derecha
        (-1, -1, 1.5), # Arriba Izquierda
        (-1, 1, 1.5),  # Arriba Derecha
        (1, -1, 1.5),  # Abajo Izquierda
        (1, 1, 1.5)    # Abajo Derecha
    ]

    for d in direcciones:
        Afila = fila + d[0]
        ACol = col + d[1]
        
        if 0 <= Afila < mapi.getAlto() and 0 <= ACol < mapi.getAncho() and mapi.getCelda(Afila, ACol) != 1:
            auxCasilla = Casilla(Afila, ACol)
            adyacentes.append((auxCasilla, d[2])) #guardamos una tupla de la casilla y coste de desplazamiento
    
    return adyacentes

    
def AAsterisco(camino, posConejo, posZana, mapi):
    inicio = time.time()
    listaInterior = set()  # Usar un conjunto para los nodos ya procesados.
    listaFrontera = [posConejo]  # Lista para los nodos a procesar
    
    # Inicializar el nodo de inicio
    posConejo.g = 0
    posConejo.h = heuristica(posConejo, posZana)
    posConejo.f = posConejo.g + posConejo.h
    
    while listaFrontera:
        # Seleccionar el nodo con menor f
        n = min(listaFrontera, key=lambda casilla: casilla.f)
        
        # Si hemos llegado a la zanahoria
        if n.getFila() == posZana.getFila() and n.getCol() == posZana.getCol():
            coste = n.g
            cal = calcularCalorias(n, mapi)
            
            # Marcar el camino
            while n is not None:
                camino[n.getFila()][n.getCol()] = 'X'
                n = n.padre
            
            print(f'Lista interior: {len(listaInterior)}')
            fin = time.time()
            print(f'Tiempo de ejecución: {fin - inicio} segundos')

            return camino, coste
        
        # Eliminar n de listaFrontera y añadir a listaInterior
        listaFrontera.remove(n)
        listaInterior.add((n.getFila(), n.getCol()))  # Guardar las coordenadas del nodo
        
        print(f'Nodo añadido: ({n.getFila()}, {n.getCol()})')
        
        # Procesar nodos adyacentes
        for m, coste in casillasAdyacentes(n, mapi):
            if (m.getFila(), m.getCol()) in listaInterior:
                continue  # Ya procesado
            
            gP = n.g + coste
            
            # Si el nodo m no está en listaFrontera, añadirlo
            if not any(m.getFila() == casilla.getFila() and m.getCol() == casilla.getCol() for casilla in listaFrontera):
                m.padre = n
                m.g = gP
                m.h = heuristica(m, posZana)
                m.f = m.g + m.h
                listaFrontera.append(m)
            elif gP < m.g:  # Si encontramos un mejor camino hacia m
                m.padre = n
                m.g = gP
                m.h = heuristica(m, posZana)
                m.f = m.g + m.h  # Recalculamos f

    return camino, -1


def AEstrella_focal(camino, posConejo, posZana, mapi, epsilon=0.9):

    inicio = time.time()
    listaInterior = set()
    listaFrontera = [posConejo]
    
    # Inicialización del nodo inicial
    posConejo.g = 0
    posConejo.h = heuristica(posConejo, posZana)
    posConejo.f = posConejo.g + posConejo.h
    
    while listaFrontera:
        # Encontrar el mejor f(n) en la frontera
        f_min = min(nodo.f for nodo in listaFrontera)
        
        # Construir lista focal con nodos que cumplen f(n) ≤ (1+ε)f_min
        listaFocal = [
            nodo for nodo in listaFrontera 
            if nodo.f <= (1 + epsilon) * f_min
        ]
        
        # Seleccionar el nodo de la lista focal con menor gasto calórico
        n = min(listaFocal, key=lambda nodo: calcularCalorias(nodo, mapi))
        
        # Verificar si hemos llegado al objetivo
        if n.getFila() == posZana.getFila() and n.getCol() == posZana.getCol():
            coste = n.g
            calorias = calcularCalorias(n, mapi)
            
            # Reconstruir y marcar el camino
            while n is not None:
                camino[n.getFila()][n.getCol()] = 'X'
                n = n.padre
            
            fin = time.time()
            print(f'Nodos explorados: {len(listaInterior)}')
            print(f'Tiempo: {fin - inicio:.3f} segundos')
            return camino, coste, calorias
        
        # Procesar el nodo actual
        listaFrontera.remove(n)
        listaInterior.add((n.getFila(), n.getCol()))
        
        # Expandir nodos adyacentes
        for m, coste_movimiento in casillasAdyacentes(n, mapi):
            if (m.getFila(), m.getCol()) in listaInterior:
                continue
            
            # Calcular nuevo coste g considerando tanto distancia como calorías
            gasto_calorico = 0
            if mapi.getCelda(m.getFila(), m.getCol()) == 0:
                gasto_calorico = 2
            elif mapi.getCelda(m.getFila(), m.getCol()) == 4:
                gasto_calorico = 4
            elif mapi.getCelda(m.getFila(), m.getCol()) == 5:
                gasto_calorico = 6
                
            #recalcular g  
            gP = n.g + coste_movimiento
            
            nodo_en_frontera = None
            for nodo in listaFrontera:
                if nodo.getFila() == m.getFila() and nodo.getCol() == m.getCol():
                    nodo_en_frontera = nodo
                    break
            
            if nodo_en_frontera is None:  # Nodo nuevo
                m.padre = n
                m.g = gP
                m.h = heuristica(m, posZana)
                m.f = m.g + m.h
                listaFrontera.append(m)
            elif gP < nodo_en_frontera.g:  # Mejor camino encontrado
                nodo_en_frontera.padre = n
                nodo_en_frontera.g = gP
                nodo_en_frontera.f = gP + nodo_en_frontera.h
    
    return camino, -1, 0  # No se encontró camino

    
# función principal
def main():
    pygame.init()    
    
    reloj=pygame.time.Clock()
    
    if len(sys.argv)==1: #si no se indica un mapa coge mapa.txt por defecto
        file='mapa.txt'
    else:
        file=sys.argv[-1]
         
    mapi=Mapa(file)     
    camino=inic(mapi)   
    
    anchoVentana=mapi.getAncho()*(TAM+MARGEN)+MARGEN
    altoVentana= MARGEN_INFERIOR+mapi.getAlto()*(TAM+MARGEN)+MARGEN    
    dimension=[anchoVentana,altoVentana]
    screen=pygame.display.set_mode(dimension)
    pygame.display.set_caption("Practica 1")
    
    # boton del algoritmo A*
    boton1=pygame.image.load("boton1.png").convert()
    boton1=pygame.transform.scale(boton1,[50, 30])
    
    # boton del algoritmo A*e
    boton2=pygame.image.load("boton2.png").convert()
    boton2=pygame.transform.scale(boton2,[50, 30])
    
    personaje=pygame.image.load("rabbit.png").convert()
    personaje=pygame.transform.scale(personaje,[TAM, TAM])
    
    objetivo=pygame.image.load("carrot.png").convert()
    objetivo=pygame.transform.scale(objetivo,[TAM, TAM])
    
    # Variable que muestra el coste del camino.
    coste=-1
    # Variable que indica el gasto en calorias.
    cal=0
    running= True    
    origen=Casilla(-1,-1)
    destino=Casilla(-1,-1)
    
    while running:        
        #procesamiento de eventos
        for event in pygame.event.get():
            if event.type==pygame.QUIT:               
                running=False
                
            if event.type==pygame.MOUSEBUTTONDOWN:
                pos=pygame.mouse.get_pos()
                # 1 para A* y 2 para A*e
                if pulsaBoton(mapi, pos)==1 or pulsaBoton(mapi, pos)==2:
                    if origen.getFila()==-1 or destino.getFila()==-1:
                        print('Error: No hay origen o destino')
                    else:
                        camino=inic(mapi)
                        if pulsaBoton(mapi, pos)==1: #empieza el algoritmo A*
                            ###########################                                                 
                            #coste, cal=llamar a A estrella
                            camino, coste = AAsterisco(camino, origen, destino, mapi)
                            cal = 0
                            if coste==-1:
                                print('Error: No existe un camino válido entre origen y destino')
                        else:
                            # aqui empieza nuestra funcion del algoritmo A*e
                            ###########################                                                   
                            #coste, cal=llamar a A estrella subepsilon
                            camino, coste, cal = AEstrella_focal(camino, origen, destino, mapi)
                            
                            if coste==-1:
                                print('Error: No existe un camino válido entre origen y destino')
                            
                elif esMapa(mapi,pos):                    
                    if event.button==1: #botón izquierdo                        
                        colOrigen=pos[0]//(TAM+MARGEN)
                        filOrigen=pos[1]//(TAM+MARGEN)
                        
                        # Casilla del conejo
                        casO=Casilla(filOrigen, colOrigen)                        
                        if bueno(mapi, casO):
                            origen=casO
                        else: # se ha hecho click en una celda no accesible
                            print('Error: Esa casilla no es válida')
                            
                    elif event.button==3: #botón derecho
                        colDestino=pos[0]//(TAM+MARGEN)
                        filDestino=pos[1]//(TAM+MARGEN)
                        
                        # Casilla de la zanaoria
                        casD=Casilla(filDestino, colDestino)                        
                        if bueno(mapi, casD):
                            destino=casD
                        else: # se ha hecho click en una celda no accesible
                            print('Error: Esa casilla no es válida')         
        
        #código de dibujo        
        #limpiar pantalla
        screen.fill(NEGRO)
        #pinta mapa
        for fil in range(mapi.getAlto()):
            for col in range(mapi.getAncho()):                
                if camino[fil][col]!='.':
                    pygame.draw.rect(screen, AMARILLO, [(TAM+MARGEN)*col+MARGEN, (TAM+MARGEN)*fil+MARGEN, TAM, TAM], 0)
                elif mapi.getCelda(fil,col)==0:
                    pygame.draw.rect(screen, HIERBA, [(TAM+MARGEN)*col+MARGEN, (TAM+MARGEN)*fil+MARGEN, TAM, TAM], 0)
                elif mapi.getCelda(fil,col)==4:
                    pygame.draw.rect(screen, AGUA, [(TAM+MARGEN)*col+MARGEN, (TAM+MARGEN)*fil+MARGEN, TAM, TAM], 0)
                elif mapi.getCelda(fil,col)==5:
                    pygame.draw.rect(screen, ROCA, [(TAM+MARGEN)*col+MARGEN, (TAM+MARGEN)*fil+MARGEN, TAM, TAM], 0)                                    
                elif mapi.getCelda(fil,col)==1:
                    pygame.draw.rect(screen, MURO, [(TAM+MARGEN)*col+MARGEN, (TAM+MARGEN)*fil+MARGEN, TAM, TAM], 0)
                    
        #pinta origen
        screen.blit(personaje, [(TAM+MARGEN)*origen.getCol()+MARGEN, (TAM+MARGEN)*origen.getFila()+MARGEN])        
        #pinta destino
        screen.blit(objetivo, [(TAM+MARGEN)*destino.getCol()+MARGEN, (TAM+MARGEN)*destino.getFila()+MARGEN])       
        #pinta botón
        screen.blit(boton1, [anchoVentana//2-65, mapi.getAlto()*(TAM+MARGEN)+MARGEN+10])
        screen.blit(boton2, [anchoVentana//2+15, mapi.getAlto()*(TAM+MARGEN)+MARGEN+10])
        #pinta coste y energía
        if coste!=-1:            
            fuente= pygame.font.Font(None, 25)
            textoCoste=fuente.render("Coste: "+str(coste), True, AMARILLO)            
            screen.blit(textoCoste, [anchoVentana-90, mapi.getAlto()*(TAM+MARGEN)+MARGEN+15])
            textoEnergía=fuente.render("Cal: "+str(cal), True, AMARILLO)
            screen.blit(textoEnergía, [5, mapi.getAlto()*(TAM+MARGEN)+MARGEN+15])
            
        #actualizar pantalla
        pygame.display.flip()
        reloj.tick(40)
        
    pygame.quit()
    
#---------------------------------------------------------------------
if __name__=="__main__":
    main()
