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
        $product = $sCommerce->getProduct((int)request()->i);
        $categories = array_unique(array_merge(
            [(int)($product->category ?? evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1)))],
            ($product->categories ? $product->categories->pluck('id')->toArray() : [(int)($product->category ?? evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1)))])
        ));
        $pTexts = $product->texts->toArray();
        $texts = [];
        foreach ($pTexts as $pText) {
            $texts[$pText['lang']] = $pText;
        }
        $data['product'] = $product;
        $data['categories'] = $categories;
        $data['texts'] = $texts;
        $data['editor'] = $sCommerce->textEditor("content");
        break;
    case "productSave":
        $sCommerce->saveProduct(request()->all());
        break;
    case "filter":
        $filter = $sCommerce->getFilter((int)request()->i);
        $categories = (
        $filter->categories ? $filter->categories->pluck('id')->toArray() : [(int)($filter->category ?? evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1)))]
        );
        $data['filter'] = $filter;
        $data['categories'] = $categories;
        break;
    case "filterSave":
        $sCommerce->saveFilter(request()->all());
        break;
}

$sCommerce->view('index', $data);