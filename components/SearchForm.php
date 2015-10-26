<?php namespace PKleindienst\BlogSearch\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use RainLab\Blog\Models\Category as BlogCategory;

/**
 * Search Form Component
 * @package PKleindienst\BlogSearch\Components
 */
class SearchForm extends ComponentBase
{
    /**
     * @var string Reference to the search results page.
     */
    public $resultPage;

    /**
     * @var array
     */
    public $categories = [];

    /**
     * @var bool
     */
    public $categoryFilter = false;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Search Form',
            'description' => 'Outputs a search form for the blog.'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'resultPage' => [
                'title'   => 'Search Results Page',
                'type'    => 'dropdown',
                'default' => 'blog/search'
            ],
            'categoryFilter' => [
                'title'   => 'Show Category Filter',
                'type'    => 'checkbox',
                'default' => 0
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function getResultPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Prepare vars
     */
    public function onRun()
    {
        $this->resultPage = $this->page[ 'resultPage' ] = $this->property('resultPage');
        $this->categories = $this->page[ 'categories' ] = BlogCategory::lists('name', 'id');
        $this->categoryFilter = $this->page[ 'categoryFilter' ] = $this->property('categoryFilter');
    }
}
