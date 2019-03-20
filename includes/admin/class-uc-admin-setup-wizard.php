<?php
/**
 * Setup Wizard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UC_Admin_Setup_Wizard Class.
 */
class UC_Admin_Setup_Wizard {

	/**
	 * Current step
	 */
	private $step = '';

	/**
	 * Steps for the setup wizard
	 */
	private $steps = array();

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		if ( apply_filters( 'usercamp_enable_setup_wizard', true ) && current_user_can( 'manage_usercamp' ) ) {
			add_action( 'admin_menu', array( $this, 'admin_menus' ) );
			add_action( 'admin_init', array( $this, 'setup_wizard' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	/**
	 * Add admin menus/screens.
	 */
	public function admin_menus() {
		add_dashboard_page( '', '', 'manage_options', 'uc-setup', '' );
	}

	/**
	 * Register/enqueue scripts and styles for the Setup Wizard.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'uc-setup', uc()->plugin_url() . '/assets/css/uc-setup.css', array( 'dashicons', 'install' ), UC_VERSION );
		wp_register_script( 'uc-setup', uc()->plugin_url() . '/assets/js/admin/uc-setup.js', array( 'jquery', 'jquery-tiptip' ), UC_VERSION );
	}

	/**
	 * Show the setup wizard.
	 */
	public function setup_wizard() {
		if ( empty( $_GET['page'] ) || 'uc-setup' !== $_GET['page'] ) {
			return;
		}
		$default_steps = array(
			'store_setup' => array(
				'name'    => __( 'Store setup', 'usercamp' ),
				'view'    => array( $this, 'uc_setup_store_setup' ),
				'handler' => array( $this, 'uc_setup_store_setup_save' ),
			),
			'activate'    => array(
				'name'    => __( 'Activate', 'usercamp' ),
				'view'    => array( $this, 'uc_setup_activate' ),
				'handler' => array( $this, 'uc_setup_activate_save' ),
			),
			'next_steps'  => array(
				'name'    => __( 'Ready!', 'usercamp' ),
				'view'    => array( $this, 'uc_setup_ready' ),
				'handler' => '',
			),
		);

		// Hide activate section when the user does not have capabilities to install plugins, think multiside admins not being a super admin.
		if ( ! current_user_can( 'install_plugins' ) ) {
			unset( $default_steps['activate'] );
		}

		$this->steps = apply_filters( 'usercamp_setup_wizard_steps', $default_steps );
		$this->step  = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) ); // WPCS: CSRF ok, input var ok.

		if ( ! empty( $_POST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
			call_user_func( $this->steps[ $this->step ]['handler'], $this );
		}

		ob_start();
		$this->setup_wizard_header();
		$this->setup_wizard_steps();
		$this->setup_wizard_content();
		$this->setup_wizard_footer();
		exit;
	}

	/**
	 * Get the URL for the next step's screen.
	 */
	public function get_next_step_link( $step = '' ) {
		if ( ! $step ) {
			$step = $this->step;
		}

		$keys = array_keys( $this->steps );
		if ( end( $keys ) === $step ) {
			return admin_url();
		}

		$step_index = array_search( $step, $keys, true );
		if ( false === $step_index ) {
			return '';
		}

		return add_query_arg( 'step', $keys[ $step_index + 1 ], remove_query_arg( 'activate_error' ) );
	}

	/**
	 * Setup Wizard Header.
	 */
	public function setup_wizard_header() {
		set_current_screen();
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta name="viewport" content="width=device-width" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title><?php esc_html_e( 'Usercamp &rsaquo; Setup Wizard', 'usercamp' ); ?></title>
			<?php do_action( 'admin_enqueue_scripts' ); ?>
			<?php wp_print_scripts( 'uc-setup' ); ?>
			<?php do_action( 'admin_print_styles' ); ?>
			<?php do_action( 'admin_head' ); ?>
		</head>
		<body class="uc-setup wp-core-ui">
			<h1 id="uc-logo"><a href="https://usercamp.io"><?php esc_html_e( 'Usercamp', 'usercamp' ); ?></a></h1>
		<?php
	}

	/**
	 * Setup Wizard Footer.
	 */
	public function setup_wizard_footer() {
		?>
			<?php if ( 'store_setup' === $this->step ) : ?>
				<a class="uc-setup-footer-links" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Not right now', 'usercamp' ); ?></a>
			<?php elseif ( 'activate' === $this->step ) : ?>
				<a class="uc-setup-footer-links" href="<?php echo esc_url( $this->get_next_step_link() ); ?>"><?php esc_html_e( 'Skip this step', 'usercamp' ); ?></a>
			<?php endif; ?>
			</body>
		</html>
		<?php
	}

	/**
	 * Output the steps.
	 */
	public function setup_wizard_steps() {
		$output_steps      = $this->steps;
		?>
		<ol class="uc-setup-steps">
			<?php
			foreach ( $output_steps as $step_key => $step ) {
				$is_completed = array_search( $this->step, array_keys( $this->steps ), true ) > array_search( $step_key, array_keys( $this->steps ), true );

				if ( $step_key === $this->step ) {
					?>
					<li class="active"><?php echo esc_html( $step['name'] ); ?></li>
					<?php
				} elseif ( $is_completed ) {
					?>
					<li class="done">
						<a href="<?php echo esc_url( add_query_arg( 'step', $step_key, remove_query_arg( 'activate_error' ) ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
					</li>
					<?php
				} else {
					?>
					<li><?php echo esc_html( $step['name'] ); ?></li>
					<?php
				}
			}
			?>
		</ol>
		<?php
	}

	/**
	 * Output the content for the current step.
	 */
	public function setup_wizard_content() {
		echo '<div class="uc-setup-content">';
		if ( ! empty( $this->steps[ $this->step ]['view'] ) ) {
			call_user_func( $this->steps[ $this->step ]['view'], $this );
		}
		echo '</div>';
	}

	/**
	 * Initial step.
	 */
	public function uc_setup_store_setup() {
		?>
		<form method="post">
			<?php wp_nonce_field( 'uc-setup' ); ?>
			<p class="uc-setup-actions step">
				<button type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( "Let's go!", 'usercamp' ); ?>" name="save_step"><?php esc_html_e( "Let's go!", 'usercamp' ); ?></button>
			</p>
		</form>
		<?php
	}

	/**
	 * Save initial settings.
	 */
	public function uc_setup_store_setup_save() {
		check_admin_referer( 'uc-setup' );
		wp_safe_redirect( esc_url_raw( $this->get_next_step_link() ) );
		exit;
	}

	/**
	 * Activate step.
	 */
	public function uc_setup_activate() {
		?>
		<form method="post">
			<?php wp_nonce_field( 'uc-setup' ); ?>
			<p class="uc-setup-actions step">
				<button type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( "Let's go!", 'usercamp' ); ?>" name="save_step"><?php esc_html_e( "Let's go!", 'usercamp' ); ?></button>
			</p>
		</form>
		<?php
	}

	/**
	 * Activate step save.
	 */
	public function uc_setup_activate_save() {
		check_admin_referer( 'uc-setup' );
		wp_safe_redirect( esc_url_raw( $this->get_next_step_link() ) );
		exit;
	}

	/**
	 * Final step.
	 */
	public function uc_setup_ready() {
		// We've made it! Don't prompt the user to run the wizard again.
		// UC_Admin_Notices::remove_notice( 'install' );
	}

}

new UC_Admin_Setup_Wizard();