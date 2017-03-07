=== ACF Photo Gallery Field ===
Contributors: navzme
Tags: acf, advanced, custom, fields, photo gallery, album, fancybox, litebox
Requires at least: 3.8
Tested up to: 4.7
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A cool plugin that extends the Advanced Custom Fields (ACF) functionality to add ‘Photo Gallery’ to any post/pages of your choice.

== Description ==
We are just a lightweight extension of Advanced Custom Field (ACF) that adds ‘Photo Gallery’ field to any post/pages on your WordPress website. 

* Visually create your Fields
* Add multiple photos and you can also modify title, caption and link to anything
* Assign your fields to multiple edit pages (via custom location rules)
* Easily load data through a simple and friendly API
* Uses the native WordPress custom post type for ease of use and fast processing
* Uses the native WordPress metadata for ease of use and fast processing

= Usage =
The following example is using Twitter Bootstrap framework to layout. You can use any framework of your choice.
`<?php
	//Get the images ids from the post_metadata
	$images = acf_photo_gallery('gallery_images', $post->ID);
	//Check if return array has anything in it
	if( count($images) ):
		//Cool, we got some data so now let's loop over it
		foreach($images as $image):
			$title = $image['title'];
			$caption= $image['content'];
			$full_image_url= $image['full_image_url'];
			$full_image_url = acf_photo_gallery_resize_image($full_image_url, 262, 160);
			$thumbnail_image_url= $image['thumbnail_image_url'];
			$url= $image['url'];
			$target= $image['target'];
?>
<div class="col-xs-6 col-md-3">
	<div class="thumbnail">
		<?php if( !empty($url) ){ ?><a href="<?php echo $url; ?>" <?php echo ($target == 'true' )? 'target="_blank"': ''; ?>><?php } ?>
			<img src="<?php echo $full_image_url; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>">
		<?php if( !empty($url) ){ ?></a><?php } ?>
	</div>
</div>
<?php endforeach; endif; ?>`

= Compatibility =
This ACF field type is compatible with:
* ACF 4

= Tested on =
* Mac Firefox 	:)
* Mac Safari 	:)
* Mac Chrome	:)
* PC Safari 	:)
* PC Chrome		:)
* PC Firefox	:)
* iPhone Safari :)
* iPad Safari 	:)
* PC ie7		:S

== Installation ==

1. Copy the `navz-photo-gallery` folder into your `wp-content/plugins` folder
2. Activate the Advanced Custom Fields: Photo Gallery plugin via the plugins admin page
3. Create a new field via ACF and select the Photo Gallery type
4. Please refer to the description for more info regarding the field type settings

== Changelog ==
= 1.4.0 =
* [Added] Can sortable gallery images
* [Bugfix] Fixed the order to follow the sort order for gallery images

= 1.3.0 =
* Change the meta_key from acf field_key to acf field_name
* Added helper function acf_photo_gallery() to pull images
* Added helper function acf_photo_gallery_resize_image to resize the image on fly and save it
* Fixed issue with target parameter in config
* Fixed issue with url not saving properly

= 1.2.0 =
* Bug fix for WordPress 4.6
* Some changes on on the display
* Changes on how to fetch the data from the database

= 1.1.0 =
* Bug fix for Undefined index: acf-photo-gallery-field on file acf-photo_gallery-v4
* When delete the photos from the gallery, the last photo was not deleting
* Changes to the gallery photo was saving in the database

= 1.0.0 =
* Initial Release.