# Load More Posts

Easily add an ajax load more button.

Usage
-----

The Load More Posts plugin allows you to easily set up an ajax load more button.

Simply add the function

`load_more_button();`

into your code where you desire the load button to appear.

Templates
-----

By default the load more posts plugin will use `content-default.php` with `content.php` as a fallback to load the layout of the new content.  By passing a context parameter to the `load_more_button()` function, you can specify a custom content template to use for the newly loaded content. 

Examples
-----
`load_more_button( 'homepage', 'Get More Articles' );`

Will load a button with the label 'Get More Articles' which uses `content-homepage.php` as the template for newly loaded content.  This setup works well with index pages with the loop set up as:

```
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		get_template_part( 'content', 'homepage' );
	endwhile; // end of the loop.
endif;
load_more_button( 'homepage' );
```
