<!DOCTYPE html>
<html>
  <head>
    <title>API da Betfair</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body>
    <canvas id="chart"></canvas>

    <?php
    // incluir o arquivo jsonrpc.php para enviar a solicitação para a API da Betfair
    require_once 'jsonrpc.php';

    // definir a carga JSON-RPC para a solicitação
    $request = array(
      'jsonrpc' => '2.0',
      'method' => 'SportsAPING/v1.0/listEvents',
      'params' => array(
        'filter' => array(
          'eventTypeIds' => array(1), // 1 = futebol
          'marketCountries' => array('GB'), // Reino Unido
          'marketTypeCodes' => array('MATCH_ODDS'), // correspondências com odds
          'marketStartTime' => array(
            'from' => date('c', strtotime('yesterday')), // ontem
            'to' => date('c', strtotime('today')), // hoje
          ),
        ),
        'sort' => 'FIRST_TO_START',
        'maxResults' => 5, // 5 jogos
        'marketProjection' => array('RUNNER_DESCRIPTION'),
      ),
      'id' => 1,
    );

    // enviar a solicitação para a API da Betfair
    $response = jsonrpc($request);

    // extrair os resultados da resposta
    $results = $response['result'];

    // contar o número de jogos por competição
    $competitions = array();
    foreach ($results as $result) {
      $competitionName = $result['event']['competition']['name'];
      if (!isset($competitions[$competitionName])) {
        $competitions[$competitionName] = 0;
      }
      $competitions[$competitionName]++;
    }

    // construir os dados para o gráfico
    $labels = array();
    $data = array();
    foreach ($competitions as $competitionName => $count) {
      $labels[] = $competitionName;
      $data[] = $count;
    }
    ?>

    <script>
      var ctx = document.getElementById('chart').getContext('2d');
      var chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: <?php echo json_encode($labels); ?>,
          datasets: [{
            label:'Número de jogos',
data: <?php echo json_encode($data); ?>,
backgroundColor: 'rgba(54, 162, 235, 0.2)',
borderColor: 'rgba(54, 162, 235, 1)',
borderWidth: 1
}]
},
options: {
responsive: true,
scales: {
yAxes: [{
ticks: {
beginAtZero: true
}
}]
}
}
});
</script>

  </body>
</html>
