class Casilla():
    def __init__(self, f, c):
        self.fila=f
        self.col=c
        self.g=0
        self.h=0
        self.f=0
        self.padre = None
        
    def getFila (self):
        return self.fila
    
    def getCol (self):
        return self.col
    
    def calcularF(self):
        self.f = self.g + self.h
        
