<?php
namespace BigupWeb\Reviews;

/**
 * Sanitization methods.
 *
 * @package bigup-reviews
 */
class Sanitize {

	/**
	 * Sanitize Callback
	 *
	 * Returns a callback which can be passed as a function call argument.
	 */
	public static function get_callback( $type ) {
		switch ( $type ) {

			case 'wp_post_name':
				return array( new Sanitize(), 'wp_post_name' );

			case 'alphanumeric':
				return array( new Sanitize(), 'alphanumeric' );

			case 'text':
				return array( new Sanitize(), 'text' );

			case 'date':
				return array( new Sanitize(), 'date' );

			case 'url':
				return array( new Sanitize(), 'url' );

			case 'email':
				return array( new Sanitize(), 'email' );

			case 'domain':
				return array( new Sanitize(), 'domain' );

			case 'port':
				return array( new Sanitize(), 'port' );

			case 'number':
				return array( new Sanitize(), 'number' );

			case 'checkbox':
				return array( new Sanitize(), 'checkbox' );

			case 'wp_post_key':
				return array( new Sanitize(), 'wp_post_key' );

			case 'key':
				return array( new Sanitize(), 'key' );

			case 'general_api_key':
				return array( new Sanitize(), 'general_api_key' );

			case 'image-upload':
				return array( new Sanitize(), 'number' );

			case 'rating':
				return array( new Sanitize(), 'rating' );

			default:
				error_log( "Bigup Plugin: Invalid sanitize type '{$type}' passed with option" );
		}
	}


	/**
	 * Sanitize a WordPress post name string.
	 */
	public static function wp_post_name( $wp_post_name ) {

		$alpha_wp_post_name = self::alphanumeric( $wp_post_name );
		$trim_wp_post_name  = trim( $alpha_wp_post_name );
		$clean_wp_post_name = substr( $trim_wp_post_name, 0, 30 );
		return $clean_wp_post_name;
	}


	/**
	 * Sanitize an alphanumeric string.
	 */
	public static function alphanumeric( $alphanumeric ) {

		$word_chars         = preg_replace( '/[£]||[^- \p{L}\p{N}]/', '', $alphanumeric );
		$no_uscore          = preg_replace( '/_/', '-', $word_chars );
		$single_hyphen      = preg_replace( '/--+/', '-', $no_uscore );
		$clean_alphanumeric = preg_replace( '/  +/', ' ', $single_hyphen );
		return $clean_alphanumeric;
	}


	/**
	 * Sanitize a text string.
	 */
	public static function text( $text ) {

		$clean_text = sanitize_text_field( $text );
		return $clean_text;
	}


	/**
	 * Sanitize a date.
	 */
	public static function date( $date ) {

		$sanitized_string = sanitize_text_field( $date );
		$clean_date       = strtotime( $sanitized_string );
		return $clean_date;
	}


	/**
	 * Sanitize a URL.
	 */
	public static function url( $url ) {

		$clean_url = filter_var( $url, FILTER_SANITIZE_URL );
		return $clean_url;
	}


	/**
	 * Sanitize an email.
	 */
	public static function email( $email ) {

		$clean_email = sanitize_email( $email );
		return $clean_email;
	}


	/**
	 * Sanitize a domain name.
	 */
	public static function domain( $domain ) {

		$ip = gethostbyname( $domain );
		$ip = filter_var( $ip, FILTER_VALIDATE_IP );

		if ( $domain == '' || $domain == null ) {
			return '';
		} elseif ( $ip ) {
			return $domain;
		} else {
			return 'INVALID DOMAIN';
		}
	}


	/**
	 * Sanitize a port number.
	 */
	public static function port( $port ) {

		$port = (int) $port;

		if ( is_int( $port )
			&& $port >= 1
			&& $port <= 65535 ) {
			return $port;
		} else {
			return '';
		}
	}


	/**
	 * Sanitize a number.
	 */
	public static function number( $number ) {

		$clean_number = (float) $number;
		return $clean_number;
	}

	/**
	 * Sanitize a rating number allowing a maximum of 2 decimals.
	 */
	public static function rating( $rating ) {

		$rounded = round( (float) $rating, 2, PHP_ROUND_HALF_UP );
		if ( $rounded > 5 ) {
			$clean_rating = 5;
		} elseif ( $rounded < 0 ) {
			$clean_rating = 0;
		}
		return $clean_rating;
	}


	/**
	 * Sanitize a checkbox.
	 */
	public static function checkbox( $checkbox ) {

		$bool_checkbox = (bool) $checkbox;
		$bool_checkbox = $bool_checkbox ? 1 : 0;
		return $bool_checkbox;
	}


	/**
	 * Sanitize a WP post type key.
	 */
	public static function wp_post_key( $wp_post_key ) {

		$sanitized         = sanitize_key( $wp_post_key );
		$clean_wp_post_key = substr( $sanitized, 0, 20 );
		return $clean_wp_post_key;
	}


	/**
	 * Sanitize a WP key.
	 */
	public static function key( $key ) {
		$clean_key = sanitize_key( $key );
		return $clean_key;
	}


	/**
	 * Sanitize a key with uppercase chars allowed.
	 */
	public static function general_api_key( $general_api_key ) {
		$clean_general_api_key = preg_replace( '/[^+-_.\p{L}\p{N}]/', '', $general_api_key );
		return $clean_general_api_key;
	}


	/**
	 * Sanitize an image ID.
	 */
	public static function image_id( $image_id ) {

		$int    = (int) $image_id;
		$exists = wp_get_attachment_image( $int );

		$clean_image_id = 0;
		if ( $exists ) {
			$clean_image_id = $int;
		}

		return $clean_image_id;
	}
}
