<?php

    include("simple_html_dom.php");
    $html= getdom("http://supermercado.telemercados.cl/openstore/asp/LstProdProductos.asp",1041,1045);

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
    	echo "name;title;desc;image;url\n";
    	foreach($html->find('.td_nombre_producto2') as $article) {
    		$link  = $article->find('a', 0)->href;
    		$link_explode = explode("'", $link);
    		parse_str($link_explode[1], $link_var);
    		$title        		= trim($article->find('a', 0)->plaintext);
		$salto   		= array("\r\n", "\n", "\r");
        	$item['title']          = str_replace($salto, " ", $title);
    		$item['idproducto']    	= $link_var['argProductoId'];
    		$item['image']	   	= "https://supermercado.telemercados.cl/openstore/images/ProductosXL/".$link_var['argProductoId'].".jpg"; 
    		$item['url']	   	= "https://supermercado.telemercados.cl/fichaproducto/ficha_producto.asp?argProductoId=".$link_var['argProductoId'];
        	$html_product      	= getdom($item['url']);
        	$item['desc']	   	= trim($html_product->find('#fichadescripcion',0)->plaintext); 
        	$item['name']           = trim($html_product->find('.fp_pp_nombre_producto',0)->plaintext);
		$price           	= trim($html_product->find('.fp_pp_det_tabla_carac',0)->plaintext);
        	$price 			= substr($price, 2);
        	$item['price']          = substr($price, 0, -6);
    		$articles[] = $item;
        	echo $item['name'].";".$item['title'].";".$item['desc'].";".$item['image'].";".$item['url']."\n";
    	}
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
