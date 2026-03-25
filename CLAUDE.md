# AdvancedToggle Development Guidelines

## Elementor Atomic Architecture (Mandatory)

Always build widgets for Elementor's Atomic architecture by default. Do not build for legacy markup unless explicitly requested.

- Always implement `has_widget_inner_wrapper()` returning `false` when `e_optimized_markup` is active
- Do not rely on `.elementor-widget-container` in CSS selectors — it may not exist in Atomic mode
- Target widget output elements directly in CSS (e.g., `.widget__wrapper`, `.widget__item`) rather than depending on Elementor's wrapper divs
- Keep DOM output minimal — single wrapper div inside the widget, no unnecessary nesting
- All CSS should work with a single-div wrapper structure
