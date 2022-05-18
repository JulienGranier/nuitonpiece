<?php
function mlbr_custom_post_type_librairies()
{
    $labels = [
        'name'               => __('Librairies', 'nuitonpiece'),
        'singular_name'      => __('Librairie', 'nuitonpiece'),
        'menu_name'          => __('Librairie', 'nuitonpiece'),
        'all_items'          => __('Toutes les librairies', 'nuitonpiece'),
        'view_item'          => __('Voir les librairies', 'nuitonpiece'),
        'add_new_item'       => __('Ajouter une nouvelle librairie', 'nuitonpiece'),
        'add_new'            => __('Ajouter', 'nuitonpiece'),
        'edit_item'          => __('Éditer la librairie', 'nuitonpiece'),
        'update_item'        => __('Modifier la librairie', 'nuitonpiece'),
        'search_items'       => __('Rechercher une librairie', 'nuitonpiece'),
        'not_found'          => __('librairies non trouvé', 'nuitonpiece'),
        'not_found_in_trash' => __('librairies non trouvé dans la corbeille', 'nuitonpiece'),
    ];

    $args = [
        'label'              => __('Librairies', 'nuitonpiece'),
        'description'        => __('Tout sur les librairies', 'nuitonpiece'),
        'labels'             => $labels,
        'supports'           => ['title','editor','author'],
        'menu_icon'          => 'dashicons-store',
        'rewrite'            => ['slug' => 'librairies'],
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 26,
    ];
    register_post_type('librairie', $args);
}

add_action('init', 'mlbr_custom_post_type_librairies', 0);