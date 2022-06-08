<?php


namespace testwork538pl;


if ( ! defined( 'WPINC' ) ) {
	die;
}


$post_id = isset( $post ) ? $post->ID : ( isset( $_GET[ 'post' ] ) ? $_GET[ 'post' ] : null );

$custom_thumbnail_id = $post_id ? get_post_meta( $post_id, 'custom_thumbnail_id', true ) : 0;

$custom_create_date = $post_id ? get_post_meta( $post_id, 'custom_create_date', true ) : '';

$product_type_selected = $post_id ? wp_get_object_terms( $post_id, 'product_custom_type', [ 'fields' => 'ids' ] ) : '';
$product_type_labels = get_taxonomy_labels( get_taxonomy( 'product_custom_type' ) );
$product_type_terms = get_terms( [
	'taxonomy'   => 'product_custom_type',
	'hide_empty' => false,
	'fields'     => 'id=>name',
], '' );


?>


<div class="form-control">
	<label class="label" for="custom_thumbnail_id"><?php _e( 'Кастомное превью', TESTWORK538PL_TEXTDOMAIN ); ?></label>
	<div class="image-select" data-image-select="custom_thumbnail_id">
		<input id="custom_thumbnail_id" type="hidden" name="custom_thumbnail_id" value="<?php echo esc_attr( $custom_thumbnail_id ); ?>" />
		<?php if ( $custom_thumbnail_id ) : echo wp_get_attachment_image( $custom_thumbnail_id, 'thumbnail', false, [ 'class' => 'thumbnail' ] ); endif; ?>
		<button class="add" type="button" href="#"><?php _e( 'Добавить', TESTWORK538PL_TEXTDOMAIN ); ?></button>
		<button class="delete" type="button" href="#"><?php _e( 'Удалить', TESTWORK538PL_TEXTDOMAIN ); ?></button>
	</div>
</div>


<div class="form-control">
	<label class="label" for="custom_create_date"><?php _e( 'Дата создания продукта', TESTWORK538PL_TEXTDOMAIN ); ?></label>
	<input id="custom_create_date" class="regular-text" type="date" name="custom_create_date" value="<?php echo esc_attr( $custom_create_date ); ?>" />
</div>


<div class="form-control">
	<label class="label" for="product_custom_type"><?php echo $product_type_labels->singular_name; ?></label>
	<?php if ( is_array( $product_type_terms ) && ! empty( $product_type_terms ) ) : ?>		
		<select name="product_custom_type" id="product_custom_type" class="regular-text">
			<option value="0"></option>
			<?php foreach ( $product_type_terms as $term_id => $term_name ) : ?>
				<option value="<?php echo esc_attr( $term_id ); ?>" <?php selected( in_array( $term_id, $product_type_selected ), true, true ); ?> >
					<?php echo esc_html( $term_name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	<?php else : ?>
		<div class="msg"><?php _e( 'Таксономия не заполнена', TESTWORK538PL_TEXTDOMAIN ); ?></div>
	<?php endif; ?>
</div>

<div class="form-control">
	<button class="button button-reset metabox-reset" type="button"><?php _e( 'Очистить метабокс', TESTWORK538PL_TEXTDOMAIN ); ?></button>
	<button class="button button-primary custom-submit" type="button"><?php _e( 'Сохранить всё', TESTWORK538PL_TEXTDOMAIN ); ?></button>
</div>