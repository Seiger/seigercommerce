<?php
/**
 * Class sCommerce - Seiger Commerce - E-commerce Management Module for Evolution CMS admin panel.
 */

require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/models/sCategory.php';
require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/models/sProduct.php';
require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/models/sProductTranslate.php';
require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/models/sFilter.php';
require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/models/sFilterTranslate.php';
require_once MODX_BASE_PATH . 'assets/modules/seigercommerce/models/sFilterValue.php';

use EvolutionCMS\Models\SiteModule;
use Illuminate\Pagination\Paginator;
use sCommerce\Models\sCategory;
use sCommerce\Models\sFilter;
use sCommerce\Models\sFilterTranslate;
use sCommerce\Models\sFilterValue;
use sCommerce\Models\sProduct;
use sCommerce\Models\sProductTranslate;

if (!class_exists('sCommerce')) {
    class sCommerce
    {
        public $url;
        public $perPage = 30;
        protected $basePath = MODX_BASE_PATH . 'assets/modules/seigercommerce/';
        protected $categories = [];
        protected $tblFVals = 's_filter_values';

        public function __construct()
        {
            $this->url = $this->moduleUrl();
            $this->tblFVals = evo()->getDatabase()->getFullTableName($this->tblFVals);
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

            return sProduct::lang($lang)->whereProduct($productId)->first() ?? new sProduct();
        }

        /**
         * Ged all features where category as the product
         *
         * @param int $productId
         * @return object
         */
        public function getProductFeatures(int $productId): object
        {
            if ($productId) {
                $productAllCats = [];
                $product = $this->getProduct($productId);
                $categories = array_unique(array_merge(
                    [(int)($product->category ?? evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1)))],
                    (
                    $product->categories
                        ? $product->categories->pluck('id')->toArray()
                        : [(int)($product->category ?? evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1)))]
                    )
                ));

                foreach ($categories as $category) {
                    $productAllCats = array_merge($productAllCats, [$category], $this->categoryParentsIds($category));
                }

                $filters = sFilter::withLangCategories($this->langDefault(), $productAllCats)->orderBy('s_filters.position')->get();
            }

            return $filters ?? (object)[];
        }

        /**
         * Save the product with parameters
         *
         * @param array $data
         * @return void
         */
        public function saveProduct(array $data)
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

            if (isset($data['categories']) && is_array($data['categories'])) {
                $product->product = $product->id;
                $product->categories()->sync($data['categories']);
            }

            return header('Location: ' . $this->moduleUrl() . '&get=product&i=' . $product->id);
        }

        /**
         * List filters with default language
         *
         * @return array
         */
        public function filters(): object
        {
            $order = 's_filters.updated_at';
            $direc = 'desc';

            return sFilter::lang($this->langDefault())->orderBy($order, $direc)->get();
        }

        /**
         * Get filter object with translation
         *
         * @param int $filterId
         * @param string $lang
         * @return object
         */
        public function getFilter(int $filterId, string $lang = ''): object
        {
            if (!trim($lang)) {
                $lang = $this->langDefault();
            }

            return sFilter::lang($lang)->whereFilter($filterId)->first() ?? new sFilter();
        }

        /**
         * Save the filter with parameters
         *
         * @param array $data
         * @return void
         */
        public function saveFilter(array $data)
        {
            $filter = false;
            if ((int)$data['filter']) {
                $filter = sFilter::find((int)$data['filter']);
            }
            if (!$filter) {
                $filter = new sFilter();
            }

            $filter->type = (int)$data['type'];
            $filter->type_select = (int)$data['type_select'];
            $filter->position = (int)$data['position'];
            $filter->alias = $this->validateAlias(array_merge($data, $data['texts']), "filter");
            $filter->save();

            foreach ($this->langTabs() as $lang => $label) {
                if (isset($data['texts'][$lang]) && is_array($data['texts'][$lang])) {
                    $this->setFilterTexts($filter->id, $lang, $data['texts'][$lang]);
                }
            }

            if (isset($data['categories']) && is_array($data['categories'])) {
                $filter->filter = $filter->id;
                $filter->categories()->sync($data['categories']);
            }

            return header('Location: ' . $this->moduleUrl() . '&get=filter&i=' . $filter->id);
        }

        /**
         * List filter values
         *
         * @return array
         */
        public function filterValues(): object
        {
            $order = 's_filter_values.position';
            $direc = 'asc';

            return sFilterValue::whereFilter((int)request()->i)->orderBy($order, $direc)->get();
        }

        /**
         * Save the filter values
         *
         * @param array $data
         * @return void
         */
        public function saveFilterValues(array $data)
        {
            if ((int)$data['filter']) {
                $filterValues = sFilterValue::whereFilter((int)$data['filter'])->get();
                if (isset($data['values']) && is_array($data['values'])) {
                    $values = [];
                    $fields = array_keys($data['values']);
                    if (count($fields)) {
                        foreach ($data['values']['vid'] as $idx => $vid) {
                            $array = [];
                            foreach ($fields as $field) {
                                $array[$field] = $data['values'][$field][$idx];
                            }

                            if (count(array_diff($array, [""]))) {
                                $array['alias'] = $this->validateFilterValueAlias($array);
                                $array['filter'] = (int)$data['filter'];
                                $array['position'] = $idx;
                                unset($array['vid']);
                                $values[$array['alias']] = $array;
                            }
                        }
                    }

                    foreach ($filterValues as $filterValue) {
                        if (isset($values[$filterValue->alias])) {
                            foreach ($values[$filterValue->alias] as $field => $item) {
                                $filterValue->{$field} = $item;
                            }
                            $filterValue->update();

                            unset($values[$filterValue->alias]);
                        } else {
                            $filterValue->delete();
                        }
                    }

                    if (count($values)) {
                        foreach ($values as $value) {
                            $filterValue = new sFilterValue();
                            foreach ($value as $field => $item) {
                                $filterValue->{$field} = $item;
                            }
                            $filterValue->save();
                        }
                    }
                } else {
                    foreach ($filterValues as $filterValue) {
                        $filterValue->delete();
                    }
                }
            }

            return header('Location: ' . $this->moduleUrl() . '&get=filterValues&i=' . (int)$data['filter']);
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
         * Saving Filter texts
         *
         * @param int $filterId
         * @param string $lang
         * @param array $fields
         * @return void
         */
        protected function setFilterTexts(int $filterId, string $lang, array $fields): void
        {
            sFilterTranslate::updateOrCreate(['filter' => $filterId, 'lang' => $lang], $fields);
        }

        /**
         * Modifying table filter values for translates
         *
         * @return void
         */
        public function setModifyTables(): void
        {
            $lang = evo()->getConfig('s_lang_config', '');
            if (trim($lang)) {
                $needs = [];
                $columns = [];
                $lang = explode(',', $lang);
                $query = evo()->getDatabase()->query("DESCRIBE {$this->tblFVals}");

                if ($query) {
                    $fields = evo()->getDatabase()->makeArray($query);

                    foreach ($fields as $field) {
                        $columns[$field['Field']] = $field;
                    }

                    foreach ($lang as $item) {
                        if (!isset($columns[$item])) {
                            $needs[] = "ADD `{$item}` tinytext COMMENT '" . strtoupper($item) . " filter value version'";
                        }
                    }
                }

                if (count($needs)) {
                    $need = implode(', ', $needs);
                    $query = "ALTER TABLE `{$this->tblFVals}` {$need}";
                    evo()->getDatabase()->query($query);
                }
            }
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
         * Get all parents of the current category up to the root of the directory
         *
         * @param int $categoryId
         * @param array $array
         * @return array
         */
        protected function categoryParentsIds(int $categoryId, array $array = [])
        {
            if ($categoryId != evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1))) {
                $category = sCategory::find($categoryId);
                $parent = $category->getParent();
                $array[] = $parent->id;
                if ($categoryId != evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1))) {
                    $array = $this->categoryParentsIds($parent->id, $array);
                }
            }
            return $array;
        }

        /**
         * Alias validation
         *
         * @param $data
         * @param string $table
         * @return string
         */
        protected function validateAlias($data, string $table = ''): string
        {
            if (trim($data['alias'])) {
                $alias = Str::slug(trim($data['alias']), '-');
            } elseif (isset($data['en']['pagetitle']) && trim($data['en']['pagetitle'])) {
                $alias = Str::slug(trim($data['en']['pagetitle']), '-');
            } elseif (isset($data['base']['pagetitle']) && trim($data['base']['pagetitle'])) {
                $alias = Str::slug(trim($data['base']['pagetitle']), '-');
            } else {
                $langDefault = evo()->getConfig('s_lang_default', 'uk');
                $alias = Str::slug(trim($data[$langDefault]['pagetitle']), '-');
            }

            $category = sCategory::withTrashed()->get('alias')->pluck('alias')->toArray();
            switch ($table) {
                case "filter":
                    $others = sFilter::where('id', '<>', (int)$data['filter'])->get('alias')->pluck('alias')->toArray();
                    break;
                default:
                    $others = sProduct::where('id', '<>', (int)$data['product'])->get('alias')->pluck('alias')->toArray();
                    break;
            }
            $aliases = array_merge($category, $others);

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
         * Alias validation
         *
         * @param $data
         * @return string
         */
        protected function validateFilterValueAlias($data): string
        {
            if (trim($data['alias'])) {
                $alias = Str::slug(trim($data['alias']), '-');
            } elseif (isset($data['en']) && trim($data['en'])) {
                $alias = Str::slug(trim($data['en']), '-');
            } elseif (isset($data['base']) && trim($data['base'])) {
                $alias = Str::slug(trim($data['base']), '-');
            } else {
                $langDefault = evo()->getConfig('s_lang_default', 'uk');
                $alias = Str::slug(trim($data[$langDefault]), '-');
            }

            $aliases = sFilterValue::where('vid', '<>', (int)$data['vid'])->get('alias')->pluck('alias')->toArray();

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