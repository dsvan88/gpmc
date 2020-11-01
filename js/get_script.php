<?
if (isset($_GET['js'])){
    header('Content-Type: text/javascript');
    require './scripts/'.$_GET['js'].'.js';
}
if (isset($_GET['php'])){
    require './scripts/'.$_GET['php'].'.php';
}