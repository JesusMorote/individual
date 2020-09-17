<?php

include_once('curl_functions.inc.php');

//Incluyo la Librería PHP Simple HTML DOM Parser
include_once('simple_html_dom.php');

//******************************************************//

function muestra_web_cifra($ref_cifra) {
    //En primer lugar "simulamos" el login en la home (https://www.cifra.es/index.php?route=common/home)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(https://www.cifra.es/index.php?route=account/login), por lo que es en esa URL en la que simulamos el login
    login("https://www.cifra.es/index.php?route=account/login", "email=jbp@jblascopublicidad.com&password=JavierJimena8066");
    
    //Obtengo la referencia del artículo que he recibido como parámetro
    $ref_articulo = $ref_cifra;
    
    //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
    //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
    $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);
    
    //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
    $html_scraped = grab_page("https://www.cifra.es/index.php?route=product/advanced_search&keyword=".$ref_articulo_saneada);

    //Muestro el HTML scrapeado
    echo $html_scraped;
}

function muestra_web_giving($ref_giving) {
    //En primer lugar "simulamos" el login en la home (https://www.impression-catalogue.com/es)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(https://www.impression-catalogue.com/es/bienvenido-su-buscador-de-regalos?destination=node/19668), por lo que es en esa URL en la que simulamos el login
    login("https://www.impression-catalogue.com/es/bienvenido-su-buscador-de-regalos?destination=node/19668", "name=javi@jblasco.es&pass=JavierJimena8066&remember_me=1&form_build_id=form-pdEhS-k8MHvgxdkKcaIUt0KQbrbHhwIA0THAxoDV-Tk&form_id=user_login_block&op=Inicio+de+sesión");
    
    //Obtengo la referencia del artículo que he recibido como parámetro
    $ref_articulo = $ref_giving;
    
    //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
    //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
    $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

    //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
    //En primer lugar, he de obtener la URL que tiene la referencia de búsqueda del artículo que se obtiene del buscador
    //(p.ej. https://www.impression-catalogue.com/es/article/7681, para el artículo cuya referencia real es 8958)
    $url_busqueda = "https://www.impression-catalogue.com/es/search?keywords=".$ref_articulo_saneada."&op=Buscar";
    $ch = curl_init($url_busqueda);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE); // We'll parse redirect url from header.
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); // We want to just get redirect url but not to follow it.
    $response = curl_exec($ch);
    preg_match_all('/^Location:(.*)$/mi', $response, $matches);
    curl_close($ch);
    //echo !empty($matches[1]) ? trim($matches[1][0]) : 'No redirect found';
    $url_giving = trim($matches[1][0]);
    
    //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
    $html_scraped = grab_page($url_giving);

    //Muestro el HTML scrapeado
    echo $html_scraped;
}

function muestra_web_mob($ref_mob) {
    //En primer lugar "simulamos" el login en la página de login (https://www.midoceanbrands.com/Iberia/es/eur/login)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(https://www.midoceanbrands.com/Iberia/es/eur/processLogin), por lo que es en esa URL en la que simulamos el login
    login("https://www.midoceanbrands.com/Iberia/es/eur/processLogin", "SynchronizerToken=d4cb8e5bbfa56afd46dc59a41bdc0f…c00d51bcbb216de6893877b6ff9ce&ShopLoginForm_Login=javi@jblasco.es&ShopLoginForm_Password=JavierJimena8066&rememberMe=on");
    
    //Obtengo la referencia del artículo que he recibido como parámetro
    $ref_articulo = $ref_mob;
    
    //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
    //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
    $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

    //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
    $html_scraped = grab_page("https://www.midoceanbrands.com/Iberia/es/eur/QuickSearch?SearchTerm=".$ref_articulo_saneada);
    
    //Muestro el HTML scrapeado
    //echo $html_scraped;
    //OJO, en este caso, el resultado mostrado es muy deficiente, puesto que la mayor parte del contenido de la página se obtiene asíncronamente mediante AJAX (entre otros datos el stock) y no queda registrado en $html_scraped, por eso, de momento muestro sólo el contenido de la página que se obtiene por AJAX y que muestra sólo los datos de stock    
    //Recupero el valor de la URL que devuelve por AJAX los datos de stock
    $html = str_get_html($html_scraped);
    
    $url_stock = $html->find('#productImagesAndStockURL', 0)->value; 
    
    //Obtengo el código HTML del resultado de la consulta de stock por AJAX del artículo
    $html_stock_scraped = grab_page($url_stock);
    
    //Antes de mostrar el HTML finalmente scrapeado, inserto la URL base del dominio en el atributo src de las imágenes, pues éste viene con rutas relativas en el html scrapeado
    $html2 = str_get_html($html_stock_scraped);
    
    $imagenes = $html2->find('img');
    
    foreach($imagenes as $imagen) {
        $src_imagen = $imagen->src;
        
        $imagen->src = "https://www.midoceanbrands.com".$src_imagen;
    }
    
    $html_stock_scraped_mod = $html2->outertext;
    
    //Muestro el HTML scrapeado
    echo $html_stock_scraped_mod; 
}

function muestra_web_ps($ref_ps) {
    //En primer lugar "simulamos" el login en la página de login (https://www.stricker-europe.com/es/)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(https://www.stricker-europe.com/es/zona-reservada/login/), por lo que es en esa URL en la que simulamos el login
    login("https://www.stricker-europe.com/es/zona-reservada/login/", "usr=javierbp&pwd=JavierJimena8066&subm=true&urlReturn=/es/");
    
    //Obtengo la referencia del artículo que he recibido como parámetro
    $ref_articulo = $ref_ps;
    
    //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
    //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
    $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

    //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
    $html_scraped = grab_page("https://www.stricker-europe.com/es/busqueda/?q=".$ref_articulo_saneada);
    
    //Antes de mostrar el contenido del HTML scrapeado, añado https://www.stricker-europe.com al comienzo de cada URL que se encuentra en un href o un src, puesto que si no, los enlaces o las imágenes apuntan a localhost y no a la web de PS
    $html = str_get_html($html_scraped);
    
    $elementos_con_href = $html->find('*[href]'); 
    
    foreach($elementos_con_href as $elemento_con_href) {
        $href_elemento = $elemento_con_href->href;
        
        $elemento_con_href->href = "https://www.stricker-europe.com".$href_elemento;
    }
    
    $elementos_con_src = $html->find('*[src]'); 
    
    foreach($elementos_con_src as $elemento_con_src) {
        $src_elemento = $elemento_con_src->src;
        
        $elemento_con_src->src = "https://www.stricker-europe.com".$src_elemento;
    }
    
    $html_scraped_mod = $html->outertext;
    
    //Muestro el HTML scrapeado
    echo $html_scraped_mod;
}

function muestra_web_pf($ref_pf) {
    //En primer lugar "simulamos" el login en la página de login (http://www.pfconcept.com/cgi-bin/wspd_pcdb_cgi.sh/y/y2ygeneralworld.p?world=general)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(http://www.pfconcept.com/cgi-bin/wspd_pcdb_cgi.sh/y/y2login-ajax.p), por lo que es en esa URL en la que simulamos el login
    login("http://www.pfconcept.com/cgi-bin/wspd_pcdb_cgi.sh/y/y2login-ajax.p", "haccount=1419786&huser=JAVIERBL&landing=no&frmdeeplink=&url=&account_number=1419786&user_name=JAVIERBL&user_password=JavierJimena8066&checker=");
    
    //Obtengo la referencia del artículo que he recibido como parámetro
    $ref_articulo = $ref_pf;
    
    //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
    //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
    $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

    //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
    $html_scraped = grab_page("http://www.pfconcept.com/cgi-bin/wspd_pcdb_cgi.sh/y/y2facetmain.p?fctkeywords=".$ref_articulo_saneada);
    
    //Antes de mostrar el contenido del HTML scrapeado, añado http://www.pfconcept.com al comienzo de La URL que se encuentra en el href del link a la hoja de estilos de la página
    $html = str_get_html($html_scraped); 
    
    $link_css = $html->find('link[rel="stylesheet"]', 1);
    
    $href_elemento = $link_css->href;
    
    $link_css->href = "http://www.pfconcept.com".$href_elemento;
    
    $html_scraped_mod = $html->outertext;
    
    //Muestro el HTML scrapeado
    echo $html_scraped_mod;
}

function muestra_web_ggoya($ref_ggoya) {
    //En primer lugar "simulamos" el login en la página de login https://www.ggoya.com/customer/account/login/)
    //OJO tras introducir el email y la contraseña en el formulario de acceso se pasan por POST y se comprueban en
    //(https://www.ggoya.com/customer/account/loginPost/referer/aHR0cHM6Ly93d3cuZ2dveWEuY29tL2N1c3RvbWVyL2FjY291bnQvbG9nb3V0Lw,,/), por lo que es en esa URL en la que simulamos el login
    login("https://www.ggoya.com/customer/account/loginPost/referer/aHR0cHM6Ly93d3cuZ2dveWEuY29tL2N1c3RvbWVyL2FjY291bnQvbG9nb3V0Lw,,/", "form_key=hZgCPRUsz5P3w0br&login[username]=B54909403&login[password]=011104&send=");
    
    //Obtengo la referencia del artículo que he recibido como parámetro
    $ref_articulo = $ref_ggoya;
    
    //Para que funcione correctamente, en la URL que genero para consultar el stock de cada artículo,
    //en las referencias de los artículos hay que remplazar los espacios (si los hay) por %20
    $ref_articulo_saneada = str_replace(" ", "%20", $ref_articulo);

    //Obtengo el código HTML del resultado de la consulta del artículo con esa referencia
    $html_scraped = grab_page("https://www.ggoya.com/catalogsearch/result/?q=".$ref_articulo_saneada);
    echo $html_scraped;
}

//******************************************************//


if(isset($_GET['ref'])) {
    $ref = $_GET['ref'];
    $competidor = $_GET['comp'];
    
    switch($competidor) {
        case "cifra":
            muestra_web_cifra($ref);
            break;
        case "giving":
            muestra_web_giving($ref);
            break;
        case "mob":
            muestra_web_mob($ref);
            break;
        case "ps":
            muestra_web_ps($ref);
            break;
        case "pf":
            muestra_web_pf($ref);
            break;
        case "ggoya":
            muestra_web_ggoya($ref);
            break;
            
    }
    
}

?>