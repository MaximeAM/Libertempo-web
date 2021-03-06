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

//suppression de la variable print_disable_users de la bdd
$del_conf_from_db="DELETE FROM conges_config WHERE conf_nom = 'print_disable_users';";
$res_del_conf_from_db=\includes\SQL::query($del_conf_from_db);

//suppression de l'option calcul de nombre de jours, par défaut c'est automatique
$del_config_db="DELETE FROM conges_config WHERE conf_nom = 'affiche_bouton_calcul_nb_jours_pris';";
$res_del_config_from_db=\includes\SQL::query($del_config_db);

$del_config_db="DELETE FROM conges_config WHERE conf_nom = 'rempli_auto_champ_nb_jours_pris';";
$res_del_config_from_db=\includes\SQL::query($del_config_db);

/* Ajout des champs de l'utilisateur requis pour l'API */
$alterApiUser = 'ALTER TABLE `conges_users`
    ADD `date_inscription` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD `token` VARCHAR(100) NOT NULL DEFAULT "",
    ADD INDEX `token` (`token`)';
$sql->query($alterApiUser);

/* Ajout du token d'instance */
$addApiToken = 'INSERT IGNORE INTO `conges_appli` VALUES ("token_instance", "")';
$sql->query($addApiToken);

$sql->getPdoObj()->commit();

$del_config_db="DELETE FROM conges_config WHERE conf_nom = 'disable_saise_champ_nb_jours_pris';";
$res_del_config_from_db=\includes\SQL::query($del_config_db);
