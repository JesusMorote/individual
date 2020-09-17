<?php

//GENERADOR DE CÓDIGOS QR PARA EUROPA
// SE USA $textoqr = "https://qrpromo.eu/?r=".$i;
// Valdría para generar cualquier otros QR, modificando el script

require_once('phpqrcode/phpqrcode.php');
//include('phpqrcode/qrlib.php');


//Arrays con las referencias que corresponden a memorias (con y sin el texto xGB ó xxGB)
$array_memorias = array("3226", "3560", "3654", "3655", "3910", "4065", "4194", "4195", "4196", "4312", "4334", "4483", "4484", "4485", "4486", "4487", "4488", "4489", "4757", "4758", "4759", "4760", "4764", "4765", "4766", "4767", "4768", "5071", "5245", "5346", "5426", "5427", "5428", "5429", "5430", "5431", "5432", "5433", "5434", "5435", "5436", "5462", "5756", "5757", "5758", "5759", "5760", "5761", "5846", "5847", "5848", "5849", "5850", "5851", "6052", "6124", "6125", "6227", "6228", "6229", "6230", "6231", "6232", "6233", "6234", "6235", "6236", "6237", "6238", "6239", "6240", "6241", "6242", "6243", "7172", "7255", "7310", "7359");

$array_memorias_GB = array("3226 4GB", "3560 4GB", "3654 4GB", "3655 4GB", "3910 4GB", "4065 4GB", "4194 4GB", "4195 4GB", "4196 4GB", "4312 4GB", "4334 4GB", "4483 8GB", "4484 8GB", "4485 8GB", "4486 8GB", "4487 8GB", "4488 8GB", "4489 8GB", "4757 8GB", "4758 8GB", "4759 8GB", "4760 8GB", "4764 8GB", "4765 8GB", "4766 8GB", "4767 8GB", "4768 8GB", "5071 16GB", "5245 8GB", "5346 8GB", "5426 8GB", "5427 8GB", "5428 8GB", "5429 8GB", "5430 8GB", "5431 8GB", "5432 8GB", "5433 8GB", "5434 8GB", "5435 8GB", "5436 8GB", "5462 8GB", "5756 8GB", "5757 8GB", "5758 8GB", "5759 8GB", "5760 8GB", "5761 8GB", "5846 16GB", "5847 16GB", "5848 16GB", "5849 16GB", "5850 16GB", "5851 16GB", "6052 32GB", "6124 16GB", "6125 16GB", "6227 16GB", "6228 16GB", "6229 16GB", "6230 16GB", "6231 16GB", "6232 16GB", "6233 16GB", "6234 16GB", "6235 16GB", "6236 16GB", "6237 16GB", "6238 16GB", "6239 16GB", "6240 16GB", "6241 16GB", "6242 16GB", "6243 16GB", "7172 4GB", "7255 8GB", "7310 8GB", "7359 32GB");


//Genera los qr de las ref 2000 a 3000
/*for($i = 2000; $i< 3000; $i++) {
    $textoqr = "https://qrpromo.eu/?r=".$i;
    $nombre_archivo_qr = "qr/".$i;

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12); //Esto genera QR con fondo blanco en PNG y cierto margen, que mediante una acción de Photoshop (qr_png_recortar) se pueden procesar por lotes para dejar el fondo transparente y recortarlos a 300x300px. OJO PUEDE SER NECESARIO AJUSTAR EL PIXEL SIZE (EL PARÁMETRO QUE VA DESPUÉS DEL QR_ECLEVEL_L) EN 8 O 10 EN LUGAR DE 12
}*/

for($i = 2549; $i<= 2551; $i++) {
    $textoqr = "https://qrpromo.eu/?r=".$i;
    $nombre_archivo_qr = "qr/".$i;

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12); //Esto genera QR con fondo blanco en PNG y cierto margen, que mediante una acción de Photoshop (qr_png_recortar) se pueden procesar por lotes para dejar el fondo transparente y recortarlos a 300x300px. OJO PUEDE SER NECESARIO AJUSTAR EL PIXEL SIZE (EL PARÁMETRO QUE VA DESPUÉS DEL QR_ECLEVEL_L) EN 8 O 10 EN LUGAR DE 12
}

//Genera los qr de las ref 3000 a 4000
/*for($i = 3000; $i< 4000; $i++) {
    
    if(in_array($i, $array_memorias)) {
        $indice = array_search($i, $array_memorias);
        $nuevo_i = $array_memorias_GB[$indice];
        
        $textoqr = "https://qrpromo.eu/?r=".$nuevo_i;
        $nombre_archivo_qr = "qr/".$nuevo_i;
    } else {
        $textoqr = "https://qrpromo.eu/?r=".$i;
        $nombre_archivo_qr = "qr/".$i;
    }

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);
}*/

//Genera los qr de las ref 4000 a 5000
/*for($i = 4000; $i< 5000; $i++) {
    
    if(in_array($i, $array_memorias)) {
        $indice = array_search($i, $array_memorias);
        $nuevo_i = $array_memorias_GB[$indice];
        
        $textoqr = "https://qrpromo.eu/?r=".$nuevo_i;
        $nombre_archivo_qr = "qr/".$nuevo_i;
    } else {
        $textoqr = "https://qrpromo.eu/?r=".$i;
        $nombre_archivo_qr = "qr/".$i;
    }

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);
}*/

//Genera los qr de las ref 5000 a 6000
/*for($i = 5000; $i< 6000; $i++) {
    
    if(in_array($i, $array_memorias)) {
        $indice = array_search($i, $array_memorias);
        $nuevo_i = $array_memorias_GB[$indice];
        
        $textoqr = "https://qrpromo.eu/?r=".$nuevo_i;
        $nombre_archivo_qr = "qr/".$nuevo_i;
    } else {
        $textoqr = "https://qrpromo.eu/?r=".$i;
        $nombre_archivo_qr = "qr/".$i;
    }

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);
}*/

for($i = 5878; $i< 6000; $i++) {
    
    if(in_array($i, $array_memorias)) {
        $indice = array_search($i, $array_memorias);
        $nuevo_i = $array_memorias_GB[$indice];
        
        $textoqr = "https://qrpromo.eu/?r=".$nuevo_i;
        $nombre_archivo_qr = "qr/".$nuevo_i;
    } else {
        $textoqr = "https://qrpromo.eu/?r=".$i;
        $nombre_archivo_qr = "qr/".$i;
    }

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);
}

//Genera los qr de las ref 6000 a 7000
/*for($i = 6000; $i< 7000; $i++) {
    
    if(in_array($i, $array_memorias)) {
        $indice = array_search($i, $array_memorias);
        $nuevo_i = $array_memorias_GB[$indice];
        
        $textoqr = "https://qrpromo.eu/?r=".$nuevo_i;
        $nombre_archivo_qr = "qr/".$nuevo_i;
    } else {
        $textoqr = "https://qrpromo.eu/?r=".$i;
        $nombre_archivo_qr = "qr/".$i;
    }

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);
}*/

for($i = 6000; $i<= 6243; $i++) {
    
    if(in_array($i, $array_memorias)) {
        $indice = array_search($i, $array_memorias);
        $nuevo_i = $array_memorias_GB[$indice];
        
        $textoqr = "https://qrpromo.eu/?r=".$nuevo_i;
        $nombre_archivo_qr = "qr/".$nuevo_i;
    } else {
        $textoqr = "https://qrpromo.eu/?r=".$i;
        $nombre_archivo_qr = "qr/".$i;
    }

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);
}

//Genera los qr de las ref 7000 a 8000
/*for($i = 7000; $i< 8000; $i++) {
    
    if(in_array($i, $array_memorias)) {
        $indice = array_search($i, $array_memorias);
        $nuevo_i = $array_memorias_GB[$indice];
        
        $textoqr = "https://qrpromo.eu/?r=".$nuevo_i;
        $nombre_archivo_qr = "qr/".$nuevo_i;
    } else {
        $textoqr = "https://qrpromo.eu/?r=".$i;
        $nombre_archivo_qr = "qr/".$i;
    }

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);
}*/

//Genera los qr de las ref 8000 a 8000
/*for($i = 8000; $i< 9000; $i++) {
    
    if(in_array($i, $array_memorias)) {
        $indice = array_search($i, $array_memorias);
        $nuevo_i = $array_memorias_GB[$indice];
        
        $textoqr = "https://qrpromo.eu/?r=".$nuevo_i;
        $nombre_archivo_qr = "qr/".$nuevo_i;
    } else {
        $textoqr = "https://qrpromo.eu/?r=".$i;
        $nombre_archivo_qr = "qr/".$i;
    }

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);
}*/

//Genera los qr de las ref 9000 a 10000
/*for($i = 9000; $i< 10000; $i++) {
    
    if(in_array($i, $array_memorias)) {
        $indice = array_search($i, $array_memorias);
        $nuevo_i = $array_memorias_GB[$indice];
        
        $textoqr = "https://qrpromo.eu/?r=".$nuevo_i;
        $nombre_archivo_qr = "qr/".$nuevo_i;
    } else {
        $textoqr = "https://qrpromo.eu/?r=".$i;
        $nombre_archivo_qr = "qr/".$i;
    }

    QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);
}*/

//Genero manualmente el qr de la ref. 2008C
/*$textoqr = "https://qrpromo.eu/?r=2008C";
$nombre_archivo_qr = "qr/2008C";
QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);*/

//Genero manualmente el qr de la ref. 811401
/*$textoqr = "https://qrpromo.eu/?r=811401";
$nombre_archivo_qr = "qr/811401";
QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);*/

//Genero manualmente el qr de la ref. 7359 32GB
$textoqr = "https://qrpromo.eu/?r=7359 32GB";
$nombre_archivo_qr = "qr/7359 32GB";
QRcode::png($textoqr, $nombre_archivo_qr.'.png', QR_ECLEVEL_L, 12);


//mensaje para identificar el fin del proceso
echo "Código/s QR generado/s con éxito";

?>