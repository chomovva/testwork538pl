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
 * Файл, который определяет основной класс плагина
 *
 * @link       https://chomovva.ru
 * @since      1.0.0
 *
 * @package    testwork538pl
 * @subpackage testwork538pl/includes
 */


/**
 * Основной класс плагина, который запускает все хуки и фильры
 * @since      1.0.0
 * @package    testwork538pl
 * @subpackage testwork538pl/includes
 * @author     chomovva <chomovva@gmail.com>
 */
class Main {


	/**
	 * Массив хуков зарегистрирвоанных в WordPress
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    Хуки, зарегистрированные в WordPress, запускаются при загрузке плагина.
	 */
	protected $actions = [];


	/**
	 * Массив фильтров зарегистрирвоанных в WordPress
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    Фильтры, зарегистрированные в WordPress, запускаются при загрузке плагина.
	 */
	protected $filters = [];


	/**
	 * Массив зарегистрированных шорткодов
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $shortcodes    Шорткоды
	 */
	protected $shortcodes = [];


	/**
	 * Инициализация переменных плагина, подключение файлов.
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->actions = [];
		$this->filters = [];
		$this->load_init_dependencies();
		$this->define_init_hooks();
		if ( is_admin() ) {
			if ( wp_doing_ajax() ) {
				$this->load_ajax_dependencies();
				$this->define_ajax_hooks();
			} else {
				$this->load_admin_dependencies();
				$this->define_admin_hooks();
			}
		} else {
			$this->load_public_dependencies();
			$this->define_public_hooks();
		}
	}

	/**
	 * Подключает файлы с "зависимостями"
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_init_dependencies() {
		require_once dirname( TESTWORK538PL_FILE ) . '/includes/class-init.php';
	}


	/**
	 * Подключает файлы с "зависимостями" консоли сайта
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_admin_dependencies() {
		require_once dirname( TESTWORK538PL_FILE ) . '/includes/class-admin.php';
	}


	/**
	 * Подключает файлы с "зависимостями" для публичной части сайта
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_public_dependencies() {
		require_once dirname( TESTWORK538PL_FILE ) . '/includes/class-public.php';
	}


	/**
	 * Подключает файлы с "зависимостями" ajax запросов
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_ajax_dependencies() {
		require_once dirname( TESTWORK538PL_FILE ) . '/includes/class-ajax.php';
	}


	/**
	 * Добавляет в коллекцию хуки и фильтры необходимые для работы плагина
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_init_hooks() {
		$init_part = new InitPart();
		$this->actions[] = $this->add( 'plugins_loaded', $init_part, 'load_textdomain', 10, 0 );
		$this->actions[] = $this->add( 'init', $init_part, 'register_taxonomies', 10, 0 );
		$this->actions[] = $this->add( 'init', $init_part, 'register_taxonomies_for_object_types', 20, 0 );
		$this->filters[] = $this->add( 'wp_sitemaps_taxonomies', $init_part, 'remove_sitemaps_taxonomies', 10, 1 );
	}

 
	/**
	 * Регистрация хуков и фильтров для админ части плагина
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$admin_part = new AdminPart();
		$this->actions[] = $this->add( 'admin_notices', $admin_part, 'render_notices', 10, 0 );
		$this->actions[] = $this->add( 'add_meta_boxes', $admin_part, 'add_meta_box', 10, 0 );
		$this->actions[] = $this->add( 'save_post', $admin_part, 'save_post_data', 10, 1 );
		$this->actions[] = $this->add( 'admin_enqueue_scripts', $admin_part, 'admin_enqueue', 10, 1 );
		$this->actions[] = $this->add( 'manage_' . TESTWORK538PL_POST_TYPE_NAME . '_posts_custom_column', $admin_part, 'render_columns', 5, 2 );
		$this->filters[] = $this->add( 'manage_' . TESTWORK538PL_POST_TYPE_NAME . '_posts_columns', $admin_part, 'add_columns', 4, 1 );
	}

	/**
	 * Регистрация хуков и фильтров для публично части плагина
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$public_part = new PublicPart();
		$this->shortcodes[] = $this->add( 'insert_product_form', $public_part, 'shortode_manager' );
		$this->actions[] = $this->add( 'wp_enqueue_scripts', $public_part, 'enqueue_styles', 10, 0 );
		$this->actions[] = $this->add( 'wp_enqueue_scripts', $public_part, 'enqueue_scripts', 10, 0 );
	}


	/**
	 * Регистрация хуков и фильтров для ajax запросов
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_ajax_hooks() {
		$ajax_part = new AjaxPart();
		$this->actions[] = $this->add( 'wp_ajax_' . 'insert_entry', $ajax_part, 'insert_entry', 10, 0 );
	}


	/**
	 * Запск загрузчика для регистрации хукой, фильтров и шорткодов в WordPress
	 * @since    1.0.0
	 */
	public function run() {
		foreach ( $this->filters as $hook ) {
			add_filter( $hook[ 'hook' ], array( $hook[ 'component' ], $hook[ 'callback' ] ), $hook[ 'priority' ], $hook[ 'accepted_args' ] );
		}
		foreach ( $this->actions as $hook ) {
			add_action( $hook[ 'hook' ], array( $hook[ 'component' ], $hook[ 'callback' ] ), $hook[ 'priority' ], $hook[ 'accepted_args' ] );
		}
		foreach ( $this->shortcodes as $hook ) {
			add_shortcode( $hook[ 'hook' ], array( $hook[ 'component' ], $hook[ 'callback' ] ) );
			add_shortcode( mb_strtoupper( $hook[ 'hook' ] ), array( $hook[ 'component' ], $hook[ 'callback' ] ) );
		}
	}


	/**
	 * Добавляет фильтры и хуки в коллекцию
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hook, $component, $callback, $priority = null, $accepted_args = null ) {
		return [
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		];
	}


}