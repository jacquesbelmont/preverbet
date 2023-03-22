const form = document.querySelector("form");
form.addEventListener("submit", handleSubmit);

function handleSubmit(event) {
  event.preventDefault();

  const team = document.getElementById("team").value;
  const numGames = document.getElementById("numGames").value;
  const predictionModel = document.getElementById("predictionModel").value;

  predict(team, numGames, predictionModel);
}

async function predict(team, numGames, predictionModel) {
  const apiEndpoint = `https://api.sportmonks.com/v2.0/teams/search/${team}`;
  const apiKey = "<sua chave de API aqui>";
  const response = await fetch(`${apiEndpoint}?api_token=${apiKey}`);
  const data = await response.json();
  const teamId = data.data[0].id;

  const fixturesEndpoint = `https://api.sportmonks.com/v2.0/fixtures/between/${getStartDate()}/${getEndDate()}`;
  const fixturesResponse = await fetch(`${fixturesEndpoint}?api_token=${apiKey}&team_id=${teamId}`);
  const fixturesData = await fixturesResponse.json();

  const fixtures = fixturesData.data.slice(0, numGames);

  // Lógica de previsão com base nos fixtures
  // ...

  const result = document.getElementById("result");
  result.innerHTML = "Previsão: " + predictedResult;
}

function getStartDate() {
  const today = new Date();
  const startDate = new Date(today);
  startDate.setDate(today.getDate() - 365);
  return startDate.toISOString().substring(0, 10);
}

function getEndDate() {
  const today = new Date();
  return today.toISOString().substring(0, 10);
}
