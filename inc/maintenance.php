<?php

    class YOP_POLL_Maintenance {

        private function network_propagate( $pfunction, $networkwide ) {

            if( function_exists( 'is_multisite' ) && is_multisite() ) {

                if( $networkwide ) {

                    $old_blog = $GLOBALS['wpdb']->blogid;

                    $blogids  = $GLOBALS['wpdb']->get_col( "SELECT blog_id FROM {$GLOBALS['wpdb']->blogs}" );

                    foreach( $blogids as $blog_id ) {

                        switch_to_blog( $blog_id );

                        yop_poll_create_table_names( $GLOBALS['wpdb']->prefix );



                        update_option( "yop_poll_version", YOP_POLL_VERSION );

                        $poll_archive_page = get_page_by_path( 'yop-poll-archive', ARRAY_A );

                        if( ! $poll_archive_page ) {

                            $_p                   = array();

                            $_p['post_title']     = 'Yop Poll Archive';

                            $_p['post_content']   = "[yop_poll_archive]";

                            $_p['post_status']    = 'publish';

                            $_p['post_type']      = 'page';

                            $_p['comment_status'] = 'open';

                            $_p['ping_status']    = 'open';

                            $_p['post_category']  = array( 1 ); // the default 'Uncategorised'



                            $poll_archive_page_id = wp_insert_post( $_p );



                        }

                        else {

                            $poll_archive_page_id = $poll_archive_page['ID'];

                        }





                        call_user_func( array( $this, $pfunction ), $networkwide );

                        $version = get_option( "yop_poll_version" );



                        if( $version != false ) {

                            self:: activation_hook();

                        }

                        $default_options                             = get_option( 'yop_poll_options' );

                        $default_options['archive_url']              = get_permalink( $poll_archive_page_id );

                        $default_options['yop_poll_archive_page_id'] = $poll_archive_page_id;



                        //addind default options

                        update_option( 'yop_poll_options', $default_options );



                    }

                    switch_to_blog( $old_blog );

                    yop_poll_create_table_names( $GLOBALS['wpdb']->prefix );

                    return;

                }

            }



            update_option( "yop_poll_version", YOP_POLL_VERSION );

            $poll_archive_page = get_page_by_path( 'yop-poll-archive', ARRAY_A );

            if( ! $poll_archive_page ) {

                $_p                   = array();

                $_p['post_title']     = 'Yop Poll Archive';

                $_p['post_content']   = "[yop_poll_archive]";

                $_p['post_status']    = 'publish';

                $_p['post_type']      = 'page';

                $_p['comment_status'] = 'open';

                $_p['ping_status']    = 'open';

                $_p['post_category']  = array( 1 ); // the default 'Uncategorised'



                $poll_archive_page_id = wp_insert_post( $_p );



            }

            else {

                $poll_archive_page_id = $poll_archive_page['ID'];

            }



            //addind default options

            call_user_func( array( $this, $pfunction ), $networkwide );



            $version = get_option( "yop_poll_version" );

            if( $version != false ) {

                self:: activation_hook();

            }

            $default_options                             = get_option( 'yop_poll_options' );

            $default_options['archive_url']              = get_permalink( $poll_archive_page_id );

            $default_options['yop_poll_archive_page_id'] = $poll_archive_page_id;



            //addind default options

            update_option( 'yop_poll_options', $default_options );



        }

        function propagate_activation( $networkwide ) {
            $this->network_propagate( 'activate', $networkwide );
        }

        function propagate_deactivation( $networkwide ) {
            global $wpdb;
            $wpdb->query( "DROP TABLE `" . $wpdb->prefix . "yop_pollmeta`, `" . $wpdb->prefix . "yop_polls`, `" . $wpdb->prefix . "yop_poll_answermeta`, `" . $wpdb->prefix . "yop_poll_answers`, `" . $wpdb->prefix . "yop_poll_custom_fields`, `" . $wpdb->prefix . "yop_poll_logs`, `" . $wpdb->prefix . "yop_poll_voters`, `" . $wpdb->prefix . "yop_poll_bans`, `" . $wpdb->prefix . "yop_poll_templates`, `" . $wpdb->prefix . "yop_poll_votes_custom_fields`, `" . $wpdb->prefix . "yop_poll_facebook_users`" );
            $this->network_propagate( 'deactivate', $networkwide );
        }

        private function install_default_options() {
            update_option( "yop_poll_version", YOP_POLL_VERSION );

            $default_poll_options = array(
                'is_default_answer' => 'no',
                'poll_start_date'   => current_time( 'mysql' ),
                'poll_end_date'     => '18-01-2038 23:59:59',
            );
            update_option( 'yop_poll_options', $default_poll_options );
        }

        private function uninstall_default_options() {
            delete_option( "yop_poll_version" );
            delete_option( "yop_poll_options" );
        }

        public function activate( $networkwide ) {
            if( ! current_user_can( 'activate_plugins' ) ) {
                $error = new WP_Error ( 'Wordpress_version_error', __yop_poll( 'You need permissions to activate this plugin' ), __yop_poll( 'Error: Wordpress Activation Permissions Problem' ) );
            }

            if( ! version_compare( $GLOBALS['wp_version'], YOP_POLL_WP_VERSION, '>=' ) ) {
                $error = new WP_Error ( 'Wordpress_version_error', sprintf( __yop_poll( 'You need at least Wordpress version %s to use this plugin' ), YOP_POLL_WP_VERSION ), __yop_poll( 'Error: Wordpress Version Problem' ) );
            }

            if( isset ( $error ) && is_wp_error( $error ) ) {
                wp_die( $error->get_error_message(), $error->get_error_data() );
            }


            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            require_once( YOP_POLL_INC . 'db_schema.php' );
            $this->install_default_options();
            $capObj = YOP_POLL_Capabilities::get_instance();
            $capObj->install_capabilities();
            Yop_Poll_DbSchema::install_database();
        }

        public function deactivatedelete( $networkwide ) {
            require_once( YOP_POLL_INC . 'db_schema.php' );
            Yop_Poll_DbSchema::delete_database_tables();
            $capObj = YOP_POLL_Capabilities::get_instance();
             $capObj->uninstall_capabilities();
             $this->uninstall_default_options();
        }

        function new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
            if( ! function_exists( 'is_plugin_active_for_network' ) ) {
                require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
            }
            if( is_plugin_active_for_network( YOP_POLL_SHORT_PLUGIN_FILE ) ) {
                $old_blog = $GLOBALS['wpdb']->blogid;
                switch_to_blog( $blog_id );
                yop_poll_create_table_names( $GLOBALS['wpdb']->prefix );
                $this->activate( null );
                switch_to_blog( $old_blog );
                yop_poll_create_table_names( $GLOBALS['wpdb']->prefix );
            }
        }

        function delete_blog( $blog_id ) {
            $old_blog = $GLOBALS['wpdb']->blogid;
            switch_to_blog( $blog_id );
            yop_poll_create_table_names( $GLOBALS['wpdb']->prefix );
            $this->deactivate( null );
            require_once( YOP_POLL_INC . 'db_schema.php' );
           // Yop_Poll_DbSchema::delete_database_tables();
            $capObj = YOP_POLL_Capabilities::get_instance();
            $capObj->uninstall_capabilities();
            $this->uninstall_default_options();
            switch_to_blog( $old_blog );
            yop_poll_create_table_names( $GLOBALS['wpdb']->prefix );
        }

        function update() {
        }

        private function activation_hook() {
            $current_options=yop_poll_default_options() ;
            $polls         = self::yop_poll_get_polls_from_db();
            $answers       = self::yop_poll_get_answers_from_db();
            $logs          = self::yop_poll_get_logs_from_db();
            $bans          = self::yop_poll_get_bans_from_db();
            $custom_fields = self::yop_poll_get_custom_fields_from_db();
            $custom_votes  = self::yop_poll_get_custom_fields_votes_from_db();
            $metas         = self::yop_poll_get_polls_meta_from_db();
            $answers_meta  = self::yop_poll_get_answers_meta_from_db();

            foreach( $polls as $poll ) {

                foreach( $answers as $answer ) {
                    if( $answer['poll_id'] == $poll['id'] ) {
                        $answers_ordonate[$poll['id']][] = (array)$answer;
                    }
                    if( $answer['poll_id'] > $poll['id'] ) {
                        break;
                    }

                    foreach( $logs as $log ) {
                        if( $log['poll_id'] == $poll['id'] && $log['answer_id'] == $answer['id'] ) {
                            $logs_ordonate[$poll['id']][]        = (array)$log;
                            $logs_ordonate_details[$log['id']][] = $answer['answer'];
                        }
                    }
                    foreach( $answers_meta as $answer_meta ) {
                        if( $answer_meta['yop_poll_answer_id'] == $answer['id'] ) {
                            $answer_meta_ordonate[$answer['id']][] = (array)$answer_meta;
                        }
                    }
                }

                foreach( $bans as $ban ) {
                    if( $ban['poll_id'] == $poll['id'] ) {
                        $bans_ordonate[$poll['id']][] = (array)$ban;
                    }
                    if( $ban['poll_id'] > $poll['id'] ) {
                        break;
                    }
                }

                foreach( $metas as $meta ) {
                    if( $meta['yop_poll_id'] == $poll['id'] ) {
                        $metas_ordonate[$poll['id']][] = (array)$meta;
                    }
                    if( $meta['yop_poll_id'] > $poll['id'] ) {
                        break;
                    }
                }

                foreach( $custom_fields as $custom_field ) {
                    if( $custom_field['poll_id'] == $poll['id'] ) {
                        $custom_fields_ordonate[$poll['id']][] = (array)$custom_field;
                        if( $custom_field['poll_id'] > $poll['id'] ) {
                            break;
                        }

                        foreach( $custom_votes as $custom_vote ) {
                            if( $custom_field['id'] == $custom_vote['custom_field_id'] ) {
                                $custom_votes_fields_ordonate[$custom_field['id']][] = (array)$custom_vote;
                            }
                        }
                    }
                }
            }


            foreach( $polls as $poll ) {
                $current_poll                = new YOP_POLL_Poll_Model();
                $current_poll->poll_author   = $poll['poll_author'];
                $current_poll->poll_title    = $poll['name'];
                $current_poll->poll_name     = $poll['name'];
                $current_poll->poll_date     = $poll['date_added'];
                $current_poll->poll_modified = $poll['last_modified'];
                $current_poll->poll_status   = $poll['status'];
                $current_poll->poll_type     = "poll";
                $current_poll->poll_status   = $poll['status'];
                if( $poll['end_date'] <= "2038-01-18 23:59:59" ) {

                    $current_poll->poll_end_date =convert_date ($poll['end_date'],'Y-m-d H:i:s',1 );

                }

                else {

                    $current_poll->poll_end_date = "18-01-2038 23:59:59";

                }

                if( $poll['start_date'] <= "2038-01-18 23:59:59" ) {

                    $current_poll->poll_start_date = convert_date($poll['start_date'],'Y-m-d H:i:s',1 );

                }

                else {

                    $current_poll->poll_start_date = "18-01-2038 23:59:59";

                }
                $current_poll->poll_total_votes = $poll['total_votes'];
                $question                       = new YOP_POLL_Question_Model();
                $question->type                 = "text";
                $question->question             = $poll['question'];
                $question->question_date        = $current_poll->poll_date;
                $question->question_author      = $current_poll->poll_author;
                $question->question_modified    = $current_poll->poll_modified;
                $question->question_status      = $current_poll->poll_status;
                $question->poll_order           = 1;
                $i                              = 0;


                if( isset( $metas_ordonate[$poll['id']][0] ) ) {
                    $poll_option = maybe_unserialize( $metas_ordonate[$poll['id']][0]['meta_value'] );


                    foreach( $current_options as $key => &$value ) {

                        if( isset( $poll_option[$key] ) ) {
                            $current_poll-> $key = $poll_option[$key];
                            $question->$key=    $poll_option[$key];

                        }
                    }
                }
                foreach( $answers_ordonate[$poll['id']] as $answer_ordonate ) {

                    if( $answer_ordonate['type'] == "other" ) {
                        $current_poll->allow_other_answers='yes';
                        $question->allow_other_answers=    'yes';
                    }
                }
                $q[]                            = $question;
                $current_poll->questions = $q;
                $current_poll_id         = $current_poll->save();
                if($poll['show_in_archive'] =="yes"){
                    self::save_poll_order($current_poll_id,$poll['archive_order']);
                }


                foreach( $answers_ordonate[$poll['id']] as $answer_ordonate ) {
                    $ans                  = new YOP_POLL_Answer_Model();
                    $ans->answer          = $answer_ordonate['answer'];
                    $ans->answer_author   = $current_poll->poll_author;
                    $ans->answer_date     = $current_poll->poll_date;
                    $ans->answer_modified = $poll['last_modified'];
                    if( $answer_ordonate['type'] != "other" ) {
                        $ans->type = "text";
                    }
                    else{
                        $current_poll->allow_other_answers  = 'yes';
                        $question->allow_other_answers      = 'yes';
                        $ans->type = "other";
                    }
                    $ans->answer_status  = $answer['status'];
                    $ans->votes          = $answer_ordonate['votes'];
                    $ans->question_order = $i ++;


                    $answersa[] = $ans;
                    if( isset( $answer_meta_ordonate[$answer_ordonate['id']] ) ) {
                        $answer_option  = maybe_unserialize( $answer_meta_ordonate[$answer_ordonate['id']][0]['meta_value'] );
                        $answer_options = $ans->options;
                        foreach( $ans->options as $key => &$value ) {
                            if( isset( $answer_option[$key] ) ) {
                                $ans->$key = $answer_option[$key];
                            }

                        }
                        $ans->options = $answer_options;
                    }
                    $question->addAnswer( $ans );
                    $question->save_answers();
                    $answersids[$answer_ordonate['id']] = $ans->ID;

                }


                foreach( $custom_fields_ordonate[$poll['id']] as $custom_ordonate ) {
                    $oldid                          = $custom_ordonate['id'];
                    $question                       = $current_poll->questions;
                    $custom_ordonate['question_id'] = $current_poll_id;
                    $custom_ordonate['poll_id']     = $current_poll_id;
                    $newid                          = insert_custom_field_in_db( $custom_ordonate );
                    foreach( $custom_votes_fields_ordonate[$oldid] as $votes ) {
                        $votes['custom_field_id']         = $newid;
                        $votes['question_id']             = $current_poll_id;
                        $votes['poll_id']                 = $current_poll_id;
                        $votes['id']                      = insert_votes_custom_in_db( $votes );
                        $custom_field_vote[$poll['id']][] = $votes;
                    }
                }


                foreach( $logs_ordonate[$poll['id']] as $log_ordonate ) {
                    $log['poll_id']                                       = $current_poll_id;
                    $log['vote_id']                                       = $log_ordonate['vote_id'];
                    $log['ip']                                            = $log_ordonate['ip'];
                    $log['user_id']                                       = $log_ordonate['user_id'];
                    $log['user_type']                                     = $log_ordonate['user_type'];
                    $log['vote_date']                                     = $log_ordonate['vote_date'];
                    $log['tr_id']                                         = $log_ordonate['tr_id'];
                    $vote_details[1]["q-" . $current_poll_id]['question'] = $poll['question'];
                    $vote_details[1]["q-" . $current_poll_id]['id']       = $current_poll_id;
                    $vote_details[1]["q-" . $current_poll_id]['a'][]      = $answersids[$log_ordonate['answer_id']];
                    foreach( $custom_field_vote[$poll['id']] as $vote ) {
                        if( $vote['vote_id'] == $log_ordonate['vote_id'] ) {
                            $vote_details[1]["q-" . $current_poll_id]['cf'][] = $vote['id'];
                        }

                    }
                    foreach( $logs_ordonate_details[$log_ordonate['id']] as $a ) {
                        $vote_details[1]["q-" . $current_poll_id]['answers'][] = $a;
                        $log['vote_details']                                   = json_encode( $vote_details[1] );
                        $log['message']                                        = "Succes";
                        insert_result_in_db( $log );
                        insert_log_in_db( $log );
                        unset( $vote_details[1]["q-" . $current_poll_id]['answers'] );
                    }
                    unset( $vote_details );
                }

                foreach( $bans_ordonate[$poll['id']] as $ban_ordonate ) {
                    $ban            = $ban_ordonate;
                    $ban['poll_id'] = $current_poll_id;
                    self::insert_ban_in_db( $ban );
                }


            }


        }

        public function yop_poll_get_polls_from_db() {
            global $wpdb;
            $result = $wpdb->get_results( $GLOBALS['wpdb']->prepare( "
                            SELECT *
                            FROM   " . $wpdb->prefix . "yop_polls ORDER BY id ASC
                            " ), ARRAY_A );
            return $result;

        }

        public function yop_poll_get_polls_meta_from_db() {
            global $wpdb;
            $result = $wpdb->get_results( $GLOBALS['wpdb']->prepare( "
                            SELECT *
                            FROM " . $wpdb->prefix . "yop_pollmeta ORDER BY yop_poll_id ASC
                            " ), ARRAY_A );
            return $result;

        }

        public function yop_poll_get_answers_meta_from_db() {
            global $wpdb;

            $result = $wpdb->get_results( $GLOBALS['wpdb']->prepare( "
                            SELECT *
                            FROM " . $wpdb->prefix . "yop_poll_answermeta
                            " ), ARRAY_A );
            return $result;

        }

        public function yop_poll_get_templates_from_db() {
            global $wpdb;
            $result = $wpdb->get_results( $wpdb->prepare( "
                            SELECT *
                            FROM  " . $wpdb->prefix . "yop_poll_templates
                            " ), ARRAY_A );
            return $result;
        }

        public function yop_poll_get_custom_fields_from_db() {
            global $wpdb;
            $result = $wpdb->get_results( $GLOBALS['wpdb']->prepare( "
                            SELECT *
                            FROM " . $wpdb->prefix . "yop_poll_custom_fields ORDER BY poll_id ASC
                            " ), ARRAY_A );
            return $result;
        }

        public function yop_poll_get_custom_fields_votes_from_db() {
            global $wpdb;
            $result = $wpdb->get_results( $GLOBALS['wpdb']->prepare( "
                            SELECT *
                            FROM  " . $wpdb->prefix . "yop_poll_votes_custom_fields
                            " ), ARRAY_A );
            return $result;
        }

        public function yop_poll_get_bans_from_db() {
            global $wpdb;
            $result = $wpdb->get_results( $wpdb->prepare( "
                            SELECT *
                            FROM   " . $wpdb->prefix . "yop_poll_bans ORDER BY poll_id ASC
                            " ), ARRAY_A );
            return $result;
        }

        public function yop_poll_get_answers_from_db() {
            global $wpdb;
            $result = $wpdb->get_results( $wpdb->prepare( "
                            SELECT *
                            FROM  " . $wpdb->prefix . "yop_poll_answers ORDER BY poll_id ASC
                            " ), ARRAY_A );
            return $result;
        }

        public function yop_poll_get_logs_from_db() {
            global $wpdb;
            $result = $wpdb->get_results( $wpdb->prepare( "
                            SELECT *
                            FROM " . $wpdb->prefix . "yop_poll_logs
                            " ), ARRAY_A );
            return $result;
        }

        private static function insert_ban_in_db( $ban ) {
            global $wpdb;
            $sql = $wpdb->query( $wpdb->prepare( "
	                INSERT INTO $wpdb->yop_poll_bans
                              ( poll_id,type,value,period ,unit)
		  	                    VALUES(%d,%s,%s,%d,%s)
	                        ", $ban['poll_id'], $ban['type'], $ban['value'], intval( $ban['period'] ), $ban['unit'] ) );
            return $wpdb->get_results( $sql );
        }
        private function save_poll_order( $poll, $poll_order ) {
            $poll_archive_order = get_option( 'yop_poll_archive_order', array() );
            if( $poll_archive_order == "" ) {
                $poll_archive_order = array();
            }if( trim( $poll_order ) <= 0 ) {
                            $poll_order = 1;
                        }
                        $key = array_search( $poll, $poll_archive_order );
                        if( $key !== false ) {
                            unset( $poll_archive_order[$key] );
                        }
                        if( $poll_order > count( $poll_archive_order ) ) {
                            array_push( $poll_archive_order, $poll );
                        }
                        else {
                            array_splice( $poll_archive_order, trim( $poll_order ) - 1, 0, array( $poll ) );
                        }
            update_option( 'yop_poll_archive_order', $poll_archive_order );
        }

    }
