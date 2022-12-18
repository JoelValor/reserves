
<?php require_once "vistas/parte_superior.php"?>

    <!--Els 4 per a generar el pdf de l'Informe-->
    <script src="js/informe-pdf/bootstrap.min.js"></script>
    <script src="js/informe-pdf/jquery.min.js"></script>
    <script src="js/informe-pdf/jspdf.min.js"></script>
    <script src="js/informe-pdf/jspdf.plugin.autotable.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.css"/>
    <link rel="stylesheet" href="css/quesito.css"/>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.js"></script>

    <!--https://cdn.jsdelivr.net/npm/sweetalert2@11-->
    <script src="js/sweetalert-borrar.js"></script>
   
    <style type="text/css">
        .imp-reserva{
            width: 12%;
            margin-top: -38px;
            margin-left: 260px;
        }
        .imp-boto{
            margin-top: -97px;
            margin-left: 150px;
        }
        .imp-boto-informe{
            margin-top: -55px;
            margin-left: 0;
        }
        @media only screen and (max-width: 468px) {
            .imp-boto{
                margin-top: -97px;
                margin-left: 0px;
            }
            .imp-reserva{
                width: 52%;
                margin-top: 14px;
                margin-left: 120px;
            }
        }
        /*El icono del tipo de sweetalert era massa gros i per aixo el font-size*/
        .swal2-icon.swal2-question.swal2-icon-show .swal2-icon-content {
            -webkit-animation: swal2-animate-question-mark .8s;
            animation: swal2-animate-question-mark .8s;
            font-size: 80px;
        }
        .col-12 {
            margin-bottom: 20px;
        }    
        /*Per amagar la barra del menu lateral de l'esquerra en els mobils i tablets */
        @media only screen and (max-width: 868px) {
            .sidebar {
                display: none;
            }
        }
    </style>

    <!--INICIO del cont principal-->
    <div class="container">
        <h1>Reserves Origens</h1>
    
        <?php
            include_once 'bd/conexion.php';
            require('../bd/dbconnect_create.php');
            $objeto = new Conexion();
            $conexion = $objeto->Conectar();
            
            /*Mostrar tota la taula de les reserves*/
            $consulta = "SELECT * FROM reservas";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $data=$resultado->fetchAll(PDO::FETCH_ASSOC);

            /*Contar Numero de Reserves*/
            /*Obté la resultat de la consulta*/
            $consulta_reserves = $conexion->query('SELECT COUNT(*) FROM reservas');
            /*Obté la primera fila (l'única fila d'aquesta consulta)*/
            $resultat = $consulta_reserves->fetch();
            /*emmagatzema el recompte*/
            $total_reserves = $resultat[0];

            /*Calcular percentatge reserves*/
            $maxim_reserves = 300;
            /*Calcular percentatge del maxim de reserves*/
            $percentatge_reserves = ($total_reserves / $maxim_reserves) * 100;

            /*Top 5 Hores en mes ocupacio*/
            $consulta_top5_hores = "SELECT data,hora, SUM(persones) as persones FROM reservas group by hora,data ORDER BY 3 DESC,2,1 limit 5";
            $resultat_top5_hores = $conexion->prepare($consulta_top5_hores);
            $resultat_top5_hores->execute();
            $array_top5_hores=$resultat_top5_hores->fetchAll(PDO::FETCH_ASSOC);

            /*Top 5 Taules en mes persones*/
            $consulta_top5_taules = "SELECT data,hora,persones FROM reservas ORDER BY `reservas`.`persones` DESC limit 5";
            $resultat_top5_taules = $conexion->prepare($consulta_top5_taules);
            $resultat_top5_taules->execute();
            $array_top5_taules=$resultat_top5_taules->fetchAll(PDO::FETCH_ASSOC);


            //Per imprimir Informe
            include "bd/conexion-pdf.php";
            $db =  connect();
            $query=$db->query("SELECT * FROM reservas ORDER BY data,hora");
            $clientes = array();
            $n=0;
            while($r=$query->fetch_object()){ $clientes[]=$r; $n++;}
        ?>

            <div class="row">
                <div class="col-lg-12">            
                    <button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal">Nova Reserva</button> 
                    <form action="../pdf/print.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control imp-reserva" id="imprimir" name="imprimir" placeholder="Reserva a Imprimir">
                        </div> 
                        <button class='btn btn-primary imp-boto' type="submit" name="submit"><i class='fa fa-print'></i> Imprimir</button>
                    </form>
                    
                    <!-- Per imprimir sols reserves de un dia en concret
                    <form action="informe.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control imp-reserva" id="informe" name="informe" placeholder="Informe">
                        </div> 
                        <button class='btn btn-primary imp-boto' type="submit" id="boto" name="submit"><i class='fa fa-print'></i> Imprimir</button>
                    </form>-->
                    <button class='btn btn-primary imp-boto-informe' type="submit" id="informe" name="submit"><i class='fa fa-print'></i> Informe</button>
                </div>    
            </div>
    
        <br>  
        <div _ngcontent-vug-c2="" class="row">
            <div _ngcontent-vug-c2="" class="col-xl-2 col-lg-12 col-md-12 col-sm-12 col-12">
                <div _ngcontent-vug-c2="" class="card p-t-20 p-b-20 p-r-20 p-l-20">
                    <div _ngcontent-vug-c2="" class="card-header" style="text-align:center">
                        <h4>Reserves</h4>
                        <div _ngcontent-vug-c2="" class="metric-value d-inline-block"><h1 _ngcontent-vug-c2="" class="mb-1"><?php echo $total_reserves;?></h1></div>
                    </div>

                    <div class="flex-wrapper">
                      <div class="single-chart">
                        <svg viewBox="0 0 36 36" class="circular-chart blue">
                          <path class="circle-bg"
                            d="M18 2.0845
                              a 15.9155 15.9155 0 0 1 0 31.831
                              a 15.9155 15.9155 0 0 1 0 -31.831"
                          />
                          <path class="circle"
                            stroke-dasharray="<?php echo $percentatge_reserves;?>, 100"
                            d="M18 2.0845
                              a 15.9155 15.9155 0 0 1 0 31.831
                              a 15.9155 15.9155 0 0 1 0 -31.831"
                          />
                          <!--Imprimir per pantalla percentatge de reserves fetes, arodonint a 1 decima-->
                          <text x="18" y="20.35" class="percentage"><?php echo round($percentatge_reserves, 1);?>%</text>
                        </svg>
                      </div>
                    </div>
                </div>
            </div>
            
            <div _ngcontent-vug-c2="" class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-12">
                <div _ngcontent-vug-c2="" class="card p-t-20 p-b-20 p-r-20 p-l-20">
                    <h5 _ngcontent-vug-c2="" class="card-header">Top 5 Hores en mes ocupació</h5>
                    <div _ngcontent-vug-c2="" class="card-body p-0">
                        <div _ngcontent-vug-c2="">
                            <table _ngcontent-vug-c2="" class="table">
                                <thead _ngcontent-vug-c2="" class="bg-light">
                                    <tr _ngcontent-vug-c2="">
                                        <th _ngcontent-vug-c2=""></th>
                                        <th _ngcontent-vug-c2="">Dia</th>
                                        <th _ngcontent-vug-c2="">Hora</th>
                                        <th _ngcontent-vug-c2="">Comensals</th>
                                    </tr>
                                </thead>
                                <tbody _ngcontent-vug-c2=""><!---->
                                    <?php     
                                        //El contador torna al index de cada element del array i finalitze quant sigui => del array i per a imprimir +1 per a la seguen posició
                                        foreach($array_top5_hores as $contador => $top5_hores) {   
                                            //canviar format de la data a Dia-Mes-Any
                                            $data_correcta_top5 = $data_correcta_top5 = date("d-m-Y", strtotime($top5_hores['data']));
                                        ?>
                                    <tr _ngcontent-vug-c2="">
                                        <td _ngcontent-vug-c2=""><?php echo $contador+1 ?></td>
                                        <td _ngcontent-vug-c2=""><?php echo $data_correcta_top5 ?></td>
                                        <td _ngcontent-vug-c2=""><?php echo $top5_hores['hora'] ?></td>
                                        <td _ngcontent-vug-c2=""><?php echo $top5_hores['persones'] ?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>   
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div _ngcontent-vug-c2="" class="card-footer d-flex p-t-20"></div>
                </div>
            </div>
            <div _ngcontent-vug-c2="" class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-12">
                <div _ngcontent-vug-c2="" class="card p-t-20 p-b-20 p-r-20 p-l-20">
                    <h5 _ngcontent-vug-c2="" class="card-header">Top 5 Taules en mes comensals</h5>
                    <div _ngcontent-vug-c2="" class="card-body p-0">
                        <div _ngcontent-vug-c2="">
                            <table _ngcontent-vug-c2="" class="table">
                                <thead _ngcontent-vug-c2="" class="bg-light">
                                    <tr _ngcontent-vug-c2="">
                                        <th _ngcontent-vug-c2=""></th>
                                        <th _ngcontent-vug-c2="">Dia</th>
                                        <th _ngcontent-vug-c2="">Hora</th>
                                        <th _ngcontent-vug-c2="">Comensals</th>
                                    </tr>
                                </thead>
                                <tbody _ngcontent-vug-c2=""><!---->
                                    <?php    
                                        //El contador torna al index de cada element del array i finalitze quant sigui => del array i per a imprimir +1 per a la seguen posició
                                        foreach($array_top5_taules as $contador => $top5_taules) {   
                                            //canviar format de la data a Dia-Mes-Any
                                            $data_correcta_top5 = $data_correcta_top5 = date("d-m-Y", strtotime($top5_taules['data']));
                                        ?>
                                    <tr _ngcontent-vug-c2="">
                                        <td _ngcontent-vug-c2=""><?php echo $contador+1 ?></td>
                                        <td _ngcontent-vug-c2=""><?php echo $data_correcta_top5 ?></td>
                                        <td _ngcontent-vug-c2=""><?php echo $top5_taules['hora'] ?></td>
                                        <td _ngcontent-vug-c2=""><?php echo $top5_taules['persones'] ?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>   
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div _ngcontent-vug-c2="" class="card-footer d-flex p-t-20"></div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">        
                    <table id="tablaPersonas" class="table table-striped table-bordered table-condensed" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th>Id</th>
                                <th>Nom</th>
                                <th>Cognom</th>                                
                                <th>Email</th> 
                                <th>Telefon</th> 
                                <th>Persones</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Accions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                            
                            foreach($data as $dat) {   
                                //canviar format de la data a Dia-Mes-Any
                                $data_correcta = $data_correcta = date("d-m-Y", strtotime($dat['data']));
                            ?>
                            <tr>
                                <td><?php echo $dat['id'] ?></td>
                                <td><?php echo $dat['nom'] ?></td>
                                <td><?php echo $dat['apellidos'] ?></td>
                                <td><?php echo $dat['email'] ?></td> 
                                <td><?php echo $dat['telefon'] ?></td> 
                                <td><?php echo $dat['persones'] ?></td>
                                <td><?php echo $data_correcta ?></td> 
                                <td><?php echo $dat['hora'] ?></td> 
                                <td></td>
                            </tr>
                            <?php
                                }
                            ?>                                
                        </tbody>        
                    </table>                    
                </div>
            </div>
        </div>  
        
      
    <!--Modal para CRUD-->
    <div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form id="formPersonas">    
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nom" class="col-form-label">Nom:</label>
                        <input type="text" class="form-control" id="nom">
                    </div>
                    <div class="form-group">
                        <label for="apellidos" class="col-form-label">Cognom:</label>
                        <input type="text" class="form-control" id="apellidos">
                    </div>                
                    <div class="form-group">
                        <label for="email" class="col-form-label">Email:</label>
                        <input type="text" class="form-control" id="email">
                    </div> 
                    <div class="form-group">
                        <label for="telefon" class="col-form-label">Telefon:</label>
                        <input type="text" class="form-control" id="telefon">
                    </div> 
                    <div class="form-group">
                        <label for="persones" class="col-form-label">Persones:</label>
                        <input type="number" class="form-control" id="persones">
                    </div> 
                    <div class="form-group">
                        <label for="data" class="col-form-label">Data:</label>
                        <input type="date" class="form-control" id="data">
                    </div> 
                    <div class="form-group">
                        <label for="hora" class="col-form-label">Hora:</label>
                        <select id="hora" class="form-control" type="text">
                            <option disabled selected>Selecciona una Hora</option>
                            <option value="12:00">12:00</option>
                            <option value="12:30">12:30</option>
                            <option value="13:00">13:00</option>
                            <option value="13:30">13:30</option>
                            <option value="14:00">14:00</option>
                            <option value="20:00">20:00</option>
                            <option value="20:30">20:30</option>
                            <option value="21:00">21:00</option>
                            <option value="21:30">21:30</option>
                            <option value="22:00">22:00</option>
                            <option value="22:30">22:30</option>
                        </select>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnGuardar" class="btn btn-dark">Crear</button>
                </div>
            </form>    
            </div>
        </div>
    </div>  
</div>

<script>
    $("#informe").click(function($clientes){
      var pdf = new jsPDF();
      pdf.text(14,20,"Informe Reserves");

      var columns = ["Reserva", "Nom", "Apellidos","Telèfon", "Persones", "Dia", "Hora"];
      var data = [
    <?php foreach($clientes as $c): $data_correcta = $data_correcta = date("d-m-Y", strtotime($c->data));?>
          
     [<?php echo $c->id; ?>, "<?php echo $c->nom; ?>", "<?php echo $c->apellidos; ?>","<?php echo $c->telefon; ?>","<?php echo $c->persones; ?>","<?php echo $data_correcta; ?>","<?php echo $c->hora; ?>"],
    <?php endforeach; ?>  
      ];

      pdf.autoTable(columns,data,
        { margin:{ top: 25  }}
      );

      pdf.save('Informe Reserves.pdf');

    });
</script>
<!--FIN del cont principal-->
<?php require_once "vistas/parte_inferior.php"?>