<?php

// Definindo a URL base da API e o token de acesso
$base_url = 'http://api.football-data.org/v2/';
$token = '3c3051894ce443b1985cf8bccceadd19';

// Obtendo a data de hoje
$date = date('Y-m-d');

// Montando a URL para buscar os jogos do dia
$url = $base_url . 'matches?dateFrom=' . $date . '&dateTo=' . $date;

// Configurando as opções para a requisição HTTP
$options = array(
    'http' => array(
        'method' => 'GET',
        'header' => 'X-Auth-Token: ' . $token
    )
);

// Criando um contexto para a requisição HTTP
$context = stream_context_create($options);

// Fazendo a requisição HTTP e obtendo a resposta
$response = file_get_contents($url, false, $context);

// Decodificando a resposta JSON em um array associativo
$data = json_decode($response, true);

// Verificando se a busca retornou resultados
if (count($data['matches']) == 0) {
    echo 'Não há jogos marcados para hoje.';
} else {
    // Exibindo os 10 primeiros jogos do dia
    for ($i = 0; $i < 10 && $i < count($data['matches']); $i++) {
        $match = $data['matches'][$i];

        // Extraindo as informações relevantes do jogo
        $homeTeam = $match['homeTeam']['name'];
        $awayTeam = $match['awayTeam']['name'];
        $matchDate = $match['utcDate'];
        $matchStatus = $match['status'];
        $matchId = $match['id'];

        // Exibindo as informações do jogo
        echo '<p>Jogo ' . ($i + 1) . ': ' . $homeTeam . ' x ' . $awayTeam . ' - ' . $matchDate . '</p>';

        // Obtendo as informações dos times A e B
        $url_team_a = $base_url . 'teams/' . $match['homeTeam']['id'] . '/matches?status=FINISHED&limit=50';
        $url_team_b = $base_url . 'teams/' . $match['awayTeam']['id'] . '/matches?status=FINISHED&limit=50';

        $team_a_matches_response = file_get_contents($url_team_a, false, $context);
        $team_a_matches_data = json_decode($team_a_matches_response, true);

        $team_b_matches_response = file_get_contents($url_team_b, false, $context);
        $team_b_matches_data = json_decode($team_b_matches_response, true);

        // Obtendo as informações do confronto direto
        $url_confronto_direto = $base_url . 'matches/' . $matchId;
        $confronto_direto_response = file_get_contents($url_confronto_direto, false, $context);
        $confronto_direto_data = json_decode($confronto_direto_response, true);

       


// Realizando as análises estatísticas

// 1 - Contando quantos jogos tem em cada tabela
$contagem_tabela1 = count($confronto_direto_data['matches']);
$contagem_tabela2 = count($ultimos_jogos_timeA_data['matches']);
$contagem_tabela3 = count($ultimos_jogos_timeB_data['matches']);

// 2 - Contando quantos jogos terminaram em 0x0 nos dois times
$contagem_0x0_tabela2 = 0;
foreach ($ultimos_jogos_timeA_data['matches'] as $jogo) {
    if ($jogo['score']['fullTime']['homeTeam'] == 0 && $jogo['score']['fullTime']['awayTeam'] == 0) {
        $contagem_0x0_tabela2++;
    }
}

$contagem_0x0_tabela3 = 0;
foreach ($ultimos_jogos_timeB_data['matches'] as $jogo) {
    if ($jogo['score']['fullTime']['homeTeam'] == 0 && $jogo['score']['fullTime']['awayTeam'] == 0) {
        $contagem_0x0_tabela3++;
    }
}

// Verificando a estatística de OVER 0,5
$contagem_over05 = ($contagem_tabela2 - $contagem_0x0_tabela2) + ($contagem_tabela3 - $contagem_0x0_tabela3);
$percentual_over05 = number_format(($contagem_over05 / ($contagem_tabela2 + $contagem_tabela3)) * 100, 2);

$percentual_under05 = 100 - $percentual_over05;

// Verificando a estatística de OVER 5,5
$contagem_over55 = 0;
foreach ($confronto_direto_data['matches'] as $jogo) {
    $gols = $jogo['score']['fullTime']['homeTeam'] + $jogo['score']['fullTime']['awayTeam'];
    if ($gols >= 6) {
        $contagem_over55++;
    }
}

foreach ($ultimos_jogos_timeA_data['matches'] as $jogo) {
    $gols = $jogo['score']['fullTime']['homeTeam'] + $jogo['score']['fullTime']['awayTeam'];
    if ($gols >= 6) {
        $contagem_over55++;
    }
}

foreach ($ultimos_jogos_timeB_data['matches'] as $jogo) {
    $gols = $jogo['score']['fullTime']['homeTeam'] + $jogo['score']['fullTime']['awayTeam'];
    if ($gols >= 6) {
        $contagem_over55++;
    }
}

$percentual_over55 = number_format(($contagem_over55 / ($contagem_tabela1 + $contagem_tabela2 + $contagem_tabela3)) * 100, 2);

$percentual_under55 = 100 - $percentual_over55;

$percentual_over55 = number_format(($contagem_over55 / ($contagem_tabela1 + $contagem_tabela2 + $contagem_tabela3)) * 100, 2);

$percentual_under55 = 100 - $percentual_over55;

// Verificando a estatística de GOLEAR
$contagem_golear = 0;
foreach ($confronto_direto_data['matches'] as $jogo) {
    $gols_home = $jogo['score']['fullTime']['homeTeam'];
    $gols_away = $jogo['score']['fullTime']['awayTeam'];
if ($gols_home >= 4 || $gols_away >= 4) {
$contagem_golear++;
}
}
$percentual_golear = number_format(($contagem_golear / count($confronto_direto_data['matches'])) * 100, 2);

// Imprimindo os resultados
echo "Probabilidade de Over 0.5 gols: $percentual_over05%\n";
echo "Probabilidade de Over 1.5 gols: $percentual_over15%\n";
echo "Probabilidade de Over 2.5 gols: $percentual_over25%\n";
echo "Probabilidade de Over 3.5 gols: $percentual_over35%\n";
echo "Probabilidade de Over 4.5 gols: $percentual_over45%\n";
echo "Probabilidade de Under 0.5 gols: $percentual_under05%\n";
echo "Probabilidade de Under 1.5 gols: $percentual_under15%\n";
echo "Probabilidade de Under 2.5 gols: $percentual_under25%\n";
echo "Probabilidade de Under 3.5 gols: $percentual_under35%\n";
echo "Probabilidade de Under 4.5 gols: $percentual_under45%\n";
echo "Probabilidade de Over 5.5 gols: $percentual_over55%\n";
echo "Probabilidade de Under 5.5 gols: $percentual_under55%\n";
echo "Probabilidade de Golear: $percentual_golear%\n";

// Verificando a estatística de SER GOLEADO
$contagem_ser_goleado = 0;
foreach ($confronto_direto_data['matches'] as $jogo) {
$gols_home = $jogo['score']['fullTime']['homeTeam'];
$gols_away = $jogo['score']['fullTime']['awayTeam'];
if ($gols_home <= 1 && $gols_away >= 3) {
$contagem_ser_goleado++;
}
if ($gols_home >= 3 && $gols_away <= 1) {
$contagem_ser_goleado++;
}
}
$percentual_ser_goleado = number_format(($contagem_ser_goleado / count($confronto_direto_data['matches'])) * 100, 2);
echo "Probabilidade de ser goleado: $percentual_ser_goleado%\n";

// Verificando a estatística de GOLEAR O ADVERSÁRIO
$contagem_golear_adversario = 0;
foreach ($confronto_direto_data['matches'] as $jogo) {
$gols_home = $jogo['score']['fullTime']['homeTeam'];
$gols_away = $jogo['score']['fullTime']['awayTeam'];
if ($gols_home >= 3 && $gols_away <= 1) {
$contagem_golear_adversario++;
}
if ($gols_home <= 1 && $gols_away >= 3) {
$contagem_golear_adversario++;
}
}
$percentual_golear_adversario = number_format(($contagem_golear_adversario / count($confronto_direto_data['matches'])) * 100, 2);
echo "Probabilidade de golear o adversário: $percentual_golear_adversario%\n";


// Verificando a estatística de EMPATAR
$contagem_empate = 0;
foreach ($confronto_direto_data['matches'] as $jogo) {
$gols_home = $jogo['score']['fullTime']['homeTeam'];
$gols_away = $jogo['score']['fullTime']['awayTeam'];
if ($gols_home == $gols_away) {
$contagem_empate++;
}
}
$percentual_empate = number_format(($contagem_empate / count($confronto_direto_data['matches'])) * 100, 2);

// Verificando a estatística de GANHAR POR UM GOL DE DIFERENÇA
$contagem_vitoria_1gol = 0;
foreach ($confronto_direto_data['matches'] as $jogo) {
$gols_home = $jogo['score']['fullTime']['homeTeam'];
$gols_away = $jogo['score']['fullTime']['awayTeam'];
if (abs($gols_home - $gols_away) == 1 && ($gols_home > $gols_away && $jogo['homeTeam']['id'] == $teamId || $gols_away > $gols_home && $jogo['awayTeam']['id'] == $teamId)) {
$contagem_vitoria_1gol++;
}
}
$percentual_vitoria_1gol = number_format(($contagem_vitoria_1gol / count($confronto_direto_data['matches'])) * 100, 2);

// Verificando a estatística de GANHAR POR MAIS DE UM GOL DE DIFERENÇA
$contagem_vitoria_mais1gol = 0;
foreach ($confronto_direto_data['matches'] as $jogo) {
$gols_home = $jogo['score']['fullTime']['homeTeam'];
$gols_away = $jogo['score']['fullTime']['awayTeam'];
if (abs($gols_home - $gols_away) > 1 && ($gols_home > $gols_away && $jogo['homeTeam']['id'] == $teamId || $gols_away > $gols_home && $jogo['awayTeam']['id'] == $teamId)) {
$contagem_vitoria_mais1gol++;
}
}
$percentual_vitoria_mais1gol = number_format(($contagem_vitoria_mais1gol / count($confronto_direto_data['matches'])) * 100, 2);


// Verificando a estatística de PERDER POR DOIS GOLS DE DIFERENÇA
$contagem_derrota_2gols = 0;
foreach ($confronto_direto_data['matches'] as $jogo) {
$gols_home = $jogo['score']['fullTime']['homeTeam'];
$gols_away = $jogo['score']['fullTime']['awayTeam'];
if (abs($gols_home - $gols_away) == 2 && ($gols_home < $gols_away && $jogo['homeTeam']['id'] == $teamId || $gols_away < $gols_home && $jogo['awayTeam']['id'] == $teamId)) {
$contagem_derrota_2gols++;
}
}
$percentual_derrota_2gols = number_format(($contagem_derrota_2gols / count($confronto_direto_data['matches'])) * 100, 2);

// Imprimindo os resultados
echo "Probabilidade de Over 0.5 gols: $percentual_over05%\n";
echo "Probabilidade de Over 1.5 gols: $percentual_over15%\n";
echo "Probabilidade de Over 2.5 gols: $percentual_over25%\n";
echo "Probabilidade de Over 3.5 gols: $percentual_over35%\n";
echo "Probabilidade de Over 4.5 gols: $percentual_over45%\n";
echo "Probabilidade de Under 0.5 gols: $percentual_under05%\n";
echo "Probabilidade de Under 1.5 gols: $percentual_under15%\n";
echo "Probabilidade de Under 2.5 gols: $percentual_under25%\n";
echo "Probabilidade de Under 3.5 gols: $percentual_under35%\n";
echo "Probabilidade de Under 4.5 gols: $percentual_under45%\n";
echo "Probabilidade de Over 5.5 gols: $percentual_over55%\n";
echo "Probabilidade de Under 5.5 gols: $percentual_under55%\n";
echo "Probabilidade de Golear: $percentual_golear%\n";
echo "Probabilidade de Vencer por um gol de diferença: $percentual_vitoria_1gol%\n";
echo "Probabilidade de Vencer por dois gols de diferença: $percentual_vitoria_2gols%\n";
echo "Probabilidade de Empatar: $percentual_empate%\n";
echo "Probabilidade de Perder por um gol de diferença: $percentual_derrota_1gol%\n";
echo "Probabilidade de Perder por dois gols de diferença: $percentual_derrota_2gols%\n";

?>
