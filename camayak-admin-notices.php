<?php

class camayak_admin_notices {

	var $package_field_name = 'cmyk-package-uuid';
	var $org_field_name = 'cmyk-organization-slug';
	var $protected_fields;

	function __construct() {
		$this->protected_fields = array( $this->package_field_name, $this->org_field_name );

		add_action( 'admin_notices', array( $this, 'admin_post_notices' ) );
	}
	function is_protected_meta( $protected, $meta_key ) {
		if ( in_array( $meta_key, $this->protected_fields ) ) {
			return true;
		}

		return $protected;
	}

	function admin_post_notices( ) {
		$screen = get_current_screen();

		if ( $screen->id == 'post' ) {
			global $post_ID;
			if ( isset( $post_ID ) ) {

				$cmyk_package_uuid = get_post_meta( $post_ID, $this->package_field_name, true );
				if ( ! empty( $cmyk_package_uuid ) ) {

					$cmyk_org_slug = get_post_meta( $post_ID, $this->org_field_name, true );
					if ( ! empty( $cmyk_org_slug ) ) {
						$camayak_url = 'https://' . $cmyk_org_slug . '.camayak.com/#/packages/' . $cmyk_package_uuid;
						$camayak_link_text = 'Edit this assignment in Camayak';
						$message = 'This post is being produced and archived in Camayak. <a href="' . $camayak_url . '">' . $camayak_link_text . '</a>';
						// display a warning message with link to assignment overview page in Camayak
						echo '<div class="error"><p style="height: 20px; line-height: 20px"><img src="';
						echo plugins_url( 'images/camayak_icon.png', __FILE__ );
						echo '" style="float: left; margin-right: 8px" alt="" />' . $message . '</p></div>';
						echo '<script>';
						echo '	( function( wp ) {';
						echo '		wp.data.dispatch("core/notices").createNotice(';
						echo '			"error", "This post is being produced and archived in Camayak.",';
						echo '			{';
						echo '				isDismissible: false,';
						echo '				actions: [{url: "' . $camayak_url . '", label: "' . $camayak_link_text . '",},],';
						echo '			}';
						echo '		);';
						echo '	} )( window.wp );';
						echo '</script>';

						// hide these custom fields from the UI to prevent tampering
						add_filter( 'is_protected_meta', array( $this, 'is_protected_meta' ), 1, 2 );
					}
				}
			}
		}
	}
}
