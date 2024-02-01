<?php
/**
 * Pattern
 *
 * Review Query Loop
 *
 */

$markup = <<<END
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:query {"queryId":24,"query":{"perPage":10,"pages":0,"offset":0,"postType":"review","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"align":"wide","layout":{"type":"default"}} -->
	<div class="wp-block-query alignwide">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|40","left":"var:preset|spacing|40","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}},"elements":{"link":{"color":{"text":"var:preset|color|bur-bg"}}}},"backgroundColor":"bur-bg-alt","textColor":"bur-bg","className":"has-border-radius-medium","layout":{"inherit":false}} -->
		<div class="wp-block-group has-border-radius-medium has-bur-bg-color has-bur-bg-alt-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
				<p class="has-text-align-center" style="margin-bottom:var(--wp--preset--spacing--40)">⭐⭐⭐⭐⭐</p>
				<!-- /wp:paragraph -->
				<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"margin":{"top":"0","bottom":"0","left":"0","right":"0"}},"typography":{"fontStyle":"italic","fontWeight":"700"}},"fontFamily":"body"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
				<!-- wp:post-excerpt {"showMoreOnNewLine":false} /-->
				<!-- wp:bigup-reviews/review-source-url {"linkText":"See full review \u003e"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:columns -->
			<div class="wp-block-columns">
				<!-- wp:column {"width":"50%"} -->
				<div class="wp-block-column" style="flex-basis:50%">
					<!-- wp:bigup-reviews/review-name /-->
				</div>
				<!-- /wp:column -->
				<!-- wp:column {"width":"30%"} -->
				<div class="wp-block-column" style="flex-basis:30%">
					<!-- wp:post-featured-image {"aspectRatio":"1","width":"4rem","height":"","align":"center","style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"border":{"radius":"1rem","width":"0px","style":"none"}}} /-->
				</div>
				<!-- /wp:column -->
			</div>
			<!-- /wp:columns -->
		</div>
		<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
END;

return array(
	'title'       => __( 'Review Query Loop', 'bigup-cpt-review' ),
	'description' => _x( 'Display latest reviews as wrapping-cards', 'Block pattern description', 'bigup-cpt-review' ),
	'categories'  => array( 'bigup-reviews' ),
	'keywords'    => array( 'reviews', 'section' ),
	'content'     => $markup,
);