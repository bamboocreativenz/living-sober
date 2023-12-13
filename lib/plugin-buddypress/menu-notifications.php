<?php

/* Buddypress Notifications in menu item */
add_filter('kleo_nav_menu_items', 'kleo_add_notifications_nav_item' );
function kleo_add_notifications_nav_item( $menu_items ) {
  $menu_items[] = array(
    'name' => esc_html__( 'Live Notifications', 'kleo' ),
    'slug' => 'notifications',
    'link' => '#',
  );

  return $menu_items;
}


add_filter('kleo_setup_nav_item_notifications' , 'kleo_setup_notifications_nav');
function kleo_setup_notifications_nav( $menu_item ) {
    $menu_item->classes[] = 'kleo-toggle-menu';
    $menu_item->classes[] = 'dropdown-submenu';
    if ( ! is_user_logged_in() ) {
        $menu_item->_invalid = true;
    } else {
        add_filter( 'walker_nav_menu_start_el_notifications', 'kleo_menu_notifications', 10, 4 );
    }

    return $menu_item;
}

function kleo_bp_mobile_notify() {
    global $kleo_config;

    $output = '';
    $url = bp_loggedin_user_domain() + "/notifications";
    $notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
    $count         = ! empty( $notifications ) ? count( $notifications ) : 0;

    if ($count > 0 ) {
        $alert = 'new-alert';
    } else {
        $alert = 'no-alert';
    }

    if ( isset( $kleo_config['mobile_notify_icon'] ) ) {
        $icon = $kleo_config['mobile_notify_icon'];
    } else {
        $icon = 'bell';
    }

    $title = '<span class="notify-items sq-notify-mobile">' .
                '<i class="icon-' . esc_attr( $icon ) . '"></i> <span class="kleo-notifications ' . esc_attr( $alert ) . '">' . esc_html( $count ) . '</span>' .
             '</span>';
    $output .= '<a title="' . esc_html__( 'View Notifications', 'kleo' ) . '" class="notify-contents" href="' . esc_url( $url ) .'">' . $title . '</a>';
    echo $output; // PHPCS: XSS ok.
}

if (! function_exists( 'kleo_menu_notifications' )) {
    function kleo_menu_notifications( $item_output, $item, $depth, $args ) {
        global $kleo_config;

        $output        = '';
        $url           = bp_loggedin_user_domain() + "/notifications";
        $notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
        $count         = ! empty( $notifications ) ? count( $notifications ) : 0;

        if ( $count > 0 ) {
            $alert  = 'new-alert';
            $status = ' has-notif';
        } else {
            $alert  = 'no-alert';
            $status = '';
        }
        $attr_title = strip_tags( $item->attr_title );

        if ( isset( $item->icon ) && $item->icon != '' ) {

            $kleo_config['mobile_notify_icon'] = $item->icon;

            $title_icon = '<i class="icon-' . $item->icon . '"></i>';

            if ( $item->iconpos == 'after' ) {
                $title = $item->title . ' ' . $title_icon;
            } elseif ( $item->iconpos == 'icon' ) {
                $title = $title_icon;
            } else {
                $title = $title_icon . ' ' . $item->title;
            }


        } else {
            $title = $item->title;
        }

        //If we have the menu item then add it to the mobile menu
        add_action( 'kleo_mobile_header_icons', 'kleo_bp_mobile_notify', 9 );

        /* Menu style */
        $atts = array();
        if ( $depth === 0 && isset( $item->istyle ) ) {
            if ( $item->istyle == 'buy' ) {
                $atts['class'] =  (isset($atts['class']) ? $atts['class'] : '' ) . ' btn-buy';
            } elseif( $item->istyle == 'border' ) {
                $atts['class'] =  (isset($atts['class']) ? $atts['class'] : '' ) . ' btn btn-see-through';
            } elseif( $item->istyle == 'highlight' ) {
                $atts['class'] =  (isset($atts['class']) ? $atts['class'] : '' ) . ' btn btn-highlight';
            }
        }

        $class = 'notify-contents';
	    if ( $depth === 0 ) {
		    $class .= ' js-activated';
	    }
        $class .= isset($atts['class']) ? ' ' . $atts['class'] : '';

        $output .= '<a class="' . $class . '" href="' . $url . '" title="' . $attr_title . '">'
                   . '<span class="notify-items"> ' . $title . ' <span class="kleo-notifications ' . $alert . '">' . $count . '</span></span>'
                   . '</a>';

        $output .= '<div class="kleo-toggle-submenu dropdown-menu sub-menu"><ul class="submenu-inner' . $status . '">';

        if ( ! empty( $notifications ) ) {
            foreach ( (array) $notifications as $notification ) {
                $output .= '<li class="kleo-submenu-item" id="kleo-notification-' . $notification->id . '">';
                $output .= '<a href="' . $notification->href . '">' . $notification->content . '</a>';
                $output .= '</li>';
            }
        } else {
            $output .= '<li class="kleo-submenu-item">' . esc_html__( 'No new notifications', 'kleo' ) . '</li>';
        }

        $output .= '</ul>';
        if ( ! empty( $notifications ) ) {
            $style = '';
        } else {
            $style = ' style="display: none;"';
        }
        $output .= '<div class="minicart-buttons text-center"' . $style . '><a class="btn btn-default mark-as-read" href="#">' . esc_html__( 'Mark all as read', 'kleo' ) . '</a></div>';

        $output .= '</div>';

        return $output;
    }
}


/* Mark notfications as read by AJAX */
add_action('wp_ajax_kleo_bp_notification_mark_read', 'kleo_bp_notification_mark_read');

function kleo_bp_notification_mark_read() {
  $response = array();

  if ( BP_Notifications_Notification::mark_all_for_user( bp_loggedin_user_id() ) ) {
    $response['status'] = 'success';
  }
  else {
    $response['status'] = 'failure';
  }

  $notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
  $count         = ! empty( $notifications ) ? count( $notifications ) : 0;
  $response['count']  = $count;
  $response['empty']  = '<li class="kleo-submenu-item">' . esc_html__( 'No new notifications' , 'kleo' ) . '</li>';

  echo json_encode( $response );
  exit;
}


/* Refresh notifications by AJAX */
add_filter( 'kleo_bp_ajax_call','kleo_bp_notifications_refresh' );

if(!function_exists('kleo_bp_notifications_refresh')) {
    function kleo_bp_notifications_refresh($response)
    {

        if (!isset($_GET['current_notifications'])) {
            $response['statusNotif'] = 'failure';
            return $response;
        }

        $old_count = (int)$_GET['current_notifications'];

        $notifications = bp_notifications_get_notifications_for_user(bp_loggedin_user_id(), 'object');
        $count = !empty($notifications) ? count($notifications) : 0;

        if ($count == $old_count) {
            $response['statusNotif'] = 'no_change';
            return $response;
        }

        $output = '';

        if (!empty($notifications)) {
            foreach ((array)$notifications as $notification) {
                $output .= '<li class="kleo-submenu-item" id="kleo-notification-' . $notification->id . '">';
                $output .= '<a href="' . $notification->href . '">' . $notification->content . '</a>';
                $output .= '</li>';
            }
        } else {
            $output .= '<li class="kleo-submenu-item">' . esc_html__('No new notifications', 'kleo') . '</li>';
        }
        $response['dataNotif'] = $output;
        $response['countNotif'] = $count;
        $response['statusNotif'] = 'success';

        return $response;
    }

}
