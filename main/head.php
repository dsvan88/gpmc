<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script defer type='text/javascript' src='js/jquery-3.3.1.min.js'></script>
    <script defer type='text/javascript' src='js/jquery-ui.min.js'></script>
    <script defer type='text/javascript' src="js/jquery.datetimepicker.full.min.js"></script>
    <script defer type='text/javascript' src='js/jquery.cleditor.min.js'></script>
    <script defer type='text/javascript' src='js/get_script.php/?js=main_func'></script>
    <script defer type='text/javascript' src='js/get_script.php/?js=main'></script>

    <link rel="stylesheet" href="css/fonts/Lobster-Regular.css">
    <link rel='stylesheet' type="text/css" href='css/style.css?u=<?=$_SERVER['REQUEST_TIME']?>'/>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.structure.min.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.min.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery.cleditor.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.min.css"/>

    <? if (isset($_SESSION['ba']) && $_SESSION['ba'] > 0):?>
        <script defer type='text/javascript' src="js/ckeditor/ckeditor.js"></script>
        <script defer type='text/javascript' src='js/get_script.php/?js=admin'></script>
    <?endif?>
    <?if (isset($_SESSION['id']) && $_SESSION['id'] > 0):?>
        <script defer type='text/javascript' src='js/get_script.php/?js=users_scripts'></script>
    <?endif?>
    <?if (isset($_GET['profile'])):?>
        <script defer type='text/javascript' src='js/get_script.php/?js=profile'></script>
        <script defer type='text/javascript' src='js/jquery-cropper.js'></script>
        <link rel="stylesheet" type="text/css" href="css/cropper.css" />
    <?endif?>
    <?if (isset($_GET['trg'])):
        if ($_GET['trg'] ==='voting'):?>
            <script defer type='text/javascript' src='js/get_script.php/?js=voting'></script>
        <?elseif ($_GET['trg'] ==='evening'):?>
            <script defer type='text/javascript' src='js/get_script.php/?js=evening'></script>
        <?endif;?>
    <?endif;?>

    <?if (isset($_GET['g_id'])):?>
        <script defer type='text/javascript' src='js/get_script.php/?js=game'></script>
        <!-- <script defer type='text/javascript' src='js/get_script.php/?js=mafia_common_func'></script>
        <script defer type='text/javascript' src='js/get_script.php/?js=mafia_func'></script>
        <script defer type='text/javascript' src='js/get_script.php/?js=mafia'></script> -->
    <?endif?>
    <title>
        <?
        if ($_SESSION['ba'] > 0)
            echo 'Админ панель: ';
        echo MAFCLUB_SNAME.' Mafia v'.SCRIPT_VERSION;
        ?>
    </title>
</head>