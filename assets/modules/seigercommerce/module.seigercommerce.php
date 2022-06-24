<?php
/**
 * E-commerce Management Module
 */

use EvolutionCMS\Models\SystemSetting;

if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') die("No access");

require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/sCommerce.class.php';

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
            (
            $product->categories
                ? $product->categories->pluck('id')->toArray()
                : [(int)($product->category ?? evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1)))]
            )
        ));
        $pTexts = $product->texts;
        $texts = [];
        foreach ($pTexts as $text) {
            $texts[$text['lang']] = $text->toArray();
        }
        $pFeatures = $product->features;
        $features  = [];
        foreach ($pFeatures as $feature) {
            $features[$feature->filter][$feature->vid] = $feature->toArray();
        }
        $data['product'] = $product;
        $data['categories'] = $categories;
        $data['texts'] = $texts;
        $data['features'] = $features;
        $data['editor'] = $sCommerce->textEditor("content");
        break;
    case "productSave":
        $sCommerce->saveProduct(request()->all());
        break;
    case "filterValues":
        $sCommerce->setModifyTables();
    case "filter":
        $filter = $sCommerce->getFilter((int)request()->i);
        $categories = (
        $filter->categories
            ? $filter->categories->pluck('id')->toArray()
            : [(int)($filter->category ?? evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1)))]
        );
        $fTexts = $filter->texts->toArray();
        $texts = [];
        foreach ($fTexts as $text) {
            $texts[$text['lang']] = $text;
        }
        if (!isset($texts[evo()->getConfig('s_lang_default', 'base')]) && isset($texts['base'])) {
            $texts[evo()->getConfig('s_lang_default', 'base')] = $texts['base'];
        }
        $data['filter'] = $filter;
        $data['categories'] = $categories;
        $data['texts'] = $texts;
        break;
    case "filterSave":
        $sCommerce->saveFilter(request()->all());
        break;
    case "filterValuesSave":
        $sCommerce->saveFilterValues(request()->all());
        break;
    case "configs":
        if (request()->isMethod('post')) {
            foreach (request()->post() as $key => $value) {
                if (is_scalar($value)) {
                    $setting = SystemSetting::whereSettingName($key)->firstOrCreate();
                    $setting->setting_name = $key;
                    $setting->setting_value = $value;
                    $setting->save();
                    evo()->setConfig($key, $value);
                }
            }
            evo()->clearCache('full');
        }
        break;
    case "template":
        $data['template'] = $sCommerce->getTemplate(request()->i);
        $data['editor'] = $sCommerce->textEditor("template", "500px", "Codemirror");
        break;
    case "templateSave":
        $sCommerce->saveTemplate(request()->all());
        break;
    case "promoCodeSave":
        $sCommerce->savePromoCode(request()->all());
        break;
}

$sCommerce->view('index', $data);