let mafiaGame = null;
let mafiaTimer = null;
let eveningId = 0;
let load = false;
if (document.readyState == 'loading') {
	document.addEventListener('DOMContentLoaded', () => {
		mafiaGame = new MafiaGameLogic();
		mafiaTimer = new GameTimer(mafiaGame);
	});
} else {
	mafiaGame = new MafiaGameLogic();
	mafiaTimer = new GameTimer(mafiaGame);
}
mafiaGame.gameTable.querySelectorAll('[data-game-double-click-action]').forEach(element => element.addEventListener('dblclick', (event) => { mafiaGame[camelize(element.dataset.gameDoubleClickAction)].call(mafiaGame, event) }));