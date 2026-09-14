<?php

// 1. URL del WSDL (Web Services Description Language)
$wsdlUrl = 'http://45.163.28.118:18092/Despachos/Servicios/Despacho.asmx';
// El nombre del método que quieres invocar
$metodo = 'Mensaje';

// 2. Prepara los datos que necesita el método
// *** AVISO: Debes reemplazar 'param1' y 'valor1' con los nombres de los parámetros
// y los valores reales que espera el método CargarDespacho() del servicio web. ***
$parametros = [
    'Codigo' => '1'
];

try {
    // 3. Crear una instancia del cliente SOAP
    // El primer argumento es la URL del WSDL
    $client = new SoapClient($wsdlUrl, [
        'trace'      => 1,     // Habilitar el rastreo para ver las peticiones/respuestas (útil para depuración)
        'exceptions' => 1,     // Lanzar excepciones en caso de error
        'cache_wsdl' => WSDL_CACHE_NONE // Desactivar caché durante el desarrollo
    ]);

    // 4. Invocar el método del servicio web
    // El nombre del método es el primer argumento, y el array de parámetros es el segundo
    $respuesta = $client->__soapCall($metodo, [$parametros]);

    // 5. Mostrar el resultado
    echo "<h2>✅ Conexión Exitosa y Resultado de $metodo:</h2>";
    // El resultado suele ser un objeto o un array
    print_r($respuesta);

    // Si quieres ver la estructura exacta del XML que se envió y se recibió (útil para depuración)
    // ---
    echo "<h3>XML de la Petición (Request):</h3>";
    echo "<pre>" . htmlentities($client->__getLastRequest()) . "</pre>";
    echo "<h3>XML de la Respuesta (Response):</h3>";
    echo "<pre>" . htmlentities($client->__getLastResponse()) . "</pre>";
    // ---

} catch (SoapFault $e) {
    // 6. Manejo de Errores SOAP
    echo "<h2>❌ Error de SOAP:</h2>";
    echo "El servicio web devolvió un error: " . $e->getMessage();

    // Puedes obtener detalles adicionales del error si el trace está activado
    if (isset($client)) {
        echo "<h3>Detalle del Request:</h3>";
        echo "<pre>" . htmlentities($client->__getLastRequest()) . "</pre>";
    }

} catch (Exception $e) {
    // 7. Manejo de Otros Errores
    echo "<h2>❌ Error General:</h2>";
    echo "Ocurrió un error inesperado: " . $e->getMessage();
}

?>