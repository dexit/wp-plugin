<?php
// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Get template part 
 *
 * @param        $slug
 * @param string $name
 * @param array  $args
 */
function {%= function_prefix %}_get_template_part( $slug, $name = '', $args = array() ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}
	$template = '';

	// Look in yourtheme/everhrm/slug-name.php and yourtheme/everhrm/slug.php
	$template = locate_template( array( {%= function_prefix %}()->template_path() . "{$slug}-{$name}.php", {%= function_prefix %}()->template_path() . "{$slug}.php" ) );

	// Get default slug-name.php.
	if ( ! $template && $name && file_exists( {%= function_prefix %}()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
		$template = {%= function_prefix %}()->plugin_path() . "/templates/{$slug}-{$name}.php";
	}

	if ( ! $template ) {
		$template = locate_template( array( "{$slug}.php", {%= function_prefix %}()->plugin_path() . "{$slug}.php" ) );
	}

	// Allow 3rd party plugin filter template file from their plugin
	$template = apply_filters( '{%= function_prefix %}_get_template_part', $template, $slug, $name );

	if ( ! file_exists( $template ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template ), '1.0.0' );

		return;
	}

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Locate and render template
 *
 * @since 1.0.0
 *
 * @param        $template_name
 * @param array  $args
 * @param string $template_path
 * @param string $default_path
 */
function {%= function_prefix %}_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args ); // @codingStandardsIgnoreLine
	}

	$located = {%= function_prefix %}_locate_template( $template_name, $template_path, $default_path );
	if ( ! file_exists( $located ) ) {
		/* translators: %s template */
		_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', '{%= text_domain %}' ), '<code>' . $located . '</code>' ), '1.0.0' );

		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( '{%= function_prefix %}_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( '{%= function_prefix %}_before_template_part', $template_name, $template_path, $located, $args );

	include $located;

	do_action( '{%= function_prefix %}_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate template path
 *
 * @since 1.0.0
 *
 * @param        $template_name
 * @param string $template_path
 * @param string $default_path
 *
 * @return string
 */
function {%= function_prefix %}_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = {%= function_prefix %}()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = {%= function_prefix %}()->plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template/.
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( '{%= function_prefix %}_locate_template', $template, $template_name, $template_path );
}

/**
 * Get the html of the template file
 *
 * @since 1.0.0
 *
 * @param        $template_name
 * @param array  $args
 * @param string $template_path
 * @param string $default_path
 *
 * @return string
 */
function {%= function_prefix %}_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	{%= function_prefix %}_get_template( $template_name, $args, $template_path, $default_path );

	return ob_get_clean();
}
