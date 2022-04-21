<?php
/**
 * Class sCommerce - Seiger Commerce - E-commerce Management Module for Evolution CMS admin panel.
 */

require_once MODX_BASE_PATH . 'assets/modules/seigerсommerce/models/sCategory.php';
require_once MODX_BASE_PATH . 'assets/modules/seigerсommerce/models/sProduct.php';
require_once MODX_BASE_PATH . 'assets/modules/seigerсommerce/models/sProductTranslate.php';

use EvolutionCMS\Models\SiteModule;
use Illuminate\Pagination\Paginator;
use sCommerce\Models\sCategory;
use sCommerce\Models\sProduct;
use sCommerce\Models\sProductTranslate;

if (!class_exists('sCommerce')) {
    class sCommerce
    {
        public $url;
        public $perPage = 30;
        protected $basePath = MODX_BASE_PATH . 'assets/modules/seigerсommerce/';
        protected $categories = [];

        public function __construct()
        {
            $this->url = $this->moduleUrl();
            Paginator::defaultView('pagination');
        }

        /**
         * List products with default language
         *
         * @return array
         */
        public function products(): object
        {
            $order = 's_products.updated_at';
            $direc = 'desc';

            return sProduct::lang($this->langDefault())->orderBy($order, $direc)->get();
        }

        /**
         * Get product object with translation
         *
         * @param int $productId
         * @param string $lang
         * @return object
         */
        public function getProduct(int $productId, string $lang = ''): object
        {
            if (!trim($lang)) {
                $lang = $this->langDefault();
            }

            return sProduct::lang($lang)->whereProduct($productId)->first();
        }

        public function saveProduct($data)
        {
            $product = false;
            if ((int)$data['product']) {
                $product = sProduct::find((int)$data['product']);
            }
            if (!$product) {
                $product = new sProduct();
            }

            $product->published = (int)$data['published'];
            $product->availability = (int)$data['availability'];
            $product->status = (int)$data['status'];
            $product->category = (int)$data['category'];
            $product->position = (int)$data['position'];
            $product->type = (int)$data['type'];
            $product->rating = (int)$data['rating'];
            $product->code = (string)$data['code'];
            $product->alias = $this->validateAlias($data);
            $product->cover = $this->validateImage($data['cover']);
            $product->price = $this->validatePrice($data['price']);
            $product->price_old = $this->validatePrice($data['price_old']);
            $product->weight = $this->validatePrice($data['weight']);
            $product->save();

            foreach ($this->langTabs() as $lang => $label) {
                if (request()->has('texts.'.$lang)) {
                    $this->setProductTexts($product->id, $lang, request()->input('texts.'.$lang));
                }
            }

            /*if ($request->has('tags')) {
                $product->product = $product->id;
                $product->tags()->sync($request->get('tags'));
            }*/

            return header('Location: ' . $this->moduleUrl() . '&get=product&i=' . $product->id);
        }

        /**
         * List of categories and subcategories
         *
         * @return array
         */
        public function listCategories(): array
        {
            $root = sCategory::find(evo()->getConfig('catalog_root', 1));
            $this->categories[$root->id] = $root->pagetitle;
            if ($root->hasChildren()) {
                foreach ($root->children as $item) {
                    $this->categoryCrumb($item);
                }
            }
            return $this->categories;
        }

        /**
         * Default language
         *
         * @return string
         */
        public function langDefault(): string
        {
            return evo()->getConfig('s_lang_default', 'base');
        }

        /**
         * Display render
         *
         * @param $tpl
         * @param array $data
         * @return bool
         */
        public function view($tpl, array $data = [])
        {
            global $_lang;
            if (is_file($this->basePath . 'lang/' . evo()->getConfig('manager_language', 'uk') . '.php')) {
                require_once $this->basePath . 'lang/' . evo()->getConfig('manager_language', 'uk') . '.php';
            }
            if (is_file(MODX_BASE_PATH . 'assets/modules/seigerlang/lang/' . evo()->getConfig('manager_language', 'uk') . '.php')) {
                require_once MODX_BASE_PATH . 'assets/modules/seigerlang/lang/' . evo()->getConfig('manager_language', 'uk') . '.php';
            }

            $data = array_merge($data, ['modx' => evo(), 'data' => $data, '_lang' => $_lang]);

            View::getFinder()->setPaths([
                $this->basePath . 'views',
                MODX_MANAGER_PATH . 'views'
            ]);
            echo View::make($tpl, $data);
            return true;
        }

        /**
         * Content Tabs
         *
         * @return array
         */
        public function langTabs(): array
        {
            global $_lang;
            if (is_file($this->basePath . 'lang/' . evo()->getConfig('manager_language', 'uk') . '.php')) {
                require_once $this->basePath . 'lang/' . evo()->getConfig('manager_language', 'uk') . '.php';
            }
            $tabs = [];
            $lang = evo()->getConfig('s_lang_config', '');
            if (trim($lang)) {
                $lang = explode(',', $lang);
                foreach ($lang as $item) {
                    $tabs[$item] = $_lang['scommerce_texts'] . ' ' . $item;
                }
            } else {
                $tabs['base'] = $_lang['scommerce_texts'];
            }
            return $tabs;
        }

        /**
         * Saving Product texts
         *
         * @param int $productId
         * @param string $lang
         * @param array $fields
         * @return void
         */
        protected function setProductTexts(int $productId, string $lang, array $fields): void
        {
            sProductTranslate::updateOrCreate(['product' => $productId, 'lang' => $lang], $fields);
        }

        /**
         * Get Google Translations
         *
         * @param $text
         * @param string $source
         * @param string $target
         * @return string
         */
        protected function googleTranslate(string $text, string $source = 'ru', string $target = 'uk'): string
        {
            if ($source == $target) {
                return $text;
            }

            $out = '';

            // Google translate URL
            $url = 'https://translate.google.com/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=uk-RU&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e';
            $fields_string = 'sl=' . urlencode($source) . '&tl=' . urlencode($target) . '&q=' . urlencode($text);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 3);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_USERAGENT, 'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1');

            $result = curl_exec($ch);
            $result = json_decode($result, TRUE);

            if (isset($result['sentences'])) {
                foreach ($result['sentences'] as $s) {
                    $out .= isset($s['trans']) ? $s['trans'] : '';
                }
            } else {
                $out = 'No result';
            }

            if (preg_match('%^\p{Lu}%u', $text) && !preg_match('%^\p{Lu}%u', $out)) { // Если оригинал с заглавной буквы то делаем и певерод с заглавной
                $out = mb_strtoupper(mb_substr($out, 0, 1)) . mb_substr($out, 1);
            }

            return $out;
        }

        /**
         * Connecting the visual editor to the required fields
         *
         * @param string $ids List of id fields separated by commas
         * @param string $height Window height
         * @param string $editor Which editor to use TinyMCE5, CodeMirror
         * @return string
         */
        public function textEditor(string $ids, string $height = '500px', string $editor = ''): string
        {
            if (!trim($editor)) {
                $editor = evo()->getConfig('which_editor', 'TinyMCE5');
            }
            $elements = [];
            $ids = explode(",", $ids);
            $s_lang = evo()->getConfig('s_lang_config', '');

            if (trim($s_lang)) {
                $s_lang = explode(',', $s_lang);
                foreach ($s_lang as $lang) {
                    foreach ($ids as $id) {
                        $elements[] = trim($lang) . "_" . trim($id);
                    }
                }
            } else {
                foreach ($ids as $id) {
                    $elements[] = trim($id);
                }
            }

            return implode("", evo()->invokeEvent('OnRichTextEditorInit', [
                'editor' => $editor,
                'elements' => $elements,
                'height' => $height
            ]));
        }

        /**
         * Module link
         *
         * @return string
         */
        protected function moduleUrl(): string
        {
            $module = SiteModule::whereName('sCommerce')->first();
            return 'index.php?a=112&id=' . $module->id;
        }

        /**
         * Categories name as crumb
         *
         * @param $resource
         * @param $crumb
         * @return void
         */
        protected function categoryCrumb($resource, $crumb = ''): void
        {
            $crumb = trim($crumb) ? $crumb . ' > ' . $resource->pagetitle : $resource->pagetitle;
            $this->categories[$resource->id] = $crumb;
            if ($resource->hasChildren()) {
                foreach ($resource->children as $item) {
                    $this->categoryCrumb($item, $crumb);
                }
            }
        }

        /**
         * Alias validation
         *
         * @param $data
         * @return string
         */
        protected function validateAlias($data): string
        {
            if (trim($data['alias'])) {
                $alias = Str::slug(trim($data['alias']), '-');
            } elseif (isset($data['en_pagetitle']) && trim($data['en_pagetitle'])) {
                $alias = Str::slug(trim($data['en_pagetitle']), '-');
            } elseif (isset($data['base_pagetitle']) && trim($data['base_pagetitle'])) {
                $alias = Str::slug(trim($data['base_pagetitle']), '-');
            } else {
                $langDefault = evo()->getConfig('s_lang_default', 'uk');
                $alias = Str::slug(trim($langDefault . '_pagetitle'), '-');
            }

            $category = sCategory::withTrashed()->get('alias')->pluck('alias')->toArray();
            $products = sProduct::where('id', '<>', (int)$data['product'])->get('alias')->pluck('alias')->toArray();
            $aliases = array_merge($category, $products);

            if (in_array($alias, $aliases)) {
                $cnt = 1;
                $tempAlias = $alias;
                while (in_array($tempAlias, $aliases)) {
                    $tempAlias = $alias . $cnt;
                    $cnt++;
                }
                $alias = $tempAlias;
            }
            return $alias;
        }

        /**
         * Image validation
         *
         * @param string $image
         * @return string
         */
        protected function validateImage(string $image): string
        {
            $validImg = '';
            if (trim($image)) {
                if (is_file(MODX_BASE_PATH . $image)) {
                    $validImg = $image;
                }
            }
            return $validImg;
        }

        /**
         * Price validation
         *
         * @param mixed $price
         * @return float
         */
        protected function validatePrice(mixed $price): float
        {
            $validPrice = 0.00;

            if (is_int($price) || is_numeric($price)) {
                $price = floatval($price);
                $validPrice = floatval(number_format($price, 2, '.', ''));
            } elseif (is_float($price)) {
                $validPrice = floatval(number_format($price, 2, '.', ''));
            }

            return $validPrice;
        }
    }
}