<?php
	class Yop_Poll_Public_Admin extends Yop_Poll_Plugin {
		protected function init() {
			$this->add_action( 'init', 'load_translation_file', 1 );
			$this->add_action( 'init', 'public_loader' );
			$this->add_action( 'widgets_init', 'widget_init' );
			$this->add_filter( 'the_content', 'yop_poll_do_shortcode_the_content_filter');
			$this->add_filter( 'widget_text', 'do_shortcode');
		}

		/**
		* this file initialize the text domain for translation file
		*
		*/
		public function load_translation_file() {
			$plugin_path = $this->_config->plugin_dir . '/' . $this->_config->languages_dir;
			load_plugin_textdomain( 'yop_poll', false, $plugin_path );
		}

		public function do_shortcode( $content ) {
			return do_shortcode( $content );
		}

		public function public_loader() {
			add_shortcode( 'yop_poll', array( &$this, 'yop_poll_shortcode_function' ) );
			add_shortcode( 'yop_poll_archive', array( &$this, 'yop_poll_archive_shortcode_function' ) );
		}

		/**
		* Start shortcodes
		*/

		public function yop_poll_archive_shortcode_function() {
			$template		= '';
			$yop_poll_page	= 1;
			$big			= 99999;
			if ( isset( $_REQUEST['yop_poll_page'] ) )
				$yop_poll_page	= $_REQUEST['yop_poll_page'];
			$general_default_options	= get_option( 'yop_poll_options', false );
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$archive = YOP_POLL_MODEL::get_archive_polls( 'archive_order', 'asc', ( intval( $yop_poll_page ) - 1)  * intval( $general_default_options['archive_polls_per_page'] ), intval( $general_default_options['archive_polls_per_page'] ) );
			$total_archive = ceil( count( YOP_POLL_MODEL::get_archive_polls( 'archive_order', 'asc', 0, $big ) ) / intval( $general_default_options['archive_polls_per_page'] ) );
			if ( count( $archive ) > 0 ) {
				foreach( $archive as $poll ) {
					$template	.= $this->return_yop_poll( $poll['id'] );
				}
			}
			$args = array(
				'base'         => remove_query_arg( 'yop_poll_page', $_SERVER['REQUEST_URI'] ).'%_%',
				'format'       => '?yop_poll_page=%#%',
				'total'        => $total_archive,
				'current'      => max( 1, $yop_poll_page ),
				'prev_next'    => True,
				'prev_text'    => __('&laquo; Previous', 'yop_poll'),
				'next_text'    => __('Next &raquo;', 'yop_poll')
			);
			return $template.paginate_links( $args );
		}

		public function return_yop_poll( $id ) {
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$yop_poll_model		= new YOP_POLL_MODEL( $id );
			$id					= $yop_poll_model->poll['id'];
			if ( ! $yop_poll_model->poll )
				return '';
			$template			= $yop_poll_model->return_poll_html( );
			if ( 'yes' == $yop_poll_model->poll_options['use_default_loading_image'] )
				$loading_image_src	= $this->_config->plugin_url.'/images/loading36x36.gif';
			else
				$loading_image_src	= $yop_poll_model->poll_options['loading_image_url'];
			wp_enqueue_script( 'jquery' );
			wp_enqueue_style( 'yop-poll-user-defined_'.$id, add_query_arg( array( 'id' => $id ), admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')).'?action=yop_poll_load_css' ), array(), $this->_config->version);
			wp_enqueue_style( 'yop-poll-public', "{$this->_config->plugin_url}/css/yop-poll-public.css", array(), $this->_config->version );
			wp_enqueue_script( 'yop-poll-user-defined_'.$id, add_query_arg( array( 'id' => $id ), admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')).'?action=yop_poll_load_js' ), array( 'jquery' ), $this->_config->version, true);
			wp_enqueue_script( 'yop-poll-public_'.$id, "{$this->_config->plugin_url}/js/yop-poll-public.js", array(), $this->_config->version, true );
			wp_enqueue_script( 'yop-poll-json2', "{$this->_config->plugin_url}/js/yop-poll-json2.js", array(), $this->_config->version, true );
			$yop_poll_public_config = array(
				'ajax' => array(
					'url'					=> admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')),
					'vote_action' 			=> 'yop_poll_do_vote',
					'captcha_action' 		=> 'yop_poll_show_captcha',
					'view_results_action'	=> 'yop_poll_view_results',
					'back_to_vote_action'	=> 'yop_poll_back_to_vote'
				),
				'loading_image_src'	=> 	$loading_image_src,
				'loading_image_alt'	=> __( 'Loading', 'yop_poll')
			);
			wp_localize_script( 'yop-poll-public_'.$id, 'yop_poll_public_config_'.$id, $yop_poll_public_config );

			return $template;
		}

		public function yop_poll_shortcode_function ( $atts, $content = NULL ) {
			extract( shortcode_atts( array(
						'id' => -1,
					), $atts ) );
			return $this->return_yop_poll( $id );

		}

		public function yop_poll_do_shortcode_the_content_filter( $content ) {
			return do_shortcode( $content );
		}

		public function widget_init(){
			register_widget('Yop_Poll_Widget');
		}

		/**
		* End shortcodes
		*/
}