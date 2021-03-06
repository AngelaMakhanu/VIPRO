<?php
/**
 * Class Google\Site_Kit\Core\Permissions\Permissions
 *
 * @package   Google\Site_Kit\Core\Permissions
 * @copyright 2021 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Core\Permissions;

use Google\Site_Kit\Context;
use Google\Site_Kit\Core\Authentication\Authentication;

/**
 * Class managing plugin permissions.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class Permissions {
	/*
	 * Custom base capabilities.
	 */
	const AUTHENTICATE        = 'googlesitekit_authenticate';
	const SETUP               = 'googlesitekit_setup';
	const VIEW_POSTS_INSIGHTS = 'googlesitekit_view_posts_insights';
	const VIEW_DASHBOARD      = 'googlesitekit_view_dashboard';
	const VIEW_MODULE_DETAILS = 'googlesitekit_view_module_details';
	const MANAGE_OPTIONS      = 'googlesitekit_manage_options';

	/*
	 * Custom meta capabilities.
	 */
	const VIEW_POST_INSIGHTS = 'googlesitekit_view_post_insights';

	/**
	 * Plugin context.
	 *
	 * @since 1.0.0
	 * @var Context
	 */
	private $context;

	/**
	 * Authentication instance.
	 *
	 * @since 1.0.0
	 * @var Authentication
	 */
	protected $authentication;

	/**
	 * Mappings for custom base capabilities to WordPress core built-in ones.
	 *
	 * @since 1.30.0
	 * @var array
	 */
	private $base_to_core = array();

	/**
	 * Mappings for custom meta capabilities to WordPress core built-in ones.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $meta_to_core = array();

	/**
	 * Mappings for custom meta capabilities to custom base capabilities.
	 *
	 * @since 1.30.0
	 * @var array
	 */
	private $meta_to_base = array();

	/**
	 * List of custom base capabilities that should require network access if the plugin is in network mode.
	 *
	 * @since 1.30.0
	 * @var array
	 */
	private $network_base = array();

	/**
	 * Constructor.
	 *
	 * Sets up the capability mappings.
	 *
	 * @since 1.0.0
	 *
	 * @param Context        $context        Plugin context.
	 * @param Authentication $authentication Optional. Authentication instance. Default is a new instance.
	 */
	public function __construct( Context $context, Authentication $authentication = null ) {
		$this->context = $context;

		if ( ! $authentication ) {
			$authentication = new Authentication( $this->context );
		}
		$this->authentication = $authentication;

		$this->base_to_core = array(
			// By default, only allow administrators to authenticate.
			self::AUTHENTICATE        => 'manage_options',

			// Allow contributors and up to view their own post's insights.
			// TODO change to map to edit_posts when Site Kit supports non admin access.
			self::VIEW_POSTS_INSIGHTS => 'manage_options',

			// Allow editors and up to view the dashboard and module details.
			// TODO change to map to edit_others_posts when Site Kit supports non admin access.
			self::VIEW_DASHBOARD      => 'manage_options',
			self::VIEW_MODULE_DETAILS => 'manage_options',

			// Allow administrators and up to manage options and set up the plugin.
			self::MANAGE_OPTIONS      => 'manage_options',
			self::SETUP               => 'manage_options',
		);

		$this->meta_to_core = array(
			// Allow users that can edit a post to view that post's insights.
			self::VIEW_POST_INSIGHTS => 'edit_post',
		);

		$this->meta_to_base = array(
			// Allow users that can generally view posts insights to view a specific post's insights.
			self::VIEW_POST_INSIGHTS => self::VIEW_POSTS_INSIGHTS,
		);

		$this->network_base = array(
			// Require network admin access to view the dashboard and module details in network mode.
			// TODO change to map to manage_network when Site Kit supports non admin access.
			self::VIEW_DASHBOARD      => 'manage_options',
			self::VIEW_MODULE_DETAILS => 'manage_options',

			// Require network admin access to manage options and set up the plugin in network mode.
			self::MANAGE_OPTIONS      => 'manage_network_options',
			self::SETUP               => 'manage_network_options',
		);
	}

	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_filter(
			'map_meta_cap',
			function( array $caps, $cap, $user_id, $args ) {
				return $this->map_meta_capabilities( $caps, $cap, $user_id, $args );
			},
			10,
			4
		);

		add_filter(
			'googlesitekit_user_data',
			function( $data ) {
				$data['permissions'] = $this->check_all_for_current_user();
				return $data;
			}
		);

		// This constant can be set if an alternative mechanism to grant these capabilities is in place.
		if ( defined( 'GOOGLESITEKIT_DISABLE_DYNAMIC_CAPABILITIES' ) && GOOGLESITEKIT_DISABLE_DYNAMIC_CAPABILITIES ) {
			return;
		}

		add_filter(
			'user_has_cap',
			function( array $allcaps ) {
				return $this->grant_additional_caps( $allcaps );
			}
		);
	}

	/**
	 * Check permissions for current user.
	 *
	 * @since 1.21.0
	 *
	 * @return array
	 */
	public function check_all_for_current_user() {
		$permissions = array(
			self::AUTHENTICATE,
			self::SETUP,
			self::VIEW_POSTS_INSIGHTS,
			self::VIEW_DASHBOARD,
			self::VIEW_MODULE_DETAILS,
			self::MANAGE_OPTIONS,
		);

		return array_combine(
			$permissions,
			array_map( 'current_user_can', $permissions )
		);
	}

	/**
	 * Resolves meta capabilities to their base capabilities.
	 *
	 * This method first maps plugin meta capabilities to their base capabilities. In addition, if the meta
	 * capability should also map to a core meta capability, that mapping is taken care of as well.
	 *
	 * If in network mode and the custom base capability requires network access, it is checked that the user
	 * has that access, and if not, the method bails early causing in a result of false.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $caps    List of resolved capabilities.
	 * @param string $cap     Capability checked.
	 * @param int    $user_id Current user ID.
	 * @param array  $args    Additional arguments passed to the capability check.
	 * @return array Filtered value of $caps.
	 */
	private function map_meta_capabilities( array $caps, $cap, $user_id, $args ) {
		// Bail early under these circumstances as we already know for sure the check will result in false.
		if ( isset( $this->network_base[ $cap ] ) && $this->context->is_network_mode() && ! is_super_admin( $user_id ) ) {
			return array( 'do_not_allow' );
		}

		if ( isset( $this->meta_to_base[ $cap ] ) ) {
			$caps = (array) $this->meta_to_base[ $cap ];
		}

		if ( isset( $this->meta_to_core[ $cap ] ) ) {
			$required_core_caps = call_user_func_array(
				'map_meta_cap',
				array_merge(
					array( $this->meta_to_core[ $cap ], $user_id ),
					$args
				)
			);

			$caps = array_merge( $caps, $required_core_caps );
		}

		// Special setup and authentication rules.
		if ( ( isset( $this->base_to_core[ $cap ] ) || isset( $this->meta_to_core[ $cap ] ) ) ) {
			// If setup has not yet been completed, require administrator capabilities for everything.
			if ( self::SETUP !== $cap && ! $this->authentication->is_setup_completed() ) {
				$caps[] = self::SETUP;
			}

			if ( ! in_array( $cap, array( self::AUTHENTICATE, self::SETUP ), true ) ) {
				// For regular users, require being authenticated. TODO: Take $user_id into account.
				$prevent_access = ! $this->authentication->is_authenticated();

				// For admin users, also require being verified. TODO: Take $user_id into account.
				if ( ! $prevent_access && user_can( $user_id, self::SETUP ) ) {
					$prevent_access = ! $this->authentication->verification()->has();
				}

				// For all users, require setup to have been completed.
				if ( ! $prevent_access ) {
					$prevent_access = ! $this->authentication->is_setup_completed();
				}

				if ( $prevent_access ) {
					$caps[] = 'do_not_allow';
				}
			}
		}

		return $caps;
	}

	/**
	 * Grants custom capabilities on-the-fly, based on core capabilities.
	 *
	 * If you want to instead set up your own custom role or mechanism to grant these capabilities, you can set a
	 * constant flag `GOOGLESITEKIT_DISABLE_DYNAMIC_CAPABILITIES` to ensure this function is not hooked in.
	 *
	 * @since 1.0.0
	 *
	 * @param array $allcaps Associative array of $capability => $grant pairs.
	 * @return array Filtered value of $allcaps.
	 */
	private function grant_additional_caps( array $allcaps ) {
		foreach ( $this->base_to_core as $custom_cap => $core_cap ) {
			if ( isset( $allcaps[ $core_cap ] ) ) {
				$allcaps[ $custom_cap ] = $allcaps[ $core_cap ];
			}
		}

		return $allcaps;
	}

	/**
	 * Gets all capabilities used in Google Site Kit.
	 *
	 * @since 1.31.0
	 *
	 * @return array
	 */
	public static function get_capabilities() {
		return array(
			self::AUTHENTICATE,
			self::SETUP,
			self::VIEW_POSTS_INSIGHTS,
			self::VIEW_DASHBOARD,
			self::VIEW_MODULE_DETAILS,
			self::MANAGE_OPTIONS,
		);
	}
}
