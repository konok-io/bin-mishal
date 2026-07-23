<?php

declare(strict_types=1);

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;

return [

    /*
    |--------------------------------------------------------------------------
    | Section Type Registry
    |--------------------------------------------------------------------------
    |
    | This array defines all available section types for the page builder.
    | Each type has metadata, content schema, settings schema, and view path.
    |
    */

    'types' => [

        // ═══════════════════════════════════════════════════════════════════════
        // HERO SECTIONS
        // ═══════════════════════════════════════════════════════════════════════

        'hero_simple' => [
            'key' => 'hero_simple',
            'label' => 'Hero - Simple',
            'icon' => 'heroicons-m-photo',
            'description' => 'Static image or gradient hero with heading and CTA',
            'view' => 'public.sections.hero-simple',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
                'description' => 'editor',
                'button_text' => 'text',
                'button_url' => 'text',
                'secondary_button_text' => 'text',
                'secondary_button_url' => 'text',
            ],
            'settings_schema' => [
                'background_type' => 'select:image|gradient|video|none',
                'background_image' => 'file:image',
                'background_color' => 'color',
                'gradient_from' => 'color',
                'gradient_to' => 'color',
                'overlay_opacity' => 'number',
                'text_alignment' => 'select:left|center|right',
                'text_color' => 'select:light|dark|custom',
                'min_height' => 'number',
                'show_particles' => 'boolean',
                'parallax_enabled' => 'boolean',
            ],
        ],

        'hero_slider' => [
            'key' => 'hero_slider',
            'label' => 'Hero - Slider',
            'icon' => 'heroicons-m-view-columns',
            'description' => 'Multiple slides with auto-play and navigation',
            'view' => 'public.sections.hero-slider',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Slides',
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'slider_autoplay' => 'boolean',
                'slider_interval' => 'number',
                'slider_animation' => 'select:fade|slide',
                'show_dots' => 'boolean',
                'show_arrows' => 'boolean',
                'fullscreen' => 'boolean',
            ],
        ],

        'search_widget' => [
            'key' => 'search_widget',
            'label' => 'Search Widget',
            'icon' => 'heroicons-m-magnifying-glass',
            'description' => 'Multi-tab Flight|Umrah|Visa|Appointment search',
            'view' => 'public.sections.search-widget',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'default_tab' => 'select:flight|umrah|visa|appointment',
                'show_flight_tab' => 'boolean',
                'show_umrah_tab' => 'boolean',
                'show_visa_tab' => 'boolean',
                'show_appointment_tab' => 'boolean',
                'background_color' => 'color',
                'widget_style' => 'select:boxed|floating|minimal',
            ],
        ],

        // ═══════════════════════════════════════════════════════════════════════
        // CONTENT SECTIONS
        // ═══════════════════════════════════════════════════════════════════════

        'service_icons' => [
            'key' => 'service_icons',
            'label' => 'Service Icons Grid',
            'icon' => 'heroicons-m-cube',
            'description' => 'Icon grid showcasing services',
            'view' => 'public.sections.service-icons',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Services',
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4|6',
                'icon_style' => 'select:filled|outlined|gradient',
                'card_style' => 'select:default|hover|minimal',
                'icon_size' => 'select:sm|md|lg',
            ],
        ],

        'stats_counter' => [
            'key' => 'stats_counter',
            'label' => 'Stats Counter',
            'icon' => 'heroicons-m-chart-bar',
            'description' => 'Animated counter numbers',
            'view' => 'public.sections.stats-counter',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Statistics',
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4',
                'animation_enabled' => 'boolean',
                'counter_duration' => 'number',
                'style' => 'select:cards|inline|minimal',
                'prefix' => 'text',
                'suffix' => 'text',
            ],
        ],

        'feature_cards' => [
            'key' => 'feature_cards',
            'label' => 'Feature Cards',
            'icon' => 'heroicons-m-view-grid',
            'description' => 'Feature cards with icon, title and text',
            'view' => 'public.sections.feature-cards',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Features',
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4',
                'card_style' => 'select:default|elevated|outlined|minimal',
                'icon_position' => 'select:top|left',
                'show_description' => 'boolean',
            ],
        ],

        'two_column' => [
            'key' => 'two_column',
            'label' => 'Two Column',
            'icon' => 'heroicons-m-queue',
            'description' => 'Text and image side by side',
            'view' => 'public.sections.two-column',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'heading' => 'text',
                'content' => 'editor',
                'image' => 'file:image',
                'image_caption' => 'text',
                'button_text' => 'text',
                'button_url' => 'text',
            ],
            'settings_schema' => [
                'image_position' => 'select:left|right',
                'image_ratio' => 'select:16:9|4:3|1:1|full',
                'content_alignment' => 'select:left|center',
                'show_button' => 'boolean',
                'show_badge' => 'boolean',
                'badge_text' => 'text',
            ],
        ],

        'rich_text' => [
            'key' => 'rich_text',
            'label' => 'Rich Text',
            'icon' => 'heroicons-m-document-text',
            'description' => 'WYSIWYG text content block',
            'view' => 'public.sections.rich-text',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'content' => 'editor',
            ],
            'settings_schema' => [
                'max_width' => 'select:sm|md|lg|xl|full',
                'text_alignment' => 'select:left|center|right',
                'drop_cap' => 'boolean',
            ],
        ],

        'accordion' => [
            'key' => 'accordion',
            'label' => 'Accordion',
            'icon' => 'heroicons-m-chevron-down',
            'description' => 'Collapsible content items',
            'view' => 'public.sections.accordion',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Accordion Items',
            'content_schema' => [
                'heading' => 'text',
            ],
            'settings_schema' => [
                'allow_multiple_open' => 'boolean',
                'first_item_open' => 'boolean',
                'style' => 'select:default|bordered|minimal',
            ],
        ],

        'tabs' => [
            'key' => 'tabs',
            'label' => 'Tabs',
            'icon' => 'heroicons-m-bars-3',
            'description' => 'Tabbed content',
            'view' => 'public.sections.tabs',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Tabs',
            'content_schema' => [
                'heading' => 'text',
            ],
            'settings_schema' => [
                'style' => 'select:default|boxed|pills|underline',
                'tab_position' => 'select:top|bottom',
            ],
        ],

        'timeline' => [
            'key' => 'timeline',
            'label' => 'Timeline',
            'icon' => 'heroicons-m-clock',
            'description' => 'Vertical timeline for stories/processes',
            'view' => 'public.sections.timeline',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Timeline Items',
            'content_schema' => [
                'heading' => 'text',
            ],
            'settings_schema' => [
                'style' => 'select:default|minimal|modern',
                'alignment' => 'select:left|center|right',
                'show_icons' => 'boolean',
            ],
        ],

        // ═══════════════════════════════════════════════════════════════════════
        // DYNAMIC DATA SECTIONS
        // ═══════════════════════════════════════════════════════════════════════

        'umrah_packages' => [
            'key' => 'umrah_packages',
            'label' => 'Umrah Packages',
            'icon' => 'heroicons-m-cube-transparent',
            'description' => 'Dynamic grid of Umrah packages',
            'view' => 'public.sections.umrah-packages',
            'accepts_items' => false,
            'accepts_data_source' => true,
            'data_source_models' => ['UmrahPackage'],
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4',
                'show_carousel' => 'boolean',
                'show_filters' => 'boolean',
                'show_pagination' => 'boolean',
                'limit' => 'number',
                'style' => 'select:cards|list|compact',
            ],
        ],

        'visa_grid' => [
            'key' => 'visa_grid',
            'label' => 'Visa Types Grid',
            'icon' => 'heroicons-m-document',
            'description' => 'Dynamic grid of visa types',
            'view' => 'public.sections.visa-grid',
            'accepts_items' => false,
            'accepts_data_source' => true,
            'data_source_models' => ['VisaType'],
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4',
                'show_descriptions' => 'boolean',
                'show_prices' => 'boolean',
            ],
        ],

        'flight_routes' => [
            'key' => 'flight_routes',
            'label' => 'Popular Flight Routes',
            'icon' => 'heroicons-m-paper-airplane',
            'description' => 'Popular flight destinations',
            'view' => 'public.sections.flight-routes',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Routes',
            'content_schema' => [
                'heading' => 'text',
            ],
            'settings_schema' => [
                'columns' => 'select:3|4|6',
                'show_prices' => 'boolean',
                'style' => 'select:cards|list|badges',
            ],
        ],

        'news_grid' => [
            'key' => 'news_grid',
            'label' => 'News Grid',
            'icon' => 'heroicons-m-newspaper',
            'description' => 'News articles grid',
            'view' => 'public.sections.news-grid',
            'accepts_items' => false,
            'accepts_data_source' => true,
            'data_source_models' => ['News'],
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4',
                'show_excerpt' => 'boolean',
                'show_author' => 'boolean',
                'show_date' => 'boolean',
                'limit' => 'number',
            ],
        ],

        'blog_grid' => [
            'key' => 'blog_grid',
            'label' => 'Blog Posts',
            'icon' => 'heroicons-m-rss',
            'description' => 'Blog articles grid',
            'view' => 'public.sections.blog-grid',
            'accepts_items' => false,
            'accepts_data_source' => true,
            'data_source_models' => ['Blog'],
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3',
                'show_excerpt' => 'boolean',
                'show_author' => 'boolean',
                'limit' => 'number',
            ],
        ],

        'testimonials' => [
            'key' => 'testimonials',
            'label' => 'Testimonials',
            'icon' => 'heroicons-m-chat-bubble-left-ellipsis',
            'description' => 'Customer testimonials',
            'view' => 'public.sections.testimonials',
            'accepts_items' => true,
            'accepts_data_source' => true,
            'data_source_models' => ['Testimonial'],
            'items_label' => 'Testimonials',
            'content_schema' => [
                'heading' => 'text',
            ],
            'settings_schema' => [
                'columns' => 'select:1|2|3',
                'style' => 'select:cards|slider|minimal',
                'show_rating' => 'boolean',
                'show_company' => 'boolean',
            ],
        ],

        'faq_accordion' => [
            'key' => 'faq_accordion',
            'label' => 'FAQ Accordion',
            'icon' => 'heroicons-m-question-mark-circle',
            'description' => 'Frequently asked questions',
            'view' => 'public.sections.faq-accordion',
            'accepts_items' => false,
            'accepts_data_source' => true,
            'data_source_models' => ['Faq'],
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'allow_multiple_open' => 'boolean',
                'first_item_open' => 'boolean',
                'show_categories' => 'boolean',
            ],
        ],

        'team_grid' => [
            'key' => 'team_grid',
            'label' => 'Team Members',
            'icon' => 'heroicons-m-users',
            'description' => 'Team/Staff members grid',
            'view' => 'public.sections.team-grid',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Team Members',
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4',
                'style' => 'select:cards|minimal|overlay',
                'show_social' => 'boolean',
                'show_bio' => 'boolean',
            ],
        ],

        'gallery' => [
            'key' => 'gallery',
            'label' => 'Image Gallery',
            'icon' => 'heroicons-m-photo',
            'description' => 'Image gallery with lightbox',
            'view' => 'public.sections.gallery',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Images',
            'content_schema' => [
                'heading' => 'text',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4|6',
                'style' => 'select:grid|masonry|carousel',
                'show_lightbox' => 'boolean',
                'aspect_ratio' => 'select:1:1|4:3|16:9|original',
            ],
        ],

        'logo_carousel' => [
            'key' => 'logo_carousel',
            'label' => 'Logo Carousel',
            'icon' => 'heroicons-m-queue',
            'description' => 'Partner/brand logos carousel',
            'view' => 'public.sections.logo-carousel',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Logos',
            'content_schema' => [
                'heading' => 'text',
            ],
            'settings_schema' => [
                'autoplay' => 'boolean',
                'show_arrows' => 'boolean',
                'grayscale' => 'boolean',
                'columns' => 'select:3|4|5|6',
            ],
        ],

        // ═══════════════════════════════════════════════════════════════════════
        // CTA & FORMS
        // ═══════════════════════════════════════════════════════════════════════

        'cta_banner' => [
            'key' => 'cta_banner',
            'label' => 'CTA Banner',
            'icon' => 'heroicons-m-megaphone',
            'description' => 'Call to action banner with buttons',
            'view' => 'public.sections.cta-banner',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'heading' => 'text',
                'description' => 'textarea',
                'button_text' => 'text',
                'button_url' => 'text',
                'secondary_button_text' => 'text',
                'secondary_button_url' => 'text',
            ],
            'settings_schema' => [
                'background_type' => 'select:color|gradient|image',
                'background_color' => 'color',
                'gradient_from' => 'color',
                'gradient_to' => 'color',
                'background_image' => 'file:image',
                'text_color' => 'select:light|dark',
                'style' => 'select:default|minimal|modern',
            ],
        ],

        'newsletter' => [
            'key' => 'newsletter',
            'label' => 'Newsletter Signup',
            'icon' => 'heroicons-m-envelope',
            'description' => 'Email subscription form',
            'view' => 'public.sections.newsletter',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'heading' => 'text',
                'description' => 'textarea',
                'placeholder' => 'text',
                'button_text' => 'text',
                'success_message' => 'text',
            ],
            'settings_schema' => [
                'style' => 'select:default|minimal|boxed',
                'show_social' => 'boolean',
                'background_color' => 'color',
            ],
        ],

        'contact_form' => [
            'key' => 'contact_form',
            'label' => 'Contact Form',
            'icon' => 'heroicons-m-envelope-open',
            'description' => 'Contact form with branch info',
            'view' => 'public.sections.contact-form',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'heading' => 'text',
                'description' => 'textarea',
                'submit_text' => 'text',
                'success_message' => 'textarea',
            ],
            'settings_schema' => [
                'show_branch_info' => 'boolean',
                'show_map' => 'boolean',
                'form_style' => 'select:default|minimal|boxed',
                'fields' => 'text',
            ],
        ],

        // ═══════════════════════════════════════════════════════════════════════
        // OTHER SECTIONS
        // ═══════════════════════════════════════════════════════════════════════

        'ceo_message' => [
            'key' => 'ceo_message',
            'label' => 'CEO Message',
            'icon' => 'heroicons-m-user',
            'description' => 'Photo with message and signature',
            'view' => 'public.sections.ceo-message',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'heading' => 'text',
                'name' => 'text',
                'designation' => 'text',
                'message' => 'editor',
                'signature' => 'text',
                'image' => 'file:image',
            ],
            'settings_schema' => [
                'image_position' => 'select:left|right',
                'style' => 'select:default|modern|minimal',
            ],
        ],

        'trust_badges' => [
            'key' => 'trust_badges',
            'label' => 'Trust Badges',
            'icon' => 'heroicons-m-shield-check',
            'description' => 'Trust/certification badges',
            'view' => 'public.sections.trust-badges',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Badges',
            'content_schema' => [
                'heading' => 'text',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4|6',
                'style' => 'select:grid|inline',
            ],
        ],

        'process_steps' => [
            'key' => 'process_steps',
            'label' => 'Process Steps',
            'icon' => 'heroicons-m-list-bullet',
            'description' => 'Numbered process/workflow steps',
            'view' => 'public.sections.process-steps',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Steps',
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3|4',
                'style' => 'select:default|numbered|cards',
                'show_connector' => 'boolean',
            ],
        ],

        'pricing_table' => [
            'key' => 'pricing_table',
            'label' => 'Pricing Table',
            'icon' => 'heroicons-m-currency-dollar',
            'description' => 'Pricing plans comparison',
            'view' => 'public.sections.pricing-table',
            'accepts_items' => true,
            'accepts_data_source' => false,
            'items_label' => 'Pricing Plans',
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'style' => 'select:cards|table|toggle',
                'show_featured' => 'boolean',
                'currency' => 'text',
            ],
        ],

        'video_embed' => [
            'key' => 'video_embed',
            'label' => 'Video Embed',
            'icon' => 'heroicons-m-video-camera',
            'description' => 'YouTube/Vimeo embed',
            'view' => 'public.sections.video-embed',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'heading' => 'text',
                'video_url' => 'text',
                'description' => 'textarea',
            ],
            'settings_schema' => [
                'aspect_ratio' => 'select:16:9|4:3|1:1',
                'autoplay' => 'boolean',
                'show_controls' => 'boolean',
            ],
        ],

        'branch_map' => [
            'key' => 'branch_map',
            'label' => 'Branch Map',
            'icon' => 'heroicons-m-map-pin',
            'description' => 'Google Map with branch locations',
            'view' => 'public.sections.branch-map',
            'accepts_items' => false,
            'accepts_data_source' => true,
            'data_source_models' => ['Branch'],
            'content_schema' => [
                'heading' => 'text',
            ],
            'settings_schema' => [
                'map_style' => 'select:standard|satellite|hybrid|terrain',
                'zoom_level' => 'number',
                'show_list' => 'boolean',
                'map_height' => 'number',
            ],
        ],

        'spacer_divider' => [
            'key' => 'spacer_divider',
            'label' => 'Spacer / Divider',
            'icon' => 'heroicons-m-arrows-pointing-out',
            'description' => 'Spacing and divider control',
            'view' => 'public.sections.spacer-divider',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [],
            'settings_schema' => [
                'height' => 'number',
                'show_divider' => 'boolean',
                'divider_style' => 'select:line|dots|gradient',
                'divider_color' => 'color',
            ],
        ],

        'custom_html' => [
            'key' => 'custom_html',
            'label' => 'Custom HTML',
            'icon' => 'heroicons-m-code-bracket',
            'description' => 'Raw HTML (super-admin only)',
            'view' => 'public.sections.custom-html',
            'accepts_items' => false,
            'accepts_data_source' => false,
            'content_schema' => [
                'html_content' => 'textarea',
            ],
            'settings_schema' => [
                'max_width' => 'select:sm|md|lg|xl|full',
            ],
        ],

        'labour_law_cards' => [
            'key' => 'labour_law_cards',
            'label' => 'Labour Law Info Cards',
            'icon' => 'heroicons-m-scale',
            'description' => 'Labour law information cards',
            'view' => 'public.sections.labour-law-cards',
            'accepts_items' => false,
            'accepts_data_source' => true,
            'data_source_models' => ['LabourLaw'],
            'content_schema' => [
                'heading' => 'text',
                'subheading' => 'textarea',
            ],
            'settings_schema' => [
                'columns' => 'select:2|3',
                'show_excerpt' => 'boolean',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | These settings are applied to all section types by default.
    |
    */

    'default_settings' => [
        'background' => 'none',
        'background_color' => '#ffffff',
        'padding_top' => 'default',
        'padding_bottom' => 'default',
        'container_width' => 'contained',
        'heading_alignment' => 'center',
        'columns_desktop' => 4,
        'columns_tablet' => 2,
        'columns_mobile' => 1,
        'animation_enabled' => true,
        'visible_desktop' => true,
        'visible_mobile' => true,
        'custom_css_class' => null,
        'custom_id' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Padding Options
    |--------------------------------------------------------------------------
    */

    'padding_options' => [
        'none' => 'None',
        'xs' => 'Extra Small',
        'sm' => 'Small',
        'default' => 'Default',
        'md' => 'Medium',
        'lg' => 'Large',
        'xl' => 'Extra Large',
        '2xl' => '2XL',
    ],

    /*
    |--------------------------------------------------------------------------
    | Container Width Options
    |--------------------------------------------------------------------------
    */

    'container_width_options' => [
        'contained' => 'Contained (max-width container)',
        'full' => 'Full Width',
        'narrow' => 'Narrow',
        'wide' => 'Wide',
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Source Model Whitelist
    |--------------------------------------------------------------------------
    |
    | Only models listed here can be used as data sources.
    | This prevents arbitrary code execution.
    |
    */

    'data_source_models' => [
        'UmrahPackage' => \App\Models\UmrahPackage::class,
        'VisaType' => \App\Models\VisaType::class,
        'Branch' => \App\Models\Branch::class,
        'News' => \App\Models\News::class,
        'Blog' => \App\Models\Blog::class,
        'Faq' => \App\Models\Faq::class,
        'Testimonial' => \App\Models\Testimonial::class,
        'LabourLaw' => \App\Models\LabourLaw::class,
    ],

];
