<?php


namespace testwork538pl;


use WP_Post;
use WP_Term;
use WP_Error;
use WP_Screen;
use WP_Post_Type;


if ( ! defined( 'WPINC' ) ) {
    die;
}



/**
 * Запускается при деактивации плагина
 *
 * В этом классе находится весь код, который необходимый при деактивации плагина.
 *
 * @since      1.0.0
 * @package    testwork538pl
 * @subpackage testwork538pl/includes
 * @author     chomovva <chomovva@gmail.com>
 */
class Deactivator {

	/**
	 * Действия при деактивации
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( TESTWORK538PL_NAME );
	}

}
