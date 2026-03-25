<?php
namespace AdvancedToggle\Controls;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Group_Control_Foreground extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'foreground';
	}

	public function init_fields() {
		$fields = [];

		$fields['color_type'] = [
			'label'       => _x( 'Text Color', 'Background Control', 'advanced-toggle' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'render_type' => 'ui',
			'options'     => [
				'classic'  => [
					'title' => _x( 'Classic', 'Text Color Control', 'advanced-toggle' ),
					'icon'  => 'eicon-paint-brush',
				],
				'gradient' => [
					'title' => _x( 'Gradient', 'Text Color Control', 'advanced-toggle' ),
					'icon'  => 'eicon-barcode',
				],
			],
			'default'     => 'classic',
		];

		$fields['color'] = [
			'label'     => _x( 'Color', 'Background Control', 'advanced-toggle' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'title'     => _x( 'Text Color', 'Background Control', 'advanced-toggle' ),
			'selectors' => [
				'{{SELECTOR}}' => 'color: {{VALUE}};',
			],
			'condition' => [
				'color_type' => [ 'classic', 'gradient' ],
			],
		];

		$fields['color_stop'] = [
			'label'       => _x( 'Location', 'Background Control', 'advanced-toggle' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%' ],
			'default'     => [
				'unit' => '%',
				'size' => 0,
			],
			'render_type' => 'ui',
			'condition'   => [
				'color_type' => [ 'gradient' ],
			],
			'of_type'     => 'gradient',
		];

		$fields['color_b'] = [
			'label'       => _x( 'Second Color', 'Background Control', 'advanced-toggle' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#f2295b',
			'render_type' => 'ui',
			'condition'   => [
				'color_type' => [ 'gradient' ],
			],
			'of_type'     => 'gradient',
		];

		$fields['color_b_stop'] = [
			'label'       => _x( 'Location', 'Background Control', 'advanced-toggle' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%' ],
			'default'     => [
				'unit' => '%',
				'size' => 100,
			],
			'render_type' => 'ui',
			'condition'   => [
				'color_type' => [ 'gradient' ],
			],
			'of_type'     => 'gradient',
		];

		$fields['gradient_type'] = [
			'label'       => _x( 'Type', 'Background Control', 'advanced-toggle' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'linear' => _x( 'Linear', 'Background Control', 'advanced-toggle' ),
				'radial' => _x( 'Radial', 'Background Control', 'advanced-toggle' ),
			],
			'default'     => 'linear',
			'render_type' => 'ui',
			'condition'   => [
				'color_type' => [ 'gradient' ],
			],
			'of_type'     => 'gradient',
		];

		$fields['gradient_angle'] = [
			'label'      => _x( 'Angle', 'Background Control', 'advanced-toggle' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'deg' ],
			'default'    => [
				'unit' => 'deg',
				'size' => 180,
			],
			'range'      => [
				'deg' => [
					'step' => 10,
				],
			],
			'selectors'  => [
				'{{SELECTOR}}' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent; background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition'  => [
				'color_type'    => [ 'gradient' ],
				'gradient_type' => 'linear',
			],
			'of_type'    => 'gradient',
		];

		$fields['gradient_position'] = [
			'label'     => _x( 'Position', 'Background Control', 'advanced-toggle' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'center center' => _x( 'Center Center', 'Background Control', 'advanced-toggle' ),
				'center left'   => _x( 'Center Left', 'Background Control', 'advanced-toggle' ),
				'center right'  => _x( 'Center Right', 'Background Control', 'advanced-toggle' ),
				'top center'    => _x( 'Top Center', 'Background Control', 'advanced-toggle' ),
				'top left'      => _x( 'Top Left', 'Background Control', 'advanced-toggle' ),
				'top right'     => _x( 'Top Right', 'Background Control', 'advanced-toggle' ),
				'bottom center' => _x( 'Bottom Center', 'Background Control', 'advanced-toggle' ),
				'bottom left'   => _x( 'Bottom Left', 'Background Control', 'advanced-toggle' ),
				'bottom right'  => _x( 'Bottom Right', 'Background Control', 'advanced-toggle' ),
			],
			'default'   => 'center center',
			'selectors' => [
				'{{SELECTOR}}' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent; background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition' => [
				'color_type'    => [ 'gradient' ],
				'gradient_type' => 'radial',
			],
			'of_type'   => 'gradient',
		];

		return $fields;
	}

	protected function get_child_default_args() {
		return [
			'types' => [ 'classic', 'gradient' ],
		];
	}

	protected function filter_fields() {
		$fields = parent::filter_fields();
		$args   = $this->get_args();

		foreach ( $fields as &$field ) {
			if ( isset( $field['of_type'] ) && ! in_array( $field['of_type'], $args['types'] ) ) {
				unset( $field );
			}
		}

		return $fields;
	}

	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}
