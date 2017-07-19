<?php

    include("simple_html_dom.php");
    $html= getdom("https://app.klipfolio.com/published/12fd7f15f637639b4f879bed583f5aa2/chilevision",1041,1045);

    function getdom($url, $param1=1041, $param2=1043){
    	$user_agent="Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0";
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
    	curl_setopt($curl, CURLOPT_HEADER, 0);
    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,120);
    	curl_setopt($curl, CURLOPT_TIMEOUT,120);
    	curl_setopt($curl, CURLOPT_MAXREDIRS,10);
    	curl_setopt($curl, CURLOPT_COOKIEFILE,"cookie.txt");
    	curl_setopt($curl, CURLOPT_COOKIEJAR,"cookie.txt");
    	curl_setopt($curl, CURLOPT_COOKIE, 'argSuperEconomicos=1');
    	curl_setopt($curl, CURLOPT_COOKIE, 'argFamiliaId='.$param1);
    	curl_setopt($curl, CURLOPT_COOKIE, 'argSubCategoriaId='.$param2);
    	$html = curl_exec ($curl);        
    	if(curl_errno($curl) === true) {
                    return "error";
    		}
    	else {
        		$html= str_get_html($html);
                    return $html;
    		}
    	curl_close($curl);
    }
   
    if ($html != "error") { 
    	// Find all products
        echo "Muchas cosas !\n";
        //var_dump($html);
    	$texto = $html->find('div[id=kfpublished]');
        var_dump($texto);
        
        
    }
    else {
	echo "Error de conexion\n";
    }
#
#  URL de imagenes es:
#  http://supermercado.telemercados.cl/openstore/images/ProductosXL/(idproducto).jpg
#  
#  URL de producto es:
#  https://supermercado.telemercados.cl/fichaproducto/ficha_producto.asp?argProductoId=(idproducto)
#
#  Descripcion de prodcuto es:
#  id="fichadescripcion" en URL producto
#
#  URL de landing producto en supermercado:
#  https://supermercado.telemercados.cl/?IdLayout=13&cargaTabs=1&anclaje=&AtributoId=&lbFicha=1&argProductoId=(idproducto) 
#

?>
