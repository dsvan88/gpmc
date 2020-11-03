let gameId = -1;
let players = {};
let vars = {}
let prevVars = [];
let prevPlayers = [];
let maxStepBacks = 10;
let MainTimer;
let load = false;

// if (document.readyState == 'loading') {
// 	document.addEventListener('DOMContentLoaded', getGameData);
// } else {
// 	getGameData();
// }

// function getGameData() {
// 	gameId = document.body.querySelector('table.content__game__table').dataset.gameId;
// 	postAjax({
// 		data: {
// 			need: "game-data",
// 			gid: gameId
// 		},
// 		successFunc: function (result) {
// 			result = JSON.parse(result);
// 			if (result["error"] === 0) {
// 				let data = JSON.parse(result["txt"]);
// 				players = JSON.parse(data['players']);
// 				vars = JSON.parse(data['vars']);
// 				console.log(players);
// 				console.log(vars);
// 			} else alert(result["txt"]);
// 		},
// 	});
// 	// let script = document.createElement('script');
// 	// script.src = window.location.protocol +'//'+ window.location.hostname + "/switcher.php/?need=script&php=game-data&gid=" + gameId;
// 	// document.head.append(script);
// 	// script.onload = function (event) {
// 	// 	console.log()
// 	// };
// }