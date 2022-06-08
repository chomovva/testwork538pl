<?php


namespace testwork538pl;


if ( ! defined( 'WPINC' ) ) {
	die;
}


$product_type_labels = get_taxonomy_labels( get_taxonomy( 'product_custom_type' ) );
$product_type_terms = get_terms( [
	'taxonomy'   => 'product_custom_type',
	'hide_empty' => false,
	'fields'     => 'id=>name',
], '' );


if ( is_user_logged_in() ) : ?>

	<?php if ( current_user_can( 'edit_posts' ) ) : ?>

		<form class="form insert-product-form" id="insert-product-form">

			<div class="form-group">
				<label for="title"><?php _e( 'Название товара', TESTWORK538PL_TEXTDOMAIN ); ?></label>
				<input type="text" id="title" name="title" value="" required />
			</div>

			<div class="form-group">
				<label for="price"><?php _e( 'Цена', TESTWORK538PL_TEXTDOMAIN ); ?></label>
				<input type="number" id="price" name="price" value="" min="0" required />
			</div>

			<?php if ( current_user_can( 'upload_files' ) ) : ?>
				<div class="form-group">
					<label for="custom_thumbnail_id"><?php _e( 'Кастомное превью', TESTWORK538PL_TEXTDOMAIN ); ?></label>
					<input type="file" id="custom_thumbnail" name="custom_thumbnail" value="" required />
				</div>
			<?php endif; ?>

			<div class="form-group">
				<label for="custom_create_date"><?php _e( 'Дата создания продукта', TESTWORK538PL_TEXTDOMAIN ); ?></label>
				<input type="date" id="custom_create_date" name="custom_create_date" value="" required />
			</div>

			<?php if ( is_array( $product_type_terms ) && ! empty( $product_type_terms ) ) : ?>	
				<div class="form-group">
					<label for="product_custom_type"><?php _e( 'Тип продукта', TESTWORK538PL_TEXTDOMAIN ); ?></label>
					<select name="product_custom_type" id="product_custom_type">
						<option value="0"></option>
						<?php foreach ( $product_type_terms as $term_id => $term_name ) : ?>
							<option value="<?php echo esc_attr( $term_id ); ?>" >
								<?php echo esc_html( $term_name ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<div class="form-group d-flex">
				<button class="mr-auto" type="reset"><?php esc_html_e( 'Очистить', TESTWORK538PL_TEXTDOMAIN ); ?></button>
				<button class="ml-auto" type="submit"><?php esc_html_e( 'Добавить', TESTWORK538PL_TEXTDOMAIN ); ?></button>
			</div>

		</form>

	<?php else : wpautop( __( 'У Вас недостаточно прав для добавления нового товара!', TESTWORK538PL_TEXTDOMAIN ) ); endif;

else : woocommerce_login_form(); endif;