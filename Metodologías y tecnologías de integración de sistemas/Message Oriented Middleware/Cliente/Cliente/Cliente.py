import asyncio
import stomp

# Configuracion de ActiveMQ
ACTIVEMQ_HOST = "localhost"
ACTIVEMQ_PORT = 61613

# Topics para ambas oficinas
TOPIC_TEMP_SENSOR_OFICINA1 = "/topic/sensor/temperatura/oficina1"
TOPIC_LUZ_SENSOR_OFICINA1 = "/topic/sensor/luz/oficina1"
TOPIC_TEMP_ACTUADOR_OFICINA1 = "/topic/actuador/temperatura/oficina1"
TOPIC_LUZ_ACTUADOR_OFICINA1 = "/topic/actuador/luz/oficina1"

TOPIC_TEMP_SENSOR_OFICINA2 = "/topic/sensor/temperatura/oficina2"
TOPIC_LUZ_SENSOR_OFICINA2 = "/topic/sensor/luz/oficina2"
TOPIC_TEMP_ACTUADOR_OFICINA2 = "/topic/actuador/temperatura/oficina2"
TOPIC_LUZ_ACTUADOR_OFICINA2 = "/topic/actuador/luz/oficina2"

# Valores de control
TEMP_MIN = 18
TEMP_MAX = 28
LUZ_MIN = 300
LUZ_MAX = 800

class SensorListener(stomp.ConnectionListener):
    """Escucha los mensajes de las oficinas y controla los actuadores segun sea necesario"""
    def __init__(self, conn):
        self.conn = conn

    def on_message(self, frame):
        """Procesa los mensajes de las oficinas y activa los actuadores si es necesario"""
        oficina = frame.headers['destination'].split("/")[-1]
        mensaje = frame.body
        print(f"Mensaje recibido de {oficina}: {mensaje}")
        
        # Detectar valores de temperatura y luz
        if "temperatura" in frame.headers['destination']:
            temperatura = int(mensaje)
            self.controlar_temperatura(oficina, temperatura)
        elif "luz" in frame.headers['destination']:
            luz = int(mensaje)
            self.controlar_luz(oficina, luz)

    def controlar_temperatura(self, oficina, temperatura):
        """Controla la temperatura segun los valores preestablecidos"""
        if temperatura > TEMP_MAX:
            print(f"{oficina}: Temperatura alta! Activando sistema de frio.")
            self.conn.send(destination=f"/topic/actuador/temperatura/{oficina}", body="frio ON")
        elif temperatura < TEMP_MIN:
            print(f"{oficina}: Temperatura baja! Activando sistema de calor.")
            self.conn.send(destination=f"/topic/actuador/temperatura/{oficina}", body="calor ON")
        else:
            print(f"{oficina}: Temperatura dentro del rango.")
            self.conn.send(destination=f"/topic/actuador/temperatura/{oficina}", body="frio OFF")
            self.conn.send(destination=f"/topic/actuador/temperatura/{oficina}", body="calor OFF")

    def controlar_luz(self, oficina, luz):
        """Controla la luz segun los valores preestablecidos"""
        if luz > LUZ_MAX:
            print(f"{oficina}: Luz alta! Disminuyendo intensidad.")
            self.conn.send(destination=f"/topic/actuador/luz/{oficina}", body="luz -")
        elif luz < LUZ_MIN:
            print(f"{oficina}: Luz baja! Aumentando intensidad.")
            self.conn.send(destination=f"/topic/actuador/luz/{oficina}", body="luz +")
        else:
            print(f"{oficina}: Luz dentro del rango.")
            self.conn.send(destination=f"/topic/actuador/luz/{oficina}", body="luz OFF")

async def main():
    """Configura la conexion con ActiveMQ y ejecuta el cliente"""
    conn = stomp.Connection([(ACTIVEMQ_HOST, ACTIVEMQ_PORT)])
    listener = SensorListener(conn)
    conn.set_listener("", listener)
    
    conn.connect("cliente", "cliente123", wait=True) #Autenticacion

    # Suscribirse a los topics de los sensores de ambas oficinas
    conn.subscribe(destination=TOPIC_TEMP_SENSOR_OFICINA1, id=1, ack="auto")
    conn.subscribe(destination=TOPIC_LUZ_SENSOR_OFICINA1, id=2, ack="auto")
    conn.subscribe(destination=TOPIC_TEMP_SENSOR_OFICINA2, id=3, ack="auto")
    conn.subscribe(destination=TOPIC_LUZ_SENSOR_OFICINA2, id=4, ack="auto")

    # Mantener la conexion activa
    while True:
        await asyncio.sleep(1)

if __name__ == "__main__":
    print("CLIENTE ACTIVO Y A LA ESCUCHA")
    asyncio.run(main())
