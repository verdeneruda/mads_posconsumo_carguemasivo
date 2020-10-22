<?php


?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    
    <script src="https://code.jquery.com/jquery-1.11.3.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBje3JGY0rNRd32BAuZWcsjSI7AIJbLL2A"></script>
    <title>Cargue Masivo</title>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.5/xlsx.js"></script>

<script language="JavaScript">
    var oFileIn;
    //Código JQuery
    $(function() {
        oFileIn = document.getElementById('my_file_input');
        if (oFileIn.addEventListener) {
            oFileIn.addEventListener('change', filePicked, false);
        }
    });

    function latitudLongitud(id, direccion){

        var geocoder = new google.maps.Geocoder();
        var latitud = 0;
        var longitud = 0;

        geocoder.geocode({ 'address': direccion}, function(results, status){

            if (status == 'OK'){

                latitud = results[0].geometry.location.lat();
                longitud = results[0].geometry.location.lng();

            }else{

                latitud = 0;
                longitud = 0;

            }

            jQuery("#latitud"+id).html(latitud);
            jQuery("#longitud"+id).html(longitud);

        });

    }

    //Método que hace el proceso de importar excel a html
    function filePicked(oEvent) {
        // Obtener el archivo del input
        var oFile = oEvent.target.files[0];
        var sFilename = oFile.name;
        // Crear un Archivo de Lectura HTML5
        var reader = new FileReader();

        // Leyendo los eventos cuando el archivo ha sido seleccionado
        reader.onload = function(e) {
            var data = e.target.result;
            var cfb = XLS.CFB.read(data, {
                type: 'binary'
            });
            var wb = XLS.parse_xlscfb(cfb);
            // Iterando sobre cada sheet

            var page = 1;
            var column = 0;
            var fila = 1;
            var latitud = "";
            var longitud = "";
            var direccion = "";

            wb.SheetNames.forEach(function(sheetName) {

                //  Unicamente la hoja 1

                if(page == 1){

                    // Obtener la fila actual como CSV
                    var sCSV = XLS.utils.make_csv(wb.Sheets[sheetName]);
                    var data = XLS.utils.sheet_to_json(wb.Sheets[sheetName], {
                        header: 2
                    });
                    $.each(data, function(indexR, valueR) {

                        column = 0;

                        var sRow = "<tr>";
                        $.each(data[indexR], function(indexC, valueC) {

                            if(column == 2)
                                direccion = valueC;

                            sRow = sRow + "<td>" + valueC + "</td>";
                            column++;

                        });

                        sRow = sRow + "<td id='latitud"+fila+"'>" + latitud + "</td>";
                        sRow = sRow + "<td id='longitud"+fila+"'>" + longitud + "</td>";
                        sRow = sRow + "</tr>";
                        $("#my_file_output").append(sRow);

                        latitudLongitud(fila, direccion);

                        fila++;

                    });

                }

                page++;

            });

            $("#imgImport"). css("display", "none");
        };


// Llamar al JS Para empezar a leer el archivo .. Se podría retrasar esto si se desea
        reader.readAsBinaryString(oFile);
    }
</script>

<body style="background-color: #c0c0c0">
<div class="container-fluid">
    
    <br>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">Cargar Puntos de Recoleccion</div>
                <div class="panel-body">
                    <div class="form-group">
                        <input type="file" id="my_file_input" class="form-control" />
                        <div id="imgImport">
                            <br>
                            <center>
                                <img src="../bundles/madsposconsumos/images/logo_posconsumo.png" alt="" width="50%" >
                            </center>
                        </div>
                        <br>
                        <div class="table table-responsive">
                            <table id='my_file_output' border=""
                                   class="table table-bordered table-condensed table-striped"></table>
                        </div>
                        <button id="btn_lectura" class="btn btn-info">Cargar Datos</button>
                        <a href="../admin/?entity=CollectionPoint&action=list&menuIndex=2&submenuIndex=0&sortField=createdAt" class="btn btn-default ">Cancelar</a>
                        <p id="respuesta">

                        </p>
                        <p id="contador">

                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>


<script>
    $('#btn_lectura').click(function () {
        valores=new Array();
        var contador = 0;
        $('#my_file_output tr').each(function () {

            var d1= $(this).find('td').eq(0).html();
            var d2 = $(this).find('td').eq(1).html();
            var d3 = $(this).find('td').eq(2).html();
            var d4 = $(this).find('td').eq(3).html();
            var d5 = $(this).find('td').eq(4).html();
            var d6 = $(this).find('td').eq(5).html();
            var d7 = $(this).find('td').eq(6).html();
            var d8 = $(this).find('td').eq(7).html();
            var d9 = $(this).find('td').eq(8).html();
            var d10 = $(this).find('td').eq(9).html();
            var d11 = $(this).find('td').eq(10).html();
            valor=new Array(d1, d2, d3, d4, d5, d6, d7, d8, d9, d10, d11);
            valores.push(valor);
            console.log (valor);
           // alert(valor);
            $.post('/load_massive.php', {d1:d1, d2:d2, d3:d3, d4:d4, d5:d5, d6:d6, d7:d7, d8:d8, d9:d9, d10:d10, d11:d11}, function (datos) {
                
                //$('#respuesta').html(datos);

            });

            contador = contador + 1;
            $('#contador').html("Se ingresaron "+contador+" registros correctamente.");

        });



    });
</script>
</body>
</html>

