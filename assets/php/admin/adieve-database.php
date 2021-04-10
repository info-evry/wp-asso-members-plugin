<?php
if (!function_exists('adieve_setup_database')) {
    function adieve_setup_database()
    {
        global $wpdb;
        $version = 101;
        $charset_collate = $wpdb->get_charset_collate();
        $adieve_members_db_version = (int) get_site_option('adieve_members_db_version');
        if ($adieve_members_db_version !== $version) {
            $sql = "CREATE TABLE `{$wpdb->base_prefix}adieve_members` (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            statut int(20) NOT NULL DEFAULT 0,
            firstname varchar(40) NOT NULL,
            lastname varchar(40) NOT NULL,
            email varchar(128) NOT NULL,
            phone varchar(16) NOT NULL,
            student varchar(8) NOT NULL,
            studlevel varchar(32) NOT NULL,
            date datetime NOT NULL DEFAULT '0000-00-00',
            PRIMARY KEY  (id)
            ) $charset_collate;";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
            update_site_option('adieve_members_db_version', $version);
        }
    }
}

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Members_List extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular' => __('Member', 'adieve'),
            'plural' => __('Members', 'adieve'),
            'ajax' => false,
        ));
    }

    public static function get_members($per_page = 25, $current_page = 1)
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}adieve_members";
        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        $result = array_slice($result, (($current_page - 1) * $per_page), $per_page);
        return $result;
    }

    public static function delete_member($id)
    {
        global $wpdb;
        return $wpdb->delete("{$wpdb->prefix}adieve_members", ['id' => $id], ['%d']);
    }
    public static function accept_member($id)
    {
        global $wpdb;
        return $wpdb->update(
            "{$wpdb->prefix}adieve_members",
            array('statut' => 1),
            array('id' => $id),
            array('%d')
        );
    }

    public static function record_count()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}adieve_members");
    }


    public function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="member[]" value="%s" />',
            $item['id']
        );
    }


    public function column_statut($item)
    {
        $statut = '';
        if (absint($item['statut']) === 0) {
            $statut = 'En attente';
        } else {
            $statut = 'Membre';
        }

        $action = array();

        if (absint($item['statut']) === 0) {
            $actions['accept'] = sprintf(
                '<a href="?page=%s&action=%s&id=%s">Accept</a>',
                esc_attr($_GET['page']),
                'accept',
                absint($item['id'])
            );
            $actions['delete'] = sprintf(
                '<a href="?page=%s&action=%s&id=%s">Reject</a>',
                esc_attr($_GET['page']),
                'delete',
                absint($item['id'])
            );
        } else {
            $actions['delete'] = sprintf(
                '<a href="?page=%s&action=%s&id=%s">Reject</a>',
                esc_attr($_GET['page']),
                'delete',
                absint($item['id'])
            );
        }

        return $statut . $this->row_actions($actions);
    }

    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'id' => __('ID', 'adieve'),
            'statut' => __('Statut', 'adieve'),
            'firstname' => __('Prénom', 'adieve'),
            'lastname' => __('Nom', 'adieve'),
            'email' => __('Email', 'adieve'),
            'phone' => __('Téléphone', 'adieve'),
            'student' => __('Numéro', 'adieve'),
            'studlevel' => __('Filière', 'adieve'),
            'date' => __('Date', 'adieve'),
        );

        return $columns;
    }

    public function get_hidden_columns()
    {
        return array(
            'id',
            'date',
        );
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'statut' => array('statut', true),
            'firstname' => array('firstname', true),
            'lastname' => array('lastname', true),
            'email' => array('email', true),
            'phone' => array('phone', true),
            'student' => array('student', true),
            'studlevel' => array('studlevel', true),
        );
        return $sortable_columns;
    }

    public function get_bulk_actions()
    {
        $actions = array(
            'bulk-accept' => 'Accept',
            'bulk-delete' => 'Delete'
        );
        return $actions;
    }

    public function prepare_items()
    {
        $this->process_bulk_action();
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $per_page = $this->get_items_per_page('members_per_page', 25);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();
        $data = self::get_members($per_page, $current_page);

        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page' => $per_page
            )
        );
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    public function process_bulk_action()
    {
        switch ($this->current_action()) {
            case 'delete': {
                    self::delete_member(absint($_GET['id']));
                    break;
                }
            case 'accept': {
                    self::accept_member(absint($_GET['id']));
                    break;
                }
            case 'bulk-delete': {
                    $member_ids = esc_sql($_POST['member']);
                    foreach ($member_ids as $id) {
                        self::delete_member(absint($id));
                    }
                    break;
                }
            case 'bulk-accept': {
                    $member_ids = esc_sql($_POST['member']);
                    foreach ($member_ids as $id) {
                        self::accept_member(absint($id));
                    }
                    break;
                }
            default: {
                    break;
                }
        }
    }
}
add_action('init', 'adieve_setup_database');
