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


class InitPart {


	/**
	 * Подключает текстовый домен
	 * @since      1.0.0
	 * */
	public function load_textdomain() {
		load_plugin_textdomain( TESTWORK538PL_TEXTDOMAIN, false, dirname( plugin_basename( TESTWORK538PL_FILE ) ) . '/languages' ); 
	}


	/**
	 * Регистрация таксономий
	 * */
	public function register_taxonomies() {
		register_taxonomy( 'product_custom_type', [], [ 
			'label'                 => '',
			'labels'                => [
				'name'              => __( 'Тип продукта', TESTWORK538PL_TEXTDOMAIN ),
				'singular_name'     => __( 'Тип продукта' , TESTWORK538PL_TEXTDOMAIN ),
				'search_items'      => __( 'Найти запись' , TESTWORK538PL_TEXTDOMAIN ),
				'all_items'         => __( 'Смотреть все запиcи' , TESTWORK538PL_TEXTDOMAIN),
				'view_item '        => __( 'Смотреть запись' , TESTWORK538PL_TEXTDOMAIN),
				'parent_item'       => __( 'Родительская запись', TESTWORK538PL_TEXTDOMAIN ),
				'parent_item_colon' => __( 'Родительская запись' , TESTWORK538PL_TEXTDOMAIN),
				'edit_item'         => __( 'Редактировать запись' , TESTWORK538PL_TEXTDOMAIN),
				'update_item'       => __( 'Обновить запись', TESTWORK538PL_TEXTDOMAIN ),
				'add_new_item'      => __( 'Добавить новый тип продукта', TESTWORK538PL_TEXTDOMAIN ),
				'new_item_name'     => __( 'Новый тип' , TESTWORK538PL_TEXTDOMAIN),
				'menu_name'         => __( 'Тип продукта', TESTWORK538PL_TEXTDOMAIN ),
			],
			'description'           => '',
			'public'                => false,
			'publicly_queryable'    => false,
			'query_var'             => false,
			'show_in_nav_menus'     => false,
			'show_ui'               => true,
			'show_tagcloud'         => false,
			'show_in_rest'          => true,
			'rest_base'             => null,
			'hierarchical'          => false,
			'update_count_callback' => '',
			'rewrite'               => false,
			'capabilities'          => [],
			'meta_box_cb'           => false,
			'show_admin_column'     => true,
			'_builtin'              => false,
			'show_in_quick_edit'    => null,
		] );
	}


	/**
	 * Привязывает таксономии к типам постов.
	 */
	public function register_taxonomies_for_object_types() {
		register_taxonomy_for_object_type( 'product_custom_type', TESTWORK538PL_POST_TYPE_NAME );
	}


	/**
	 * Удаляет таксономии из карты сайта
	 * */
	public function remove_sitemaps_taxonomies( $taxonomies ) {
		unset( $taxonomies[ 'product_custom_type' ] );
		return $taxonomies;
	}


}