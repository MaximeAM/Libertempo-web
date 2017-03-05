<?php

define('ROOT_PATH', '../');
include ROOT_PATH . 'define.php';
defined( '_PHP_CONGES' ) or die( 'Restricted access' );

/*******************************************************************/
// SCRIPT DE MIGRATION DE LA VERSION 1.9 vers 1.10
/*******************************************************************/
include ROOT_PATH .'fonctions_conges.php' ;
include INCLUDE_PATH .'fonction.php';

$PHP_SELF = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);

$version = (isset($_GET['version']) ? $_GET['version'] : (isset($_POST['version']) ? $_POST['version'] : "")) ;
$version = htmlentities($version, ENT_QUOTES | ENT_HTML401);
$lang = (isset($_GET['lang']) ? $_GET['lang'] : (isset($_POST['lang']) ? $_POST['lang'] : "")) ;
$lang = htmlentities($lang, ENT_QUOTES | ENT_HTML401);
if (!in_array($lang, ['fr_FR', 'en_US', 'es_ES'], true)) {
    $lang = '';
}

$sql = \includes\SQL::singleton();
$sql->getPdoObj()->begin_transaction();
/* Migrations */
// recuperation de la version dans la config
// depot de la confi dans une table adaptee
// schema de la table version : majeur | mineur | patch | timestamp_last_patch
// on boucle
// goto api en fait, sinon on fera le dev deux fois. Si l'installation passe par l'api, le prérequis est la sécurité des accès. Et pour l'api, on a davantage de marge de manoeuvre, compte tenu qu'il n'y a rien. Il « suffira » de brancher l'un sur l'autre le moment venu

$sql->getPdoObj()->commit();

/* Goto next */
// on renvoit à la page mise_a_jour.php (là d'ou on vient)
echo "<a href=\"mise_a_jour.php?etape=2&version=$version&lang=$lang\">upgrade_from_v1.8  OK</a><br>\n";
