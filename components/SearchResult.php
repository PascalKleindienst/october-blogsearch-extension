<?php namespace PKleindienst\BlogSearch\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Input;
use RainLab\Blog\Models\Category as BlogCategory;
use RainLab\Blog\Models\Post as BlogPost;
use Redirect;
// use System\Models\Parameters;

/**
 * Search Result component
 * @see RainLab\Blog\Components\Posts
 * @package PKleindienst\BlogSearch\Components
 */
class SearchResult extends ComponentBase
{
    /**
     * Parameter to use for the search
     * @var string
     */
    public $searchParam;

    /**
     * The search term
     * @var string
     */
    public $searchTerm;

    /**
     * A collection of posts to display
     * @var Collection
     */
    public $posts;

    /**
     * Parameter to use for the page number
     * @var string
     */
    public $pageParam;

    /**
     * Message to display when there are no messages.
     * @var string
     */
    public $noPostsMessage;

    /**
     * Reference to the page name for linking to posts.
     * @var string
     */
    public $postPage;

    /**
     * Reference to the page name for linking to categories.
     * @var string
     */
    public $categoryPage;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Search Result',
            'description' => 'Displays a list of blog posts that match the search term on the page.'
        ];
    }

    /**
     * @see RainLab\Blog\Components\Posts::defineProperties()
     * @return array
     */
    public function defineProperties()
    {
        // check build to add fallback to not supported inspector types if needed
        // $hasNewInspector = Parameters::get('system::core.build') >= 306;
        $categoryItems = BlogCategory::lists('name', 'id');

        return [
            'searchTerm' => [
                'title'       => 'Search Term',
                'description' => 'The value to determine what the user is searching for.',
                'type'        => 'string',
                'default'     => '{{ :search }}',
            ],
            'pageNumber' => [
                'title'       => 'rainlab.blog::lang.settings.posts_pagination',
                'description' => 'rainlab.blog::lang.settings.posts_pagination_description',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'disableUrlMapping' => [
                'title'       => 'Disable URL Mapping',
                'description' => 'If the url Mapping is disabled the search form uses the default GET Parameter q '
                                    . '(e.g. example.com/search?search=Foo instead of example.com/search/Foo)',
                'type'        => 'checkbox',
                'default'     => false,
                'showExternalParam' => false
            ],
            'hightlight' => [
                'title'       => 'Hightlight Matches',
                'type'        => 'checkbox',
                'default'     => false,
                'showExternalParam' => false
            ],
            'postsPerPage' => [
                'title'             => 'rainlab.blog::lang.settings.posts_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_per_page_validation',
                'default'           => '10',
            ],
            'noPostsMessage' => [
                'title'        => 'rainlab.blog::lang.settings.posts_no_posts',
                'description'  => 'rainlab.blog::lang.settings.posts_no_posts_description',
                'type'         => 'string',
                'default'      => 'No posts found',
                'showExternalParam' => false
            ],
            'sortOrder' => [
                'title'       => 'rainlab.blog::lang.settings.posts_order',
                'description' => 'rainlab.blog::lang.settings.posts_order_description',
                'type'        => 'dropdown',
                'default'     => 'published_at desc'
            ],
            'includeCategories' => [
                'title'       => 'Include Categories',
                'description' => 'Only Posts with selected categories are included in the search result',
                // 'type'        => $hasNewInspector ? 'set' : 'dropdown',
                'type'        => 'set',
                'items'       => $categoryItems,
                'group'       => 'Categories'
            ],
            'excludeCategories' => [
                'title'       => 'Exclude Categories',
                'description' => 'Posts with selected categories are excluded from the search result',
                // 'type'        => $hasNewInspector ? 'set' : 'dropdown',
                'type'        => 'set',
                'items'       => $categoryItems,
                'group'       => 'Categories'
            ],
            'categoryPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_category',
                'description' => 'rainlab.blog::lang.settings.posts_category_description',
                'type'        => 'dropdown',
                'default'     => 'blog/category',
                'group'       => 'Links',
            ],
            'postPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_post',
                'description' => 'rainlab.blog::lang.settings.posts_post_description',
                'type'        => 'dropdown',
                'default'     => 'blog/post',
                'group'       => 'Links',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getIncludeCategoriesOptions()
    {
        return BlogCategory::lists('name', 'id');
    }

    /**
     * @return array
     */
    public function getExcludeCategoriesOptions()
    {
        return BlogCategory::lists('name', 'id');
    }

    /**
     * @see RainLab\Blog\Components\Posts::getCategoryPageOptions()
     * @return mixed
     */
    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * @see RainLab\Blog\Components\Posts::getPostPageOptions()
     * @return mixed
     */
    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * @see RainLab\Blog\Components\Posts::getSortOrderOptions()
     * @return mixed
     */
    public function getSortOrderOptions()
    {
        return BlogPost::$allowedSortingOptions;
    }

    /**
     * @see RainLab\Blog\Components\Posts::onRun()
     * @return mixed
     */
    public function onRun()
    {
        $this->prepareVars();

        // map get request to :search param
        $searchTerm = Input::get('search');
        if (!$this->property('disableUrlMapping') && \Request::isMethod('get') && $searchTerm) {
            // add ?cats[] query string
            $cats = Input::get('cat');
            $query = http_build_query(['cat' => $cats]);
            $query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $query);
            $query = !empty($query) ? '?' . $query : '';

            return Redirect::to(
                $this->currentPageUrl([
                    $this->searchParam => urlencode($searchTerm)
                ])
                . $query
            );
        }

        // load posts
        $this->posts = $this->page[ 'posts' ] = $this->listPosts();

        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');

            if ($currentPage > ($lastPage = $this->posts->lastPage()) && $currentPage > 1) {
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
            }
        }
    }

    /**
     * @see RainLab\Blog\Components\Posts::prepareVars()
     */
    protected function prepareVars()
    {
        $this->pageParam = $this->page[ 'pageParam' ] = $this->paramName('pageNumber');
        $this->searchParam = $this->page[ 'searchParam' ] = $this->paramName('searchTerm');
        $this->searchTerm = $this->page[ 'searchTerm' ] = urldecode($this->property('searchTerm'));
        $this->noPostsMessage = $this->page[ 'noPostsMessage' ] = $this->property('noPostsMessage');

        if ($this->property('disableUrlMapping')) {
            $this->searchTerm = $this->page[ 'searchTerm' ] = urldecode(Input::get('search'));
        }

        /*
         * Page links
         */
        $this->postPage = $this->page[ 'postPage' ] = $this->property('postPage');
        $this->categoryPage = $this->page[ 'categoryPage' ] = $this->property('categoryPage');
    }

    /**
     * @see RainLab\Blog\Components\Posts::prepareVars()
     * @return mixed
     */
    protected function listPosts()
    {
        // Filter posts
        $posts = BlogPost::where(function ($q) {
            $q->where('title', 'LIKE', "%{$this->searchTerm}%")
                ->orWhere('content', 'LIKE', "%{$this->searchTerm}%")
                ->orWhere('excerpt', 'LIKE', "%{$this->searchTerm}%");
        });
        
        if (!is_null($this->property('includeCategories'))) {
            $posts = $posts->whereHas('categories', function ($q) {
                $q->whereIn('id', $this->property('includeCategories'));
            });
        }

        if (!is_null($this->property('excludeCategories'))) {
            $posts = $posts->whereDoesntHave('categories', function ($q) {
                $q->whereIn('id', $this->property('excludeCategories'));
            });
        }

        // filter categories
        $cat = Input::get('cat');
        if ($cat) {
            $cat = is_array($cat) ? $cat : [$cat];
            $posts->filterCategories($cat);
        }

        // List all the posts that match search terms, eager load their categories
        $posts = $posts->listFrontEnd([
            'page'    => $this->property('pageNumber'),
            'sort'    => $this->property('sortOrder'),
            'perPage' => $this->property('postsPerPage'),
        ]);

        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        $posts->each(function ($post) {
            $post->setUrl($this->postPage, $this->controller);

            $post->categories->each(function ($category) {
                $category->setUrl($this->categoryPage, $this->controller);
            });

            // apply highlight of search result
            $this->highlight($post);
        });

        return $posts;
    }

    /**
     * @param \RainLab\Blog\Models\Post $post
     */
    protected function highlight(BlogPost $post)
    {
        if ($this->property('hightlight')) {
            $searchTerm = preg_quote($this->searchTerm, '|');

            // apply highlight
            $post->title = preg_replace('|(' . $searchTerm . ')|iu', '<mark>$1</mark>', $post->title);
            $post->excerpt = preg_replace('|(' . $searchTerm . ')|iu', '<mark>$1</mark>', $post->excerpt);

            $post->content_html = preg_replace(
                '~(?![^<>]*>)(' . $searchTerm . ')~ismu',
                '<mark>$1</mark>',
                $post->content_html
            );
        }
    }
}
