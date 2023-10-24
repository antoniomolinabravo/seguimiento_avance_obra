<html>
<head>
<style>
.lnk {
  cursor: pointer;
  display:block;
  background-color: #ccc;
  color: #000;
  text-decoration:none;
  padding:10px;
  margin-bottom:1px;
}
.lnk:hover {
  background-color: #ddd;
}
.lnkNO {
  display:block;
  background-color: #444;
  color: #000;
  text-decoration:none;
  padding:10px;
  margin-bottom:1px;
}
</style>
</head>
<body>
<div class='sep-mini'></div>
<div class='grp' >CREADA GANTT</div>
<div class='sep-mini'></div>
<div class='lnkNO' _onclick='call(this)' ref='GRT'>TRUCAR RESUMEN GANTT</div>
<div class='lnkNO' _onclick='call(this)' ref='GRG'>GENERAR RESUMEN GANTT</div>
<div class='sep-mini'></div>
<div class='lnk' onclick='call(this)' ref='GST'>TRUCAR SEGMENTOS</div>
<div class='lnk' onclick='call(this)' ref='GSG'>GENERAR SEGMENTOS</div>
<div class='lnk' onclick='call(this)' ref='GSU'>ACTUALIZAR SEGMENTOS</div>
<div class='sep-medio'></div>
<div class='grp' >CREADOS ELEMENTOS</div>
<div class='sep-mini'></div>
<div class='lnk' onclick='call(this)' ref='RT'>TRUCAR RELACION</div>
<div class='lnk' onclick='call(this)' ref='RG'>GENERAR RELACION</div>
<div class='lnk' onclick='call(this)' ref='RUP1'>ACTUALIZA RELACION PASO 1</div>
<div class='lnk' onclick='call(this)' ref='RUP2'>ACTUALIZA RELACION PASO 2</div>
<div class='lnk' onclick='call(this)' ref='RUP3'>ACTUALIZA RELACION PASO 3</div>
<div class='lnk' onclick='call(this)' ref='EU'>ACTUALIZAR COSTOS ELEM</div>
<div class='sep-mini'></div>
<div class='lnk' onclick='call(this)' ref='ERT'>TRUCAR RESUMEN ELEM</div>
<div class='lnk' onclick='call(this)' ref='ERG'>GENERAR RESUMEN ELEM</div>
<div class='sep-mini'></div>
<div class='lnk' onclick='call(this)' ref='EST'>TRUCAR SEGMENTOS ELEM</div>
<div class='lnk' onclick='call(this)' ref='ESG'>GENERAR SEGMENTOS ELEM</div>
<div class='lnk' onclick='call(this)' ref='ESU'>ACTUALIZA SEGMENTOS ELEM</div>
<div class='sep-medio'></div>
<div class='grp' >CREADOS REGISTROS</div>
<div class='sep-mini'></div>
<div class='lnk' onclick='call(this)' ref='AT'>TRUCAR AVANCE</div>
<div class='lnk' onclick='call(this)' ref='AG'>GENERAR AVANCE</div>
<div class='lnk' onclick='call(this)' ref='AU'>ACTUALIZAR AVANCE</div>
<div class='sep-mini'></div>
<div class='lnk' onclick='call(this)' ref='AGT'>TRUCAR SEGMENTOS AVC.GANTT</div>
<div class='lnk' onclick='call(this)' ref='AGG'>GENERAR SEGMENTOS AVC.GANTT</div>
<div class='lnk' onclick='call(this)' ref='AGU'>ACTUALIZA SEGMENTOS AVC.GANTT</div>
<div class='sep-mini'></div>
<div class='lnk' onclick='call(this)' ref='AET'>TRUCAR SEGMENTOS AVC.ELEM</div>
<div class='lnk' onclick='call(this)' ref='AEG'>GENERAR SEGMENTOS AVC.ELEM</div>
<div class='lnk' onclick='call(this)' ref='AEU'>ACTUALIZA SEGMENTOS AVC.ELEM</div>
<div></div>

<script>
/*
$(function() {
  $(".lnk").click( function() {
   //  window.location.href='?fnc='.$(this).find("div").attr("ref");
   alert('');
//   window.location.href='?fnc=' . $(this).attr("ref");
  });
});
*/
</script>

<script>
function call(d) {
  window.location.href='?fnc=' + d.attributes.ref.value;
}
</script>


<?php
        /* utilizar la variable que nos viene o establecerla nosotros */
        $number_of_posts = isset($_GET['num']) ? intval($_GET['num']) : 10; //10 es por defecto
        $fnc = !isset($_GET['fnc']) ? '' : strtoupper($_GET['fnc']);
        $format = !isset($_GET['format']) ? 'html' : strtolower($_GET['format']);
        //$format = strtolower($_GET['format']) == 'json' ? 'json' : 'xml'; //xml es por defecto
        //$user_id = intval($_GET['user']); 

		//echo $fnc;
		
		
		switch($fnc) {
/*NUEVO*/		case 'GU' : // Actualiza la columna ORD para ordenar los items
					$query = "update GANTT2 G
					, GANTT_ORD O
					set G.ORD = O.ORD
					WHERE G.ITEM = O.ITEM ";
					break;
		case 'GRT' : break; $query = "TRUNCATE TABLE RES_GANTT ";
					break;
		case 'GRG' : break; $query = "insert into RES_GANTT (I1, I2, DSC, MONTO, INI, FIN) 
					SELECT I1, I2, DSC, SUM(TOTAL_INF), MIN(P_INICIO), MAX(P_FIN)
					FROM GANTT2 
					GROUP BY I2 ";
					break;
					
		case 'GST' : $query = "TRUNCATE TABLE GANTT_SEG ";
					break;
		case 'GSG' :  // no se inserta ya que tiene otros campos 
					$query = "insert into GANTT_SEG (ITEM, TIPO, COSTO)
					select t1.ITEM, '', sum(t2.TOTAL_INF)  as COSTO_TOT
					FROM GANTT t1, GANTT t2
					WHERE t2.ITEM LIKE CONCAT(t1.ITEM, '%')  
					GROUP BY t1.ITEM ";
					break;
/*NUEVO*/		case 'GSU' : // ACTUALIZADO --> ITEMS para todos los casos
					$query = "update  GANTT_SEG as G
					,(select t1.ORD, t1.ITEM as ID, sum(t2.TOTAL_INF)  as COSTO_TOT
					FROM GANTT2 t1, GANTT2 t2
					WHERE t2.ORD LIKE CONCAT(t1.ORD, '%')  
					GROUP BY t1.ORD )  as R
					set G.COSTO = R.COSTO_TOT
					WHERE R.ID = G.ITEM ";
					break;
					
		case 'RT' : $query = "TRUNCATE TABLE RELACION ";
					break;
		case 'RG' : // para crear relacion
					$query ="insert into RELACION ( `ITEM`, I1, I2, `ELEMENTO_ID`, E1, `TOTAL_INF`, `UNI_LINEA`, `CNT_LINEA`, `MONTO_LINEA`, `REL_LINEAS`, `REL_PORC_SUM`, `REL_MONTO`, `PORC_APL`, `dsc_item`, `dsc_elem`) 
					select G.ITEM, G.I1, G.I2, E.ELEMENTO_ID, '' as E1, G.TOTAL_INF, G.UNI_LINEA, G.CNT_LINEA, G.MONTO_LINEA, 0,0,0, E.PORC_APL, G.DSC as dsc_item, E.DSC as dsc_elem 
					from GANTT2 as G 
					inner join ELEMENTO as E 
					ON G.CONTEXTO LIKE concat('%',E.CONTEXTO,'%') 
					AND E.CONTEXTO <> ''
					WHERE G.TOTAL_INF >0 
					order by G.ITEM, E.ELEMENTO_ID asc ";
					break;
		
/*NUEVO*/		case 'RUP1' : // se debe corregir el piso 0 y 1 para los casos que tienen 100% aplicabilidad como escalera, barandas, muebles, D.7.1 C.EX.4 C.EX.15 C.7.1 B.7.1      (25 lin)
							// cambiar a 0.5 con los ciclos
					$query ="UPDATE RELACION
					SET PORC_APL = 0.5
					WHERE ITEM in ('D.7.1', 'C.EX.4', 'C.EX.15', 'C.7.1', 'B.7.1' )
					and PORC_APL <0.5 ";
					break;
/*NUEVO*/		case 'RUP2' : //							// actualiza la cantidad de lineas elemento por item gantt
					$query ="update RELACION R1
					, (select ITEM, count(*) as cantidad, sum(PORC_APL) as suma  from RELACION R2 group by ITEM ) as R2
					SET R1.REL_LINEAS = R2.cantidad 
					,R1.REL_PORC_SUM = R2.suma 
					where R1.ITEM = R2.ITEM ";
					break;
/*NUEVO*/		case 'RUP3' : // actualiza el monto de la linea elemento para un item gantt
					$query ="update RELACION
					SET REL_MONTO = TOTAL_INF / REL_PORC_SUM * PORC_APL
					where REL_PORC_SUM > 0 ";
					break;

		case 'ERT' : $query = "TRUNCATE TABLE RES_ELEM_COSTO ";
					break;
		case 'ERG' : // crea el costo total del ELEMENTO  
					$query = "insert into RES_ELEM_COSTO  (ELEM, costo) 
					select SUBSTR(ELEMENTO_ID,1,3) AS ELEM, sum(REL_MONTO) as suma 
					FROM RELACION 
					GROUP BY SUBSTR(ELEMENTO_ID,1,3)  ";			
					break;
					
		case 'EST' : $query = "TRUNCATE TABLE ELEM_SEG ";
					break;
		case 'ESG' :  // OJO    REQUIERE REVISION   hay otros campos       PASO A PASO ELEMENTOS para todos los siguientes casos =>> 1,3,5,6,8,9,10
					$query = "insert into ELEM_SEG (ID_S, ID, COSTO)
					select t1.ELEMENTO_ID ID_S, t1.ELEMENTO_ID as ID, sum(t2.REL_MONTO)  as COSTO_TOT
					FROM ELEMENTO t1, RELACION t2
					WHERE t2.ELEMENTO_ID LIKE CONCAT(t1.ELEMENTO_ID, '%')  
					GROUP BY t1.ELEMENTO_ID  ";
					break;
/*NUEVO*/		case 'ESU' :  // OJO  ES PARCIAL              PASO A PASO ELEMENTOS para todos los siguientes casos =>> 1,3,5,6,8,9,10
					$query = "update  ELEM_SEG as E
					,(select SUBSTR(ELEMENTO_ID,1,3) AS ELEM, sum(REL_MONTO) as REL_MONTO_SUMA
					FROM RELACION
					GROUP BY SUBSTR(ELEMENTO_ID,1,3) )  as R
					set E.COSTO = R.REL_MONTO_SUMA
					WHERE R.ELEM = E.ID
					AND CHAR_LENGTH(R.ELEM) = 3 ";
					
					// forma temporal, no abarca todo, pero actualiza de un solo SQL
					$query = "update ELEM_SEG S
					, (select t1.ELEMENTO_ID as ID, sum(t2.REL_MONTO)  as COSTO_TOT
					FROM ELEMENTO t1, RELACION t2
					WHERE t2.ELEMENTO_ID LIKE CONCAT(t1.ELEMENTO_ID, '%')  
					GROUP BY t1.ELEMENTO_ID ) as R
					SET S.COSTO = R.COSTO_TOT
					WHERE S.ID = R.ID";
					break;

		case 'EU' : // #se agregan dos columnas para pre calcular los costos de los elementos y su participacion
					$query = "UPDATE ELEMENTO E
					, (select R.ELEMENTO_ID, SUM(R.REL_MONTO) COSTO, (SUM(R.REL_MONTO)/P.MONTO_TOTAL) PORC
					from RELACION R,
					PROYECTO P
					group by R.ELEMENTO_ID) R
					SET E.COSTO = R.COSTO
					, E.COSTO_PORC = R.PORC
					WHERE E.ELEMENTO_ID = R.ELEMENTO_ID ";
					break;
	
		case 'AT' : $query = "TRUNCATE TABLE AVANCE ";
					break;
		case 'AG' :  // insert desde relacion para quedar simil a registro
					$query = "INSERT INTO AVANCE 
					(FECHA, ITEM, I1, I2, ELEMENTO_ID, E1, PORC_AVANCE, MONTO_AVANCE, OBS, ESTADO)
					SELECT NOW(), ITEM, I1, I2, ELEMENTO_ID, E1, 0,0, '', ''  FROM RELACION	";
					break;
		case 'AU' : // actualiza avance desde el ultimo registro de cada item,elemento
					$query = "UPDATE AVANCE as A
					,( select D.*
					from REGISTRO as D
					,(select ITEM, ELEMENTO_ID, max(FECHA) as FECHA
					from REGISTRO
					group by ITEM, ELEMENTO_ID) as F
					where D.ITEM = F.ITEM
					and D.ELEMENTO_ID = F.ELEMENTO_ID
					and D.FECHA = F.FECHA ) as R
					SET A.MONTO_AVANCE = R.MONTO_AVANCE
					, A.PORC_AVANCE = R.PORC_AVANCE
					, A.FECHA = R.FECHA
					, A.OBS = R.OBS
					, A.ESTADO = R.ESTADO
					where A.ITEM = R.ITEM
					and A.ELEMENTO_ID = R.ELEMENTO_ID ";
					break;
					
		case 'AGT' : $query = "TRUNCATE TABLE AVANCE_GANTT_SEG ";
					break;
		case 'AGG' : // crea a partir de GANTT
					$query = "insert into AVANCE_GANTT_SEG  (ITEM, MONTO_AVANCE ) 
					select t1.ITEM as ID, sum(t2.MONTO_AVANCE) as AVANCE_TOT 
					FROM GANTT2 t1, AVANCE t2 
					WHERE t2.ITEM LIKE CONCAT(t1.ITEM, '%') 
					AND t2.MONTO_AVANCE > 0
					GROUP BY t1.ITEM ";
					break;
/*NUEVO*/		case 'AGU' : // actualiza la segregacion del avance por item 
					$query = "update  AVANCE_GANTT_SEG as G
					,(select t1.ITEM as ID, sum(t2.MONTO_AVANCE)  as AVANCE_TOT
					FROM GANTT2 t1, AVANCE t2
					WHERE t2.ITEM LIKE CONCAT(t1.ITEM, '%')  
					GROUP BY t1.ITEM  )  as R
					set G.MONTO_AVANCE = R.AVANCE_TOT
					WHERE G.ITEM = R.ID "; 
					break;
			
		case 'AET' : $query = "TRUNCATE TABLE AVANCE_ELEM_SEG ";
					break;
		case 'AEG' : // crea a partir de AVANCE
					$query = "insert into AVANCE_ELEM_SEG (ELEMENTO_ID, MONTO_AVANCE)
					select t1.ID, sum(t2.MONTO_AVANCE)  as AVANCE_TOT
					FROM ELEM_SEG t1, AVANCE t2
					WHERE t2.ELEMENTO_ID LIKE CONCAT(t1.ID, '%') 
					GROUP BY t1.ID ";

					$query = "insert into AVANCE_ELEM_SEG (ID, ELEMENTO_ID, MONTO_AVANCE)
					select t1.ID, t1.ID, sum(t2.MONTO_AVANCE)  as AVANCE_TOT
					FROM ELEM_SEG t1, AVANCE t2
					WHERE t2.ELEMENTO_ID LIKE CONCAT(t1.ID, '%') 
       				and t2.MONTO_AVANCE >0
					GROUP BY t1.ID";
					break;
/*NUEVO*/		case 'AEU' : // actualiza la segregacion del avance por elemento
					$query = "update  AVANCE_ELEM_SEG as S
					,(select t1.ELEMENTO_ID as ID, sum(t2.MONTO_AVANCE)  as AVANCE_TOT
					FROM ELEMENTO t1, AVANCE t2
					WHERE t2.ELEMENTO_ID LIKE CONCAT(t1.ELEMENTO_ID, '%') 
					GROUP BY t1.ELEMENTO_ID  )  as R
					set S.MONTO_AVANCE = R.AVANCE_TOT
					WHERE S.ELEMENTO_ID = R.ID ";
					break;
		default : $query="";
		}
//			echo $query;
		if($query!="") {
			/* conectamos a la bd */
			$link = mysqli_connect('localhost','id15957446_dbuser','Clavedb2021!', 'id15957446_db') or die('No se puede conectar a la BD');

			/* sacamos los posts de bd */
			//$query = "SELECT ";
			echo $query;
			$result = mysqli_query($link, $query) or die('Query no funcional:  '.$query);

			/* creamos el array con los datos */
			$posts = array();
			/*
			if(mysqli_num_rows($result)) {
			$i=0;
					while($post = mysqli_fetch_assoc($result)) {
							// $posts[] = array('post'=>$post);
							$posts[$i++] = $post;
					}
			}
			*/
echo "<br>".$result."LISTO!!!";
			/* nos desconectamos de la bd */
			@mysqli_close($link);
		}
?>

</body>


</html>
