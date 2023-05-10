<?php
/**
 * Plugin Name:     Ultimate Member - Polylang Ajax
 * Description:     Extension to Ultimate Member for Members Directory translations of Users hit counter with and without Polylang
 * Version:         2.0.0
 * Requires PHP:    7.4
 * Author:          Miss Veronica
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Author URI:      https://github.com/MissVeronica
 * Text Domain:     ultimate-member
 * Domain Path:     /languages
 * UM version:      2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; 
if ( ! class_exists( 'UM' ) ) return;
 
class UM_Polylang_Ajax {

    function __construct() {

        add_filter( 'um_ajax_get_members_response', array( $this, 'um_ajax_get_members_response_headers' ), 100, 2 );
        add_filter( 'pll_preferred_language',       array( $this, 'um_ajax__pll_preferred_language' ), 10, 2 );

    }

    public function um_ajax_get_members_response_headers( $member_directory_response, $directory_data ) {

        if ( empty( $directory_data['header'] )) {
            $string = __( '{total_users} Members', 'ultimate-member' );
            $member_directory_response['pagination']['header'] = UM()->Member_Directory()->convert_tags( $string, $member_directory_response['pagination'] );
        }

        if ( empty( $directory_data['header_single'] )) {
            $string = __( '{total_users} Member', 'ultimate-member' );
            $member_directory_response['pagination']['header_single'] = UM()->Member_Directory()->convert_tags( $string, $member_directory_response['pagination'] );
        }

        if ( empty( $directory_data['no_users'] )) {
            $member_directory_response['pagination']['no_users'] = __( 'We are sorry. We cannot find any users who match your search criteria.', 'ultimate-member' );
        }

        return $member_directory_response;
    }

    public function um_ajax__pll_preferred_language( $slug, $cookie ) {  

        global $current_user;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX && is_user_logged_in() ) {

            $pll_locale = pll_default_language( 'locale' );
            if ( um_user( 'locale' ) != $pll_locale ) {
                update_user_meta( $current_user->ID, 'locale', $pll_locale );
                UM()->user()->remove_cache( $current_user->ID );
                um_fetch_user( $current_user->ID );
            }
        }

        return $slug;
    }
    
}

new UM_Polylang_Ajax();
