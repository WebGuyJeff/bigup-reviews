<?php
/**
 * Pattern
 *
 * @author Jefferson Real <me@jeffersonreal.uk>
 * @package bigup-cpt-review
 * @since 0.1.1
 *
 * Review Section
 *
 */

return array(
	'title'       => __( 'Review Section', 'bigup-cpt-review' ),
	'description' => _x( 'Display reviews in a section of wrapping-rows', 'Block pattern description', 'bigup-cpt-review' ),
	'categories'  => array( 'bigupweb-reviews' ),
	'keywords'    => array( 'reviews', 'section' ),
	'content'     => "<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"30px\",\"right\":\"30px\",\"bottom\":\"30px\",\"left\":\"30px\"}}},\"layout\":{\"inherit\":false}} -->\n<div class=\"wp-block-group\" style=\"padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px\"><!-- wp:query {\"queryId\":24,\"query\":{\"perPage\":6,\"pages\":0,\"offset\":0,\"postType\":\"review\",\"order\":\"desc\",\"orderBy\":\"date\",\"author\":\"\",\"search\":\"\",\"exclude\":[],\"sticky\":\"exclude\",\"inherit\":false}} -->\n<div class=\"wp-block-query\"><!-- wp:post-template {\"layout\":{\"type\":\"grid\",\"columnCount\":3}} -->\n<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"30px\",\"right\":\"30px\",\"bottom\":\"30px\",\"left\":\"30px\"}}},\"layout\":{\"inherit\":false}} -->\n<div class=\"wp-block-group\" style=\"padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px\"><!-- wp:post-title {\"level\":3,\"isLink\":true} /-->\n\n<!-- wp:post-excerpt /-->\n\n<!-- wp:bigupweb/review-source-url /-->\n\n<!-- wp:columns {\"verticalAlignment\":null} -->\n<div class=\"wp-block-columns\"><!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"33.33%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:33.33%\"><!-- wp:post-featured-image {\"width\":\"100px\",\"height\":\"100px\",\"scale\":\"contain\",\"align\":\"left\"} /--></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"66.66%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:66.66%\"><!-- wp:bigupweb/review-name /--></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:group -->\n<!-- /wp:post-template --></div>\n<!-- /wp:query --></div>\n<!-- /wp:group -->",
);
