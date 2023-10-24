<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<html>
<head>
	<title>Gantt</title>
	<link rel="stylesheet" type="text/css" href="forma.css">
</head>
<body>
<pre>
<ul id="menu">

<?php
        /* conectamos a la bd */
        $link = mysqli_connect('localhost','id15957446_dbuser','Clavedb2021!', 'id15957446_db') or die('No se puede conectar a la BD');

        /* sacamos los posts de bd */
        $query = "SELECT * FROM GANTT2 ORDER BY ITEM ASC ";
        $query = "SELECT G.ITEM, G.DSC, G.TOTAL_INF, A.MONTO_AVANCE, A.PORC_AVANCE FROM GANTT2 G LEFT JOIN AVANCE A ON A.ITEM = G.ITEM ORDER BY G.ITEM ASC";
        $query = "SELECT G.ITEM, G.DSC, G.TOTAL_INF, A.MONTO_AVANCE,  (A.MONTO_AVANCE/G.TOTAL_INF*100) AS PORC_AVANCE FROM GANTT2 G LEFT JOIN AVANCE_GANTT_SEG A ON A.ITEM = G.ITEM ORDER BY G.ITEM ASC";
        $query = "SELECT G.ITEM, G.DSC, G.TOTAL_INF, S.COSTO, A.MONTO_AVANCE, ROUND(A.MONTO_AVANCE/S.COSTO*100, 2) AS PORC_AVANCE FROM GANTT2 G LEFT JOIN GANTT_SEG S ON G.ITEM = S.ITEM  LEFT JOIN AVANCE_GANTT_SEG A ON A.ITEM = G.ITEM ORDER BY G.ITEM ASC";
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

        /* nos desconectamos de la bd */
        @mysqli_close($link);

$NIVEL = array();
array_push($NIVEL, 0 );
$lenex = 0;

$j=0;
 foreach($posts as $index => $row) {
	$j++;
//	$lennx = (sizeof($posts)>=$j)? strlen($posts[$j]['ITEM']):strlen($posts[$j]['ITEM']);
$lennx = isset($posts[$j]['ITEM'])? substr_count($posts[$j]['ITEM'], '.') : 0;
//	$lennx = substr_count($posts[$j]['ITEM'], '.'); // (sizeof($posts)>=$j)? strlen($posts[$j]['ITEM']):strlen($posts[$j]['ITEM']);
	$lencu = substr_count($row['ITEM'], '.'); //    strlen($row['ITEM']);
	$text = $row['PORC_AVANCE'] .'%'; //$row['ITEM']. ' ex:'. $lenex. ' cu:'. $lencu. ' nx:'. $lennx;
	$alt = '('. $row['MONTO_AVANCE'] .'/'. $row['COSTO'] .')';  // alt o title
//	echo $row['ITEM'], ' cu:', $lencu, ' ex:', $lenex, ' nx:', $lennx, '<br>';
//	echo '<li><a href="#r">', $row['ITEM'], ' ', $row['DSC'], '</a></li>';

//	if($row['TIPO'] == 'H') {
//	if( $lencu == $lenex ) {  //HOJA esta y la prox (mismo nivel)
	if( $lencu == $lennx ) {
		echo '<li><a href="#r">', $text, ' ', $row['ITEM'], ' ', $row['DSC'], '</a></li>';
		//echo '<li><a>HOJA --> ', $text, ' ', $row['ITEM'], ' ', $row['DSC'], '</a></li>';
	}

//	$TIPO == 'R' || $TIPO == 'C'
//	if( $lencu < $lenex ) {
	if( $lencu > $lennx ) {	// HOJA esta, la proxima es rama (nivel menor)
		echo '<li><a href="#r">', $text, ' ', $row['ITEM'], ' ', $row['DSC'], '</a></li>';
//		echo '<li><a>HOJA prox rama --> ', $text, ' ', $row['ITEM'], ' ', $row['DSC'], '</a></li>';
//		$lenex = $lencu;
//echo 'salio!!!!!!!!!!!    size:', sizeof($NIVEL)-1, '<br>';
//                echo '<pre>';
//                print_r($NIVEL);
//                echo '</pre>';
//		for($i=sizeof($NIVEL)-1; $NIVEL[$i]>$lencu; $i--) {
		//$i=(sizeof($NIVEL)>0)?sizeof($NIVEL)-1:0;
//		for($i=sizeof($NIVEL)-1; $NIVEL[$i] > $lennx; $i--) {
		for($i=$lencu; $i > $lennx; $i--) {
		  array_pop($NIVEL); //retira el ultimo
		  echo '</ul>';
		  echo '</li>';
//echo 'salio CICLO!!!!!!!!!!!<br>';
		}
	//	  echo '</ul>';
	//	  echo '</li>';
	}


//	if($row['TIPO'] == 'R' || $row['TIPO] == 'C') {
//	if( $lencu > $lenex ) {
	if( $lencu < $lennx ) {	// RAMA esta, la proxima es mayor nivel
//echo 'entro!!!!!!!!!!!<br>';
		array_push($NIVEL, $lencu );
//		$lenex = $lencu;
//                echo '<pre>';
//                print_r($NIVEL);
//                echo '</pre>';
//		echo '<li><a>RAMA --> ', $text, ' ', $row['ITEM'], ' ', $row['DSC'], '</a>';
		echo '<li><input type="checkbox" name="list" id="', $row['ITEM'], '"><label for="', $row['ITEM'], '">', $text, ' ', $row['ITEM'], ' ', $row['DSC'], '</label>';
		echo '<ul class="interior">';
	}

	$lenex = $lencu;
 } //foreach

  echo '</ul>';
  echo '</li>';
  echo '</ul>';

/*
select order by ID

len($ID) > len($ID_prev)
$TIPO == 'R' || $TIPO == 'C'
$NIVEL[len($NIVEL)] = len($ID);   // array_push($NIVEL, len($ID) );
echo '<li><input type="checkbox" name="list" id="nivel1-1"><label for="nivel1-1">Nivel 1</label>';
echo '<ul class="interior">';

$TIPO == 'H'
echo '<li><a href="#r">Nivel 3</a></li>';

len($ID) < len($ID_prev)
$TIPO == 'R' || $TIPO == 'C'
for(i=len($NIVEL)-1; $NIVEL[i]<=len($ID) ;i--) {
  array_pop($NIVEL); //retira el ultimo
  echo '</ul>';
  echo '</li>';
}

ID		TIPO	NIVEL (len)
A		R	1
A.1		C	3
A.1.2		R	5
A.1.2.1		H	7
A.1.2.2		H	7
A.1.2.3		H	7
B		R	1
*/
?>
</pre>
</body>
</html>