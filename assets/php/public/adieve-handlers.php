<?php




if (!function_exists('is_this_ip_allowed')) {
    function is_this_ip_allowed($ip)
    {
        global $wpdb;
        //crée la bdd id, date, ip, url,
        $date = new DateTime();
        $date = $date->format('Y-m-d');
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . "wp-admin/includes/upgrade.php");

        $table_name = $wpdb->base_prefix . 'adieve_members';

        $period_start = time() - (int) get_option('adieve_limit_period_duration', 86400);

        $prepare = $wpdb->prepare(
            "SELECT COUNT( * ) FROM $table_name WHERE  `ip` =  '%s' AND  `time` > %s;",
            $ip,
            $period_start
        );

        $visits = $wpdb->get_var($prepare);

        if ($visits > get_option( 'adieve_max_try_per_period', 5 ) ) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('is_member_not_in_database')) {
    function is_member_not_in_database( $firstname, $lastname, $email, $phone, $student, $studlevel )
    {
        global $wpdb;
        $date = new DateTime();
        $date = $date->format('Y-m-d');
        require_once(ABSPATH . "wp-admin/includes/upgrade.php");

        $table_name = $wpdb->base_prefix . 'adieve_members';

        $prepare = $wpdb->prepare(
            "SELECT COUNT( * ) FROM $table_name WHERE  `firstname` =  '%s' AND  `lastname` = '%s' AND ( `email` = '%s' OR `phone` = '%s' OR `student` = '%s' );",
            $firstname,
            $lastname,
            $email,
            $phone,
            $student,
            $studlevel
        );

        $visits = $wpdb->get_var($prepare);

        if ( $visits > 0 ) {
            return false;
        } else {
            return true;
        }
    }
}


function save_member($firstname, $lastname, $email, $phone, $student, $studlevel)
{
    global $wpdb;

    $table_name = $wpdb->base_prefix . 'adieve_members';

    $date = new DateTime();
    $date = $date->format('Y-m-d');

    $return = false;

    if ( is_member_not_in_database( $firstname, $lastname, $email, $phone, $student, $studlevel ) ) {
        $wpdb->insert(
            $table_name,
            array(
                'statut' => 0,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'phone' => $phone,
                'student' => $student,
                'studlevel' => $studlevel,
                'date' => $date,
            ),
            array("%s", "%s", "%s", "%s", "%s", "%s")
        );
        $return = true;
    }
    return $return;
}

/**
 * Handle Ask Access
 *
 * @return void
 */
if (!function_exists('adieve_members_handler')) {
    function adieve_members_handler()
    {
        check_ajax_referer('adieve_members_nonce', 'adieve_members_nonce');
        $firstname = $_POST['adieve_firstname'];
        $lastname = $_POST['adieve_lastname'];
        $email = $_POST['adieve_email'];
        $phone = $_POST['adieve_phone'];
        $student = $_POST['adieve_student'];
        $studlevel = $_POST['adieve_studlevel'];
        echo !save_member( $firstname, $lastname, $email, $phone, $student, $studlevel ) ? 0 : 1;
        die();
    }
}

add_action( 'wp_ajax_adieve_members_handler', 'adieve_members_handler' );
add_action( 'wp_ajax_nopriv_adieve_members_handler', 'adieve_members_handler' );