<?php


namespace testwork538pl;


/**
 * Отзывы
 *
 * @link              http://chomovva.ru/
 * @package           testwork538pl
 *
 * @wordpress-plugin
 * Plugin Name:       TestWork 538
 * Plugin URI:        https://theyard.in.ua/
 * Description:       Тестовая работа на вакансию Программист WordPress
 * Version:           1.0.0
 * Author:            chomovva
 * Author URI:        https://chomovva.ru/
 * Text Domain:       testwork538pl
 * Domain Path:       /languages
 * Network:           FALSE
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! defined( 'TESTWORK538PL_VERSION' ) ) {
	define( 'TESTWORK538PL_VERSION', '1.0.0' );
}


if ( ! defined( 'TESTWORK538PL_TEXTDOMAIN' ) ) {
	define( 'TESTWORK538PL_TEXTDOMAIN', 'testwork538pl' );
}


if ( ! defined( 'TESTWORK538PL_FILE' ) ) {
	define( 'TESTWORK538PL_FILE', __FILE__ );
}


if ( ! defined( 'TESTWORK538PL_BASENAME' ) ) {
	define( 'TESTWORK538PL_BASENAME', plugin_basename( TESTWORK538PL_FILE ) );
}


if ( ! defined( 'TESTWORK538PL_NAME' ) ) {
	define( 'TESTWORK538PL_NAME', 'testwork538pl' );
}


if ( ! defined( 'TESTWORK538PL_POST_TYPE_NAME' ) ) {
	define( 'TESTWORK538PL_POST_TYPE_NAME', 'product' );
}


if ( ! defined( 'TESTWORK538PL_OPTIONS' ) ) {
	define( 'TESTWORK538PL_OPTIONS', serialize( [
		'version'            => TESTWORK538PL_VERSION,
	] ) );
}


/**
 * Код который выполнится при активации плагина
 * Скрипт находится в файле includes/class-activator.php
 */
function activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	Activator::activate();
}


/**
 * Код который выполняется при деактивации плагина.
 * Скрипт находится в файле includes/class-deactivator.php
 */
function deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'testwork538pl\activate' );
register_deactivation_hook( __FILE__, 'testwork538pl\deactivate' );


/**
 * Подключение менеджер плагина в котором запускаются хуки, фильтры
 */

require plugin_dir_path( __FILE__ ) . 'includes/class-main.php';

require plugin_dir_path( __FILE__ ) . 'includes/functions.php';


/**
 * Запуск плагина
 */
function run() {
	$plugin = new Main();
	$plugin->run();
}

run();