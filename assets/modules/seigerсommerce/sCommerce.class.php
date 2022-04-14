<?php
/**
 * Class sCommerce - Seiger Commerce - E-commerce Management Module for Evolution CMS admin panel.
 */

require_once MODX_BASE_PATH . 'assets/modules/seigerсommerce/models/sProduct.php';

use EvolutionCMS\Models\SiteModule;
use Illuminate\Pagination\Paginator;
use sCommerce\Models\sProduct;

if (!class_exists('sCommerce')) {
    class sCommerce
    {
        public $url;
        public $perPage = 30;
        protected $basePath = MODX_BASE_PATH . 'assets/modules/seigerсommerce/';

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
         * Get Google Translations
         *
         * @param $text
         * @param string $source
         * @param string $target
         * @return string
         */
        protected function googleTranslate(string $text, string $source = 'ru', string $target = 'uk'): string
        {
            if ($source == 'ind') {
                $source = 'id';
            }
            if ($target == 'ind') {
                $target = 'id';
            }

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
    }
}