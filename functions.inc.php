<?php

/* qry() devuelve consulta sql de la lista de reservas tipo.*/

function qry($date, $dateEnd, $room1, $room2, $room3){

global $connection;

$sql = "SELECT mrbs_entry.name,mrbs_entry.description,from_unixtime(mrbs_entry.start_time),
        from_unixtime(mrbs_entry.end_time),mrbs_entry.room_id,mrbs_room.room_name
          FROM mrbs_entry INNER JOIN mrbs_room ON mrbs_entry.room_id = mrbs_room.id
        WHERE (from_unixtime(mrbs_entry.start_time) BETWEEN '$date' AND '$dateEnd')
          AND (mrbs_entry.room_id = '$room1' OR mrbs_entry.room_id = '$room2' OR
          mrbs_entry.room_id = '$room3')
        ORDER BY from_unixtime(mrbs_entry.start_time);";

$qry = mysqli_query($connection, $sql);
return $qry;
}

/* qryState() devuelve la última fila de la consulta sql de reservas tipo para
evaluar si existen reservas (ocupación).*/

function qryState($date, $dateEnd, $room1, $room2, $room3){

global $connection;

$sql = "SELECT mrbs_entry.name,mrbs_entry.description,from_unixtime(mrbs_entry.start_time),
        from_unixtime(mrbs_entry.end_time),mrbs_entry.room_id,mrbs_room.room_name
          FROM mrbs_entry INNER JOIN mrbs_room ON mrbs_entry.room_id = mrbs_room.id
        WHERE (from_unixtime(mrbs_entry.start_time) BETWEEN '$date' AND '$dateEnd')
          AND (mrbs_entry.room_id = '$room1' OR mrbs_entry.room_id = '$room2' OR
          mrbs_entry.room_id = '$room3')
        ORDER BY from_unixtime(mrbs_entry.start_time) DESC LIMIT 1;";

$qry = mysqli_query($connection, $sql);
return $qry;
}

/* ocupationState() evalúa si existen o no reservas en función de la fecha y hora
del sistema.*/

function occupationState($qryState){

  global $date;
   global $time;

  $row = mysqli_fetch_array($qryState);
  $dateBookingEnd = substr($row[3],0,10);
  $timeBookingEnd = substr($row[3],11,5);

  if (mysqli_num_rows($qryState) == 0){
    return 0;
  }else{
    if ( ($dateBookingEnd > $date) || ($timeBookingEnd >= $time) ){
      return 1;
    }else{
      return 0;
    }
  }
}

/* printOccupationTable() devuelve la lista de reservas en función de la hora del
sistema.*/

 function printOccupationTable($qry){

  global $date;
  global $time;

  echo '<div><ul id=contain>';

  while($row = mysqli_fetch_array($qry)) { //$row debe estar definida aquí.
    $dateBookingEnd = substr($row[3],0,10);
    $timeBookingEnd = substr($row[3],11,5);

    if ( ($dateBookingEnd > $date) || ($timeBookingEnd >= $time) ){
      echo '<li><div class="box"><table><tr>
            <th class="highlight" width="25%">Día ' . substr($row[3],8,2). '-' . substr($row[3],5,2).
            ' de ' . substr($row[2],11,5) . ' a '  . substr($row[3],11,5) . '</th>
            <th></th></tr>
            <tr><th colspan="2">' . utf8_encode ($row['name']) . '</th></tr>
            <tr><th class="highlight">' . utf8_encode ($row['room_name']);

      if (empty ($row['description'])){
        echo '<tr><td colspan="2">No existe información detallada para esta actividad
              </td></tr></table></li>';
      }else{
        echo '<tr><td colspan="2">' . utf8_encode ($row['description']) .
             '</td></tr></table></div></li>';
      }
    }
  }
  echo '</ul></div>';
}

 /*getIndicators() obtiene el número de elementos de lista, generando un elemento
 <li> para cada uno de ellos dentro de la clase css "indicartors".*/

function getIndicators($qry){
  global $date;
  global $time;

  echo '<div><ul id="indicators" class="indicators">
        <li class="active"><em></em></li>';

  $array_qry = array(); //array de reservas pendientes array_push($array_qry, $row).
  while($row = mysqli_fetch_array($qry)) { //$row debe estar definida aquí.
    $dateBookingEnd = substr($row[3],0,10);
    $timeBookingEnd = substr($row[3],11,5);
    if ( ($dateBookingEnd > $date) || ($timeBookingEnd >= $time) ){
      array_push($array_qry, $row);
    }
  }
  //Se imprime la lista de elementos (<li> - 1).
  for($i=0; $i<(count($array_qry) - 1); $i++){
    echo '<li><em></em></li>';
  }
  echo '</ul></div>';
}
?>
