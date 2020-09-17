
<?php
if(!(isset($_SESSION['idioma']))){

	//header('Location: ../index.php');

}else{


//###############################################################################################################################
//####### ESPAÑOL ###############################################################################################################
//###############################################################################################################################

	if($_SESSION['idioma']=='1'){

		## loging.php ##
		$presupuestadorIndex='Cotizador de Impresión';
		$usuarioLoggin="Usuario";
		$passLoggin="Contraseña";
		$btnEntrar="Entrar";
		$desde="Desde:";
		$iniciarSesion="Iniciar Sesión";


		## cabecera.php ##
		$salir='Salir';
		$presupuestosGuardados='Cotizaciones Guardadas';

		## prspstdr1.php
		$presupuestador='Cotizador';
		$seleccioneArticulo='Seleccione Artículo';
		$btnBuscar=' Buscar Ref / Nombre';
		$paso1='PASO 1';
		$selecionesArticuloAPresupuestar='Seleccione artículo a cotizar';
		$upss='No se han encontrado artículos que coincidan con';
		$cambiaCriterio='Realice nueva búsqueda.';
		$buscar="Buscar";
		$nuevaBusqueda="Nueva busqueda";

		## prspstdr2.php
		$seleccioneCantidad='Seleccione Cantidad';
		$btnCantidad='Introducir Cantidad';
		$paso2='PASO 2';
		$cambiarArticulo='Cambiar el Articulo';
		$cambiarCantidad='Cambiar Cantidad';
		$cambiarTrabajo='Cambiar Trabajo';
		$cantidadCotizar='Introduzca la cantidad deseada para cotizar.';
		$composicion='Composición';
		$infoComercial='Información Comercial';
		$catalogos='Catálogos';
		$ver='Ver';
		$infoPacking='Información de Packing';
			$articulo='Artículo';
			$packingFinal='Packing Final / Unitario';
			$pintermedio1='P. Intermedio';
			$pintermedio2='P. Intermedio 2';
			$cajaMaster='Caja Master';
			$palet='Palet';

			$diametro='Diámetro';
			$alto='Alto';
			$ancho='Ancho';
			$largo='Largo';
			$peso='Peso';
			$tipoPacking='Tipo';
			$unidadesAprox='Uds. Aprox';
			$bultos='Bultos';

		$fichaTecnica='Ficha Técnica';
		$preciosConfidenciales='Tarifa Precios Confidencial';
		$disponibilidad='Disponibilidad';
			$color='Color';
			$talla='Talla';
			$stock='Stock';
			$dispon='Disponible';
			$inmediata='Inmediata';

		## prspstdr4.php
		$seleccioneTrabajo='Selección Trabajo de Impresión';
		$paso3='PASO 3';
		$texto1paso3='Seleccione el trabajo que desea realizar para este artículo.';
		$texto2paso3='Tenga en cuenta que algunos colores pueden necesitar doble pasada.';
		$numColores='Nº de Colores a imprimir por posición:';
		$seleccionar='Seleccionar';
		$seleccionePuntadas='Seleccionar';
		$indiquePuntadas='Indique el Número de Puntadas';
		$areasyMedidas="Áreas y Medidas Recomendadas";
		$indiquecm2="Indique cm2 del logo:";
		$doblepasada="Para un marcaje optimo, los colores oscuros requieren una doble pasada. Seleccione esta técnica si desea imprimir sobre colores oscuros.";
        $atodocolor = "A todo Color";

		## prspstdr6.php
		$servicioPE='Servicio Printing Express';
		$paso4='PASO 4';
		$texto1paso4='Podemos enviar su pedido de impresión en 72h contratando este servicio.';
		$texto2paso4='¿Desea añaidrlo al presupuesto?';
		$contratarServicio='Contratar Servicio Printing Express';
		$noContratarServicio='No Contratar Servicio';

		## prspstdr8.php
		$presupuestoFinal='Presupuesto Final';
		$texto1PasoFinal='Impuestos no incluidos.';
		$texto2PasoFinal='Portes pagados en Península a partir de 300€ de base imponible (sin impresión).';
		$texto3PasoFinal='Para envíos internacionales consulte precio.';
		$texto4PasoFinal='Validez del presupuesto 15 días salvo fluctuaciones excepcionales en el tipo de cambio o coste de las materias primas.';
		$texto5PasoFinal='Si desea una cotización no contemplada en este presupuestador, por favor contacte con nuestro departamento comercial.';
		$texto6PasoFinal='Esta cotización es a titulo informativo.';
		$texto7PasoFinal='En caso de duda o confirmación de presupuesto contacte con nuestro Departamento Comercial.';
		$texto8PasoFinal='Tlf. +34 950 464 791';
		$texto9PasoFinal='<b>Nota</b>: Para impresión del mismo logo en varias posiciones, se debe multiplicar el coste unitario de impresión por el número de posiciones. El Cliché solo se cobrará 1 vez.<br><br>';
		$texto10PasoFinal='<b>Manipulación y Envasado incluido en el precio de impresión.</b><br>Consultar descuento en caso de no requerir reenvasado final del producto en su polybag o estuchado de origen.';
		$texto1PasoFinalPanama='Precios EXW con Impresión desde España';
		$texto2PasoFinalPanama='Tlf. +507 449-6288';
		$texto3PasoFinalPanama='e-mail: info@makito.com.pa';
		$texto4PasoFinalPanama='En pedidos con impresión podremos  suministrar +/- 5% de la cantidad solicitada';
		$texto5PasoFinalPanama='Tiempo de producción 7-10 días desde la confirmación del diseño';
		$textopicaje='Picaje';

		$imprimirPresupuesto='Imprimir Cotización';
		$guardarPresupuesto='Guardar Cotización';
		$hacerpedido="Hacer pedido a Makito";

		## total.php
		$valorDeLaCotizacion='Valor de la Cotización';
		$ref='Ref';
		$uds='Uds';
		$ud='Unidad';
		$sub='Sub';  ### De la palabra Subtotal
		$trabajoMinimo='Trabajo Mínimo';
		$primerColor='Primer Color';
		$colorExtra='Color/es Extra';
		$cliche='Cliché';
		$clichepanama='Arte';
		$color='Color';
		$total='Total';
		$impuestosNoIncluidos='Impuestos No Incluidos';
		$fecha='Fecha';
		$doscoloresincluidos='Dos Colores Incluidos';
		$puestaenmaquina = 'Puesta en Máquina';
		$gastofijo = 'Gasto Fijo';
		$precioporunidad = 'Precio por unidad';
		$gastofijopedido = 'Gasto fijo x Pedido';
		$programa = 'Programa';
		$trabajomininmosombreros = 'Cargo por pedidos de -250uds';
		$canon="Canon Digital No Incluido: 0.24€/pc";


		## preguardados.php
		$nombreguardado='Nombre';
		$trabajo='Trabajo';
		$introduzcaNombre='Introduzca un nombre para la cotización:';
		$irInicio="Ir al Inicio";
		$guardar='Guardar';
		$cancelar="Cancelar";
		$enviarAMakito="Enviar Cotización a Makito";
		$enviarAMakicoComercial="Esta cotización será enviada a Makito. Un comercial se pondrá en contacto en breve con usted.";
		$personaContacto="Introduzca persona de contacto *";
		$tlfContacto="Indicar tlf de contacto";
		$otroemail="Si desea recibir una copia indique correo electrónico";
		$enviarMakito="Enviar a Makito";
		$enviarCotizacion="Enviar Cotización";
		$deseoRecibir="Deseo recibir esta cotización en mi email.";
		$indiqueEmail="Indique email donde desea recibir la cotización";
		$noesposible="No es posible hacer una cotización en línea de este artículo.";
		$contactecomercial="Por favor, contacte con nuestro Dto. Comercial.<br>Tlf: +34 950 464 791";


		## qr.php

		$ver360="Show 360 Animation";
		$verareas="Show Print Areas";
		$enviarMail="Send to Email";
		$emailDestino="Email Destino";
		$comentarios="Comentarios:";
		$enviar="Enviar";
		$colmaxqr="Max. Colores a Imprimir: ";
		$compartirqr="Enviar";
		$inventariopanama="Inventario Disponible Zona Libre Colon - PANAMÁ";
		$inventarioespana="STOCK DISPONIBLE ESPAÑA";
		$mercanciadisponible="La mercancía estará disponible en ZONA LIBRE DE COLON - PANAMÁ en 25 días aprox. después de la confirmación de su pedido.";
		$remite="Remitente";
		$sidesea="Si desea ver más información del producto como stock, características, packing, áreas de impresión, etc haga click";
		$aqui="aquí";
		$noimprima="No me imprimas si no es necesario. Protejamos el medio ambiente ";
		$mensajecorrecto="El mensaje se envió correctamente";




	}


//###############################################################################################################################
//####### INGLÉS ################################################################################################################
//###############################################################################################################################
	
	if($_SESSION['idioma']=='2'){

		## loging.php ##
		$presupuestadorIndex='Print Calculator';
		$usuarioLoggin="User";
		$passLoggin="Password";
		$btnEntrar="Enter";
		$desde="From: ";
		$iniciarSesion="Login Your Account";



		## cabecera.php ##
		$salir='Logout';
		$presupuestosGuardados='Saved Quotations ';


		## prspstdr1.php
		$presupuestador='Quotation';
		$seleccioneArticulo='Select Item';
		$btnBuscar=' Search Ref / Name';
		$paso1='STEP 1';
		$selecionesArticuloAPresupuestar='Select item to quote';
		$upss='No articles found matching';
		$cambiaCriterio='Try again.';
		$buscar="Search";
		$nuevaBusqueda="New Search";

		## prspstdr2.php
		$seleccioneCantidad='Select quantity';
		$btnCantidad='Enter quantity';
		$paso2='STEP 2';
		$cambiarArticulo='Change Item';
		$cambiarCantidad='Change quantity';
		$cambiarTrabajo='Change Printing Job';
		$cantidadCotizar='Enter the quantity you want to Quote.';
		$min='Mínimo 1 y Máximo 10000';
		$composicion='Composition';
		$infoComercial='Commercial Information';
		$catalogos='Catalogues';
		$ver='Show';
		$infoPacking='Packing Information';
			$articulo='Item';
			$packingFinal='Final packing / Unitary';
			$pintermedio1='Intermediate Packing 1';
			$pintermedio2='Intermediate Packing 2';
			$cajaMaster='Master Box';
			$palet='Pallet';

			$diametro='Diameter';
			$alto='Heigth';
			$ancho='Width';
			$largo='Length';
			$peso='Weight';
			$tipoPacking='Type';
			$unidadesAprox='Aprox Pieces';
			$bultos='Boxes';

		$fichaTecnica='Data Sheet';
		$preciosConfidenciales='Confidential Prices List';
		$disponibilidad='Availability';
			$color='Colour';
			$talla='Size';
			$stock='Stock';
			$dispon='Availability';
			$inmediata='Immediate';

		## prspstdr4.php
		$seleccioneTrabajo='Select Printing Job';
		$paso3='STEP 3';
		$texto1paso3='Select the job you would like to do on this item.';
		$texto2paso3='Tenga en cuenta que algunos colores pueden necesitar doble pasada.';
		$numColores='Nº of colors to print per position:';
		$seleccionar='Select';
		$seleccionePuntadas='Select';
		$indiquePuntadas='Indicate the number of Stitches';
		$areasyMedidas="Print areas and dimensions recomended";
		$indiquecm2="Indicate logo size in cm2:";
		$doblepasada="Optimum marking on dark colors requires a double pass. Select this technique if you want to print on dark colors.";
        $atodocolor = "Full color";

		## prspstdr6.php
		$servicioPE='Printing Express Service';
		$paso4='STEP 4';
		$texto1paso4='Podemos enviar su pedido de impresión en 72h contratando este servicio.';
		$texto2paso4='¿Desea añaidrlo al presupuesto?';
		$contratarServicio='Contratar Servicio Printing Express';
		$noContratarServicio='No Contratar Servicio';

		## prspstdr8.php
		$presupuestoFinal='Final Quotation';
		$texto1PasoFinal='Excluding Taxes.';
		$texto2PasoFinal='Carriage paid in Peninsula from 300€ taxable payment on.';
		$texto3PasoFinal='For international shipments consult prices.';
		$texto4PasoFinal='15 days quotation validity unless there are exceptional fluctuations in the exchange rates or raw materials cost.';
		$texto5PasoFinal='If you would like a different quotation from the ones available on this budget maker, please contact our sales department.';
		$texto6PasoFinal='Quotation only for information purposes.';
		$texto7PasoFinal='If in doubt or in case you would like to confirm quotation, please contact our Sales Department.';
		$texto8PasoFinal='Tlf. +34 950 464 791';
		$texto9PasoFinal='<b>Remark:</b> For the same logo printing in several positions, it must be multiplied the unitary printing cost per positions number. Cliche will be charge only one time.<br><br>';
		$texto10PasoFinal='<b>Handling and Packaging included in the printing price.</b><br>Ask about discounts if you do not require final repackaging of the product in its origin polybag or carton.';
		$texto1PasoFinalPanama='Spain EXW Prices with printing included';
		$texto2PasoFinalPanama='Tlf. +507 449-6288';
		$texto3PasoFinalPanama='e-mail: info@makito.com.pa';
		$texto4PasoFinalPanama='For orders with printing we can supply +/- 5% of the requested quantity';
		$texto5PasoFinalPanama='Tiempo de producción 7/10 días desde la confirmación del diseño';
		$textopicaje='Stitch';
		$imprimirPresupuesto='Print Quotation';
		$guardarPresupuesto='Save Quotation';
		$hacerpedido="Hacer pedido a Makito";

		## total.php
		$valorDeLaCotizacion='Budget value';
		$ref='Ref';
		$uds='Pcs';
		$ud='Piece';
		$sub='Sub';  ### De la palabra Subtotal
		$trabajoMinimo='Minimum Job';
		$primerColor='First Colour';
		$colorExtra='Extra colour/-s';
		$cliche='Cliche';
		$clichepanama='Cliche';
		$color='Colour';
		$total='Total';
		$impuestosNoIncluidos='Excluded Taxes';
		$fecha='Date';
		$doscoloresincluidos='2 Colors Included';
		$puestaenmaquina = 'Machine Set up';
		$gastofijo = 'Fixed Cost';
		$precioporunidad = 'Price per unit';
		$gastofijopedido = 'Fixed cost per order';
		$programa = 'Program';
		$trabajomininmosombreros = 'Extra charge orders -250pcs';
		$canon="Excluded Digital Canon: 0.24€/pc";


		## preguardados.php
		$nombreguardado='Name';
		$trabajo='Print Job';
		$introduzcaNombre='Quotation Name:';
		$irInicio="Go Home";
		$guardar='Save';
		$cancelar='Cancel';
		$enviarAMakito="Send quotation to Makito";
		$enviarAMakicoComercial="This quoatation will be sent to Makito. A commercial agent will get in touch with you shortly.";
		$personaContacto="Indicate a contact person";
		$tlfContacto="Indicate a contact telephone number";
		$otroemail=" If you wiss to receive a copy, please give us an email address";
		$enviarMakito="Send to Makito";
		$enviarCotizacion="Send Quotation";
		$deseoRecibir="I want to receive this quotation in my email.";
		$indiqueEmail="Indicate email to send quotation";
		$noesposible="It is not possible to make an quotation for this reference.";
		$contactecomercial="Please contact our Customer Service <br> Tel.: +34 950 464 791";



		## qr.php

		$ver360="Show 360 Animation";
		$verareas="Show Print Areas";
		$enviarMail="Send to Email";
		$emailDestino="Email To";
		$comentarios="Coments";
		$enviar="Send Email";
		$colmaxqr="Colores Máximos: ";
		$compartirqr="Share";
		$inventariopanama="Available Inventory Free Zone Colon - PANAMÁ";
		$inventarioespana="AVAILABLE STOCK IN SPAIN";
		$mercanciadisponible="The merchandise will be available in FREE ZONE COLON - PANAMÁ 25 approximate days after the confirmation of your order.";
		$remite="Forward";
		$sidesea="If you want to see more product information like stock, features, packing, printing areas, etc. click ";
		$aqui="here";
		$noimprima="Do not print me if it’s not necessary. Protect the environment.";
		$mensajecorrecto="The message was sent successfully";

	}

//###############################################################################################################################
//####### ITALIANO ##############################################################################################################
//###############################################################################################################################
	
	if($_SESSION['idioma']=='3'){

		$presupuestadorIndex = 'Preventivatore';
		$usuarioLoggin = 'Utente';
		$passLoggin = 'Password';
		$btnEntrar = 'Entrare';
		$desde = 'Da';
		$iniciarSesion="Login Your Account";

		## cabecera.php ##
		$salir = 'Uscire';
		$presupuestosGuardados = 'Preventivi Salvati';

		## prspstdr1.php ##
		$presupuestador = 'Preventivatore';
		$seleccioneArticulo = 'Selezioni Articolo';
		$btnBuscar = ' Cercare Ref / Nome';
		$paso1 = 'PASSO 1';
		$selecionesArticuloAPresupuestar = 'Selezioni articolo a preventivare';
		$upss = 'Non si sono trovati articoli che coincidono con ';
		$cambiaCriterio = 'Provi di nuovo.';
		$buscar="Search";
		$nuevaBusqueda="New Search";

		## prspstdr2.php ##
		$seleccioneCantidad = 'Selezioni Quantità';
		$btnCantidad = 'Introdurre Quantità';
		$paso2 = 'PASSO 2';
		$cambiarArticulo = 'Cambiare Articolo';
		$cambiarCantidad = 'Cambiare Quantità';
		$cambiarTrabajo = 'Cambiare Lavoro';
		$cantidadCotizar = 'Introduca Quantità.';
		$composicion = 'Composizione';
		$infoComercial = 'Informazione Comerciale';
		$catalogos = 'Cataloghi';
		$ver = 'Vedere';
		$infoPacking = 'Informazione Packaging';
		$articulo = 'Articolo';
		$packingFinal = 'Packing Finale/ Unità';
		$pintermedio1 = 'Packing Intermedio 1';
		$pintermedio2 = 'Packing Intermedio 2';
		$cajaMaster = 'Scatola Master';
		$palet = 'Bancale';
		$diametro = 'Diámetro';
		$alto = 'Altezza';
		$ancho = 'Lunghezza';
		$largo = 'Larghezza';
		$peso = 'Peso';
		$tipoPacking = 'Tipo';
		$unidadesAprox = 'Unità Apross.';
		$bultos = 'Colli';
		$fichaTecnica = 'Scheda Tecnica';
		$preciosConfidenciales = 'Tariffa Prezzi Confidenziale';
		$disponibilidad = 'Disponibilità';
		$color = 'Colore';
		$talla = 'Taglia';
		$stock = 'Stock';
		$dispon = 'Disponibilità';
		$inmediata = 'Immediata';

		## prspstdr4.php ##
		$seleccioneTrabajo = 'Selezioni Lavoro Stampazione';
		$paso3 = 'PASSO 3';
		$texto1paso3 = 'Selezioni il lavoro che desidera fare in questo articolo.';
		$texto2paso3 = 'Abbia presente che alcuni colori possono avere bisogno di doppia passata.';
		$numColores = 'Nº di Colori a stampare per posizione:';
		$seleccionar = 'Seleccionar';
		$seleccionePuntadas = 'Seleccionar';
		$indiquePuntadas = 'Introduca il numero di puntate';
		$areasyMedidas = 'Aree e Misure consigliate';
		$indiquecm2 = 'Indicate cm2 dal logo:';
		$doblepasada = 'Per una stampa ottimale i colori scuri hanno bisogno d´una doppia passata. Selezioni questa tecnica se  desidera stampare sui colori scuri ';
        $atodocolor = "Pieno di colori";

		## prspstdr6.php ##
		$servicioPE = 'Servizio Printing  Express';
		$paso4 = 'Passo 4';
		$texto1paso4 = 'Possiamo inviare la sua ordine stampa in 72h contrattando questo servizio';
		$texto2paso4 = 'Desidera aggiungerlo al preventivo?';
		$contratarServicio = 'Contrattare  Servizio Printing Express';
		$noContratarServicio = 'Non contrattare il Servizio';

		## prspstdr8.php ##
		$presupuestoFinal = 'Preventivo Finale';
		$texto1PasoFinal = 'Tasse non incluse.';
		$texto2PasoFinal = 'Spese agenzia pagati in Peninsola a partire dai 300€ dalla base imponibile (senza stampa).';
		$texto3PasoFinal = 'PPer Invii Internazionali consulti prezzi.';
		$texto4PasoFinal = 'Il Preventivo ha validità 15 giorni salvo fluttuazioni eccezionali dal tasso di cambio o costo dalle materia prime.';
		$texto5PasoFinal = 'Se desidera una quotazione non inclusa nel preventivatore, per favore contatti il nostro dipartamento comerciale.';
		$texto6PasoFinal = 'Questa Preventivo è a titolo informativo.';
		$texto7PasoFinal = 'In caso di dubbi o conferma del preventivo Vi preghiamo di contattare il nostro Dipartamento Commerciale.';
		$texto8PasoFinal = 'Tlf. +34 950 464 791';
		$texto9PasoFinal = '<b> Nota: </b> Per la stessa stampa di marchio in diverse posizioni, deve essere moltiplicato il costo di stampa unitario per numero di posizioni.Cliche si carica una sola volta.<br><br>';
		$texto10PasoFinal = '<b>Manipolazione e Imballaggio compressa nel prezzo della stampazione.</b><br>Consultare  lo sconto nel caso in cui  non ci sia bisogno di avere la merce nel suo polybag o imballo originale.';
		$texto1PasoFinalPanama='Prezzi EXW con Stampazione dalla Spagna';
		$texto2PasoFinalPanama='Tlf. +507 449-6288';
		$texto3PasoFinalPanama='e-mail: info@makito.com.pa';
		$texto4PasoFinalPanama='For orders with printing we can supply +/- 5% of the requested quantity';
		$texto5PasoFinalPanama='Tempo di produzione 7-10 giorni da conferma bozza';
		$textopicaje = 'Picking';
		$imprimirPresupuesto = 'Stampare Preventivo';
		$guardarPresupuesto = 'Salvare Preventivo';
		$hacerpedido="Hacer pedido a Makito";

		## total.php ##
		$valorDeLaCotizacion = 'Valore della Quotazione';
		$ref = 'Ref';
		$uds = 'Pezzi';
		$ud='Pezzi';
		$sub = 'Sub';
		$trabajoMinimo = 'Importo Minimo';
		$primerColor = 'Primo Colore';
		$colorExtra = 'Colore/ri Extra';
		$cliche = 'Cliché';
		$clichepanama='Cliché';
		$color = 'Colore';
		$total = 'Totale';
		$impuestosNoIncluidos = 'Tasse Non Incluse';
		$fecha = 'Data';
		$doscoloresincluidos = 'Due Colori compresi';
		$puestaenmaquina  = 'Mesa in moto';
		$gastofijo  = 'Spesa fissa';
		$precioporunidad  = 'Prezzo unità';
		$gastofijopedido  = 'Spesa  fissa per Ordine';
		$programa  = 'Programazione';
		$trabajomininmosombreros = 'Extra charge orders -250pcs';
		$canon="Canon Ditale Non Incluse: 0.24€/pc";


		## preguardados.php ##
		$nombre = 'Nome';
		$trabajo = 'Lavoro';
		$introduzcaNombre = 'Introdurre Nome';
		$irInicio = 'Ir al Inicio';
		$guardar = 'Salvare';
		$cancelar = 'Cancella';
		$enviarAMakito = 'Inviare Preventivo a Makito';
		$enviarAMakicoComercial = 'il preventivo sarà inviato a Makito. Un comerciale si metterà in contatto per gestire il preventivo.';
		$personaContacto = 'Introduca persona di contatto';
		$tlfContacto = 'Introduca telefono di contatto';
		$otroemail = 'Si desiderà ricevere una copia ci indichi una email';
		$enviarMakito = 'Inviare  a Makito';
		$enviarCotizacion = 'Inviare Preventivo';
		$deseoRecibir = 'Desidero ricevere questo preventivo sulla mia e-mail.';
		$indiqueEmail = 'Indica e-mail dove desidera ricevere il preventivo';
		$noesposible = 'Non è possibile fare un preventivo on-line questo articolo.';
		$contactecomercial = 'Si prega di contattare contattare il nostro Dipartamento Commerciale<br>Tel .: +34 950 464 791';

		## qr.php ##
		$ver360 = 'Vedere Animazione 360';
		$verareas = 'Vedere Aree Stampa';
		$enviarMail = 'Inviare Email';
		$emailDestino = 'Email';
		$comentarios = 'Commenti';
		$enviar = 'Inviare';
		$colmaxqr="Colores Máximos: ";
		$compartirqr="Share";
		$compartirqr="Share";
		$inventariopanama="Disponibilitá Inventario Zona Libera Colon - PANAMÁ";
		$inventarioespana="DISPONIBILITÁ STOCK";
		$mercanciadisponible="La sua merce sará disponible nella ZONA LIBERA DI COLON - PANAMÁ 25 giorni circa dopo la conferma del suo ordine.";
		$remite="In avanti";
		$sidesea="Se desiderano consultare piu’ informazione sul prodotto come lo stock, caratteristiche, packing, aree stampa, faccia click ";
		$aqui="qui";
		$noimprima="Non mi stampare se non è necessario. Rispetta l’ambiente.";
		$mensajecorrecto="Il messaggio è stato inviato con successo";


	}


//###############################################################################################################################
//####### FRANCÉS ###############################################################################################################
//###############################################################################################################################
	
	if($_SESSION['idioma']=='4'){

		$presupuestadorIndex = 'Personnalisation Facile';
		$usuarioLoggin = 'Utilisateur';
		$passLoggin = 'Mot de passe';
		$btnEntrar = 'Entrer';
		$desde = 'à partir de ';
		$iniciarSesion="Identifiez-vous:";

		## cabecera.php ##
		$salir = 'Sortir';
		$presupuestosGuardados = 'Devis gardés';

		## prspstdr1.php ##
		$presupuestador = 'Personnalisation';
		$seleccioneArticulo = 'Sélectionner l´article';
		$btnBuscar = ' Recherche Ref / Nom';
		$paso1 = 'ÉTAPE 1';
		$selecionesArticuloAPresupuestar = 'Sélectionner l´article à cotiser';
		$upss = 'aucun élément correspondant n´a été trouvé';
		$cambiaCriterio = 'Essayez à nouveau.';
		$buscar="Search";
		$nuevaBusqueda="New Search";

		## prspstdr2.php ##
		$seleccioneCantidad = 'Sélectionner la quantité';
		$btnCantidad = 'Introduire la quantité';
		$paso2 = 'ÉTAPE 2';
		$cambiarArticulo = 'Modifier l´article';
		$cambiarCantidad = 'Modifier la quantité';
		$cambiarTrabajo = 'Modifier le marcage';
		$cantidadCotizar = 'Introduire  la quantité que vous désirez cotiser.';
		$composicion = 'Composition';
		$infoComercial = 'Information commerciale';
		$catalogos = 'Catalogues';
		$ver = 'Voir';
		$infoPacking = 'Information d´emballage';
		$articulo = 'Article';
		$packingFinal = 'Emballage final';
		$pintermedio1 = 'Emballage  Intermédiaire';
		$pintermedio2 = 'Emballage  Intermédiaire 2';
		$cajaMaster = 'Boîte Master';
		$palet = 'Palette';
		$diametro = 'Diamètre';
		$alto = 'Hauteur';
		$ancho = 'Largeur';
		$largo = 'Longeur';
		$peso = 'Poids';
		$tipoPacking = 'Type';
		$unidadesAprox = 'Unités Aprox.';
		$bultos = 'Colis';
		$fichaTecnica = 'Fiche technique';
		$preciosConfidenciales = 'Tarif confidentielle';
		$disponibilidad = 'Disponibilité';
		$color = 'Couleur';
		$talla = 'Taille';
		$stock = 'Stock';
		$dispon = 'Disponibilité';
		$inmediata = 'Immédiate';

		## prspstdr4.php ##
		$seleccioneTrabajo = 'Sélectionner le marcage d´impression';
		$paso3 = 'ÉTAPE 3';
		$texto1paso3 = 'Sélectionner le marcage que vous souhaitez effectuer pour cet article.';
		$texto2paso3 = 'Noter que  certaines couleurs peuvent avoir besoin d´un double passage .';
		$numColores = 'Nº de couleurs à imprimer par  position:';
		$seleccionar = 'Choisir';
		$seleccionePuntadas = 'Choisir';
		$indiquePuntadas = 'Nombre de points';
		$areasyMedidas = 'Zones et mesures recommandées';
		$indiquecm2 = 'Indicate cm2 dal logo:';
		$doblepasada = 'Pour un marquage optimum, les couleurs sombres nécessitent un double passage. Seleccionezcette technique pour l´impresión sur couleurs foncées.';
        $atodocolor = "En couleur";

		## prspstdr6.php ##
		$servicioPE = 'Service Printing Express';
		$paso4 = 'PASO 4';
		$texto1paso4 = 'Permet d´expedier votre commande personnalisée en 72h en souscrivant ce service extra.';
		$texto2paso4 = '¿Voulez vous ajouter le devis?';
		$contratarServicio = 'Souscrire Service Printing Express';
		$noContratarServicio = 'Ne pas souscrire ce service';

		## prspstdr8.php ##
		$presupuestoFinal = 'Devis final';
		$texto1PasoFinal = 'Taxes non incluses.';
		$texto2PasoFinal = 'Port payé en péninsule a partir de 300 € de  la base  imposable (sans personnalisation).';
		$texto3PasoFinal = 'Pour les envois internationaux consulter les Tarifs';
		$texto4PasoFinal = 'Validité du devis 15 jours sauf  fluctuations exceptionnelles du taux de change ou le coût des matières premières.';
		$texto5PasoFinal = 'Si la cotisation desirée n´est pas contemplé dans ce devis  , s´il vous plaît contacter notre service commercial.';
		$texto6PasoFinal = 'Devis à titre informatif.';
		$texto7PasoFinal = 'En cas de doute ou pour confirmation du devis contactez notre Département Commerciale.';
		$texto8PasoFinal = 'Tlf. +34 950 464 791';
		$texto9PasoFinal = '<b> Remarque: </b> Pour imprimer le même logo sur plusieurs positions, vous devez multiplier le coût unitaire d´impression par le nombre de positions. Cliché ne sera facturé qu´une fois.<br><br>';
		$texto10PasoFinal = '<b>Manutention et conditionnement inclus dans le prix de l’impression.</b><br>Consulter remise en cas de ne pas vouloir le reconditionnement  final du produit dans son polybag ou emballage d’origine.';
		$texto1PasoFinalPanama='Prix EXW avec impression en Espagne';
		$texto2PasoFinalPanama='Tlf. +507 449-6288';
		$texto3PasoFinalPanama='e-mail: info@makito.com.pa';
		$texto4PasoFinalPanama='For orders with printing we can supply +/- 5% of the requested quantity';
		$texto5PasoFinalPanama='Délai de fabrication 7-10 jours à partir de la confirmation du design';
		$textopicaje = 'Broderie';
		$imprimirPresupuesto = 'Imprimer';
		$guardarPresupuesto = 'Sauvegarder';
		$hacerpedido="Hacer pedido a Makito";

		## total.php ##
		$valorDeLaCotizacion = 'Valeur du devis';
		$ref = 'Ref';
		$uds = 'Pcs';
		$ud='Piece';
		$sub = 'Sous';
		$trabajoMinimo = 'Travail minumum';
		$primerColor = 'Première couleur';
		$colorExtra = 'Couleur/s supplémentaires';
		$cliche = 'Cliché';
		$clichepanama='Cliché';
		$color = 'Couleur';
		$total = 'Total';
		$impuestosNoIncluidos = 'Taxes non incluses';
		$fecha = 'Date';
		$doscoloresincluidos = 'Deux couleurs incluses.';
		$puestaenmaquina  = 'Mise en marche';
		$gastofijo  = 'Frais Fixe';
		$precioporunidad  = 'Prix par unité';
		$gastofijopedido  = 'Frais fixe par commande';
		$programa  = 'Programme';
		$trabajomininmosombreros = 'Surcharge pour commande de -250uts';
		$canon="Canon Digital Non Incluses: 0.24€/pc";


		## preguardados.php ##
		$nombre = 'Nom';
		$trabajo = 'Personnalisation';
		$introduzcaNombre ='';
		$irInicio = 'Commencer';
		$guardar = 'Sauvegarder';
		$cancelar = 'Annuler';
		$enviarAMakito = 'Envoyer le Devis à Makito';
		$enviarAMakicoComercial = 'Cette cotisation sera envoyée à Makito. Un commercial vous joindrá en bref';
		$personaContacto = 'Indiquez une personne de contacte';
		$tlfContacto = 'Indiquez un téléphone de contact';
		$otroemail = 'Si vous voulez recevoir une copie, merci de nous indiquer votre adresse mail';
		$enviarMakito = 'Envoyer à Makito';
		$enviarCotizacion = 'Envoyer le Devis';
		$deseoRecibir = 'Je désire recevoir ce Devis par email.';
		$indiqueEmail = 'Indiquez un email pour recevoir votre devis';
		$noesposible = 'Il est impossible de faire un devis en ligne cet article.';
		$contactecomercial = 'S´il vous plaît contacter notre Sales Department<br>Tlf: +34 950 464 791';

		## qr.php ##
		$ver360 = 'voir animation 360';
		$verareas = 'voir zones d´impression ';
		$enviarMail = 'Envoyer un e-mail';
		$emailDestino = 'e-mail';
		$comentarios = 'Commentaires';
		$enviar = 'Envoyer';
		$colmaxqr="Colores Máximos: ";
		$compartirqr="Share";
		$inventariopanama="Inventaire Disponible Zona Libre Colon - PANAMÁ";
		$inventarioespana="STOCK DISPONIBLE";
		$mercanciadisponible="Les produits seront disponibles dans la ZONE LIBRE DE COLON - PANAMÁ 25 jours aprés la confirmation de votre commande.";
		$remite="Expediteur";
		$sidesea="Si vous désirez plus d’information sur les produits tels que le stock disponible, leurs caractéristiques, emballage, zones d'impression, etc. Cliquez ";
		$aqui="ici";
		$noimprima="N'imprimer que en cas nécessaire. Protégeons notre environnement.";
		$mensajecorrecto="Le message a été envoyé avec succès";

	}


//###############################################################################################################################
//####### PORTUGUÉS #############################################################################################################
//###############################################################################################################################
	
	if($_SESSION['idioma']=='5'){

		## loging.php ##
		$presupuestadorIndex='Presupuestador';
		$usuarioLoggin="Usuário";
		$passLoggin="Senha";
		$btnEntrar="Entrar";
		$desde="From: ";
		$iniciarSesion="Login Your Account";


		## cabecera.php ##
		$salir='Sair';
		$presupuestosGuardados='Orçamentos Guardados';


		## prspstdr1.php
		$presupuestador='Orçamentador';
		$seleccioneArticulo='Selecione Artigo';
		$btnBuscar=' Procurar Ref / Nome';
		$paso1='PASSO 1';
		$selecionesArticuloAPresupuestar='Selecione artigo a orçamentar';
		$upss='Não encontrou-se artigos que coincidam com ';
		$cambiaCriterio='Faça uma nova.';
		$buscar="Search";
		$nuevaBusqueda="New Search";

		## prspstdr2.php
		$seleccioneCantidad='Selecione Quantidade';
		$btnCantidad='Introduzir Quantidade';
		$paso2='PASSO 2';
		$cambiarArticulo='Alterar o Artigo';
		$cambiarCantidad='Alterar Quantidade';
		$cambiarTrabajo='Alterar Trabalho';
		$cantidadCotizar='Informação Comercial.';
		$min='Mínimo 1 y Máximo 999999';
		$composicion='Composião';
		$infoComercial='Información Comercial';
		$catalogos='Catalogos';
		$ver='Ver';
		$infoPacking='Informação de Packing';
		$articulo='Artigo';
		$packingFinal='Packing Final';
		$pintermedio1='Packing Intermédio 1';
		$pintermedio2='Packing Intermédio 2';
		$cajaMaster='Caixa Master';
		$palet='Palete';

		$diametro='Diametro';
		$alto='Altura';
		$ancho='Ancho';
		$largo='Largura';
		$peso='Peso';
		$tipoPacking='Tipo';
		$unidadesAprox='Unidades Aprox';
		$bultos='Caixas';
		$fichaTecnica='Ficha Técnica';
		$preciosConfidenciales='Tabela de Preços Confidenciais';
		$disponibilidad='Disponibilidade';
		$color='Cor';
		$talla='Tamanho';
		$stock='Stock';
		$dispon='Disponibilidade';
		$inmediata='Imediata';

		## prspstdr4.php
		$seleccioneTrabajo='Selecção Trabalho de Impressão';
		$paso3='PASSO 3';
		$texto1paso3='Seleccione o trabalho que deseja realizar para este artigo.';
		$texto2paso3='Tenha em conta que algumas cores necessitam de dupla passagem de tinta.';
		$numColores='N. de cores a imprimir por posição:';
		$seleccionar='Seleccionar';
		$seleccionePuntadas='Seleccionar';
		$indiquePuntadas='Indique el Número de Puntadas';
		$areasyMedidas="Áreas e Medidas Recomendadas";
		$indiquecm2="Indique cm2 del logo:";
		$doblepasada="This tecnique requires double printing";
        $atodocolor = "Cor cheia";

		## prspstdr6.php
		$servicioPE='Servicio Printing Express';
		$paso4='PASO 4';
		$texto1paso4='Podemos enviar su pedido de impresión en 72h contratando este servicio.';
		$texto2paso4='¿Desea añaidrlo al presupuesto?';
		$contratarServicio='Contratar Servicio Printing Express';
		$noContratarServicio='No Contratar Servicio';

		## prspstdr8.php
		$presupuestoFinal='Orçamento Final';
		$texto1PasoFinal='Impostos não incluídos.';
		$texto2PasoFinal='Portes pagos na Península a partir de 300€ de base imponível (sem impressão).';
		$texto3PasoFinal='Para envios internacionais consulte preço.';
		$texto4PasoFinal='Validade do orçamento 15 dias salvo flutuações excepcionais relacionados com o tipo de cambio ou custo das materias primas.';
		$texto5PasoFinal='Se deseja um orçamento não contemplado neste gerardor de orçamentos, por favor contacte o nosso departamento comercial.';
		$texto6PasoFinal='Este orçamento é para obter informações.';
		$texto7PasoFinal='Em caso de dúvida ou confirmação de orçamento entre em contato com nossas Vendas Departamento';
		$texto8PasoFinal='Tlf. +34 950 464 791';
		$texto9PasoFinal='<B> Observação: </b> Para a mesma impressão de logotipo em várias posições, deve ser multiplicado o custo unitário de impressão por número de posições. Cliche será cobrada apenas uma vez.<br><br>';
		$texto10PasoFinal='<b>Manipulação e embalamento incluídos no preço da impressão.</b><br>Consultar desconto em caso de não requerer reembalamento final do produto no polybag ou estojo de origem.';
		$texto1PasoFinalPanama='Preços EXW com impressão desde Espanha';
		$texto2PasoFinalPanama='Tlf. +507 449-6288';
		$texto3PasoFinalPanama='e-mail: info@makito.com.pa';
		$texto4PasoFinalPanama='For orders with printing we can supply +/- 5% of the requested quantity';
		$texto5PasoFinalPanama='Tiempo de producción 7/10 días desde la confirmación del diseño';
		$textopicaje='Stitch';
		$imprimirPresupuesto='Imprimir Orçamento';
		$guardarPresupuesto='Guardar Orçamento';
		$hacerpedido="Hacer pedido a Makito";


		## total.php
		$valorDeLaCotizacion='Valor de Cotização';
		$ref='Ref';
		$uds='Uds';
		$ud='Unidad';
		$sub='Sub';  ### De la palabra Subtotal
		$trabajoMinimo='Trabalho Mínimo';
		$primerColor='Primeira Cor';
		$colorExtra='Cor/es Extra';
		$cliche='Cliché';
		$clichepanama='Cliché';
		$color='Cor';
		$total='Total';
		$impuestosNoIncluidos='Impostos Não Incluídos';
		$fecha='Data';
		$doscoloresincluidos='Dos Colores Incluidos';
		$puestaenmaquina = 'Machine Set up';
		$gastofijo = 'Fixed Cost';
		$precioporunidad = 'Price per unit';
		$gastofijopedido = 'Fixed cost per order';
		$programa = 'Program';
		$trabajomininmosombreros = 'Extra charge orders -250pcs';
		$canon="Canon Digital Não Incluído: 0.24€/pc";


		## preguardados.php
		$nombreguardado='Nome';
		$trabajo='Trabajo';
		$introduzcaNombre='Introduzca un Nome';
		$irInicio="Ir al Inicio";
		$guardar='Guardar';
		$cancelar='Cancelar';
		$enviarAMakito="Enviar Cotización a Makito";
		$enviarAMakicoComercial="Este orçamento será enviada à Makito. Em breve, um comercial  da Makito entrará em contacto convosco.";
		$personaContacto="Indique una persona de contacto";
		$tlfContacto="Indique un teléfono de contacto";
		$otroemail="E caso pretenda receber uma cópia, por favor indique o email";
		$enviarMakito="Enviar a Makito";
		$enviarCotizacion="Enviar Cotización";
		$deseoRecibir="Deseo recibir esta cotización en mi email.";
		$indiqueEmail="Indique email donde desea recibir la cotización";
		$noesposible="Não é possível fazer uma cotação on-line este artigo.";
		$contactecomercial="Entre em contato com o nosso Departamento de Vendas<br>Tlf: +34 950 464 791";

		## qr.php

		$ver360="Show 360 Animation";
		$verareas="Show Print Areas";
		$enviarMail="Send to Email";
		$emailDestino="Email To";
		$comentarios="Coments";
		$enviar="Send Email";
		$colmaxqr="Colores Máximos: ";
		$compartirqr="Share";
		$compartirqr="Share";
		$inventariopanama="Inventário Disponível Área Livre Colon - PANAMÁ";
		$inventarioespana="STOCK DISPONÍVEL";
		$mercanciadisponible="A mercadoría estará disponível na ZONA LIVRE DE COLON - PANAMÁ 25 días aprox. despois da confirmaÇão do sey pedido.";
		$remite="Remetente";
		$sidesea="Caso pretenda obter mais informação do produto como por exemplo stock, características, packing, áreas de Impressão, etc.. Clique";
		$aqui="aqui";
		$noimprima="Não imprimir em caso de não ser necessário. Proteja o meio ambiente.";
		$mensajecorrecto="A mensagem foi enviada com sucesso";


	}



//###############################################################################################################################
//####### ALEMÁN ################################################################################################################
//###############################################################################################################################
	
	if($_SESSION['idioma']=='6'){

		$presupuestadorIndex = 'Schliessen';
		$usuarioLoggin = 'Benutzer';
		$passLoggin = 'Kennwort';
		$btnEntrar = 'Eingeben';
		$desde = 'Von';
		$iniciarSesion="Login Your Account";

		## cabecera.php ##
		$salir = 'Schliessen';
		$presupuestosGuardados = 'Gespeicherte Angebote';

		## prspstdr1.php ##
		$presupuestador = 'Berechnung';
		$seleccioneArticulo = 'Artikel auswählen';
		$btnBuscar = ' Referenz / Name suchen';
		$paso1 = 'SCHRITT 1';
		$selecionesArticuloAPresupuestar = 'Wählen sie die Artikel zum anbieten aus';
		$upss = 'Keine entsprechende Artikel gefunden';
		$cambiaCriterio = 'Ändern sie die Suchkriterien und versuchen sie es erneut.';
		$buscar="Search";
		$nuevaBusqueda="New Search";

		## prspstdr2.php ##
		$seleccioneCantidad = 'Menge auswählen';
		$btnCantidad = 'Menge eingeben';
		$paso2 = 'SCHRITT 2';
		$cambiarArticulo = 'Artikel ändern';
		$cambiarCantidad = 'Stückzahl ändern';
		$cambiarTrabajo = 'Druckauftrag ändern';
		$cantidadCotizar = 'Geben Sie die gewünschte Menge zum anbieten ein.';
		$composicion = 'Zusammenstellung';
		$infoComercial = 'Geschäftsinformation';
		$catalogos = 'Kataloge';
		$ver = 'Anzeigen';
		$infoPacking = 'Packing Information';
		$articulo = 'Artikel';
		$packingFinal = 'End Verpackung';
		$pintermedio1 = 'Zwischen Verpackung';
		$pintermedio2 = 'Zwischen Verpackung 2';
		$cajaMaster = 'Master Box';
		$palet = 'Palette';
		$diametro = 'Durchmesser';
		$alto = 'Höhe';
		$ancho = 'Breite';
		$largo = 'Länge';
		$peso = 'Gewicht';
		$tipoPacking = 'Statur';
		$unidadesAprox = 'Geschätzte Einheiten';
		$bultos = 'Pakete';
		$fichaTecnica = 'Technische Details';
		$preciosConfidenciales = 'Vertrauliche Preisliste';
		$disponibilidad = 'Verfügbarkeit';
		$color = 'Farbe';
		$talla = 'Grösse';
		$stock = 'Lager';
		$dispon = 'Verfügbarkeit';
		$inmediata = 'Unmittelbar';

		## prspstdr4.php ##
		$seleccioneTrabajo = 'Wählen Sie den Druckauftrag';
		$paso3 = 'SCHRITT 3';
		$texto1paso3 = 'Wählen Sie die Druck Technick für diesen Artikel.';
		$texto2paso3 = 'Beachten Sie das einige Farben einen Doppel Druck benötigen.';
		$numColores = 'Druckfarben die pro Position bedruckt:';
		$seleccionar = 'Wählen';
		$seleccionePuntadas = 'Wählen';
		$indiquePuntadas = 'Bitte Stickkzahlen angeben';
		$areasyMedidas = 'Druckbereich und avisierte Druckgrößen';
		$indiquecm2 = 'Logo Größe angeben in cm2';
		$doblepasada = 'Eine optimale Markierung auf dunkle Farben braucht einen doppelten Druckdurchgang. Bitte wählen Sie diesen Technik falls Sie auf dunklen Farben drucken möchten.';
        $atodocolor = "Volle Farbe";

		## prspstdr6.php ##
		$servicioPE = 'Express Druck Service';
		$paso4 = 'SCHRITT 4';
		$texto1paso4 = 'Mit diesen Service können wir den Auftrag in 72 Stunden verschicken.';
		$texto2paso4 = 'Sollen wir diesen im Angebot hinzufügen?';
		$contratarServicio = 'Express Druck Service beantragen';
		$noContratarServicio = 'Nicht diesen Service beantragen';

		## prspstdr8.php ##
		$presupuestoFinal = 'Endgültiges Angebot';
		$texto1PasoFinal = 'Steuern nicht inbegriffen.';
		$texto2PasoFinal = 'Versand Freihaus in Spanien und Portugal ab 300€ Neto Summe (ohne Druckkosten).';
		$texto3PasoFinal = 'Für Internationale Lieferungen bitte Preise anfragen.';
		$texto4PasoFinal = 'Gültigkeit des Angebots sind 15 Tage, ausser in Ausnahme von Schwankungen im Wechselkurs oder Rohstoffkosten.';
		$texto5PasoFinal = 'Wenn Sie ein Angebot benötigen was nicht in diesem Budget angezeigt wird, kontaktieren Sie bitte unsere Verkaufsabteilung';
		$texto6PasoFinal = 'Dieses Zitat ist für Informationen.';
		$texto7PasoFinal = 'Im Zweifelfall, oder falls Sie das Angebot bestätigen möchten, bitte kontaktieren Sie unseren Innendienst.';
		$texto8PasoFinal = 'Tlf. +31 (0) 854852183';
		$texto9PasoFinal = '<b> Anmerkung: </b>Um das gleiche Logo an mehrere Positionen zu drucken, müssen Sie den Einzeldruckpreis des Artikels multiplizieren. Die Einstellkosten werden nur einmal berechnet<br><br>';
		$texto10PasoFinal = '<b>Behandlung und Verpackung ist im Druckpreis inbegriffen.</b>Befragen Sie für einen Rabatt wenn Sie kein Endwiederverpackung in den Polybag oder Karton brauchen.';
		$texto1PasoFinalPanama='EXW Preise mit Bedruckung aus Spanien';
		$texto2PasoFinalPanama='Tlf. +507 449-6288';
		$texto3PasoFinalPanama='e-mail: info@makito.com.pa';
		$texto4PasoFinalPanama='For orders with printing we can supply +/- 5% of the requested quantity';
		$texto5PasoFinalPanama='Tiempo de producción 7/10 días desde la confirmación del diseño';
		$textopicaje = 'Stich';
		$imprimirPresupuesto = 'Angebot Ausdrucken';
		$guardarPresupuesto = 'Angebot Speichern';
		$hacerpedido="Hacer pedido a Makito";

		## total.php ##
		$valorDeLaCotizacion = 'Wert des Angebotes';
		$ref = 'Referenz';
		$uds = 'Einheiten';
		$ud='Einheit';
		$sub = 'Zwischensumme';
		$trabajoMinimo = 'Mindestarbeit';
		$primerColor = 'Erste Farbe';
		$colorExtra = 'Extra Farbe/n';
		$cliche = 'Vorkosten';
		$clichepanama='Vorkosten';
		$color = 'Farbe';
		$total = 'Gesamtsumme';
		$impuestosNoIncluidos = 'Steuern nicht inbegriffen';
		$fecha = 'Datum';
		$doscoloresincluidos = 'Inklusiv 2 Farben';
		$puestaenmaquina  = 'Einstellkosten';
		$gastofijo  = 'Fixkosten';
		$precioporunidad  = 'Preis pro Einheit';
		$gastofijopedido  = 'Fixkosten pro Auftrag';
		$programa  = 'Programm';
		$trabajomininmosombreros = 'Extra charge orders -250pcs';
		$canon="Digital Canon nicht inbegriffen: 0.24€/pc";


		## preguardados.php ##
		$nombre = 'Name';
		$trabajo = 'Trabajo';
		$introduzcaNombre = 'Geben Sie einen Namen';
		$irInicio = 'Gehen Sie zu Start';
		$guardar = 'Speichern';
		$cancelar = 'Stornieren';
		$enviarAMakito = 'Senden Zitat zu Makito';
		$enviarAMakicoComercial = 'Diese Anfrage wird an Makito gesendet. Ein Verkäufer wird Sie in  Kürze informieren.';
		$personaContacto = 'Geben Sie einen Ansprechpartner';
		$tlfContacto = 'Geben Sie eine Telefon';
		$otroemail = 'Um eine Kopie zu erhalten, geben Sie E-Mail an';
		$enviarMakito = 'Schicken Makito';
		$enviarCotizacion = 'schicken Zitat';
		$deseoRecibir = 'Ich möchte dieses Angebot per E-Mail zu erhalten.';
		$indiqueEmail = 'Geben Sie, wo Sie E-Mail-Angebot erhalten möchten';
		$noesposible = 'Es ist leider nicht möglich ein Angebot zu machen für diesen Artikelreferenz';
		$contactecomercial = 'Bitte setzten Sie sich in Verbindung mit unseren Kundendienst Tlf. +31 (0) 854852183';

		## qr.php ##
		$ver360 = '360 Grad Bilder anschauen';
		$verareas = 'Druckbereich anschauen';
		$enviarMail = 'Zur email schicken';
		$emailDestino = 'Email';
		$comentarios = 'Bemerkungen';
		$enviar = 'Verschicken';
		$colmaxqr="Colores Máximos: ";
		$compartirqr="Share";
		$compartirqr="Share";
		$inventariopanama="Available Inventory Free Zone Colon - PANAMÁ";
		$inventarioespana="AVAILABLE STOCK";
		$mercanciadisponible="The merchandise will be available in FREE ZONE COLON - PANAMÁ 25 approximate days after the confirmation of your order.";
		$remite="Absender";
		$sidesea="If you want to see more product information like stock, features, packing, printing areas, etc. click ";
		$aqui="here";
		$noimprima="Do not print me if it’s not necessary. Protect the environment.";
		$mensajecorrecto="The message was sent successfully";
}


//###############################################################################################################################
//####### NEERLANDÉS ############################################################################################################
//###############################################################################################################################

	
	if($_SESSION['idioma']=='7'){

		$presupuestadorIndex = 'Offerte';
		$usuarioLoggin = 'User';
		$passLoggin = 'Password';
		$btnEntrar = 'Enter';
		$desde = 'Van';
		$iniciarSesion="Login Your Account";

		## cabecera.php ##
		$salir = 'Logout';
		$presupuestosGuardados = 'Saved Quotations ';

		## prspstdr1.php  ##
		$presupuestador = 'Berekening';
		$seleccioneArticulo = 'Artikel selecteren';
		$btnBuscar = ' Zoek referentie/ Naam zoeken';
		$paso1 = 'STAP 1';
		$selecionesArticuloAPresupuestar = 'Selecteer artikelen ';
		$upss = 'Geen overeenkomstige artikelen gevonden';
		$cambiaCriterio = 'Probeert u het opnieuw';
		$buscar="Search";
		$nuevaBusqueda="New Search";

		## prspstdr2.php  ##
		$seleccioneCantidad = 'Aantal selecteren';
		$btnCantidad = 'Aantal ingeven';
		$paso2 = 'STAP 2';
		$cambiarArticulo = 'Artikel aanpassen';
		$cambiarCantidad = 'Aantal veranderen';
		$cambiarTrabajo = 'Verander printmethode';
		$cantidadCotizar = 'Geeft u het gewenste aantal in';
		$composicion = 'Samenstelling';
		$infoComercial = 'Bedrijfsinformatie';
		$catalogos = 'Catalogi';
		$ver = 'Tonen';
		$infoPacking = 'Verpakkingsinformatie';
		$articulo = 'Artikelen';
		$packingFinal = 'Eindverpakking';
		$pintermedio1 = 'Tussenverpakking';
		$pintermedio2 = 'Tussenverpakking 2';
		$cajaMaster = 'Omdoos';
		$palet = 'Paletten';
		$diametro = 'Diameter';
		$alto = 'Hoogte';
		$ancho = 'Breedte';
		$largo = 'Lengte';
		$peso = 'Gewicht';
		$tipoPacking = 'Type';
		$unidadesAprox = 'Geschatte eenheden';
		$bultos = 'Dozen';
		$fichaTecnica = 'Technische Details';
		$preciosConfidenciales = 'Vertrouwelijke prijslijst';
		$disponibilidad = 'Beschikbaarheid';
		$color = 'Kleur';
		$talla = 'Maat';
		$stock = 'Stock';
		$dispon = 'Beschikbaarheid';
		$inmediata = 'Direct';

		## prspstdr4.php  ##
		$seleccioneTrabajo = 'Kiest U de drukopdracht';
		$paso3 = 'STAP 3';
		$texto1paso3 = 'Kiest U de druktechniek uit voor dit artikel';
		$texto2paso3 = 'Let u er svp op dat sommige kleuren een dubbeldruk nodig hebben';
		$numColores = 'Aantal kleuren die per positie bedrukt worden:';
		$seleccionar = 'Kiezen';
		$seleccionePuntadas = 'Kiezen';
		$indiquePuntadas = 'Graag aantal steken aangeven';
		$areasyMedidas = 'Drukoppervlakte met geadviseerde drukgrootte';
		$indiquecm2 = 'Logo aangeven in cm2';
		$doblepasada = 'Een optimale bedruking op donkere kleuren heeft een dubbele drukgang nodig. Kiest u s.v.p. Voor deze potie indien u op donkere kleuren wilt drukken.';
        $atodocolor = "Volle kleur";

		## prspstdr6.php  ##
		$servicioPE = 'Express Drukservice';
		$paso4 = 'STAP 4';
		$texto1paso4 = 'Met deze service kunnen wij uw opdracht binnen 72 uur versturen. Wilt u deze optie in uw offerte meenemen ?';
		$texto2paso4 = 'Zullen wij dit in uw offerte meenemen ?';
		$contratarServicio = 'Express Drukservice opdracht';
		$noContratarServicio = 'Gee opdracht voor deze service geven';

		## prspstdr8.php  ##
		$presupuestoFinal = 'Definitieve offerte';
		$texto1PasoFinal = 'BTW niet inbegrepen';
		$texto2PasoFinal = 'Transport vrij huis voor Spanje en Portugal vanaf € 300 (zonder drukkodten)';
		$texto3PasoFinal = 'Voor internationale leveringen graag prijs aanvragen';
		$texto4PasoFinal = 'Geldigheid van de offerte is 15 dagen, behalve bij hoge fluctuaties in wisselkoersen of grondstoffen.';
		$texto5PasoFinal = 'Indien u een offerte nodig heeft wat u niet in dit systeem kunt vinden, neemt u dan svp contact op met onze verkoopafdeling';
		$texto6PasoFinal = 'Dit citaat is voor informatie';
		$texto7PasoFinal = 'In twijfelgeval, of als u de offerte wilt bevestigen kunt u contact opnemen met onze binnendienst.';
		$texto8PasoFinal = 'Tlf. +31 (0) 854852183';
		$texto9PasoFinal = '<b> Opmerking: </b>Om hetzelfde logo op meerdere posities aan te brengen, neemt u de enkeldrukprijs van het artikel en vermenigvuldigt u deze. De instelkosten worden slechts één keer berekend.<br><br>';
		$texto10PasoFinal = '<b>Handling en verpakken zijn inbegrepen in de printprijs.</b><br>Vraagt u gerust om korting, indien het produkt na bedrukking niet hoeft te worden terug verpakt in de polybag of het doosje.';
		$texto1PasoFinalPanama='Spain EXW Prices with printing included';
		$texto2PasoFinalPanama='Tlf. +507 449-6288';
		$texto3PasoFinalPanama='e-mail: info@makito.com.pa';
		$texto4PasoFinalPanama='For orders with printing we can supply +/- 5% of the requested quantity';
		$texto5PasoFinalPanama='Tiempo de producción 7/10 días desde la confirmación del diseño';
		$textopicaje = 'Steken';
		$imprimirPresupuesto = 'Offerte printen';
		$guardarPresupuesto = 'Offerte opslaan';
		$hacerpedido="Hacer pedido a Makito";

		## total.php  ##
		$valorDeLaCotizacion = 'Waarde van offerte';
		$ref = 'Referentie';
		$uds = 'Eenheden';
		$ud = 'Eenhed';
		$sub = 'Tussensom';
		$trabajoMinimo = 'Minimum werk';
		$primerColor = 'Eerste kleur';
		$colorExtra = 'Extra Kleur(en)';
		$cliche = 'Instelkosten';
		$clichepanama='Instelkosten';
		$color = 'Kleur';
		$total = 'Totaalbedrag';
		$impuestosNoIncluidos = 'BTW niet inbegrepen';
		$fecha = 'Datum';
		$doscoloresincluidos = '2 kleuren inclusief';
		$puestaenmaquina  = 'Set up Kosten';
		$gastofijo  = 'Vaste kosten';
		$precioporunidad  = 'Prijs per eenheid';
		$gastofijopedido  = 'Vaste kosten per order';
		$programa  = 'Programma';
		$trabajomininmosombreros = 'Extra charge orders -250pcs';
		$canon="Digital Canon niet inbegrepen: 0.24€/pc";


		## preguardados.php  ##
		$nombre = 'Naam';
		$trabajo = 'Minimum werk';
		$introduzcaNombre = 'Naam offerte';
		$irInicio = 'Naar de Homepage';
		$guardar = 'Opslaan';
		$cancelar = 'annuleren';
		$enviarAMakito = 'De aanvraag naar Makito sturen';
		$enviarAMakicoComercial = 'De aanvraag wordt naar Makito gestuurd. Een account manager zal contact met u opnemen om de offerte te kunnen maken.';
		$personaContacto = 'Geeft u een contactpersoon aan';
		$tlfContacto = 'Geeft u een telefoonnummer aan';
		$otroemail = 'Indien u een copy wenst te ontvangen, gelieve een mailadres te sturen';
		$enviarMakito = 'naar Makito sturen';
		$enviarCotizacion = 'Offerte sturen';
		$deseoRecibir = 'Ik wil de offerte graag per e-mail ontvangen.';
		$indiqueEmail = 'geeft u alstublieft het e-mail adres aan waarop u de offerte wilt ontvangen';
		$noesposible = 'Het is niet mogeljk een offerte te maken voor dit referentienummer';
		$contactecomercial = 'Gelieve contact op te nemen met onze binnendienst Tlf. +31 (0) 854852183';

		## qr.php  ##
		$ver360 = '360 Graden video bekijken';
		$verareas = ' Drukoppervlak bekijken';
		$enviarMail = 'Stuur naar email';
		$emailDestino = 'Email';
		$comentarios = 'Opmerkingen';
		$enviar = 'Versturen';
		$colmaxqr="Colores Máximos: ";
		$compartirqr="Share";
		$compartirqr="Share";
		$inventariopanama="Available Inventory Free Zone Colon - PANAMÁ";
		$inventarioespana="BESCHIKBARE VOORRAAD";
		$mercanciadisponible="The merchandise will be available in FREE ZONE COLON - PANAMÁ 25 approximate days after the confirmation of your order.";
		$remite="Verzender";
		$sidesea="If you want to see more product information like stock, features, packing, printing areas, etc. click ";
		$aqui="here";
		$noimprima="Do not print me if it’s not necessary. Protect the environment.";
		$mensajecorrecto="The message was sent successfully";

	}

}