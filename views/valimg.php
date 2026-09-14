
<?php

    function valimg($imgmod, $tmaxmb = 1, $ext = ['jpg', 'jpeg', 'png', 'svg']) {
        if ($imgmod['error'] !== UPLOAD_ERR_OK) {
            echo "Error al subir el archivo.";
            return "sin imagen";
        }else{
            // Obtener la extensión del archivo
            $imgnom = $imgmod['name'];
            $imgext = strtolower(pathinfo($imgnom, PATHINFO_EXTENSION));

            // Verificar la extensión
            if (!in_array($imgext, $ext)) {
                echo "La extensión {$imgext} no está permitida. Las extensiones permitidas son: " . implode(', ', $ext);
                return "sin imagen";

            }else{
                // Verificar el tamaño del archivo
                $tmaxbts = $tmaxmb * 1024 * 1024; // Convertir MB a bytes

                if ($imgmod['size'] > $tmaxbts) {
                    echo "El archivo es demasiado grande. El tamaño máximo permitido es {$tmaxmb}MB.";
                    return "sin imagen";

                }else{
                    //Se crea token de seguridad para evitar problemas en archivos con mismo nombre
                    $token = substr(uniqid(rand(), true), 0, 4);
                    // Mover el archivo a una carpeta específica
                    $rutaimg = 'img/'.$token.$imgnom; // Ajusta la ruta según tu estructura
                    if (!move_uploaded_file($imgmod['tmp_name'], $rutaimg)) {
                        echo "Error al mover el archivo a la carpeta de destino.";
                        return "sin imagen";
                    }else{
                        return $rutaimg;
                    }
                }
            }
        }
    } 
?>

<script>
    function solonum(e) {
	key=e.keyCode || e.which;
	teclado=String.fromCharCode(key);
	numeros="0123456789";
	var especiales=["8","45"];
	teclado_especial=false;
	for(var i in especiales) {
		if(key==especiales[i]) {
			teclado_especial=true;
		}
	}
	if(numeros.indexOf(teclado)==-1 && !teclado_especial) {
		return false;
	}
}
</script>
