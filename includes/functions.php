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
 * Получает опции плагина
 * @return   array
 * */
function get_plugin_options() {
	$result = get_option( TESTWORK538PL_NAME );
	$default = unserialize( TESTWORK538PL_OPTIONS );
	if ( ! is_array( $result ) ) {
		$result = [];
	}
	if ( ! is_array( $default ) ) {
		$default = [];
	}
	$result = array_merge( $default, $result );
	return $result;
}


/**
 * Ищет шаблон для вывода контента в текущей теме
 * @since    1.0.0
 * @param    string|array  $file  имя файла
 * @return   string               путь к файлу-шаблону
 */
function get_template_file_path( $file_names ) {
	$result = false;
	if ( ! is_array( $file_names ) ) {
		$file_names = [ $file_names ];
	}
	foreach ( $file_names as $file_name ) {
		$file_name = ltrim( $file_name, '/' );
		if ( ! empty( $file_name ) ) {
			$path = TESTWORK538PL_NAME . '/' . $file_name;
			$path = get_stylesheet_directory() . '/' . $path;
			if ( file_exists( $path ) ) {
				$result = $path;
			} else {
				$path = get_template_directory() . '/' . $path;
				if ( file_exists( $path ) ) {
					$result = $path;
				} else {
					$path = dirname( TESTWORK538PL_FILE ) . '/partials/' . $file_name;
					if ( file_exists( $path ) ) {
						$result = $path;
					}
				}
			}
		}
		if ( $result ) {
			break;
		}
	}
	return $result;
}


/**
 * Создаёт контент из файла
 * */
function render_template( $file_path, $echo = false ) {
	$result = '';
	if ( file_exists( $file_path ) ) {
		ob_start();
		include $file_path;
		$result = ob_get_clean();
		if ( $echo ) {
			echo $result;
		}
	}
	return $result;
}


/**
 * Получает ip пользователя
 * @since    1.0.0
 */
function get_user_ip() {
	$user_ip = '';
		if ( ! empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
			$user_ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
		} elseif ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
			$user_ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
		} else {
			$user_ip = $_SERVER[ 'REMOTE_ADDR' ];
		}
		return $user_ip;
}


/**
 *
 * */
function get_var_dump( $var ) {
	ob_start();
	var_dump( $var );
	return ob_get_clean();
}


/**
 *
 * */
function ucfirst_utf8( $str ) {
		return mb_substr( mb_strtoupper( $str, 'utf-8' ), 0, 1, 'utf-8' ) . mb_substr( mb_strtolower( $str, 'utf-8' ), 1, mb_strlen( $str ) - 1, 'utf-8' );
}


/**
 * Функция для очистки массива параметров
 * @param  array $default           расзерённые парметры и стандартные значения
 * @param  array $args              неочищенные параметры
 * @param  array $sanitize_callback одномерный массив с именами функция, с помощью поторых нужно очистить параметры
 * @param  array $required          обязательные параметры
 * @param  array $not_empty         параметры которые не могут быть пустыми
 * @return array                    возвращает ощиченный массив разрешённых параметров
 * */
function parse_only_allowed_args( $default, $args, $sanitize_callback = [], $required = [], $not_empty = [] ) {
	$args = ( array ) $args;
	$result = [];
	$count = 0;
	while ( ( $value = current( $default ) ) !== false ) {
		$key = key( $default );
		if ( array_key_exists( $key, $args ) ) {
			$result[ $key ] = $args[ $key ];
			if ( isset( $sanitize_callback[ $count ] ) && ! empty( $sanitize_callback[ $count ] ) ) {
				$result[ $key ] = $sanitize_callback[ $count ]( $result[ $key ] );
			}
		} elseif ( in_array( $key, $required ) ) {
			return null;
		} else {
			$result[ $key ] = $value;
		}
		if ( empty( $result[ $key ] ) && in_array( $key, $not_empty ) ) {
			return null;
		}
		$count = $count + 1;
		next( $default );
	}
	return $result;
}


/**
 * Проверяет является ли переданная строка валидным URL
 * @param  string  $url исходная строка
 * @return boolean      результат проверки
 */
function is_url( $url = '' ) {
	$result = false;
	if ( is_string( $url ) ) {
		$path = parse_url( $url, PHP_URL_PATH );
		$encoded_path = array_map( 'urlencode', explode( '/', $path ) );
		$url = str_replace( $path, implode( '/', $encoded_path ), $url );
		$result = filter_var( $url, FILTER_VALIDATE_URL) ? true : false;
	}
	return $result;
}


/**
 * Очистка даты
 * */
function sanitize_date( $date_string ) {
	$date_and_timezone = explode( '(', $date_string );
	$date_no_timezone  = trim( $date_and_timezone[0] );
	try {
		$date = new DateTime( $date_no_timezone );
	} catch ( \Exception $e ) {
		return new DateTime( '1970-01-01' );
	}

	return $date;
}


/**
 * Проверяет активен ли WC
 * @return   bool
 * */
function is_woo_active() {
	return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}