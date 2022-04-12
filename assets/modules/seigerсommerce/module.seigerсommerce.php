<?php
/**
 * E-commerce Management Module
 */

if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') die("No access");

require_once MODX_BASE_PATH . 'assets/modules/seigerсommerce/sCommerce.class.php';

$sCommerce = new sCommerce();
$data['editor'] = '';
$data['get'] = $_REQUEST['get'] ?? "products";
$data['sCommerce'] = $sCommerce;
$data['lang_default'] = $sCommerce->langDefault();
$data['url'] = $sCommerce->url;

switch ($data['get']) {
    default:
        break;
}

$sCommerce->view('index', $data);