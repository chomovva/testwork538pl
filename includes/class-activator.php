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
 * Запускается при активации плагина
 *
 * @link       http://chomovva.ru
 * @since      1.0.0
 *
 * @package    testwork538pl
 * @subpackage testwork538pl/includes
 */

/**
 * Запускается при активации плагина.
 * В этом классе находится весь код, который необходимый при активации плагина.
 * @since      1.0.0
 * @package    testwork538pl
 * @subpackage testwork538pl/includes
 * @author     chomovva <chomovva@gmail.com>
 */
class Activator {


	/**
	 * Действия которые необходимо выполнить при активации
	 * @since    1.0.0
	 */
	public static function activate() {
		$options = unserialize( TESTWORK538PL_OPTIONS );
		update_option( TESTWORK538PL_NAME, $options );
	}


}