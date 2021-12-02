<?
$output['{SCRIPTS}'] .= '
    <script defer type="text/javascript" src="js/jquery.cleditor.min.js"></script>
    <script defer type="text/javascript" src="js/get_script.php/?js=game-mafia-main-funcs"></script>
    <script defer type="text/javascript" src="js/get_script.php/?js=game-mafia-logic"></script>
    <script defer type="text/javascript" src="js/get_script.php/?js=game-timer"></script>
    <script defer type="text/javascript" src="js/get_script.php/?js=game"></script>
';

$output['{MAIN_CONTENT}'] = '
    <section class="section game-in-progress">
        {GAME_TIMER}
        <div class="game__content">{GAME_MAIN_TABLE}</div>
    </section>';

require_once $_SERVER['DOCUMENT_ROOT'].'/views/game/game-load-status.php';