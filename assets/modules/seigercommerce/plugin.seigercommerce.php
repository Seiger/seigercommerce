<?php
/**
 * Plugin for Seiger Commerce Management Module for Evolution CMS admin panel.
 */

use sCommerce\Models\sProduct;
use sCommerce\Models\sProductTranslate;

require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/sCommerce.class.php';

$e = evo()->event;
$sCommerce = new sCommerce();
$_lang = $sCommerce->managerLanguage();

/**
 * Base functionality
 */
if (in_array($e->name, ['OnPageNotFound', 'OnWebPageInit'])) {
    // Asynchronous request handler
    if (request()->ajax() && request()->has('ajax')) {
        require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/handlers/ajax.php';
    }

    // Catalog Filter Handler
    if (in_array("f", request()->segments())) {
        $keys = array_flip(request()->segments());
        if (isset(request()->segments()[$keys['f']+1]) && trim(request()->segments()[$keys['f']+1])) {
            evo()->setPlaceholder('filters', request()->segments()[$keys['f']+1]);

            // Go to the page
            $path = array_slice(request()->segments(), 0, ($keys['f']));
            if (count($path)) {
                $path = implode('/', $path);
                if (array_key_exists($path, UrlProcessor::getFacadeRoot()->documentListing)) {
                    $identifier = UrlProcessor::getFacadeRoot()->documentListing[$path];
                    evo()->sendForward($identifier);
                    exit();
                }
            }
        }
    }

    // Product Handler
    $productList = Cache::get('productList', []);
    if (isset($productList['/'.request()->path().'/'])) {
        $identifier = UrlProcessor::getFacadeRoot()->documentListing['product'];
        evo()->sendForward($identifier);
        exit();
    }
}

/**
 * Caching basic product data for fast lookups at the front
 *
 * Binding Product Alias and Product ID [Product Alias => Product ID]
 */
if ($e->name == 'OnCacheUpdate') {
    $productList = [];
    $products = sProduct::wherePublished(1)->get();
    if ($products) {
        foreach ($products as $product) {
            $productList[$product->link] = $product->id;
        }
    }
    Cache::forever('productList', $productList);
}

/**
 * Add icon to tree
 */
if ($e->name == 'OnManagerNodePrerender') {
    if (is_array($e->params) && count($e->params)) {
        switch ($e->params['ph']['id']) {
            case evo()->getConfig('catalog_root') :
                $e->params['ph']['icon'] = '<i class="fa fa-store"></i>';
                $e->params['ph']['icon_folder_open'] = "<i class='fa fa-store'></i>";
                $e->params['ph']['icon_folder_close'] = "<i class='fa fa-store'></i>";
                break;
        }
        $e->addOutput(serialize($e->params['ph']));
    }
}

/**
 * Add Menu item
 */
if ($e->name == 'OnManagerMenuPrerender') {
    $menu['scommerce'] = [
        "scommerce",
        "main",
        "<i class=\"fa fa-store\"></i> <span class=\"menu-item-text\">" . $_lang['scommerce_menu'] . "</span>",
        $sCommerce->url,
        $_lang['scommerce_menu'],
        "",
        "",
        "main",
        0,
        11,
        "",
    ];
    $e->addOutput(serialize(array_merge($e->params['menu'], $menu)));
}