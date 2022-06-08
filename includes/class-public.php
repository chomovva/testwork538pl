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


class PublicPart {


	/**
	 * Менеджер шорткодов, выбирает и запускает нужные методы
	 * @param  array       $atts           аргументы шорткода
	 * @param  string      $content        контент между "тегами" шорткода
	 * @param  string|null $shortcode_name имя шорткода
	 * @return [type]                      html-код
	 */
	public function shortode_manager( $atts = [], $content = '', $shortcode_name = null ) {
		if ( null != $shortcode_name ) {
			$form_file_path = false;
			switch ( $shortcode_name ) {
				case 'insert_product_form':
					$form_file_path = get_template_file_path( [ 'form-template.php' ] );
					break;
			}
			if ( file_exists( $form_file_path ) ) {			
				$content = render_template( $form_file_path );
			}
		}
		return $content;
	}


	/**
	 * Регистрирует стили для админки
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( TESTWORK538PL_NAME, plugin_dir_url( TESTWORK538PL_FILE ) . 'assets/css/public.css', array(), TESTWORK538PL_VERSION, 'all' );
	}


	/**
	 * Регистрирует скрипты для админки
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( TESTWORK538PL_NAME, plugin_dir_url( TESTWORK538PL_FILE ) . 'assets/js/public.js',  array( 'jquery' ), TESTWORK538PL_VERSION, false );
		wp_localize_script( TESTWORK538PL_NAME, TESTWORK538PL_NAME, [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( TESTWORK538PL_NAME . date( 'Y-m-d' ) ),
		] );
	}


}