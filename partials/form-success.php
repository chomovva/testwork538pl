<?php


namespace testwork538pl;


if ( ! defined( 'WPINC' ) ) {
	die;
}


wpautop( sprintf(
	'%s <a href="%s">%s</a>',
	__( 'Товар добавлен!', TESTWORK538PL_TEXTDOMAIN ),
	get_permalink(),
	__( 'Добавить новый товар.', TESTWORK538PL_TEXTDOMAIN )
) );