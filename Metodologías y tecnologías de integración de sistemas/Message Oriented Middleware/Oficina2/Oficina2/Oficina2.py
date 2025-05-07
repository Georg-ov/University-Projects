import asyncio #todo asincrono
import random
import stomp

# Configuracion del ActiveMQ
ACTIVEMQ_HOST = "localhost"
ACTIVEMQ_PORT = 61613

# Topics para Oficina 2
TOPIC_TEMP_SENSOR = "/topic/sensor/temperatura/oficina2"
TOPIC_LUZ_SENSOR = "/topic/sensor/luz/oficina2"
TOPIC_TEMP_ACTUADOR = "/topic/actuador/temperatura/oficina2"
TOPIC_LUZ_ACTUADOR = "/topic/actuador/luz/oficina2"

# Valores de control
TEMP_MIN = 18
TEMP_MAX = 28
LUZ_MIN = 300
LUZ_MAX = 800

class SensorListener(stomp.ConnectionListener):
    """Escucha los mensajes enviados desde la consola central"""
    def __init__(self):
        self.temp_actual = random.randint(15, 30)
        self.luz_actual = random.randint(200, 1000)

    def on_message(self, frame):
        """Responde a comandos de la consola central"""
        mensaje = frame.body
        print(f"Actuador activado: {mensaje}")

        if "frio ON" in mensaje:
            self.temp_actual -= 1
        elif "calor ON" in mensaje:
            self.temp_actual += 1
        elif "luz +" in mensaje:
            self.luz_actual += 50
        elif "luz -" in mensaje:
            self.luz_actual -= 50

async def enviar_sensores(conn):
    """Publica valores de sensores cada 5 segundos"""
    while True:
        temp = random.randint(15, 30)
        luz = random.randint(200, 1000)
        
        print(f"Enviando: Temperatura {temp} C, Luz {luz} lumenes")

        conn.send(destination=TOPIC_TEMP_SENSOR, body=str(temp))
        conn.send(destination=TOPIC_LUZ_SENSOR, body=str(luz))

        await asyncio.sleep(5)

async def main():
    """Configura conexion con ActiveMQ y ejecuta procesos asincronicos"""
    conn = stomp.Connection([(ACTIVEMQ_HOST, ACTIVEMQ_PORT)])
    listener = SensorListener()
    conn.set_listener("", listener)
    
    conn.connect("oficina2", "oficina2", wait=True) #Autenticacion

    # Suscribirse a los topics de actuadores de Oficina 2
    conn.subscribe(destination=TOPIC_TEMP_ACTUADOR, id=1, ack="auto")
    conn.subscribe(destination=TOPIC_LUZ_ACTUADOR, id=2, ack="auto")

    # Enviar datos de sensores en un bucle asincronico
    await enviar_sensores(conn)

if __name__ == "__main__":
    print("OFICINA 2")
    asyncio.run(main())
