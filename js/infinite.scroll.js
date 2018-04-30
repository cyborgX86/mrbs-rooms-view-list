var time;

$(document).ready(function() {
  if ($("#contain li").length > 2){
      append_clone();
      indicators();
  }
  pageScroll();
  $("#contain").mouseover(function() {
    clearTimeout(time);
  }).mouseout(function() {
    pageScroll();
  });

});

/* append_clone() clona cada elemento de la lista y lo concatena al final de la misma.*/
function append_clone(){
  $("#contain li").each(function(){
    $("#contain li").clone().appendTo("#contain");
    if ($("#contain li").length > 200) //evitamos buble infinito con 100 elementos máx.
      return false; //similar a break.
  });
}

/* pageScroll() activa el scroll de los elementos de la lista.*/
function pageScroll() {

  var objDiv = document.getElementById("contain");
  objDiv.scrollTop = objDiv.scrollTop + 1;
  if (objDiv.scrollTop == (objDiv.scrollHeight - 760)) { //valor=elemento css
      objDiv.scrollTop = 0;
  }
  time = setTimeout('pageScroll()', 100);
}

/* indicators() cambia el estado del indicador de elemento de lista.*/
function indicators() {
  setInterval(function() {
    $('#indicators li.active').removeClass('active').next()
    .add('#indicators li:first').last().addClass('active');
  }, 30000);
}
