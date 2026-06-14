<?php
/**
 * A simple repeater Customizer control (icon / text / link rows).
 *
 * The value is stored as a JSON string in its bound setting. JS in
 * assets/js/customizer-repeater.js handles add/remove/sync.
 *
 * @package ManangatangEnergy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Repeater control.
 */
class ME_Repeater_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'me_repeater';

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		?>
		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>

		<div class="me-repeater">
			<div class="me-rep-rows"></div>
			<button type="button" class="button me-rep-add"><?php esc_html_e( 'Add item', 'manangatang-energy' ); ?></button>
			<input type="hidden" class="me-rep-data" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>" />
		</div>
		<?php
	}
}

/**
 * Single icon-picker control: a dropdown of Lucide names with a live preview.
 */
class ME_Icon_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'me_icon';

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		$val     = $this->value();
		$val     = $val ? $val : 'phone';
		$choices = is_array( $this->choices ) ? $this->choices : array();
		// Preserve a saved value that isn't in the curated list.
		if ( $val && ! in_array( $val, $choices, true ) ) {
			array_unshift( $choices, $val );
		}
		?>
		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>
		<div class="me-icon-control" style="display:flex;align-items:center;gap:8px;margin-top:6px">
			<span class="me-icon-preview" style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid #dcdcde;border-radius:4px;background:#f6f7f7;color:#1f8f47;flex:none">
				<i data-lucide="<?php echo esc_attr( $val ); ?>" style="width:18px;height:18px"></i>
			</span>
			<select class="me-icon-select" <?php $this->link(); ?> style="flex:1">
				<?php foreach ( $choices as $name ) : ?>
					<option value="<?php echo esc_attr( $name ); ?>" <?php selected( $val, $name ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}
}
