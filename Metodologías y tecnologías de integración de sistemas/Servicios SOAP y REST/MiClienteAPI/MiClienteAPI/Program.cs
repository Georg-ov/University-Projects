using System;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using System.IO;
using System.Xml.Linq;
using Newtonsoft.Json; // Asegúrate de agregar el paquete Newtonsoft.Json desde NuGet.

class Program
{
    static async Task Main(string[] args)
    {
        using (var client = new HttpClient())
        {
            client.BaseAddress = new Uri("http://localhost:50352");

            bool salir = false;

            while (!salir)
            {
                Console.WriteLine("\n--- Menú Principal ---");
                Console.WriteLine("1. Dispositivos");
                Console.WriteLine("2. Salas");
                Console.WriteLine("3. Niveles");
                Console.WriteLine("4. Notificar");
                Console.WriteLine("5. Empleados");
                Console.WriteLine("6. ControlAccesos");
                Console.WriteLine("7. ControlPresencia");
                Console.WriteLine("8. Salir");
                Console.Write("Seleccione una opción: ");
                string opcion = Console.ReadLine();

                switch (opcion)
                {
                    case "1":
                        await MenuDispositivos(client);
                        break;
                    case "2":
                        await MenuSalas(client);
                        break;
                    case "3":
                        await MenuNiveles(client);
                        break;
                    case "4":
                        await MenuNotificaciones(client);
                        break;
                    case "5":
                        await MenuEmpleados(client);
                        break;
                    case "6":
                        await MenuControlAccesos(client);
                        break;
                    case "7":
                        await MenuControlPresencia(client);
                        break;
                    case "8":
                        salir = true;
                        Console.WriteLine("Saliendo del programa...");
                        break;
                    default:
                        Console.WriteLine("Opción no válida. Intente de nuevo.");
                        break;
                }
            }
        }
    }

    //DISPOSITIVOS

    static async Task MenuDispositivos(HttpClient client)
    {
        bool volver = false;

        while (!volver)
        {
            Console.WriteLine("\n--- Menú Dispositivos ---");
            Console.WriteLine("1. Crear Dispositivo");
            Console.WriteLine("2. Consultar Dispositivo");
            Console.WriteLine("3. Modificar Dispositivo");
            Console.WriteLine("4. Borrar Dispositivo");
            Console.WriteLine("5. Volver al Menú Principal");
            Console.Write("Seleccione una opción: ");
            string opcion = Console.ReadLine();

            switch (opcion)
            {
                case "1":
                    await CrearDispositivo(client);
                    break;
                case "2":
                    await ObtenerDispositivoPorCodigo(client);
                    break;
                case "3":
                    await ActualizarDispositivo(client);
                    break;
                case "4":
                    await EliminarDispositivo(client);
                    break;
                case "5":
                    volver = true;
                    break;
                default:
                    Console.WriteLine("Opción no válida. Intente de nuevo.");
                    break;
            }
        }
    }

    static async Task CrearDispositivo(HttpClient client)
    {
        Console.Write("Ingrese código del dispositivo: ");
        int codigo = int.Parse(Console.ReadLine());
        Console.Write("Ingrese descripción del dispositivo: ");
        string descripcion = Console.ReadLine();

        var dispositivo = new { codigo, descripcion };
        string jsonData = JsonConvert.SerializeObject(dispositivo);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        HttpResponseMessage response = await client.PostAsync("/api/dispositivos", content);

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Dispositivo creado correctamente.");
        else
            Console.WriteLine($"Error al crear dispositivo: {response.StatusCode}");
    }

    static async Task ObtenerDispositivoPorCodigo(HttpClient client)
    {
        Console.Write("Ingrese código del dispositivo a consultar: ");
        int codigo = int.Parse(Console.ReadLine());

        HttpResponseMessage response = await client.GetAsync($"/api/dispositivos/{codigo}");

        if (response.IsSuccessStatusCode)
            Console.WriteLine($"Dispositivo: {await response.Content.ReadAsStringAsync()}");
        else
            Console.WriteLine($"Error al obtener dispositivo: {response.StatusCode}");
    }

    static async Task ActualizarDispositivo(HttpClient client)
    {
        Console.Write("Ingrese código del dispositivo a modificar: ");
        int codigo = int.Parse(Console.ReadLine());
        Console.Write("Ingrese nueva descripción: ");
        string descripcion = Console.ReadLine();

        var dispositivoActualizado = new { codigo, descripcion };
        string jsonData = JsonConvert.SerializeObject(dispositivoActualizado);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        HttpResponseMessage response = await client.PutAsync($"/api/dispositivos/{codigo}", content);

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Dispositivo actualizado correctamente.");
        else
            Console.WriteLine($"Error al actualizar dispositivo: {response.StatusCode}");
    }

    static async Task EliminarDispositivo(HttpClient client)
    {
        Console.Write("Ingrese código del dispositivo a eliminar: ");
        int codigo = int.Parse(Console.ReadLine());

        HttpResponseMessage response = await client.DeleteAsync($"/api/dispositivos/{codigo}");

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Dispositivo eliminado correctamente.");
        else
            Console.WriteLine($"Error al eliminar dispositivo: {response.StatusCode}");
    }


    //SALAS

    static async Task MenuSalas(HttpClient client)
    {
        bool volver = false;

        while (!volver)
        {
            Console.WriteLine("\n--- Menú Salas ---");
            Console.WriteLine("1. Crear Sala");
            Console.WriteLine("2. Consultar Sala");
            Console.WriteLine("3. Modificar Sala");
            Console.WriteLine("4. Borrar Sala");
            Console.WriteLine("5. Volver al Menú Principal");
            Console.Write("Seleccione una opción: ");
            string opcion = Console.ReadLine();

            switch (opcion)
            {
                case "1":
                    await CrearSala(client);
                    break;
                case "2":
                    await ObtenerSalaPorCodigo(client);
                    break;
                case "3":
                    await ActualizarSala(client);
                    break;
                case "4":
                    await EliminarSala(client);
                    break;
                case "5":
                    volver = true;
                    break;
                default:
                    Console.WriteLine("Opción no válida. Intente de nuevo.");
                    break;
            }
        }
    }

    static async Task CrearSala(HttpClient client)
    {
        Console.Write("Ingrese código de la sala: ");
        int codigoSala = int.Parse(Console.ReadLine());
        Console.Write("Ingrese nombre de la sala: ");
        string nombre = Console.ReadLine();
        Console.Write("Ingrese nivel de la sala: ");
        int nivel = int.Parse(Console.ReadLine());

        var sala = new { codigoSala, nombre, nivel };
        string jsonData = JsonConvert.SerializeObject(sala);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        HttpResponseMessage response = await client.PostAsync("/api/salas", content);

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Sala creada correctamente.");
        else
            Console.WriteLine($"Error al crear sala: {response.StatusCode}");
    }

    static async Task ObtenerSalaPorCodigo(HttpClient client)
    {
        Console.Write("Ingrese código de la sala a consultar: ");
        int codigoSala = int.Parse(Console.ReadLine());

        HttpResponseMessage response = await client.GetAsync($"/api/salas/{codigoSala}");

        if (response.IsSuccessStatusCode)
            Console.WriteLine($"Sala: {await response.Content.ReadAsStringAsync()}");
        else
            Console.WriteLine($"Error al obtener sala: {response.StatusCode}");
    }

    static async Task ActualizarSala(HttpClient client)
    {
        Console.Write("Ingrese código de la sala a modificar: ");
        int codigoSala = int.Parse(Console.ReadLine());
        Console.Write("Ingrese nuevo nombre de la sala: ");
        string nombre = Console.ReadLine();
        Console.Write("Ingrese nuevo nivel de la sala: ");
        int nivel = int.Parse(Console.ReadLine());

        var salaActualizada = new { codigoSala, nombre, nivel };
        string jsonData = JsonConvert.SerializeObject(salaActualizada);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        HttpResponseMessage response = await client.PutAsync($"/api/salas/{codigoSala}", content);

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Sala actualizada correctamente.");
        else
            Console.WriteLine($"Error al actualizar sala: {response.StatusCode}");
    }

    static async Task EliminarSala(HttpClient client)
    {
        Console.Write("Ingrese código de la sala a eliminar: ");
        int codigoSala = int.Parse(Console.ReadLine());

        HttpResponseMessage response = await client.DeleteAsync($"/api/salas/{codigoSala}");

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Sala eliminada correctamente.");
        else
            Console.WriteLine($"Error al eliminar sala: {response.StatusCode}");
    }

    //NIVELES

    static async Task MenuNiveles(HttpClient client)
    {
        bool volver = false;

        while (!volver)
        {
            Console.WriteLine("\n--- Menú Niveles ---");
            Console.WriteLine("1. Crear Nivel");
            Console.WriteLine("2. Consultar Nivel");
            Console.WriteLine("3. Modificar Nivel");
            Console.WriteLine("4. Borrar Nivel");
            Console.WriteLine("5. Volver al Menú Principal");
            Console.Write("Seleccione una opción: ");
            string opcion = Console.ReadLine();

            switch (opcion)
            {
                case "1":
                    await CrearNivel(client);
                    break;
                case "2":
                    await ObtenerNivelPorCodigo(client);
                    break;
                case "3":
                    await ActualizarNivel(client);
                    break;
                case "4":
                    await EliminarNivel(client);
                    break;
                case "5":
                    volver = true;
                    break;
                default:
                    Console.WriteLine("Opción no válida. Intente de nuevo.");
                    break;
            }
        }
    }

    static async Task CrearNivel(HttpClient client)
    {
        Console.Write("Ingrese número del nivel: ");
        int nivel = int.Parse(Console.ReadLine());
        Console.Write("Ingrese descripción del nivel: ");
        string descripcion = Console.ReadLine();

        var nivelObj = new { nivel, descripcion };
        string jsonData = JsonConvert.SerializeObject(nivelObj);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        HttpResponseMessage response = await client.PostAsync("/api/niveles", content);

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Nivel creado correctamente.");
        else
            Console.WriteLine($"Error al crear nivel: {response.StatusCode}");
    }

    static async Task ObtenerNivelPorCodigo(HttpClient client)
    {
        Console.Write("Ingrese número del nivel a consultar: ");
        int nivel = int.Parse(Console.ReadLine());

        HttpResponseMessage response = await client.GetAsync($"/api/niveles/{nivel}");

        if (response.IsSuccessStatusCode)
            Console.WriteLine($"Nivel: {await response.Content.ReadAsStringAsync()}");
        else
            Console.WriteLine($"Error al obtener nivel: {response.StatusCode}");
    }

    static async Task ActualizarNivel(HttpClient client)
    {
        Console.Write("Ingrese número del nivel a modificar: ");
        int nivel = int.Parse(Console.ReadLine());
        Console.Write("Ingrese nueva descripción del nivel: ");
        string descripcion = Console.ReadLine();

        var nivelActualizado = new { nivel, descripcion };
        string jsonData = JsonConvert.SerializeObject(nivelActualizado);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        HttpResponseMessage response = await client.PutAsync($"/api/niveles/{nivel}", content);

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Nivel actualizado correctamente.");
        else
            Console.WriteLine($"Error al actualizar nivel: {response.StatusCode}");
    }

    static async Task EliminarNivel(HttpClient client)
    {
        Console.Write("Ingrese número del nivel a eliminar: ");
        int nivel = int.Parse(Console.ReadLine());

        HttpResponseMessage response = await client.DeleteAsync($"/api/niveles/{nivel}");

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Nivel eliminado correctamente.");
        else
            Console.WriteLine($"Error al eliminar nivel: {response.StatusCode}");
    }


//NOTIFICACIONES


    static async Task MenuNotificaciones(HttpClient client)
    {
        bool volver = false;

        while (!volver)
        {
            Console.WriteLine("\n--- Menú Principal ---");
            Console.WriteLine("1. Notificar Error");
            Console.WriteLine("2. Notificar Presencia en Sala");
            Console.WriteLine("3. Notificar Usuario Válido");
            Console.WriteLine("4. Volver al Menú Principal");
            Console.Write("Seleccione una opción: ");
            string opcion = Console.ReadLine();

            switch (opcion)
            {
                case "1":
                    await NotificarError(client);
                    break;
                case "2":
                    await NotificarPresencia(client);
                    break;
                case "3":
                    await NotificarUsuarioValido(client);
                    break;
                case "4":
                    volver = true;
                    break;
                default:
                    Console.WriteLine("Opción no válida. Intente de nuevo.");
                    break;
            }
        }
    }

    static async Task NotificarError(HttpClient client)
    {
        Console.Write("Ingrese el NIF del empleado: ");
        string nif = Console.ReadLine();
        Console.Write("Ingrese el mensaje de error: ");
        string error = Console.ReadLine();

        var body = new { Nif = nif, Error = error };
        string jsonData = JsonConvert.SerializeObject(body);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        HttpResponseMessage response = await client.PostAsync("/api/notificaciones/error", content);

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Notificación de error enviada correctamente.");
        else
            Console.WriteLine($"Error al enviar notificación: {response.StatusCode}");
    }

    static async Task NotificarPresencia(HttpClient client)
    {
        Console.Write("Ingrese el código de la sala: ");
        int codigoSala = int.Parse(Console.ReadLine());

        var body = new { CodigoSala = codigoSala };
        string jsonData = JsonConvert.SerializeObject(body);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        HttpResponseMessage response = await client.PostAsync("/api/notificaciones/presencia", content);

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Notificación de presencia enviada correctamente.");
        else
            Console.WriteLine($"Error al enviar notificación: {response.StatusCode}");
    }

    static async Task NotificarUsuarioValido(HttpClient client)
    {
        Console.Write("Ingrese el NIF del usuario: ");
        string nif = Console.ReadLine();

        var body = new { Nif = nif };
        string jsonData = JsonConvert.SerializeObject(body);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        HttpResponseMessage response = await client.PostAsync("/api/notificaciones/usuario-valido", content);

        if (response.IsSuccessStatusCode)
            Console.WriteLine("Notificación de usuario válido enviada correctamente.");
        else
            Console.WriteLine($"Error al enviar notificación: {response.StatusCode}");
    }


    //SOAP

    private static readonly string serviceUrlC = "http://localhost:8080/EdificioInteligente/services/Empleados"; // URL del servicio

    static async Task MenuEmpleados(HttpClient client)
    {
        bool salir = false;

        while (!salir)
        {
            Console.WriteLine("\n--- Menú Empleados ---");
            Console.WriteLine("1. Registrar Empleado");
            Console.WriteLine("2. Consultar Empleado");
            Console.WriteLine("3. Modificar Empleado");
            Console.WriteLine("4. Eliminar Empleado");
            Console.WriteLine("5. Salir");
            Console.Write("Seleccione una opción: ");
            string opcion = Console.ReadLine();

            switch (opcion)
            {
                case "1":
                    await RegistrarEmpleado(client);
                    break;
                case "2":
                    await ConsultarEmpleado(client);
                    break;
                case "3":
                    await ModificarEmpleado(client);
                    break;
                case "4":
                    await EliminarEmpleado(client);
                    break;
                case "5":
                    salir = true;
                    break;
                default:
                    Console.WriteLine("Opción no válida. Intente de nuevo.");
                    break;
            }
        }
    }

    // Método para llamar al servicio SOAP
    static async Task<string> CallSoapService(HttpClient client, string soapEnvelope, string soapAction, string serviceUrl)
    {
        client.DefaultRequestHeaders.Clear();
        client.DefaultRequestHeaders.Add("SOAPAction", soapAction);  // Acción SOAP

        var content = new StringContent(soapEnvelope, Encoding.UTF8, "text/xml"); // Tipo de contenido SOAP

        try
        {
            // Realizar la solicitud POST al servicio SOAP
            HttpResponseMessage response = await client.PostAsync(serviceUrl, content);

            if (response.IsSuccessStatusCode)
            {
                // Leer la respuesta del servicio
                string responseXml = await response.Content.ReadAsStringAsync();

                // Formatear el XML para hacerlo más legible
                XDocument xDoc = XDocument.Parse(responseXml);
                StringWriter sw = new StringWriter();
                xDoc.Save(sw);  // Utilizando Save para escribir el XML con identación
                return sw.ToString();
            }
            else
            {
                return $"Error: {response.StatusCode} - {response.ReasonPhrase}";
            }
        }
        catch (Exception ex)
        {
            return $"Exception: {ex.Message}";
        }
    }


    // Método para registrar un nuevo empleado
    static async Task RegistrarEmpleado(HttpClient client)
    {
        Console.WriteLine("\n--- Registrar Empleado ---");

        Console.Write("Ingrese el ID del empleado: ");
        int id = int.Parse(Console.ReadLine());

        Console.Write("Ingrese el NIF/NIE: ");
        string nifnie = Console.ReadLine();

        Console.Write("Ingrese el nombre completo: ");
        string nombre = Console.ReadLine();

        Console.Write("Ingrese el email: ");
        string email = Console.ReadLine();

        Console.Write("Ingrese el NAF: ");
        string naf = Console.ReadLine();

        Console.Write("Ingrese el IBAN: ");
        string iban = Console.ReadLine();

        Console.Write("Ingrese el ID del nivel: ");
        int idNivel = int.Parse(Console.ReadLine());

        Console.Write("Ingrese el nombre de usuario: ");
        string usuario = Console.ReadLine();

        Console.Write("Ingrese la contraseña: ");
        string password = Console.ReadLine();

        Console.Write("¿Es válido? (1 para Sí, 0 para No): ");
        int valido = int.Parse(Console.ReadLine());

        // Construir el mensaje SOAP
        string soapEnvelope = $@"
        <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:emp='http://www.example.org/Empleados/'>
            <soapenv:Header/>
            <soapenv:Body>
                <emp:nuevo>
                    <in>
                        <id>{id}</id>
                        <nifnie>{nifnie}</nifnie>
                        <nombreApellidos>{nombre}</nombreApellidos>
                        <email>{email}</email>
                        <naf>{naf}</naf>
                        <iban>{iban}</iban>
                        <idNivel>{idNivel}</idNivel>
                        <usuario>{usuario}</usuario>
                        <password>{password}</password>
                        <valido>{valido}</valido>
                    </in>
                </emp:nuevo>
            </soapenv:Body>
        </soapenv:Envelope>";

        // Llamar al servicio SOAP
        var result = await CallSoapService(client, soapEnvelope, "http://localhost:8080/EdificioInteligente/services/Empleados/nuevo", serviceUrlC);
        Console.WriteLine(result);
    }


    static async Task ConsultarEmpleado(HttpClient client)
    {
        Console.WriteLine("\n--- Consultar Empleado ---");
        Console.Write("Ingrese el NIF/NIE del empleado a consultar: ");
        string nifnie = Console.ReadLine();

        // Construir el mensaje SOAP
        string soapEnvelope = $@"
    <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:emp='http://www.example.org/Empleados/'>
        <soapenv:Header/>
        <soapenv:Body>
            <emp:consultar>
                <in>{nifnie}</in>
            </emp:consultar>
        </soapenv:Body>
    </soapenv:Envelope>";

        try
        {
            // Llamar al servicio SOAP
            var result = await CallSoapService(client, soapEnvelope, "http://localhost:8080/EdificioInteligente/services/Empleados/consultar", serviceUrlC);

            if (result.Contains("Error") || result.Contains("InternalServerError"))
            {
                Console.WriteLine("Hubo un problema al procesar la solicitud en el servidor. Intenta de nuevo más tarde.");
                Console.WriteLine("Detalles del error:");
                Console.WriteLine(result);
            }
            else
            {
                Console.WriteLine("Respuesta del servidor:");
                Console.WriteLine(result);  // Imprimir respuesta SOAP
            }
        }
        catch (Exception ex)
        {
            Console.WriteLine($"Ocurrió un error: {ex.Message}");
        }
    }



    static async Task ModificarEmpleado(HttpClient client)
    {
        Console.WriteLine("\n--- Modificar Empleado ---");

        Console.Write("Ingrese el ID del empleado: ");
        int id = int.Parse(Console.ReadLine());

        Console.Write("Ingrese el NIF/NIE del empleado a modificar: ");
        string nifnie = Console.ReadLine();

        Console.Write("Ingrese el nuevo nombre completo: ");
        string nombre = Console.ReadLine();

        Console.Write("Ingrese el nuevo email: ");
        string email = Console.ReadLine();

        Console.Write("Ingrese el nuevo NAF: ");
        string naf = Console.ReadLine();

        Console.Write("Ingrese el nuevo IBAN: ");
        string iban = Console.ReadLine();

        Console.Write("Ingrese el nuevo ID del nivel: ");
        int idNivel = int.Parse(Console.ReadLine());

        Console.Write("Ingrese el nuevo nombre de usuario: ");
        string usuario = Console.ReadLine();

        Console.Write("Ingrese la nueva contraseña: ");
        string password = Console.ReadLine();

        Console.Write("¿Es válido? (1 para Sí, 0 para No): ");
        int valido = int.Parse(Console.ReadLine());

        // Construir el mensaje SOAP
        string soapEnvelope = $@"
        <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:emp='http://www.example.org/Empleados/'>
            <soapenv:Header/>
            <soapenv:Body>
                <emp:modificar>
                    <in>
                        <id>{id}</id>
                        <nifnie>{nifnie}</nifnie>
                        <nombreApellidos>{nombre}</nombreApellidos>
                        <email>{email}</email>
                        <naf>{naf}</naf>
                        <iban>{iban}</iban>
                        <idNivel>{idNivel}</idNivel>
                        <usuario>{usuario}</usuario>
                        <password>{password}</password>
                        <valido>{valido}</valido>
                    </in>
                </emp:modificar>
            </soapenv:Body>
        </soapenv:Envelope>";

        // Llamar al servicio SOAP
        var result = await CallSoapService(client, soapEnvelope, "http://localhost:8080/EdificioInteligente/services/Empleados/modificar", serviceUrlC);
        Console.WriteLine(result);
    }

    static async Task EliminarEmpleado(HttpClient client)
    {
        Console.WriteLine("\n--- Eliminar Empleado ---");
        Console.Write("Ingrese el NIF/NIE del empleado a eliminar: ");
        string nifnie = Console.ReadLine();

        // Construir el mensaje SOAP
        string soapEnvelope = $@"
    <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:emp='http://www.example.org/Empleados/'>
        <soapenv:Header/>
        <soapenv:Body>
            <emp:borrar>
                <in>{nifnie}</in>
            </emp:borrar>
        </soapenv:Body>
    </soapenv:Envelope>";

        // Llamar al servicio SOAP
        var result = await CallSoapService(client, soapEnvelope, "http://localhost:8080/EdificioInteligente/services/Empleados/borrar", serviceUrlC);
        Console.WriteLine(result);
    }

    private static readonly string serviceUrlCA = "http://localhost:8080/EdificioInteligente/services/ControlAccesos"; // URL del servicio

    static async Task MenuControlAccesos(HttpClient client)
    {
        bool salir = false;

        while (!salir)
        {
            Console.WriteLine("\n--- Menú Control de Accesos ---");
            Console.WriteLine("1. Registrar Acceso");
            Console.WriteLine("2. Consultar Accesos");
            Console.WriteLine("3. Salir");
            Console.Write("Seleccione una opción: ");
            string opcion = Console.ReadLine();

            switch (opcion)
            {
                case "1":
                    await RegistrarAcceso(client);
                    break;
                case "2":
                    await ConsultarAccesos(client);
                    break;
                case "3":
                    salir = true;
                    break;
                default:
                    Console.WriteLine("Opción no válida. Intente de nuevo.");
                    break;
            }
        }
    }

    static async Task RegistrarAcceso(HttpClient client)
    {
        Console.WriteLine("\n--- Registrar Acceso ---");

        Console.Write("Ingrese el NIF del empleado: ");
        string nif = Console.ReadLine();

        Console.Write("Ingrese el código de la sala: ");
        string codigoSala = Console.ReadLine();

        Console.Write("Ingrese el código del dispositivo: ");
        string codigoDispositivo = Console.ReadLine();

        // Construir el mensaje SOAP
        string soapEnvelope = $@"
    <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:con='http://www.example.org/ControlAccesos/'>
        <soapenv:Header/>
        <soapenv:Body>
            <con:registrar>
                <nif>{nif}</nif>
                <codigosala>{codigoSala}</codigosala>
                <codigodispositivo>{codigoDispositivo}</codigodispositivo>
            </con:registrar>
        </soapenv:Body>
    </soapenv:Envelope>";

        // Llamar al servicio SOAP
        var result = await CallSoapService(client, soapEnvelope, "http://localhost:8080/EdificioInteligente/services/ControlAccesos/registrar", serviceUrlCA);
        Console.WriteLine(result);
    }

    // Método para consultar accesos
    static async Task ConsultarAccesos(HttpClient client)
    {
        Console.WriteLine("\n--- Consultar Accesos ---");

        Console.Write("Ingrese el NIF del empleado: ");
        string nif = Console.ReadLine();

        Console.Write("Ingrese el código de la sala: ");
        string codigoSala = Console.ReadLine();

        Console.Write("Ingrese el código del dispositivo: ");
        string codigoDispositivo = Console.ReadLine();

        Console.Write("Ingrese la fecha de inicio (YYYY-MM-DD): ");
        string fechaA = Console.ReadLine();

        Console.Write("Ingrese la fecha de fin (YYYY-MM-DD): ");
        string fechaB = Console.ReadLine();

        // Construir el mensaje SOAP
        string soapEnvelope = $@"
    <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:con='http://www.example.org/ControlAccesos/'>
        <soapenv:Header/>
        <soapenv:Body>
            <con:consultar>
                <nif>{nif}</nif>
                <codigosala>{codigoSala}</codigosala>
                <codigodispositivo>{codigoDispositivo}</codigodispositivo>
                <fechaA>{fechaA}</fechaA>
                <fechaB>{fechaB}</fechaB>
            </con:consultar>
        </soapenv:Body>
    </soapenv:Envelope>";

        // Llamar al servicio SOAP
        var result = await CallSoapService(client, soapEnvelope, "http://localhost:8080/EdificioInteligente/services/ControlAccesos/consultar", serviceUrlCA);
        Console.WriteLine(result);
    }

    private static readonly string serviceUrlCP = "http://localhost:8080/EdificioInteligente/services/ControlPresencia"; // URL del servicio

    static async Task MenuControlPresencia(HttpClient client)
    {
        bool salir = false;

        while (!salir)
        {
            Console.WriteLine("\n--- Menú Control de Presencia ---");
            Console.WriteLine("1. Control Empleados Sala");
            Console.WriteLine("2. Eliminar Registro");
            Console.WriteLine("3. Registrar Presencia");
            Console.WriteLine("4. Salir");
            Console.Write("Seleccione una opción: ");
            string opcion = Console.ReadLine();

            switch (opcion)
            {
                case "1":
                    await ControlEmpleadosSala(client);
                    break;
                case "2":
                    await EliminarRegistro(client);
                    break;
                case "3":
                    await RegistrarPresencia(client);
                    break;
                case "4":
                    salir = true;
                    break;
                default:
                    Console.WriteLine("Opción no válida. Intente de nuevo.");
                    break;
            }
        }
    }

    static async Task ControlEmpleadosSala(HttpClient client)
    {
        Console.WriteLine("\n--- Control Empleados Sala ---");

        Console.Write("Ingrese el código de la sala: ");
        string codigoSala = Console.ReadLine();

        // Construir el mensaje SOAP
        string soapEnvelope = $@"
    <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:con='http://www.example.org/ControlPresencia/'>
        <soapenv:Header/>
        <soapenv:Body>
            <con:controlEmpleadosSala>
                <in>{codigoSala}</in>
            </con:controlEmpleadosSala>
        </soapenv:Body>
    </soapenv:Envelope>";

        // Llamar al servicio SOAP
        var result = await CallSoapService(client, soapEnvelope, "http://localhost:8080/EdificioInteligente/services/ControlPresencia/controlEmpleadosSala", serviceUrlCP);
        Console.WriteLine(result);
    }

    static async Task EliminarRegistro(HttpClient client)
    {
        Console.WriteLine("\n--- Eliminar Registro ---");

        Console.Write("Ingrese el NIF del empleado: ");
        string nif = Console.ReadLine();

        Console.Write("Ingrese el código de la sala: ");
        string codigoSala = Console.ReadLine();

        // Construir el mensaje SOAP
        string soapEnvelope = $@"
    <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:con='http://www.example.org/ControlPresencia/'>
        <soapenv:Header/>
        <soapenv:Body>
            <con:eliminar>
                <nif>{nif}</nif>
                <codigosala>{codigoSala}</codigosala>
            </con:eliminar>
        </soapenv:Body>
    </soapenv:Envelope>";

        // Llamar al servicio SOAP
        var result = await CallSoapService(client, soapEnvelope, "http://localhost:8080/EdificioInteligente/services/ControlPresencia/eliminar", serviceUrlCP);
        Console.WriteLine(result);
    }

    static async Task RegistrarPresencia(HttpClient client)
    {
        Console.WriteLine("\n--- Registrar Presencia ---");

        Console.Write("Ingrese el NIF del empleado: ");
        string nif = Console.ReadLine();

        Console.Write("Ingrese el código de la sala: ");
        string codigoSala = Console.ReadLine();

        // Construir el mensaje SOAP
        string soapEnvelope = $@"
    <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:con='http://www.example.org/ControlPresencia/'>
        <soapenv:Header/>
        <soapenv:Body>
            <con:registrar>
                <nif>{nif}</nif>
                <codigosala>{codigoSala}</codigosala>
            </con:registrar>
        </soapenv:Body>
    </soapenv:Envelope>";

        // Llamar al servicio SOAP
        var result = await CallSoapService(client, soapEnvelope, "http://localhost:8080/EdificioInteligente/services/ControlPresencia/registrar", serviceUrlCP);
        Console.WriteLine(result);
    }

}

