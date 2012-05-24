<?php

class camayak_xmlrpc {

	function __construct() {
		add_action( 'xmlrpc_methods', array( &$this, 'cmyk_xmlrpc_methods' ) );
	}

	function cmyk_xmlrpc_methods( $methods ) {
		$methods['wp.getPostTerms'] = array( &$this, 'cmyk_wp_getPostTerms' );
		$methods['wp.setPostTerms'] = array( &$this, 'cmyk_wp_setPostTerms' );
		return $methods;
	}

	function cmyk_prepare_term( $term ) {
		$_term = $term;
		if ( ! is_array( $_term) )
			$_term = get_object_vars( $_term );

		// For Intergers which may be largeer than XMLRPC supports ensure we return strings.
		$_term['term_id'] = strval( $_term['term_id'] );
		$_term['term_group'] = strval( $_term['term_group'] );
		$_term['term_taxonomy_id'] = strval( $_term['term_taxonomy_id'] );
		$_term['parent'] = strval( $_term['parent'] );

		// Count we are happy to return as an Integer because people really shouldn't use Terms that much.
		$_term['count'] = intval( $_term['count'] );

		return apply_filters( 'xmlrpc_prepare_term', $_term, $term );
	}

	/**
	 * Retrieve post terms.
	 *
	 * The optional $group_by_taxonomy parameter specifies whether
	 * the returned array should have terms grouped by taxonomy or
	 * a flat list.
	 *
	 * @uses wp_get_object_terms()
	 * @param array $args Method parameters. Contains:
	 *  - int     $blog_id
	 *  - string  $username
	 *  - string  $password
	 *  - int     $post_id
	 *  - bool    $group_by_taxonomy optional
	 * @return array term data
	 */
	function cmyk_wp_getPostTerms( $args ) {
		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape( $args );

		$blog_id            = (int) $args[0];
		$username           = $args[1];
		$password           = $args[2];
		$post_id            = (int) $args[3];
		$group_by_taxonomy        = isset( $args[4] ) ? $args[4] : true;

		if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
			return $wp_xmlrpc_server->error;

		do_action( 'xmlrpc_call', 'wp.getPostTerms' );

		$post = wp_get_single_post( $post_id, ARRAY_A );
		if ( empty( $post['ID'] ) )
			return new IXR_Error( 404, __( 'Invalid post ID.' ) );

		$post_type = get_post_type_object( $post['post_type'] );

		if ( ! current_user_can( $post_type->cap->edit_post , $post_id ) )
			return new IXR_Error( 401, __( 'Sorry, you are not allowed to edit this post.' ) );

		$taxonomies = get_taxonomies( '' );

		$terms = wp_get_object_terms( $post_id , $taxonomies );

		if ( is_wp_error( $terms ) )
			return new IXR_Error( 500 , $terms->get_error_message() );

		$struct = array();

		foreach ( $terms as $term ) {
			if ( $group_by_taxonomy ) {
				$taxonomy = $term->taxonomy;

				if ( ! array_key_exists( $taxonomy, $struct ) )
					$struct[$taxonomy] = array();

				$struct[$taxonomy][] = $this->cmyk_prepare_term( $term );
			}
			else {
				$struct[] = $this->cmyk_prepare_term( $term );
			}
		}

		return $struct;
	}

	/**
	 * Set post terms.
	 *
	 * @uses wp_set_object_terms()
	 * @param array $args Method parameters. Contains:
	 *  - int     $blog_id
	 *  - string  $username
	 *  - string  $password
	 *  - int     $post_id
	 *  - array   $content_struct contains term_ids with taxonomy as keys
	 *  - bool    $append
	 * @return boolean true
	 */
	function cmyk_wp_setPostTerms( $args ) {
		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape( $args );

		$blog_id            = (int) $args[0];
		$username           = $args[1];
		$password           = $args[2];
		$post_ID            = (int) $args[3];
		$content_struct     = $args[4];
		$append             = $args[5] ? true : false;

		if ( ! $user = $wp_xmlrpc_server->login( $username, $password ) )
			return $wp_xmlrpc_server->error;

		do_action( 'xmlrpc_call', 'wp.setPostTerms' );

		$post = wp_get_single_post( $post_ID, ARRAY_A );
		if ( empty( $post['ID'] ) )
			return new IXR_Error( 404, __( 'Invalid post ID.' ) );

		$post_type = get_post_type_object( $post['post_type'] );

		if ( ! current_user_can( $post_type->cap->edit_post , $post_ID ) )
			return new IXR_Error( 401, __( 'Sorry, You are not allowed to edit this post.' ) );

		$post_type_taxonomies = get_object_taxonomies( $post['post_type'] );

		$taxonomies = array_keys( $content_struct );

		// validating term ids
		foreach ( $taxonomies as $taxonomy ) {
			if ( ! in_array( $taxonomy , $post_type_taxonomies ) )
				return new IXR_Error( 401, __( 'Sorry, one of the given taxonomy is not supported by the post type.' ) );

			$term_ids = $content_struct[$taxonomy];
			foreach ( $term_ids as $term_id ) {

				$term = get_term( $term_id, $taxonomy );

				if ( is_wp_error( $term ) )
					return new IXR_Error( 500, $term->get_error_message() );

				if ( ! $term )
					return new IXR_Error( 403, __( 'Invalid term ID' ) );
			}
		}

		foreach ( $taxonomies as $taxonomy ) {
			$term_ids = $content_struct[$taxonomy];
			$term_ids = array_map( 'intval', $term_ids );
			$term_ids = array_unique( $term_ids );
			wp_set_object_terms( $post_ID , $term_ids, $taxonomy , $append );
		}

		return true;
	}
}