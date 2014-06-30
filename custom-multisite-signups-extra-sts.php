<?php

/*
Plugin Name:       Custom Multisite Signups Extras St Theresa
Plugin URI:        https://github.com/afragen/custom-multisite-signups-extra-sts
Description:       This plugin adds custom registration data using hooks to the <a href="https://github.com/afragen/custom-multisite-signups">Custom Multisite Signups</a> plugin for St. Theresa School.
Requires:          Custom Multisite Signups
Author:            Andy Fragen
Author URI:        http://thefragens.com
Version:           0.2.0
License:           GNU General Public License v2
License URI:       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
GitHub Plugin URI: https://github.com/afragen/custom-multisite-signups-extra-sts
GitHub Branch:     master
*/

class Custom_Multisite_Signups_Extras_STS extends Custom_Multisite_Signups {

	public function __construct() {
		add_filter( 'cms_add_extra_signup_fields', array( $this, 'extra_fields' ) );
		add_filter( 'cms_extra_fields_css_selectors', array( $this, 'extra_fields_selectors' ) );
		add_filter( 'cms_extra_fields_css', array( $this, 'extra_fields_css' ) );
		add_filter( 'cms_wpmu_validate_user_signup', array( $this, 'validate_user_signup' ) );
		add_action( 'cms_extra_signup_meta', array( $this, 'cms_extra_signup_meta' ) );
		add_filter( 'cms_show_extra_profile_fields', array( $this, 'how_extra_profile_fields' ) );
		add_action( 'cms_save_extra_profile_fields', array( $this, 'save_extra_profile_fields' ) );
	}

	public function extra_fields( $html ) {
		$street = isset( $_REQUEST['street'] ) ? (string) $_REQUEST['street'] : '';
		$extra_fields[] = '<label>Street</label>';
		$extra_fields[] = '<input id="street" name="street" type="text" value="' . $street . '" />';
	
		$zip = isset( $_REQUEST['zip'] ) ? (string) $_REQUEST['zip'] : '';
		$extra_fields[] = '<label>Zip</label>';
		$extra_fields[] = '<input id="zip" name="zip" type="text" value="' . $street . '" />';


		$html .=  implode( "\n", $extra_fields );
		echo $html;
	}

	public function extra_fields_selectors( $selectors ) {
		$selectors .= ', .mu_register #street, .mu_register #zip';
		return $selectors;
	}

	public function extra_fields_css( $css ) {
		$css .= ' /* CSS comment */ ';
		return $css;
	}

	public function validate_user_signup( $result ) {
		if ( empty( $_POST['street'] ) ) {
			$result['errors']->add( 'street', __( 'You must include a street address.' ) );
			echo '<p class="error">', $result['errors']->get_error_message('street'), '</p>';
		}

		if ( empty( $_POST['zip'] ) ) {
			$result['errors']->add( 'zip', __( 'You must include a street address.' ) );
			echo '<p class="error">', $result['errors']->get_error_message('zip'), '</p>';
		}

		return $result;
	}

	public function cms_extra_signup_meta() {
		return array(
				'street' => sanitize_text_field( $_POST['street'] ),
				'zip'    => sanitize_text_field( $_POST['zip'] ),
				);
	}

	public function show_extra_profile_fields( $user ) {

		$html[] = '<th><label for="street" id="street">' . __( 'Street' ) . '</label></th>';
		$html[] = '<td>';
		$html[] = '<input type="text" name="street" id="street" value="';
		$html[] = esc_attr( get_the_author_meta( 'street', $user->ID ) );
		$html[] = '" class="regular-text" /><br />';
		$html[] = '<span class="description">Please enter your street address.</span>';
		$html[] = '';

		$html[] = '<th><label for="zip" id="zip">' . __( 'Zip' ) . '</label></th>';
		$html[] = '<td>';
		$html[] = '<input type="text" name="zip" id="zip" value="';
		$html[] = esc_attr( get_the_author_meta( 'zip', $user->ID ) );
		$html[] = '" class="regular-text" /><br />';
		$html[] = '<span class="description">Please enter your zip code.</span>';
		$html[] = '';

		$html = implode( " ", $html );

		return $html;

	}

	public function save_extra_profile_fields() {
		return array( 'street', 'zip' );
	}

}