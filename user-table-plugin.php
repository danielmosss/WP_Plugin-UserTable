<?php
/*
Plugin Name: User Table Display
Description: Display a sortable table of WordPress users.
Version: 1.0
Author: Daniël Mosselman
*/

// Plugin code goes here
function display_user_table() {
    $users = get_users();

    if (!empty($users)) {
        echo '<table id="user-table" class="user-table">';
        echo '<thead>';
        echo '<tr><th>Lid</th><th>Functie</th></tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($users as $user) {
            $user_info = get_userdata($user->ID);

            $role = $user_info->roles[0];
            $role_translation = translateRoles($role);

            // get user info and display name
            $first_name = $user_info->first_name;
            $last_name = $user_info->last_name;
            $name_display = "";

            // check if first name and last name are empty and display name accordingly
            if (!empty($first_name) && empty($last_name)) {
                $name_display = $first_name;
            } elseif (empty($first_name) && !empty($last_name)) {
                $name_display = 'Fam ' . $last_name;
            } elseif (empty($first_name) && empty($last_name)) {
                $name_display = $user_info->display_name;
            } else {
                $name_display = $first_name . ' ' . $last_name;
            }

            echo '<tr>';
            echo '<td><a href="/profiel/' . $user_info->user_login . '">' . esc_html($name_display) . '</a></td>';
            echo '<td>' . esc_html($role_translation) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
}

function translateRoles($userrole){
    $roles = array(
        'administrator' => 'Bestuur',
        'editor' => 'Redacteur',
        'author' => 'Auteur',
        'contributor' => 'Bijdrager',
        'subscriber' => 'Lid'
    );

    if (array_key_exists($userrole, $roles)) {
        return $roles[$userrole];
    } else {
        return $userrole;
    }
}

function enqueue_user_table_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('dataTables', 'https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js', array('jquery'), '1.11.8', true);
    wp_enqueue_style('dataTables-style', 'https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css');
    wp_enqueue_script('user-table-sort', plugin_dir_url(__FILE__) . 'user-table-plugin.js', array('jquery', 'dataTables'), '1.0', true);
    wp_enqueue_style('user-table-style', plugin_dir_url(__FILE__) . 'user-table-plugin.css');
}
add_action('wp_enqueue_scripts', 'enqueue_user_table_scripts');

function user_table_shortcode() {
    ob_start();
    display_user_table();
    return ob_get_clean();
}
add_shortcode('user_table', 'user_table_shortcode');
?>