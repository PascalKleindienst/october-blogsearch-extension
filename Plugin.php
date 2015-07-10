<?php namespace PKleindienst\BlogSearch;

use System\Classes\PluginBase;
use System\Classes\PluginManager;

/**
 * blogSearch Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = ['RainLab.Blog'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Blog Search',
            'description' => 'Adds a search function to the blog',
            'author'      => 'Pascal Kleindienst',
            'icon'        => 'icon-search'
        ];
    }

    /**
     * @return array
     */
    public function registerComponents()
    {
        return [
            'PKleindienst\BlogSearch\Components\SearchForm' => 'searchForm',
            'PKleindienst\BlogSearch\Components\SearchResult' => 'searchResult',
        ];
    }

    /**
     * @return array
     */
    public function registerMarkupTags()
    {
        // add placebo filter if Translate Plugin does not exists, so things don't break
        if(!PluginManager::instance()->exists('Rainlab.Translate')) {
            return [
                'filters' => [
                    '_'  => [$this, 'placeboFilter'],
                    '__' => [$this, 'placeboFilter']
                ]
            ];
        }

        return [];
    }

    /**
     * Placebo Filter
     * @param $str
     * @param int $count
     * @param array $params
     * @return mixed
     */
    public function placeboFilter ($str, $count = 0, $params = [])
    {
        return $str;
    }
}
