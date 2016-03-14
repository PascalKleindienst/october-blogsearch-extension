# Blog Search Extension [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PascalKleindienst/october-blogsearch-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PascalKleindienst/october-blogsearch-extension/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/PascalKleindienst/october-blogsearch-extension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/PascalKleindienst/october-blogsearch-extension/build-status/master)
This plugin is an extension to the [RainLab.Blog](https://github.com/rainlab/blog-plugin) plugin. With this extension you can simply search the blog posts' title and content and display the search results

#### Search Form
The `searchForm` component outputs a simple search form to search your posts.

- **Search Results Page** - Specify the page where you display the search results *(the page with the searchResult component)*
- **Show Category Filter** - Adds a dropdown with categories to the search, so users can restrict their search to a specific categorie

#### Search Results
The `searchResults` component returns all posts that match the search term from the search form.

- **Search Term** - The URL parameter defining the search term.
- **Page number** - The URL parameter defining the page number.
- **disableUrlMapping** - If the url Mapping is disabled the search form uses the default GET Parameter search *(e.g. `example.com/search?search=Foo` instead of `example.com/search/Foo`)*
- **Hightlight Matches** - Wrap the search terms found in the posts with `<mark>`-Tags or not 
- **Posts per page** - Number of posts to display per page.
- **No Posts Message** - Message to show if no posts where found.
- **Sort Order** - The order in which the posts are sorted.
- **Include Categories** - 'Only Posts with selected categories are included in the search result
- **Exclude Categories** - Specify which categories you want to exclude from your search results, so posts with them don't show up in the results.
- **Category Page** - The page where the blog posts are filtered by a category.
- **Post Page** - The page where single blog posts are displayed.

## Documentation
### Example Usage of Components
```html
title = "Search Result"
url = "/blog/search/:search?/:page?"
... other stuff

[searchResult]
searchTerm = "{{ :search }}"
pageNumber = "{{ :page }}"
hightlight = 1
postsPerPage = 10
noPostsMessage = "No posts found"
sortOrder = "published_at desc"
excludeCategories[] = 1
excludeCategories[] = 2
excludeCategories[] = 3
categoryPage = "blog"
postPage = "blog/posts"

[searchForm]
resultPage = "blog/search"
categoryFilter = 0
==
<div class="container">
    <div class="row">
        <div class="page-header">
            <h2>Blog Search</h2>
        </div>
        <div class="col-sm-8">{% component 'searchResult' %}</div>
        <div class="col-sm-4">{% component 'searchForm' %}</div>
    </div>
</div>
```

### Exclude/Include Categories
In order to exclude/include more than one category you need OctoberCMS Build >= 306 which brings the new Inspector Feature
