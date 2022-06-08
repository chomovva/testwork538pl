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


class AjaxPart {


	/**
	 * Обработчик формы добавления отзыва
	 */
	public function insert_entry() {
		if (
			true
			&& current_user_can( 'edit_posts' )
			&& isset( $_POST[ 'nonce' ] )
			&& isset( $_POST[ 'data' ] )
			&& wp_verify_nonce( $_POST[ 'nonce' ], TESTWORK538PL_NAME . date( 'Y-m-d' ) )
		) {
			wp_parse_str( $_POST[ 'data' ], $data );
			$form_answer_content = '';
			$post_data = [
				'post_title'    => isset( $data[ 'title' ] ) ? trim( sanitize_text_field( $data[ 'title' ] ) ) : '',
				'post_status'   => 'draft',
				'post_author'   => get_current_user_id(),
				'post_type'     => TESTWORK538PL_POST_TYPE_NAME,
			];
			if ( wp_check_comment_disallowed_list( '', '', '', $post_data[ 'post_title' ], get_user_ip(), '' ) ) {
				$form_answer_file_path = get_template_file_path( [ 'form-error.php' ] );
				$form_answer_content = $form_answer_file_path ? render_template( $form_answer_file_path ) : __( 'Спам!', TESTWORK538PL_TEXTDOMAIN );
			} else {
				$post_id = wp_insert_post( $post_data );
				if ( $post_id ) {
					if ( isset( $data[ 'custom_create_date' ] ) && ! empty( $data[ 'custom_create_date' ] = sanitize_text_field( $data[ 'custom_create_date' ] ) ) ) {
						add_post_meta( $post_id, 'custom_create_date', $data[ 'custom_create_date' ], true );
					}
					if ( isset( $data[ 'price' ] ) && ! empty( $data[ 'price' ] = abs( $data[ 'price' ] ) ) ) {
						add_post_meta( $post_id, '_regular_price', ( float ) $data[ 'price' ] );
	    				add_post_meta( $post_id, '_price', ( float ) $data[ 'price' ] );
					}
					if (
						true
						&& current_user_can( 'upload_files' )
						&& isset( $_FILES[ 'custom_thumbnail' ][ 'type' ] )
						&& in_array( $_FILES[ 'custom_thumbnail' ][ 'type' ], apply_filters( TESTWORK538PL_NAME . '_custom_thumbnail_file_types', [
							'image/png',
							'image/jpeg',
							'image/jpg'
						] ) )
					) {
						$thumbnail_id = media_handle_upload( 'custom_thumbnail', $post_id, [
							'post_title'    => isset( $data[ 'title' ] ) ? sanitize_text_field( $data[ 'title' ] ) : '',
						], [ 'test_form' => false ] );
						if ( ! is_wp_error( $thumbnail_id ) ) {
							set_post_thumbnail( $post_id, $thumbnail_id );
							add_post_meta( $post_id, 'custom_thumbnail_id', $thumbnail_id, true );
						}
					}
					if ( isset( $data[ 'product_custom_type' ] ) && ! empty( $data[ 'product_custom_type' ] = wp_parse_id_list( $data[ 'product_custom_type' ] ) ) ) {
						wp_set_object_terms( $post_id, $data[ 'product_custom_type' ], 'product_custom_type', false );
					}
					$form_answer_file_path = get_template_file_path( [ 'form-success.php' ] );
					$form_answer_content = $form_answer_file_path ? render_template( $form_answer_file_path ) : __( 'Пост добавлен', TESTWORK538PL_TEXTDOMAIN );
					do_action( TESTWORK538PL_POST_TYPE_NAME . '_success', $post_id );
				} else {
					$form_answer_file_path = get_template_file_path( [ 'form-error.php' ] );
					$form_answer_content = $form_answer_file_path ? render_template( $form_answer_file_path ) : __( 'Ошибка', TESTWORK538PL_TEXTDOMAIN );
				}
			}
			wp_send_json_success( $form_answer_content );
		}
		wp_die();
	}


}