=== ACF Photo Gallery Field ===
Contributors: navzme
Donate link: https://www.buymeacoffee.com/navzme
Tags: acf, custom, fields, photo, gallery
Requires at least: 5.8
Tested up to: 6.5
Requires PHP: 7.0
Stable tag: 3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A lightweight extension of Advanced Custom Field (ACF) that adds Photo Gallery field to any post/pages on your WordPress website.

== Description ==
A lightweight extension of Advanced Custom Field (ACF) that adds **Photo Gallery** field to any post/pages on your WordPress website.

* Visually create your Fields
* Add multiple photos and you can also modify title, caption and link to anything
* Assign your fields to multiple edit pages (via custom location rules)
* Easily load data through a simple and friendly API
* Uses the native WordPress custom post type for ease of use and fast processing
* Uses the native WordPress metadata for ease of use and fast processing
* Supports WordPress classic and Gutenberg editor
* Supports Elementor (support for bricks builder coming soon)
* RESTFul API

= Note =
This plugin is a better alternative to ACF Gallery Pro. You need to have knowledge of coding because editing your WordPress theme source code is required. If you're looking for prebuilt galleries and shortcodes, then this plugin is NOT for you. This plugin will give you a PHP array of images, and you'll need to create the gallery layout yourself.

= Donation =
Navneil Naicker is the sole developer working on this free WordPress Plugin in his leisure time. He would like to integrate it with premium plugins like Elementor Pro and Advanced Custom Fields Pro. Please donate to support Navneil in continuing further development of this plugin. Click on the link “<https://www.buymeacoffee.com/navzme>” to donate.

= Usage =
*acf_photo_gallery* is a helper function that takes in **ACF_FIELD_NAME** and **POST_ID** will query the database and compile the images for you. The output of this function will be an array.

`acf_photo_gallery(ACF_FIELD_NAME, POST_ID);`

= Example =
The following example is using Twitter Bootstrap framework to layout. You can use any framework of your choice.

`<?php
	//Get the images ids from the post_metadata
	$images = acf_photo_gallery('vacation_photos', $post->ID);
	//Check if return array has anything in it
	if( count($images) ):
		//Cool, we got some data so now let's loop over it
		foreach($images as $image):
			$id = $image['id']; // The attachment id of the media
			$title = $image['title']; //The title
			$caption= $image['caption']; //The caption
			$full_image_url= $image['full_image_url']; //Full size image url
			$full_image_url = acf_photo_gallery_resize_image($full_image_url, 262, 160); //Resized size to 262px width by 160px height image url
			$thumbnail_image_url= $image['thumbnail_image_url']; //Get the thumbnail size image url 150px by 150px
			$url= $image['url']; //Goto any link when clicked
			$target= $image['target']; //Open normal or new tab
			$alt = get_field('photo_gallery_alt', $id); //Get the alt which is a extra field (See below how to add extra fields)
			$class = get_field('photo_gallery_class', $id); //Get the class which is a extra field (See below how to add extra fields)
?>
<div class="col-xs-6 col-md-3">
	<div class="thumbnail">
		<?php if( !empty($url) ){ ?><a href="<?php echo $url; ?>" <?php echo ($target == 'true' )? 'target="_blank"': ''; ?>><?php } ?>
			<img src="<?php echo $full_image_url; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>">
		<?php if( !empty($url) ){ ?></a><?php } ?>
	</div>
</div>
<?php endforeach; endif; ?>`

= Add Extra Fields =
To add extra fields add the following to your themes functions.php file.

`//Create extra fields called Altnative Text and Status
function my_extra_gallery_fields( $args, $attachment_id, $acf_key ){
    $args['alt'] = array(
		'type' => 'text', 
		'label' => 'Altnative Text', 
		'name' => 'alt', 
		'value' => get_field($field . '_alt', $attachment_id)
	);
    $args['status'] = array(
		'type' => 'select', 
		'label' => 'Status', 
		'name' => 'status', 
		'value' => array(
			array(
				'1' => 'Active',
				 '2' => 'Inactive'
			), 
			get_field($field . '_status', $attachment_id)
		)
	);
	return $args;
}
add_filter( 'acf_photo_gallery_image_fields', 'my_extra_gallery_fields', 10, 3 );`

Supported field types:
* text, date, color, datetime-local, email, number, tel, time, url, week, range, checkbox, radio, textarea, select

= How to get values of extra fields =
You can use ACF helper function `get_field`

`get_field('photo_gallery_alt', $id);`
`get_field('photo_gallery_class', $id);`

= Pull caption from attachment caption field =
By default the caption is being pulled from description field. Add the following filter to your `function.php` to pull the caption from attachment caption field.

`add_filter( 'acf_photo_gallery_caption_from_attachment', '__return_true' );`

= REST API =
Send HTTP Request to URL to get JSON response of all posts

`http://{domain}/wp-json/wp/v2/{POST_TYPE}/`

Send HTTP Request to URL to get JSON response of specific post

`http://{domain}/wp-json/wp/v2/{POST_TYPE}/{POST_ID}/`

When you receive the response, see the ACF item which contains ACF photo gallery name and array of images.

= Installation and basic usage tutorial =
https://www.youtube.com/watch?v=c7u9FwVLe9Q

= ACF Photo Gallery Field on WordPress Custom Post Type tutorial =
https://www.youtube.com/watch?v=5iTV0JVFFOE

= How to use Elementor dynamic tags with ACF Photo Gallery Field plugin tutorial =
https://www.youtube.com/watch?v=XlSx_weZXoU

= Compatibility =
This ACF field type is compatible with: ACF 4, 5 and 6

= Issues =
Just like any other WordPress plugin, this plugin can also cause issues with other themes and plugins. If you are facing issues making this plugin work on your WordPress site, please do ask for help in the support forum. This way we can help you out and prevent this issue from happening to someone else. If you want to talk to me directly, you can contact me via my website <http://www.navz.me/>

== Installation ==

From your WordPress dashboard

1. **Visit** Plugins > Add New
2. **Search** for "ACF Photo Gallery Field"
3. **Install and Activate** ACF Photo Gallery Field from your Plugins page
4. **Watch** the tutorial to [get started](https://www.youtube.com/watch?v=c7u9FwVLe9Q)

== Changelog ==
=3.0=
* [Fixed] URL and target not saving into the database.

=2.9=
* [Fixed] On image edit, popup modal not accessible on Gutenberg editor.

=2.8=
* [Fixed] Undefined array key "nonce".

=2.7=
* [Fixed] Tidy up few things.

=2.6=
* [Fixed] When click on "Add Images", WordPress media library not opening.

=2.5=
* [Fixed] Elementor dynamic tag offset index
* [Added] fallback for Elementor dynamic tag for versions prior to 3.16

=2.4=
* [Fixed] Undefined array key "option" error

=2.3=
* [Fixed] renamed a function to avoid conflict.

=2.2=
* [Fixed] Elementor Pro dynamic tag fix. Thanks to GitHub @rloes for the fix.
* [Added] Donation request popup. People gonna hate me for this. Sorry.

=2.1=
* [Fixed] explode(): Passing null to parameter error
* [Fixed] /wp-json/ not showing gallery images on custom post type. Thanks to GitHub @nykula for the fix.

=2.0=
* [Removed] Removed functionality from profile
* [Removed] errors and deprecating warnings

=1.9=
* [Added] Donation link because I'm poor
* [Removed] errors and deprecating warnings

=1.8.1=
* [Updated] Renamed function from 'my_profile_update' to 'apg_profile_update'
* [Bugfix] Backdrop not going away when the "Native" model is closed
* [Removed] SweetAlert and added native browser alert

=1.8.0=
* [Added] Support for ACF 6

=1.7.9=
* [Bugfix] Elementor Pro gallery images preview
* [Added] Support for user profile

=1.7.8=
* [Bugfix] Sanitizing and escaping inputs

=1.7.7=
* [Bugfix] Sanitizing and escaping inputs

=1.7.6=
* [Bugfix] Sanitizing and escaping inputs

=1.7.5=
* [Bugfix] Security issue and reset body overflow

=1.7.4=
* [Bugfix] Security issue

=1.7.3=
* [Bugfix] Remove edit and delete image button conditions

=1.7.2=
* [Bugfix] Illegal string offset error

=1.7.1=
* [Bugfix] ElementorPro class not found
* [Bugfix] Warning: Undefined array key "acf-photo-gallery-field"

=1.7.0=
* [Add] Option for to show/hide edit and remove button
* [Add] Support for Elementor

=1.6.8=
* [Bugfix] Make gallery images limit optional

=1.6.7=
* [Removed] Support to ACF get_field() function due to bug

=1.6.6=
* [Add] Added support to ACF get_field() function
* [Add] Added more fields types to extra fields
* [Add] Added in SweetAlert and images limit options
* [Add] Added message that ACF Photo Gallery isn't supported on taxonomy

=1.6.5=
* [Bugfix] Remove not empty condition from checkbox item on edit
* [Remove] Remove support for ACF to REST API plugin
* [Add] Native support for REST API

=1.6.4=
* [Bugfix] JavaScript error in the console when removing images from the WordPress metabox

=1.6.3=
* [Added] Support for ACF 5
* [Added] Edit gallery with built-in or WordPress native model. Thanks to Github @makepost
* [Added] Under the hood improvements of the codebase
* [Added] Support for RESTFul API with ACF to REST API plugin

=1.6.2=
* [Bugfix] Keep data synced with the attachment data
* [Bugfix] Pull caption attachment caption using add filters
* [Bugfix] PHP 7.2 count() error 

=1.6.1=
* [Bugfix] Edit image box disappears on click of pencil edit button reported by @rickytoof9

=1.6.0=
* [Bugfix] Error on saving post with empty gallery. Patch provided by @ugy
* [Bugfix] Showing multiple photo gallery fields in the same page reported by @rickytoof9
* [Added] Support for srcset. Special thanks to @ugy for the code

= 1.5.0 =
* [Added] Support for SVG
* [Added] Support for legacy PHP version
* [Added] Add extra fields

= 1.4.0 =
* [Added] Can sortable gallery images
* [Bugfix] Fixed the order to follow the sort order for gallery images

= 1.3.0 =
* Change the meta_key from acf field_key to acf field_name
* [Added] Helper function acf_photo_gallery() to pull images
* [Added] Helper function acf_photo_gallery_resize_image to resize the image on fly and save it
* [Bugfix] Issue with target parameter in config
* [Bugfix] Issue with url not saving properly

= 1.2.0 =
* [Bugfix] Support for WordPress 4.6
* [Added] Some changes on on the display
* [Added] Fetch the data from the database

= 1.1.0 =
* [Bugfix] Undefined index: acf-photo-gallery-field on file acf-photo_gallery-v4
* [Bugfix] When delete the photos from the gallery, the last photo was not deleting
* [Bugfix] Gallery photos was not saving in the database

= 1.0.0 =
* Initial Release.