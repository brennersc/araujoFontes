<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Grafico</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">        
    </head>
    <body>
        <div class="container">
            <div class="card mt-5">
                <h5 class="card-header">Grafico</h5>
                <div class="card-body">
                    <form id="formFundos">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="dataInicial">Data Inicial:</label>
                                <input type="date" class="form-control" name="dataInicial" id="dataInicial">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="dataFinal">Data Final:</label>
                                <input type="date" class="form-control" name="dataFinal" id="dataFinal">
                            </div>   
                            <label class="ml-3">Fundos:</label>                           
                            <div class="form-group col-md-5 ml-3 mt-4">                    
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="fundo1" id="fundo1" value="1">
                                    <label class="form-check-label" for="fundo1">Fundo 1</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="fundo2" id="fundo2" value="2">
                                    <label class="form-check-label" for="fundo2">Fundo 2</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="fundo3" id="fundo3" value="3">
                                    <label class="form-check-label" for="fundo3">Fundo 3</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="fundo4" id="fundo4" value="4">
                                    <label class="form-check-label" for="fundo4">Fundo 4</label>
                                </div>
                            </div>
                        </div>        
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </form>
                </div>
            </div>

            <canvas id="iniciarGrafico" width="150" height="150"></canvas>
            <canvas id="montarGrafico" width="150" height="150"></canvas>
        </div>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.0-alpha/Chart.js" integrity="sha512-IWyUmbSE/5DsDNHIOdb/5pcTrYgmusAuUmvGzah4T5Z5aSX/iE9XDi9cVfk91S2OJWpzRse8Kt+xIUWTkUJw8A==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js" integrity="sha512-U3hGSfg6tQWADDQL2TUZwdVSVDxUt2HZ6IMEIskuBizSDzoe65K3ZwEybo0JOcEjZWtWY3OJzouhmlGop+/dBg==" crossorigin="anonymous"></script>
    
    <script type="text/javascript">
        $.ajaxSetup({
                headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
        });

        $.ajax({
            type: 'POST',
            url: '/iniciarGrafico',
            dataType: 'JSON',            
            success: function(data) {

                var valorArray = [];
                var nomeArray = [];

                for(var i=0; i < data.length; i++){
                    valorArray.push(data[i].value);
                    nomeArray.push(data[i].name);
                }
                console.log(valorArray);
                console.log(nomeArray);
                graficoInicial(data, valorArray);
            }
        });

        $("#formFundos").submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '/montarGrafico',
                data: { 
                    inicial:  $("#dataInicial").val(),
                    final:    $("#dataFinal").val(),
                    fundo1:  $("input:checkbox[name=fundo1]:checked").val(),
                    fundo2:  $("input:checkbox[name=fundo2]:checked").val(),
                    fundo3:  $("input:checkbox[name=fundo3]:checked").val(),
                    fundo4:  $("input:checkbox[name=fundo4]:checked").val(),
                 },
                dataType: 'JSON',            
                success: function(data) {

                    var valorArray = [];
                    var dataArray = [];
                    var nomeArray = [];

                    for(var i=0; i < data.length; i++){
                        valorArray.push(data[i].value);
                        dataArray.push(data[i].date);
                        nomeArray.push(data[i].name);
                    }
                    console.log(valorArray);
                    console.log(dataArray);
                    console.log(nomeArray);
                    montarGrafico(valorArray, dataArray, nomeArray);
                    $('#iniciarGrafico').hide();
                }
            });
        });

        function montarGrafico(valorArray, dataArray, nomeArray){
        var config  = {            
            labels: dataArray,
            datasets: [{
                label: nomeArray,
                backgroundColor: ['rgba(255, 99, 132)','rgba(75, 192, 192)','rgba(154, 162, 235)','rgba(54, 162, 235)'],
                data: valorArray,
            }]
        };
        var ctx = document.getElementById('montarGrafico').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: config,
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Grafico Patrimônios'
                },
                tooltips: {
                    mode: 'index',
                },
                hover: {
                    mode: 'index'
                },
                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Periodo'
                        }
                    }],
                    yAxes: [{
                        stacked: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Patrimônio'
                        }
                    }]
                }
            }
        });
    }

    function graficoInicial(data, valorArray){
        var config  = {            
            labels: ['2020-01-21','2020-01-22','2020-01-23','2020-01-24','2020-01-25','2020-01-26','2020-01-27'],
            datasets: [{
                label: data[0].name,
                backgroundColor: 'rgba(255, 99, 132)',
                data: valorArray
            },{
                label: data[1].name,
                backgroundColor: 'rgba(75, 192, 192)',
                data: valorArray
            },{
                label: data[2].name,
                backgroundColor: 'rgba(154, 162, 235)',
                data: valorArray
            },{
                label: data[3].name,
                backgroundColor: 'rgba(54, 162, 235)',
                data: valorArray
            }]
        };
        var ctx = document.getElementById('iniciarGrafico').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: config,
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Grafico Patrimônios'
                },
                tooltips: {
                    mode: 'index',
                },
                hover: {
                    mode: 'index'
                },
                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Periodo'
                        }
                    }],
                    yAxes: [{
                        stacked: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Patrimônio'
                        }
                    }]
                }
            }
        });
    }
    </script>
</html>
