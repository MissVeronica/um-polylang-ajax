<?php
/**
 * Plugin Name:     Ultimate Member - Polylang Ajax
 * Description:     Extension to Ultimate Member for Members Directory translations of Users hit counter with and without Polylang
 * Version:         2.2.0
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

    public $string = array( 0 => '{total_users} Members',
                            1 => '{total_users} Member',
                            2 => 'We are sorry. We cannot find any users who match your search criteria.' );

    function __construct() {

        add_filter( 'um_ajax_get_members_response', array( $this, 'um_ajax_get_members_response_headers' ), 100, 2 );
        //add_filter( 'pll_preferred_language',       array( $this, 'um_ajax__pll_preferred_language' ), 10, 2 );
        add_filter( 'after_setup_theme',            array( $this, 'um_ajax__pll_register_strings' ), 10, 1 );
    }

    public function um_ajax__pll_register_strings() {

        if ( defined( 'POLYLANG_VERSION' ) && function_exists( 'pll_register_string' ) ) {

            $group = 'Polylang';

            pll_register_string( 'pll_0', $this->string[0], $group );
            pll_register_string( 'pll_1', $this->string[1], $group );
            pll_register_string( 'pll_2', $this->string[2], $group );
        }
    }

    public function um_ajax_get_members_response_headers( $member_directory_response, $directory_data ) {
        
        if ( defined( 'POLYLANG_VERSION' ) && function_exists( 'pll__' )) {

            foreach( $this->string as $key => $string ) {
                $this->string[$key] = pll__( $string );
            }

        } else {

            foreach( $this->string as $key => $string ) {
                $this->string[$key] = __( $string, 'ultimate-member' );
            }
        }

        if ( empty( $directory_data['header'] )) {
            $member_directory_response['pagination']['header'] = UM()->Member_Directory()->convert_tags( $this->string[0], $member_directory_response['pagination'] );
        }

        if ( empty( $directory_data['header_single'] )) {
            $member_directory_response['pagination']['header_single'] = UM()->Member_Directory()->convert_tags( $this->string[1], $member_directory_response['pagination'] );
        }

        if ( empty( $directory_data['no_users'] )) {
            $member_directory_response['pagination']['no_users'] = $this->string[2];
        }

        return $member_directory_response;
    }

    public function um_ajax__pll_preferred_language( $slug, $cookie ) {  

        global $current_user;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX && is_user_logged_in() ) {

            $pll_locale = pll_default_language( 'locale' );            

            if ( um_user( 'locale' ) != $pll_locale ) {
                
                $page_id = UM()->options()->get( UM()->options()->get_core_page_id( 'members' ) );
                $languages = include POLYLANG_DIR . '/settings/languages.php';
                pll_set_post_language( $page_id, $languages[$pll_locale]['code'] );

                update_user_meta( $current_user->ID, 'locale', $pll_locale );
                UM()->user()->remove_cache( $current_user->ID );
                um_fetch_user( $current_user->ID );
            }
        }

        return $slug;
    }
    
}

new UM_Polylang_Ajax();
