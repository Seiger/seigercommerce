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
    case "product":
        $texts = [];
        $product = $sCommerce->getProduct(request()->i);
        $pTexts = $product->texts->toArray();
        foreach ($pTexts as $pText) {
            $texts[$pText['lang']] = $pText;
        }
        $data['product'] = $product;
        $data['texts'] = $texts;
        $data['editor'] = $sCommerce->textEditor("content,epilog");
        break;
    case "productSave":
        $sCommerce->saveProduct(request()->all());
        break;
}

$sCommerce->view('index', $data);