<?php namespace PKleindienst\BlogSearch;

use System\Classes\PluginBase;

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
            'icon'        => 'icon-search',
            'homepage'    => 'https://github.com/PascalKleindienst/october-blogsearch-extension'
        ];
    }

    /**
     * @return array
     */
    public function registerComponents()
    {
        return [
            'PKleindienst\BlogSearch\Components\SearchForm'   => 'searchForm',
            'PKleindienst\BlogSearch\Components\SearchResult' => 'searchResult'
        ];
    }

    /**
     * Register new Twig variables
     * @return array
     */
    public function registerMarkupTags()
    {
        // Check the translate plugin is installed
        if (class_exists('RainLab\Translate\Behaviors\TranslatableModel')) {
            return [];
        }

        return [
            'filters' => [
                '_'  => ['Lang', 'get'],
                '__' => ['Lang', 'choice']
            ]
        ];
    }
}
