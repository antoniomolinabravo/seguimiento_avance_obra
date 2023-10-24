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
<ul id="menu">

<?php
        /* conectamos a la bd */
        $link = mysqli_connect('localhost','id15957446_dbuser','Clavedb2021!', 'id15957446_db') or die('No se puede conectar a la BD');

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

        /* nos desconectamos de la bd */
        @mysqli_close($link);

$NIVEL = array();
$lenex = 0;

$j=0;
 foreach($posts as $index => $row) {
	$j++;
	$lennx = strlen($posts[$j]['ITEM']);
	$lencu = strlen($row['ITEM']);
	echo $row['ITEM'], ' cu:', $lencu, ' ex:', $lenex, '<br>';
//	echo '<li><a href="#r">', $row['ITEM'], ' ', $row['DSC'], '</a></li>';

//	if($row['TIPO'] == 'H') {
//	if( $lencu == $lenex ) {
	if( $lencu >= $lennx ) {
		echo '<li><a href="#r">', $row['ITEM'], ' ', $row['DSC'], '</a></li>';
	}

//	$TIPO == 'R' || $TIPO == 'C'
//	if( $lencu < $lenex ) {
	if( $lencu > $lennx ) {
//echo 'salio!!!!!!!!!!!<br> size:', sizeof($NIVEL)-1, '<br>';
//                echo '<pre>';
//                print_r($NIVEL);
//                echo '</pre>';

		$lenex = $lencu;
//		for($i=sizeof($NIVEL)-1; $NIVEL[$i]>$lencu; $i--) {
		for($i=sizeof($NIVEL)-1; $NIVEL[$i]>$lennx; $i--) {
echo 'salio CICLO!!!!!!!!!!!<br>';
		  array_pop($NIVEL); //retira el ultimo
		  echo '</ul>';
		  echo '</li>';
		}
		echo '<li><input type="checkbox" name="list" id="', $row['ITEM'], '"><label for="', $row['ITEM'], '">', $row['ITEM'], ' ', $row['DSC'], '</label>';
		echo '<ul class="interior">';
	}


//	if($row['TIPO'] == 'R' || $row['TIPO] == 'C') {
//	if( $lencu > $lenex ) {
	if( $lencu < $lennx ) {
echo 'entro!!!!!!!!!!!<br>';
		array_push($NIVEL, $lencu );
                echo '<pre>';
                print_r($NIVEL);
		echo '<li><input type="checkbox" name="list" id="', $row['ITEM'], '"><label for="', $row['ITEM'], '">', $row['ITEM'], ' ', $row['DSC'], '</label>';
		echo '<ul class="interior">';
	}


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

</body>
</html>