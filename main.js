$(document).ready(function(){
    tablaPersonas = $("#tablaPersonas").DataTable({
       "columnDefs":[{
        "targets": -1,
        "data":null,
        "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-primary btnEditar'>Editar</button><button class='btn btn-danger btnBorrar'>Borrar</button></div></div>"  
       }],
        
    "language": {
            "lengthMenu": "Mostrar _MENU_ reserves",
            "zeroRecords": "No s'han trobat resultats",
            "info": "Mostrant reserves del _START_ al _END_ de un total de _TOTAL_ reserves",
            "infoEmpty": "Mostrant reserves del 0 al 0 d'un total de 0 resrves",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primer",
                "sLast":"Últim",
                "sNext":"Següent",
                "sPrevious": "Anterior"
             },
             "sProcessing":"Processant...",
        }
    });
    
$("#btnNuevo").click(function(){
    $("#formPersonas").trigger("reset");
    $(".modal-header").css("background-color", "#1cc88a");
    $(".modal-header").css("color", "white");
    $(".modal-title").text("Nova Reserva");            
    $("#modalCRUD").modal("show");        
    id=null;
    opcion = 1; //alta
});    
    
var fila; //capturar la fila para editar o borrar el registro
    

//botón EDITAR    
$(document).on("click", ".btnEditar", function(){
    
    fila = $(this).closest("tr");
    id = parseInt(fila.find('td:eq(0)').text());
    nom = fila.find('td:eq(1)').text();
    apellidos = fila.find('td:eq(2)').text();
    email = fila.find('td:eq(3)').text();
    telefon = fila.find('td:eq(4)').text();
    persones = parseInt(fila.find('td:eq(5)').text());
    dataBD = fila.find('td:eq(6)').text();
    data=new Date(dataBD);

    hora = fila.find('td:eq(7)').text();

    console.log(dataBD,data);

    $("#nom").val(nom);
    $("#apellidos").val(apellidos);
    $("#email").val(email);
    $("#telefon").val(telefon);
    $("#persones").val(persones);
    $("#data").val(data);
    $("#hora").val(hora);
    opcion = 2; //editar
    
    $(".modal-header").css("background-color", "#4e73df");
    $(".modal-header").css("color", "white");
    $(".modal-title").text("Editar Persona");            
    $("#modalCRUD").modal("show");  
    
});

//botón BORRAR
$(document).on("click", ".btnBorrar", function(){    
    fila = $(this);
    id = parseInt($(this).closest("tr").find('td:eq(0)').text());
    opcion = 3 //borrar
    Swal.fire({
      title: "Eliminar Reserva",
      text: "¿Estas segur d'eliminar la reserva: "+id+"?",
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, eliminar!',
      icon: 'question',
    }).then((result) => {
    if(result.isConfirmed){
        $.ajax({
            url: "bd/crud.php",
            type: "POST",
            dataType: "json",
            data: {opcion:opcion, id:id},
            success: function(){
                tablaPersonas.row(fila.parents('tr')).remove().draw();
            }
        });
    } 
   })
});


$("#formPersonas").submit(function(e){
    e.preventDefault();    
    nom = $.trim($("#nom").val());
    apellidos = $.trim($("#apellidos").val());
    email = $.trim($("#email").val()); 
    telefon = $.trim($("#telefon").val()); 
    persones = $.trim($("#persones").val()); 
    data = $.trim($("#data").val()); 
    hora = $.trim($("#hora").val()); 
    $.ajax({
        url: "bd/crud.php",
        type: "POST",
        dataType: "json",
        data: {nom:nom, apellidos:apellidos, email:email, telefon:telefon, persones:persones, data:data, hora:hora, id:id, opcion:opcion},
        success: function(data){  
            console.log(data);
            id = data[0].id;            
            nom = data[0].nombre;
            apellidos = data[0].pais;
            email = data[0].edad;
            telefon = data[0].telefon;
            persones = data[0].persones;
            data = data[0].data;
            hora = data[0].hora;
            if(opcion == 1){tablaPersonas.row.add([id,nom,apellidos,email,telefon,persones,data,hora]).draw();}
            else{tablaPersonas.row(fila).data([id,nom,apellidos,email,telefon,persones,data,hora]).draw();}            
        }        
    });
    $("#modalCRUD").modal("hide");    
    
});    
    
});