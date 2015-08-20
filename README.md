# Blog Search Extension [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PascalKleindienst/october-blogsearch-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PascalKleindienst/october-blogsearch-extension/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/PascalKleindienst/october-blogsearch-extension/badges/build.png?b=master)](https://scrutinizer-ci.com/g/PascalKleindienst/october-blogsearch-extension/build-status/master)
This plugin is an extension to the [RainLab.Blog](https://github.com/rainlab/blog-plugin) plugin. With this extension you can simply search the blog posts' title and content and display the search results

#### Search Form
The `searchForm` component outputs a simple search form to search your posts.

- **Search Results Page** - Specify the page where you display the search results *(the page with the searchResult component)*

#### Search Results
The `searchResults` component returns all posts that match the search term from the search form.

- **Search Term** - The URL parameter defining the search term.
- **Page number** - The URL parameter defining the page number.
- **Hightlight Matches** - Wrap the search terms found in the posts with `<mark>`-Tags or not 
- **Posts per page** - Number of posts to display per page.
- **No Posts Message** - Message to show if no posts where found.
- **Sort Order** - The order in which the posts are sorted.
- **Category Page** - The page where the blog posts are filtered by a category.
- **Post Page** - The page where single blog posts are displayed.