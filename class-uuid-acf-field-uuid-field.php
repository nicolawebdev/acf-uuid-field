<?php
/**
 * Defines the custom field type class.
 */

use Ramsey\Uuid\Uuid;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * uuid_acf_field_uuid_field class.
 */
class uuid_acf_field_uuid_field extends \acf_field {
	/**
	 * Controls field type visibilty in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private $env;

	/**
	 * Constructor.
	 */
	public function __construct() {
		/**
		 * Field type reference used in PHP and JS code.
		 *
		 * No spaces. Underscores allowed.
		 */
		$this->name = 'uuid_field';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'UUID', 'acf-uuid' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME

		/**
		 * Field type Description.
		 *
		 * For field descriptions. May contain spaces.
		 */
		$this->description = __( 'UUID static generator for ACF Fields.', 'acf-uuid' );

		/**
		 * Field type Doc URL.
		 *
		 * For linking to a documentation page. Displayed in the field picker modal.
		 */
		$this->doc_url = '';

		/**
		 * Field type Tutorial URL.
		 *
		 * For linking to a tutorial resource. Displayed in the field picker modal.
		 */
		$this->tutorial_url = '';

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = array(
			'uuid_version' => '4',
		);

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("uuid_field", "error");
		 * ```
		 */
		// $this->l10n = array(
		// 	'error'	=> __( 'Error! Please enter a higher value', 'acf-uuid' ),
		// );

		$this->env = array(
			'url'     => trailingslashit( plugin_dir_url( __FILE__ ) ), // Replace this with your theme or plugin URL.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

		/**
		 * Field type preview image.
		 *
		 * A preview image for the field type in the picker modal.
		 */
		// $this->preview_image = $this->env['url'] . '/assets/images/field-preview-custom.png';

		parent::__construct();
	}

	/**
	 * Settings to display when users configure a field of this type.
	 *
	 * These settings appear on the ACF “Edit Field Group” admin page when
	 * setting up the field.
	 *
	 * @param array $field
	 * @return void
	 */
	public function render_field_settings( $field ) {
		/*
		 * Repeat for each setting you wish to display for this field type.
		 */

		// UUID Version
		acf_render_field_setting( $field, array(
			'label'			=> __( 'UUID Version', 'acf-uuid' ),
			'instructions'	=> __( 'Select the version of UUID to generate.', 'acf-uuid' ),
			'type'			=> 'select',
			'name'			=> 'uuid_version',
			'choices'		=> array(
				'1'		=> __( 'Version 1', 'acf-uuid' ),
				'4'		=> __( 'Version 4', 'acf-uuid' ),
			),
			'layout'	=>	'horizontal',
		));

		// To render field settings on other tabs in ACF 6.0+:
		// https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/#moving-field-setting
	}

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
	public function render_field( $field ) {
		$value = empty( $field['value'] ) ? $this->generate_uuid( $field['uuid_version'] ) : $field['value'];
		?>
			<div class="acf-uuid-container">
				<span class="acf-uuid-field"><?php echo esc_attr($value) ?></span>
				<input
					type="hidden"
					name="<?php echo esc_attr( $field['name'] ); ?>"
					value="<?php echo esc_attr( $value ); ?>"
					readonly
				>
			</div>
		<?php
	}

	/**
	 * Generates a UUID based on the version provided.
	 *
	 * @param string $uuid_version The version of UUID to generate.
	 * @return string
	 */
	public function generate_uuid( $uuid_version ) {
		$uuid = '';

		switch ( $uuid_version ) {
			case '1':
				$uuid = Uuid::uuid1();
				break;
			case '4':
				$uuid = Uuid::uuid4();
				break;
			default:
				$uuid = Uuid::uuid4();
				break;
		}

		return $uuid;
	}

	/**
	 * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
	 *
	 * Callback for admin_enqueue_script.
	 *
	 * @return void
	 */
	public function input_admin_enqueue_scripts() {
		$url     = trailingslashit( $this->env['url'] );
		$version = $this->env['version'];

		wp_register_script(
			'uuid-field',
			"{$url}assets/js/field.js",
			array( 'acf-input' ),
			$version
		);

		wp_register_style(
			'uuid-field',
			"{$url}assets/css/field.css",
			array( 'acf-input' ),
			$version
		);

		wp_enqueue_script( 'uuid-field' );
		wp_enqueue_style( 'uuid-field' );
	}
}
