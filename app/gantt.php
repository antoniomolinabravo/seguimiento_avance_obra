<?php
// https://webservice2021.000webhostapp.com/dbtest.php?num=20&format=json
// https://webservice2021.000webhostapp.com/dbtest.php?num=20&format=xml
// https://webservice2021.000webhostapp.com/dbtest.php?num=20&format=arr

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* comprobamos que el usuario nos viene como un parametro */
//if(isset($_GET['user']) && intval($_GET['user'])) {

        /* utilizar la variable que nos viene o establecerla nosotros */
        $number_of_posts = isset($_GET['num']) ? intval($_GET['num']) : 10; //10 es por defecto
        $format = !isset($_GET['format']) ? 'arr' : strtolower($_GET['format']);
        //$format = strtolower($_GET['format']) == 'json' ? 'json' : 'xml'; //xml es por defecto
        //$user_id = intval($_GET['user']); 

        /* conectamos a la bd */
        $link = mysqli_connect('localhost','id15957446_dbuser','Clavedb2021!', 'id15957446_db') or die('No se puede conectar a la BD');
        //mysql_select_db('id15957446_db',$link) or die('No se puede seleccionar la BD');

        /* sacamos los posts de bd */
        $query = "SELECT * FROM GANTT2 ORDER BY ITEM ASC ";
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

        /* formateamos el resultado */
        if($format == 'arr') {
                echo '<pre>';
                print_r($posts);
                echo '</pre>';
        }
        else if($format == 'json') {
                header('Content-type: application/json');
                echo json_encode(array('posts'=>$posts));
        }
        else if($format == 'div') {
                header('Content-type: text/html');  // https://developer.mozilla.org/es/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
			echo '<html> <head> <style> .area_class {background-color: #ff0; border:1px;} </style> <body>';
                foreach($posts as $index => $row) {
						//echo '<',$key,'>';
						//echo '<DIV>', $posts['post'][$key]['AREA'], '_11</div>';
					echo "<DIV style='color:",'green',"' class='area_class' elemento_id='", $row['ITEM'], "'>", $row['ITEM'], ' ', $row['DSC'], ' ', $row['TOTAL_INF'], ' ', $row['P_INICIO'], ' ', $row['P_FIN'], '</div>';
                                        if(is_array($row)) {
                                                foreach($row as $tag => $val) {
                                                        echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
						}
					}
		}
			echo '</body> </html>';
        }
        else {
                header('Content-type: text/xml');
                echo '<posts>';
                foreach($posts as $index => $post) {
                    $exindex = $index;
                        if(is_array($post)) {
                                foreach($post as $key => $value) {
                                        $exkey = $key;
                                        echo '<',$key,'>';
                                        if(is_array($value)) {
                                                foreach($value as $tag => $val) {
                                                        echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
                                                }
                                        }
                                        echo '</',$exkey,'>';
                                }
                        }
                }
                echo '</','posts','>';
        }

        /* nos desconectamos de la bd */
        @mysqli_close($link);
//}
?>