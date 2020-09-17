<?php

echo '<strong>Generador de GIFS animados</strong><br><br>';

echo "Inicio del Proceso: ".date('H:i:s')."<hr>";

//FUNCIONES

    //Función que elimina un directorio y todo su contenido
    function removeDirectory($path)
    {
        $path = rtrim( strval( $path ), '/' ) ;

        $d = dir( $path );

        if( ! $d )
            return false;

        while ( false !== ($current = $d->read()) )
        {
            if( $current === '.' || $current === '..')
                continue;

            $file = $d->path . '/' . $current;

            if( is_dir($file) )
                removeDirectory($file);

            if( is_file($file) )
                unlink($file);
        }

        rmdir( $d->path );
        $d->close();
        return true;
    }

//FIN de FUNCIONES

//PARÁMETROS

    //Anchura en pixels con la que se van a generar los GIFs animados
    $ancho_px = 250;

//FIN de PARÁMETROS


//PROCESO

    //Obtengo el contenido del directorio clonado_ftp, en el que se copian, desde el ftp, las carpetas de todos los artículos para los que se quiera generar su gif animado correspondiente
    $contenido = array_diff(scandir('clonado_ftp/'), array('..', '.'));

    foreach($contenido as $ref) {

        $contenidos_ref = array_diff(scandir('clonado_ftp/'.$ref.'/'), array('..', '.'));

        //Limpio los directorios que he copiado en el directorio clonado_ftp
        foreach($contenidos_ref as $contenido_ref) {
            //elimino las carpetas que no contienen el 360
            if(($contenido_ref != "360") and ($contenido_ref != ".DS_Store")) {
                removeDirectory('clonado_ftp/'.$ref.'/'.$contenido_ref);
            } else {            
                $contenidos_360 = array_diff(scandir('clonado_ftp/'.$ref.'/360/'), array('..', '.'));

                //En la carpeta que contiene el 360, elimino todos los ficheros y directorios que no sean .jpg
                foreach($contenidos_360 as $contenido_360) {
                    
                    if($contenido_360 != ".DS_Store") {
                        if(is_dir('clonado_ftp/'.$ref.'/360/'.$contenido_360)) {
                            removeDirectory('clonado_ftp/'.$ref.'/360/'.$contenido_360);
                        } else if(is_file('clonado_ftp/'.$ref.'/360/'.$contenido_360)) {
                            if (substr($contenido_360, -4) != ".jpg") {
                                unlink('clonado_ftp/'.$ref.'/360/'.$contenido_360);
                            }
                        }
                    }
                    
                }
                
                //Una vez limpio el directorio, obtengo el contenido de la carpeta 360, que debe contener ya, exclusivamente, los fotogramas (jpg) con los que generar el GIF animado
                $contenidos_360_limpio = array_diff(scandir('clonado_ftp/'.$ref.'/360/'), array('..', '.'));
                
                echo "*** ref: <strong>".$ref."</strong> ***<br>";
                
                $fotogramas_empleados = 0;

                foreach($contenidos_360_limpio as $fotograma) {

                    if(substr($fotograma, -4) == ".jpg") {
                        //Creo una instancia de Imagick
                        $image = new Imagick();

                        //incorporo el fotograma a la instancia creada
                        $image->readimage($_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp/'.$ref.'/360/'.$fotograma);

                        //redimensiono la imagen (250px de anchura en principio y altura proporcional)
                        $image->thumbnailImage($ancho_px,0);

                        //transformo la imagen a gif
                        $image->setImageFormat("gif");

                        //escribo un fichero con el fotograma reducido y en formato gif
                        $image->writeImage($_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp/'.$ref.'/'.substr($fotograma, 0, -4).'.gif');
                        
                        $fotogramas_empleados ++;
                    }
                }
                
                if($fotogramas_empleados == 72) {
                    echo "Fotogramas empleados: ".$fotogramas_empleados."<br>";
                } else {
                    echo "<span style='color: red;'>Fotogramas empleados: ".$fotogramas_empleados."</span><br>";
                }
                

                //Una vez generados los 72 fotogramas (o los que hubiera), modifico el fichero bat que luego me va a generar los gifs animados en cada uno de ellos, copiará el gif generado en la carpeta gifs_generados y eliminará todos los ficheros previos de la referencia para la que se ha generado el gif animado.
                $file = fopen("genera_gif.bat", "a");
                fwrite($file, "cd ".$_SERVER['DOCUMENT_ROOT']."/GIF_generator/clonado_ftp/".$ref . PHP_EOL);
                fwrite($file, "magick convert -delay 10 -loop 0 -verbose ".$ref."-*.gif ".$ref.".gif" . PHP_EOL);
                fwrite($file, "copy ".$ref.".gif C:\\xampp\htdocs\GIF_generator\gifs_generados\ " . PHP_EOL);
                fwrite($file, "cd.." . PHP_EOL);
                fwrite($file, "rd /S /Q C:\\xampp\htdocs\GIF_generator\clonado_ftp\\".$ref . PHP_EOL);
                fclose($file);

            }
        }

    }

    echo "<hr>Fin del Proceso: ".date('H:i:s')."<br>";

//FIN del PROCESO

?>