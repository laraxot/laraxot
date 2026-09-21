---
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
module: theme
topic: algolia_docsearch
canonical: ../../../Themes/docs/shared-components/algolia-docsearch_1.md
---

<<<<<<< HEAD
See canonical documentation: ../../../Themes/docs/shared-components/algolia-docsearch_1.md
=======
<<<<<<< HEAD
See canonical documentation: ../../../Themes/docs/shared-components/algolia-docsearch_1.md
=======
<<<<<<< HEAD
See canonical documentation: ../../../Themes/docs/shared-components/algolia-docsearch_1.md
=======
<<<<<<< HEAD
See canonical documentation: ../../../Themes/docs/shared-components/algolia-docsearch_1.md
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
title: Algolia DocSearch
description: Configure Algolia DocSearch with the Jigsaw docs starter template
extends: _layouts.documentation
section: content
---
# Algolia DocSearch {#algolia-docsearch}
This starter template includes support for [DocSearch](https://community.algolia.com/docsearch/), a documentation indexing and search tool provided by Algolia for free. To configure this tool, you’ll need to sign up with Algolia and set your API Key and index name in `config.php`. Algolia will then crawl your documentation regularly, and index all your content.
[Get your DocSearch credentials here.](https://community.algolia.com/docsearch/#join-docsearch-program)
```php
// config.php
return [
    'docsearchApiKey' => '',
    'docsearchIndexName' => '',
];
```
Once the `docsearchApiKey` and `docsearchIndexName` values are set in `config.php`, the search field at the top of the page is ready to use.
<img class="block m-auto" src="/assets/img/docsearch.png" alt="Screenshot of search results" />
To help Algolia index your pages correctly, it's good practice to add a unique `id` or `name` attribute to each heading tag (`<h1>`, `<h2>`, etc.). By doing so, a user will be taken directly to the appropriate section of the page when they click a search result.
## Adding Custom Styles {#algolia-adding-custom-styles}
If you'd like to customize the styling of the search results, Algolia exposes custom CSS classes that you can modify:
```css
/* Main dropdown wrapper */
.algolia-autocomplete .ds-dropdown-menu {
  width: 500px;
}
/* Main category (eg. Getting Started) */
.algolia-autocomplete .algolia-docsearch-suggestion--category-header {
  color: darkgray;
  border: 1px solid gray;
/* Category (eg. Downloads) */
.algolia-autocomplete .algolia-docsearch-suggestion--subcategory-column {
  color: gray;
/* Title (eg. Bootstrap CDN) */
.algolia-autocomplete .algolia-docsearch-suggestion--title {
  font-weight: bold;
  color: black;
/* Description description (eg. Bootstrap currently works...) */
.algolia-autocomplete .algolia-docsearch-suggestion--text {
  font-size: 0.8rem;
/* Highlighted text */
.algolia-autocomplete .algolia-docsearch-suggestion--highlight {
  color: blue;
For more details, visit the [official Algolia DocSearch documentation.](https://community.algolia.com/docsearch/what-is-docsearch.html)
### Versione HEAD
## Collegamenti tra versioni di algolia-docsearch.md
* [algolia-docsearch.md](../../../Chart/docs/algolia-docsearch.md)
* [algolia-docsearch.md](../../../Gdpr/docs/algolia-docsearch.md)
* [algolia-docsearch.md](../../../Xot/docs/algolia-docsearch.md)
* [algolia-docsearch.md](../../../UI/docs/algolia-docsearch.md)
* [algolia-docsearch.md](../../../Cms/docs/algolia-docsearch.md)
### Versione Incoming
* [algolia-docsearch.md](../../../Chart/project_docs/algolia-docsearch.md)
* [algolia-docsearch.md](../../../Gdpr/project_docs/algolia-docsearch.md)
* [algolia-docsearch.md](../../../Xot/project_docs/algolia-docsearch.md)
* [algolia-docsearch.md](../../../UI/project_docs/algolia-docsearch.md)
* [algolia-docsearch.md](../../../Cms/project_docs/algolia-docsearch.md)

<<<<<<< HEAD
```
=======
<<<<<<< HEAD
```
=======
### Versione Incoming

---
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
See canonical documentation: ../../../Themes/docs/shared-components/algolia-docsearch_1.md
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
