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


class AdminPart {

	/**
	 * Добавляет метабокс для параметров "Отзыва"
	 */
	public function render_notices() {
		foreach ( [
			'testwork538pl\is_woo_active' => 'notice-activate-wc',
		] as $func => $file_name ) {
			if ( ! $func() ) {
				$file_path = dirname( TESTWORK538PL_FILE ) . '/partials/' . $file_name . '.php';
				if ( file_exists( $file_path ) ) {
					wp_nonce_field( plugin_basename( __FILE__ ), TESTWORK538PL_NAME );
					include $file_path;
				}
			}
		}
	}


	/**
	 * Добавляет метабокс для параметров "Отзыва"
	 */
	public function add_meta_box() {
		add_meta_box( TESTWORK538PL_NAME, __( 'Кастомные параметры', TESTWORK538PL_TEXTDOMAIN ), [ $this, 'render_meta_box' ], [ TESTWORK538PL_POST_TYPE_NAME ], 'side', 'high', null );
	}


	/**
	 * Формирует html-код метабокса для параметров "Отзыва"
	 */
	public function render_meta_box( $post, $meta ) {
		$file_path = dirname( TESTWORK538PL_FILE ) . '/partials/meta-box-' . TESTWORK538PL_POST_TYPE_NAME . '.php';
		if ( file_exists( $file_path ) ) {
			wp_nonce_field( plugin_basename( __FILE__ ), TESTWORK538PL_NAME );
			include $file_path;
		}
	}


	/**
	 * Сохраняет метаданные поста
	 */
	public function save_post_data( $post_id ) {
		if ( ! isset( $_POST[ TESTWORK538PL_NAME ] ) ) return;
		if ( ! wp_verify_nonce( $_POST[ TESTWORK538PL_NAME ], plugin_basename( __FILE__ ) ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( TESTWORK538PL_POST_TYPE_NAME == get_post_type( $post_id ) ) {
			foreach ( apply_filters( TESTWORK538PL_POST_TYPE_NAME . '_custom_meta_filds', [
				'custom_thumbnail_id' => true,
				'custom_create_date'  => true,
			] ) as $meta_key => $unique ) {
				delete_post_meta( $post_id, $meta_key );
				$meta_value = '';
				if ( isset( $_REQUEST[ $meta_key ] ) ) {
					switch ( $meta_key ) {
						case 'custom_thumbnail_id': $meta_value = absint( $_REQUEST[ $meta_key ] ); break;
						case 'custom_create_date': $meta_value = sanitize_text_field( $_REQUEST[ $meta_key ] ); break;
					}
					if ( ! empty( $meta_value ) ) {
						if ( $unique ) {
							add_post_meta( $post_id, $meta_key, $meta_value, $unique );
						} else {
							if ( ! is_array( $meta_value ) ) {
								$meta_value = [ $meta_value ];
							}
							for ( $i = 0; $i < count( $meta_value ); $i++ ) { 
								add_post_meta( $post_id, $meta_key, $meta_value[ $i ], $unique );
							}
						}
					}
				}
			}
			foreach ( apply_filters( TESTWORK538PL_POST_TYPE_NAME . '_custom_taxonomy_names', [ 'product_custom_type' ] ) as $taxonomy_name ) {
				$terms = isset( $_REQUEST[ $taxonomy_name ] ) ? wp_parse_id_list( $_REQUEST[ $taxonomy_name ] ) : [];
				wp_set_object_terms( $post_id, $terms, $taxonomy_name, false );
			}
		}
	}


	/**
	 * Регистрирует скрипты и стили для страницы настроек
	 * */
	public function admin_enqueue( $hook ) {
		$current_screes = get_current_screen();
		if ( $current_screes instanceof WP_Screen && TESTWORK538PL_POST_TYPE_NAME == $current_screes->post_type ) {
			wp_enqueue_media();
			wp_enqueue_script( TESTWORK538PL_NAME, plugin_dir_url( TESTWORK538PL_FILE ) . '/assets/js/admin.js', [ 'jquery' ], TESTWORK538PL_VERSION, true );
			wp_enqueue_style( TESTWORK538PL_NAME, plugin_dir_url( TESTWORK538PL_FILE ) . '/assets/css/admin.css', [], TESTWORK538PL_VERSION, 'all' );
		}
	}


	/**
	 * Регистрирует колонки
	 * @param    array    $columns    Заголовки таблицы
	 * @return   array
	 */
	public function add_columns( $columns ) {
		if ( isset( $_GET[ 'post_type' ] ) && TESTWORK538PL_POST_TYPE_NAME == $_GET[ 'post_type' ] ) {
			$columns[ 'custom_create_date' ] = __( 'Кастомная дата', TESTWORK538PL_TEXTDOMAIN );
			$columns[ 'custom_thumbnail_id' ] = __( 'Кастомное превью', TESTWORK538PL_TEXTDOMAIN );
		}
		return $columns;
	}


	/**
	 * Выводит данные колонки
	 * @param    string    $colname    идентификатор колонки
	 * @param    int       $post_id    идентификатор поста
	 */
	public function render_columns( $colname, $post_id ) {
		switch ( $colname ) {
			case 'custom_create_date': echo get_post_meta( $post_id, 'custom_create_date', true ); break;
			case 'custom_thumbnail_id': echo wp_get_attachment_image( get_post_meta( $post_id, 'custom_thumbnail_id', true ), 'thumbnail', false, [ 'class' => 'custom-thumbnail' ] ); break;
		}
	}


}