<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

        /* utilizar la variable que nos viene o establecerla nosotros */
        $number_of_posts = isset($_GET['num']) ? intval($_GET['num']) : 10; //10 es por defecto
        $item = !isset($_GET['item']) ? 'L' : strtoupper($_GET['item']);
        $format = !isset($_GET['format']) ? 'html' : strtolower($_GET['format']);
        //$format = strtolower($_GET['format']) == 'json' ? 'json' : 'xml'; //xml es por defecto
        //$user_id = intval($_GET['user']); 

        /* conectamos a la bd */
        $link = mysqli_connect('localhost','id15957446_dbuser','Clavedb2021!', 'id15957446_db') or die('No se puede conectar a la BD');

        /* sacamos los posts de bd */
        $query = "SELECT * FROM GANTT2 ORDER BY ITEM ASC ";
        $query = "SELECT G.ITEM, G.DSC, G.TOTAL_INF, A.MONTO_AVANCE, A.PORC_AVANCE FROM GANTT2 G LEFT JOIN AVANCE A ON A.ITEM = G.ITEM ORDER BY G.ITEM ASC";
        $query = "SELECT G.ITEM, G.DSC, G.TOTAL_INF, A.MONTO_AVANCE,  (A.MONTO_AVANCE/G.TOTAL_INF*100) AS PORC_AVANCE FROM GANTT2 G LEFT JOIN AVANCE_GANTT_SEG A ON A.ITEM = G.ITEM ORDER BY G.ITEM ASC";
        $query = "SELECT G.ITEM, G.DSC, G.TOTAL_INF, S.COSTO, A.MONTO_AVANCE, ROUND(A.MONTO_AVANCE/S.COSTO*100, 2) AS PORC_AVANCE FROM GANTT2 G LEFT JOIN GANTT_SEG S ON G.ITEM = S.ITEM  LEFT JOIN AVANCE_GANTT_SEG A ON A.ITEM = G.ITEM ORDER BY G.ITEM ASC";

        $query = "SELECT E.ELEMENTO_ID as ITEM, E.DSC, S.COSTO, A.MONTO_AVANCE, ROUND(A.MONTO_AVANCE/S.COSTO*100, 2) AS PORC_AVANCE FROM ELEMENTO E LEFT JOIN ELEM_SEG S ON E.ELEMENTO_ID = S.ID  LEFT JOIN AVANCE_ELEM_SEG A ON A.ELEMENTO_ID = E.ELEMENTO_ID ORDER BY E.ELEMENTO_ID ASC";
        $query = "select R.ELEMENTO_ID, R.dsc_elem, R.REL_MONTO, A.PORC_AVANCE, A.MONTO_AVANCE, A.OBS, A.ESTADO
from RELACION R
left join ELEMENTO E
on E.ELEMENTO_ID = R.ELEMENTO_ID
left join AVANCE A
on A.ELEMENTO_ID = R.ELEMENTO_ID
and A.ITEM = R.ITEM
WHERE R.ITEM = '".$item."'
ORDER BY R.ELEMENTO_ID
 ";
        $query = "select A.ELEMENTO_ID, E.DSC, 0 EN_MONTO, A.PORC_AVANCE, A.MONTO_AVANCE, A.OBS, A.ESTADO
from AVANCE A
left join ELEMENTO E
on E.ELEMENTO_ID = A.ELEMENTO_ID
WHERE A.ITEM = '".$item."'
ORDER BY A.ELEMENTO_ID DESC
 ";

        $result = mysqli_query($link, $query) or die('Query no funcional:  '.$query);

        /* creamos el array con los datos */
        $posts = array();
        if(mysqli_num_rows($result)) {
		$i=0;
                while($post = mysqli_fetch_assoc($result)) {
                        // $posts[] = array('post'=>$post);
                        $posts[$i++] = $post;
                }
        }

		//echo '<pre>',$query,'</pre><br>';
		//echo $i;

        /* nos desconectamos de la bd */
        @mysqli_close($link);

// https://htmlcolorcodes.com/es/		
       if($format == 'html') {
				$head=true;		
				$cols = 7;
                header('Content-type: text/html');  // https://developer.mozilla.org/es/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
				echo '<html> <head> <style> th {background-color: #D7CDCB; border:1px;} </style> <body> <table>';
                foreach($posts as $index => $row) {
					if(is_array($posts)) {
						if($head){ $head=false;
							echo "<TR>";
							$i=0;
							foreach($row as $tag => $val) {
								if($i < $cols) echo '<th>',$tag,'</th>';
								$i++;
							}
							echo "</TR>";
						}

												//        foreach($post as $key => $value) {
									//echo '<',$key,'>';
									//echo '<DIV>', $posts['post'][$key]['AREA'], '_11</div>';
									echo "<TR>";
//													echo "<TD>", $post[$key]['AREA'], ' ', $post[$key]['DSC'], '</TD>';
    //                                    if(is_array($value)) {
                                                
												$i=0;
                                                foreach($row as $tag => $val) {
													//echo "<TD>", $post[$key]['AREA'], ' ', $post[$key]['DSC'], '</TD>';
                                                        //echo '<td>',htmlentities($val),'</td>';
														if($i==0) { echo '<td><a href="vista3.php?elem='.$val.'">',$val,'</a></td>'; }
                                                        else if($i < $cols) { echo '<td>',$val,'</td>'; }
														$i++;
 												}
		//								}
									echo "</TR>";
	//							}
					}
				}
				echo '</table></body> </html>';
	   }
	   

       if($format == 'csv') {
				$head=true;		
                header('Content-type: application/vnd.ms-excel');  // https://developer.mozilla.org/es/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
                foreach($posts as $index => $row) {
					if(is_array($posts)) {
						if($head){ $head=false;
							foreach($row as $tag => $val) {
									echo '"',$tag,'";';
							}
							echo "\n";
						}
						$i=0;
						foreach($row as $tag => $val) {
							if($i==0) { echo '"',$val,'";'; }
							else { echo '"',str_replace(".",",",$val),'";'; }
							$i++;
						}
						echo "\n";
					}
				}
	   }
	   
				
?>