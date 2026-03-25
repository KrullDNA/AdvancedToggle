<?php
namespace AdvancedToggle\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use AdvancedToggle\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || exit;

class Toggle_Widget extends Widget_Base {

	public function get_name() {
		return 'advanced-toggle';
	}

	public function get_title() {
		return __( 'Advanced Toggle', 'advanced-toggle' );
	}

	public function get_icon() {
		return 'eicon-toggle';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'accordion', 'toggle', 'collapsible', 'tabs', 'switch' ];
	}

	public function get_style_depends() {
		return [ 'advanced-toggle-frontend' ];
	}

	public function get_script_depends() {
		return [ 'advanced-toggle-frontend' ];
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	private function get_section_templates() {
		$templates_manager = \Elementor\Plugin::instance()->templates_manager;
		$source            = $templates_manager->get_source( 'local' );

		$sections = $source->get_items( [ 'type' => 'section' ] );
		if ( ! empty( $sections ) ) {
			$sections = wp_list_pluck( $sections, 'title', 'template_id' );
		} else {
			$sections = [];
		}

		$containers = $source->get_items( [ 'type' => 'container' ] );
		if ( ! empty( $containers ) ) {
			$containers = wp_list_pluck( $containers, 'title', 'template_id' );
		} else {
			$containers = [];
		}

		return $sections + $containers;
	}

	protected function register_content_controls() {
		// Toggle Items
		$this->start_controls_section(
			'_section_toggle',
			[
				'label' => __( 'Toggle', 'advanced-toggle' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Title', 'advanced-toggle' ),
				'default'     => __( 'Toggle Title', 'advanced-toggle' ),
				'placeholder' => __( 'Type Toggle Title', 'advanced-toggle' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'icon',
			[
				'type'       => Controls_Manager::ICONS,
				'label'      => __( 'Icon', 'advanced-toggle' ),
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'source',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Content Source', 'advanced-toggle' ),
				'default'   => 'editor',
				'separator' => 'before',
				'options'   => [
					'editor'   => __( 'Editor', 'advanced-toggle' ),
					'template' => __( 'Template', 'advanced-toggle' ),
				],
			]
		);

		$repeater->add_control(
			'editor',
			[
				'label'      => __( 'Content Editor', 'advanced-toggle' ),
				'show_label' => false,
				'type'       => Controls_Manager::WYSIWYG,
				'condition'  => [ 'source' => 'editor' ],
				'dynamic'    => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'template',
			[
				'label'       => __( 'Section Template', 'advanced-toggle' ),
				'placeholder' => __( 'Select a section template for tab content', 'advanced-toggle' ),
				'description' => sprintf(
					__( 'Need to create a section template? Click %1$shere%2$s', 'advanced-toggle' ),
					'<a target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=section' ) ) . '">',
					'</a>'
				),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => $this->get_section_templates(),
				'condition'   => [ 'source' => 'template' ],
			]
		);

		$this->add_control(
			'tabs',
			[
				'show_label'  => false,
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{title}}',
				'default'     => [
					[
						'title'  => 'Toggle Item 1',
						'source' => 'editor',
						'editor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
					],
					[
						'title'  => 'Toggle Item 2',
						'source' => 'editor',
						'editor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
					],
				],
			]
		);

		$this->end_controls_section();

		// Options
		$this->start_controls_section(
			'_section_options',
			[
				'label' => __( 'Options', 'advanced-toggle' ),
			]
		);

		$this->add_control(
			'closed_icon',
			[
				'type'    => Controls_Manager::ICONS,
				'label'   => __( 'Closed Icon', 'advanced-toggle' ),
				'default' => [
					'library' => 'solid',
					'value'   => 'fas fa-plus',
				],
			]
		);

		$this->add_control(
			'opened_icon',
			[
				'type'    => Controls_Manager::ICONS,
				'label'   => __( 'Opened Icon', 'advanced-toggle' ),
				'default' => [
					'library' => 'solid',
					'value'   => 'fas fa-minus',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'type'           => Controls_Manager::CHOOSE,
				'label'          => __( 'Position', 'advanced-toggle' ),
				'default'        => 'left',
				'toggle'         => false,
				'options'        => [
					'left'  => [
						'title' => __( 'Left', 'advanced-toggle' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'advanced-toggle' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'prefix_class'   => 'adv-toggle--icon-',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		$this->register_item_style_controls();
		$this->register_title_style_controls();
		$this->register_title_icon_style_controls();
		$this->register_content_style_controls();
		$this->register_open_close_icon_style_controls();
	}

	protected function register_item_style_controls() {
		$this->start_controls_section(
			'_section_item',
			[
				'label' => __( 'Item', 'advanced-toggle' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_spacing',
			[
				'label'     => __( 'Vertical Spacing (px)', 'advanced-toggle' ),
				'type'      => Controls_Manager::NUMBER,
				'step'      => 'any',
				'default'   => -1,
				'selectors' => [
					'{{WRAPPER}} .adv-toggle__item:not(:first-child)' => 'margin-top: {{VALUE}}px;',
				],
			]
		);

		$this->add_control(
			'item_border_type',
			[
				'label'   => __( 'Border Type', 'advanced-toggle' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'none'    => __( 'None', 'advanced-toggle' ),
					'divider' => __( 'Divider Only', 'advanced-toggle' ),
					'solid'   => __( 'Solid', 'advanced-toggle' ),
					'double'  => __( 'Double', 'advanced-toggle' ),
					'dotted'  => __( 'Dotted', 'advanced-toggle' ),
					'dashed'  => __( 'Dashed', 'advanced-toggle' ),
					'groove'  => __( 'Groove', 'advanced-toggle' ),
				],
				'prefix_class' => 'adv-toggle--border-',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'item_border_width',
			[
				'label'      => __( 'Border Width', 'advanced-toggle' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'item_border_type!' => [ 'none', 'divider' ],
				],
			]
		);

		$this->add_control(
			'item_border_color',
			[
				'label'     => __( 'Border Color', 'advanced-toggle' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adv-toggle__item' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'item_border_type!' => [ 'none', 'divider' ],
				],
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label'   => __( 'Divider Style', 'advanced-toggle' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'solid'  => __( 'Solid', 'advanced-toggle' ),
					'double' => __( 'Double', 'advanced-toggle' ),
					'dotted' => __( 'Dotted', 'advanced-toggle' ),
					'dashed' => __( 'Dashed', 'advanced-toggle' ),
				],
				'selectors' => [
					'{{WRAPPER}} .adv-toggle__item:not(:first-child)' => 'border-top-style: {{VALUE}};',
				],
				'condition' => [
					'item_border_type' => 'divider',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label'      => __( 'Divider Width', 'advanced-toggle' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default'    => [
					'size' => 1,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item:not(:first-child)' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'item_border_type' => 'divider',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'     => __( 'Divider Color', 'advanced-toggle' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .adv-toggle__item:not(:first-child)' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'item_border_type' => 'divider',
				],
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label'      => __( 'Border Radius', 'advanced-toggle' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_shadow',
				'label'    => __( 'Box Shadow', 'advanced-toggle' ),
				'selector' => '{{WRAPPER}} .adv-toggle__item',
			]
		);

		$this->end_controls_section();
	}

	protected function register_title_style_controls() {
		$this->start_controls_section(
			'_section_title',
			[
				'label' => __( 'Title', 'advanced-toggle' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => __( 'Padding', 'advanced-toggle' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .adv-toggle__item-title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'title_text_shadow',
				'label'    => __( 'Text Shadow', 'advanced-toggle' ),
				'selector' => '{{WRAPPER}} .adv-toggle__item-title',
			]
		);

		$this->start_controls_tabs( '_tab_tab_status' );

		// Normal Tab
		$this->start_controls_tab(
			'_tab_tab_normal',
			[
				'label' => __( 'Normal', 'advanced-toggle' ),
			]
		);

		$this->add_control(
			'title_border_radius',
			[
				'label'      => __( 'Border Radius', 'advanced-toggle' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name'     => 'title_text_gradient',
				'selector' => '{{WRAPPER}} .adv-toggle__item-title-text, {{WRAPPER}} .adv-toggle__item-title-icon i:before, {{WRAPPER}} .adv-toggle__item-title-icon svg, {{WRAPPER}} .adv-toggle__icon i:before, {{WRAPPER}} .adv-toggle__icon svg',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'title_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .adv-toggle__item-title',
			]
		);

		$this->end_controls_tab();

		// Active Tab
		$this->start_controls_tab(
			'_tab_tab_active',
			[
				'label' => __( 'Active', 'advanced-toggle' ),
			]
		);

		$this->add_control(
			'title_active_border_radius',
			[
				'label'      => __( 'Border Radius', 'advanced-toggle' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item-title.adv-toggle__item--active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name'     => 'title_active_text_gradient',
				'selector' => '{{WRAPPER}} .adv-toggle__item-title.adv-toggle__item--active .adv-toggle__item-title-text, {{WRAPPER}} .adv-toggle__item-title.adv-toggle__item--active .adv-toggle__item-title-icon i:before, {{WRAPPER}} .adv-toggle__item-title.adv-toggle__item--active .adv-toggle__icon i:before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'title_active_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .adv-toggle__item-title.adv-toggle__item--active',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_title_icon_style_controls() {
		$this->start_controls_section(
			'_section_title_icon',
			[
				'label' => __( 'Title Icon', 'advanced-toggle' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'title_icon_size',
			[
				'label'      => __( 'Size', 'advanced-toggle' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 100,
					],
					'em' => [
						'min' => 0.5,
						'max' => 6,
						'step' => 0.1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item-title-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .adv-toggle__item-title-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_icon_spacing',
			[
				'label'      => __( 'Spacing', 'advanced-toggle' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item-title-icon' => 'margin-right: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_style_controls() {
		$this->start_controls_section(
			'_section_content',
			[
				'label' => __( 'Content', 'advanced-toggle' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Padding', 'advanced-toggle' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'content_border',
				'label'    => __( 'Border', 'advanced-toggle' ),
				'selector' => '{{WRAPPER}} .adv-toggle__item-content',
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label'      => __( 'Border Radius', 'advanced-toggle' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .adv-toggle__item-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .adv-toggle__item-content',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Color', 'advanced-toggle' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .adv-toggle__item-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .adv-toggle__item-content',
			]
		);

		$this->end_controls_section();
	}

	protected function register_open_close_icon_style_controls() {
		$this->start_controls_section(
			'_section_icon',
			[
				'label' => __( 'Open / Close Icon', 'advanced-toggle' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_icon_spacing',
			[
				'label'      => __( 'Spacing', 'advanced-toggle' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}}.adv-toggle--icon-left .adv-toggle__icon > span'  => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}}.adv-toggle--icon-right .adv-toggle__icon > span' => 'margin-left: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! is_array( $settings['tabs'] ) || empty( $settings['tabs'] ) ) {
			return;
		}

		$has_closed_icon = ( ! empty( $settings['closed_icon'] ) && ! empty( $settings['closed_icon']['value'] ) );
		$has_opened_icon = ( ! empty( $settings['opened_icon'] ) && ! empty( $settings['opened_icon']['value'] ) );

		$id_int = substr( $this->get_id_int(), 0, 3 );
		?>
		<div class="adv-toggle__wrapper" role="tablist">
			<?php
			foreach ( $settings['tabs'] as $index => $item ) :
				$count = $index + 1;

				$title_setting_key = $this->get_repeater_setting_key( 'title', 'tabs', $index );
				$has_title_icon    = ( ! empty( $item['icon'] ) && ! empty( $item['icon']['value'] ) );

				if ( $item['source'] === 'editor' ) {
					$content_setting_key = $this->get_repeater_setting_key( 'editor', 'tabs', $index );
				} else {
					$content_setting_key = $this->get_repeater_setting_key( 'section', 'tabs', $index );
				}

				$this->add_render_attribute(
					$title_setting_key,
					[
						'id'            => 'adv-toggle__item-title-' . $id_int . $count,
						'class'         => [ 'adv-toggle__item-title' ],
						'data-tab'      => $count,
						'role'          => 'tab',
						'aria-controls' => 'adv-toggle__item-content-' . $id_int . $count,
					]
				);

				$this->add_render_attribute(
					$content_setting_key,
					[
						'id'              => 'adv-toggle__item-content-' . $id_int . $count,
						'class'           => [ 'adv-toggle__item-content' ],
						'data-tab'        => $count,
						'role'            => 'tabpanel',
						'aria-labelledby' => 'adv-toggle__item-title-' . $id_int . $count,
					]
				);
				?>
				<div class="adv-toggle__item">
					<div <?php $this->print_render_attribute_string( $title_setting_key ); ?>>
						<?php if ( $has_opened_icon || $has_closed_icon ) : ?>
							<span class="adv-toggle__item-icon adv-toggle__icon" aria-hidden="true">
								<?php if ( $has_closed_icon ) : ?>
									<span class="adv-toggle__icon--closed"><?php \Elementor\Icons_Manager::render_icon( $settings['closed_icon'] ); ?></span>
								<?php endif; ?>
								<?php if ( $has_opened_icon ) : ?>
									<span class="adv-toggle__icon--opened"><?php \Elementor\Icons_Manager::render_icon( $settings['opened_icon'] ); ?></span>
								<?php endif; ?>
							</span>
						<?php endif; ?>
						<div class="adv-toggle__item-title-inner">
							<?php if ( $has_title_icon ) : ?>
								<span class="adv-toggle__item-title-icon"><?php \Elementor\Icons_Manager::render_icon( $item['icon'] ); ?></span>
							<?php endif; ?>
							<span class="adv-toggle__item-title-text"><?php echo wp_kses_post( $item['title'] ); ?></span>
						</div>
					</div>
					<div <?php $this->print_render_attribute_string( $content_setting_key ); ?>>
						<?php
						if ( $item['source'] === 'editor' ) :
							echo $this->parse_text_editor( $item['editor'] );
						elseif ( $item['source'] === 'template' && ! empty( $item['template'] ) ) :
							echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $item['template'] );
						endif;
						?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
