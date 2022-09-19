<?php
 function get_column_headers( $screen ) { static $column_headers = array(); if ( is_string( $screen ) ) { $screen = convert_to_screen( $screen ); } if ( ! isset( $column_headers[ $screen->id ] ) ) { $column_headers[ $screen->id ] = apply_filters( "manage_{$screen->id}_columns", array() ); } return $column_headers[ $screen->id ]; } function get_hidden_columns( $screen ) { if ( is_string( $screen ) ) { $screen = convert_to_screen( $screen ); } $hidden = get_user_option( 'manage' . $screen->id . 'columnshidden' ); $use_defaults = ! is_array( $hidden ); if ( $use_defaults ) { $hidden = array(); $hidden = apply_filters( 'default_hidden_columns', $hidden, $screen ); } return apply_filters( 'hidden_columns', $hidden, $screen, $use_defaults ); } function meta_box_prefs( $screen ) { global $wp_meta_boxes; if ( is_string( $screen ) ) { $screen = convert_to_screen( $screen ); } if ( empty( $wp_meta_boxes[ $screen->id ] ) ) { return; } $hidden = get_hidden_meta_boxes( $screen ); foreach ( array_keys( $wp_meta_boxes[ $screen->id ] ) as $context ) { foreach ( array( 'high', 'core', 'default', 'low' ) as $priority ) { if ( ! isset( $wp_meta_boxes[ $screen->id ][ $context ][ $priority ] ) ) { continue; } foreach ( $wp_meta_boxes[ $screen->id ][ $context ][ $priority ] as $box ) { if ( false === $box || ! $box['title'] ) { continue; } if ( 'submitdiv' === $box['id'] || 'linksubmitdiv' === $box['id'] ) { continue; } $widget_title = $box['title']; if ( is_array( $box['args'] ) && isset( $box['args']['__widget_basename'] ) ) { $widget_title = $box['args']['__widget_basename']; } $is_hidden = in_array( $box['id'], $hidden, true ); printf( '<label for="%1$s-hide"><input class="hide-postbox-tog" name="%1$s-hide" type="checkbox" id="%1$s-hide" value="%1$s" %2$s />%3$s</label>', esc_attr( $box['id'] ), checked( $is_hidden, false, false ), $widget_title ); } } } } function get_hidden_meta_boxes( $screen ) { if ( is_string( $screen ) ) { $screen = convert_to_screen( $screen ); } $hidden = get_user_option( "metaboxhidden_{$screen->id}" ); $use_defaults = ! is_array( $hidden ); if ( $use_defaults ) { $hidden = array(); if ( 'post' === $screen->base ) { if ( in_array( $screen->post_type, array( 'post', 'page', 'attachment' ), true ) ) { $hidden = array( 'slugdiv', 'trackbacksdiv', 'postcustom', 'postexcerpt', 'commentstatusdiv', 'commentsdiv', 'authordiv', 'revisionsdiv' ); } else { $hidden = array( 'slugdiv' ); } } $hidden = apply_filters( 'default_hidden_meta_boxes', $hidden, $screen ); } return apply_filters( 'hidden_meta_boxes', $hidden, $screen, $use_defaults ); } function add_screen_option( $option, $args = array() ) { $current_screen = get_current_screen(); if ( ! $current_screen ) { return; } $current_screen->add_option( $option, $args ); } function get_current_screen() { global $current_screen; if ( ! isset( $current_screen ) ) { return null; } return $current_screen; } function set_current_screen( $hook_name = '' ) { WP_Screen::get( $hook_name )->set_current_screen(); } 