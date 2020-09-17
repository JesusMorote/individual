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
    $ancho2_px = 500;
    $ancho3_px = 1080;

//FIN de PARÁMETROS


//PROCESO

    //Obtengo el contenido del directorio clonado_ftp_b, en el que se copian, desde el ftp, las carpetas de todos los artículos para los que se quiera generar su gif animado correspondiente
    //  OJO Si la ref. copiada al directorio clonado ftp es una USB, antes de correr este script hay que eliminar los textos xxGB del nombre de las carpetas y de todos los ficheros que lo contengan y luego ya se puede correr el script. Más adelante, cuando se ejecute el bat y se generen los gif a las 3 resoluciones habrá que renombrar de nuevo éstos añadiendoles el texto xxGB que le corresponda.

    $contenido = array_diff(scandir('clonado_ftp_b/'), array('..', '.'));

    foreach($contenido as $ref) {

        $contenidos_ref = array_diff(scandir('clonado_ftp_b/'.$ref.'/'), array('..', '.'));

        //Limpio los directorios que he copiado en el directorio clonado_ftp_b
        foreach($contenidos_ref as $contenido_ref) {
            //elimino las carpetas que no contienen el 360
            if(($contenido_ref != "360") and ($contenido_ref != ".DS_Store") and ($contenido_ref != "Thumbs.db")) {
                removeDirectory('clonado_ftp_b/'.$ref.'/'.$contenido_ref);
            } else {            
                $contenidos_360 = array_diff(scandir('clonado_ftp_b/'.$ref.'/360/'), array('..', '.'));

                //En la carpeta que contiene el 360, elimino todos los ficheros y directorios que no sean .jpg
                foreach($contenidos_360 as $contenido_360) {
                    
                    if($contenido_360 != ".DS_Store" or $contenido_360 != "Thumbs.db") {
                        if(is_dir('clonado_ftp_b/'.$ref.'/360/'.$contenido_360)) {
                            removeDirectory('clonado_ftp_b/'.$ref.'/360/'.$contenido_360);
                        } else if(is_file('clonado_ftp_b/'.$ref.'/360/'.$contenido_360)) {
                            if (substr($contenido_360, -4) != ".jpg") {
                                unlink('clonado_ftp_b/'.$ref.'/360/'.$contenido_360);
                            }
                        }
                    }
                    
                }
                
                //Una vez limpio el directorio, obtengo el contenido de la carpeta 360, que debe contener ya, exclusivamente, los fotogramas (jpg) con los que generar el GIF animado                
                if($contenido_ref == "360") {
                    $contenidos_360_limpio = array_diff(scandir('clonado_ftp_b/'.$ref.'/360/'), array('..', '.'));
                
                    echo "*** ref: <strong>".$ref."</strong> ***<br>";

                    $fotogramas_empleados = 0;

                    foreach($contenidos_360_limpio as $fotograma) {

                        if(substr($fotograma, -4) == ".jpg") {
                            //Creo una instancia de Imagick
                            $image = new Imagick();
                            $image2 = new Imagick();
                            $image3 = new Imagick();

                            //incorporo el fotograma a la instancia creada
                            $image->readimage($_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp_b/'.$ref.'/360/'.$fotograma);
                            $image2->readimage($_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp_b/'.$ref.'/360/'.$fotograma);
                            $image3->readimage($_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp_b/'.$ref.'/360/'.$fotograma);

                            //redimensiono la imagen (250px de anchura en principio y altura proporcional)
                            $image->thumbnailImage($ancho_px,0);
                            $image2->thumbnailImage($ancho2_px,0);
                            $image3->thumbnailImage($ancho3_px,0);

                            //transformo la imagen a gif
                            $image->setImageFormat("gif");
                            $image2->setImageFormat("gif");
                            $image3->setImageFormat("gif");

                            //escribo un fichero con el fotograma reducido y en formato gif
                            $image->writeImage($_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp_b/'.$ref.'/'.substr($fotograma, 0, -4).'.gif');
                            $carpeta = $_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp_b/'.$ref.'/2';
                            if (!file_exists($carpeta)) {
                                mkdir($carpeta, 0777, true);
                            }
                            $image2->writeImage($_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp_b/'.$ref.'/2/'.substr($fotograma, 0, -4).'.gif');
                            $carpeta2 = $_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp_b/'.$ref.'/3';
                            if (!file_exists($carpeta2)) {
                                mkdir($carpeta2, 0777, true);
                            }
                            $image3->writeImage($_SERVER['DOCUMENT_ROOT'].'/GIF_generator/clonado_ftp_b/'.$ref.'/3/'.substr($fotograma, 0, -4).'.gif');

                            $fotogramas_empleados ++;
                        }
                    }

                    if($fotogramas_empleados == 72) {
                        echo "Fotogramas empleados: ".$fotogramas_empleados."<br>";
                    } else {
                        echo "<span style='color: red;'>Fotogramas empleados: ".$fotogramas_empleados."</span><br>";
                    }

                    $ref_num = $ref;

                    if((substr($ref, -2)== "GB")) {
                        $ref_num = substr($ref, 0, 4);
                    }


                    //Una vez generados los 72 fotogramas (o los que hubiera), modifico el fichero bat que luego me va a generar los gifs animados en cada uno de ellos, copiará el gif generado en la carpeta gifs_generados y eliminará todos los ficheros previos de la referencia para la que se ha generado el gif animado.
                    $file = fopen("genera_gif_250_500_1080_b.bat", "a");
                    fwrite($file, "cd ".$_SERVER['DOCUMENT_ROOT']."/GIF_generator/clonado_ftp_b/".$ref . PHP_EOL);
                    fwrite($file, "magick convert -delay 10 -loop 0 -verbose ".$ref_num."-*.gif ".$ref_num.".gif" . PHP_EOL);
                    fwrite($file, "copy ".$ref_num.".gif C:\\xampp\htdocs\GIF_generator\gifs_generados\\250_visiblepresupuestador\ " . PHP_EOL);
                    fwrite($file, "cd 2" . PHP_EOL);
                    fwrite($file, "magick convert -delay 10 -loop 0 -verbose ".$ref_num."-*.gif ".$ref_num.".gif" . PHP_EOL);
                    fwrite($file, "copy ".$ref_num.".gif C:\\xampp\htdocs\GIF_generator\gifs_generados\\500_visiblepresupuestador\ " . PHP_EOL);
                    fwrite($file, "cd.." . PHP_EOL);
                    fwrite($file, "cd 3" . PHP_EOL);
                    fwrite($file, "magick convert -delay 10 -loop 0 -verbose ".$ref_num."-*.gif ".$ref_num.".gif" . PHP_EOL);
                    fwrite($file, "copy ".$ref_num.".gif C:\\xampp\htdocs\GIF_generator\gifs_generados\\1080_visiblepresupuestador\ " . PHP_EOL);
                    fwrite($file, "cd.." . PHP_EOL);
                    fwrite($file, "cd.." . PHP_EOL);
                    fwrite($file, "rd /S /Q C:\\xampp\htdocs\GIF_generator\clonado_ftp_b\\".$ref . PHP_EOL);
                    fclose($file);
                }
            }
        }

    }

    echo "<hr>Fin del Proceso: ".date('H:i:s')."<br>";

//FIN del PROCESO

?>