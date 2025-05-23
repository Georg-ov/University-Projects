from casilla import *

class Mapa():
    def __init__(self, archivo):        
        self.mapa=leer(archivo)         
        self.alto = len(self.mapa)
        self.ancho = len(self.mapa[0])        
        
    def __str__(self):
        salida = "" 
        for f in range(self.alto):            
            for c in range(self.ancho):               
                if self.mapa[f][c] == 0:
                    salida += "  "
                if self.mapa[f][c] == 1:
                    salida += "# "                 
                if self.mapa[f][c] == 3:
                    salida += "D "
                if self.mapa[f][c] == 4:
                    salida += "~ "
                if self.mapa[f][c] == 5:
                    salida += "* "   
            salida += "\n"
        return salida
    
    def getAlto (self):
        return self.alto
    
    def getAncho (self):
        return self.ancho
    
    def getCelda(self, y, x):
        return self.mapa[y][x]
    
    def setCelda(self, y, x, valor):
        self.mapa[y][x]=valor    

# ---------------------------------------------------------------------

# Funciones
# ---------------------------------------------------------------------
def leer(archivo):
    mapa=[] 
    try:  
        fich=open(archivo, "r")
        fila=-1
        for cadena in fich:
            fila=fila+1            
            mapa.append([])            
            for i in range(len(cadena)):
                # Hierba
                if cadena[i] == ".":
                    mapa[fila].append(0)                    
                # Bloque
                if cadena[i] == "#":
                    mapa[fila].append(1)
                # Agua
                if cadena[i] == "~":
                    mapa[fila].append(4)
                # Roca
                if cadena[i] == "*":
                    mapa[fila].append(5)
                    
    except:
        print ("Error de fichero")
        fich.close()
        
    fich.close()
    return mapa
 


# ---------------------------------------------------------------------
if __name__=="__main__":   
    mapa = Mapa('mapa.txt')
    print (mapa)
    
    