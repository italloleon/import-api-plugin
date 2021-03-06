<?php

namespace ImportApiPlugin\Admin\CustomPostTypes;

/**
 * Class which manage the custom post brewery
 * 
 * @package    ImportApiPlugin
 * @subpackage ImportApiPlugin/src/CustomPostTypes
 * @author     Itallo Leonardo <itallolaraujo@gmail.com.com>
 * @since      1.0.0
 * 
 */

class Brewery
{
	const post_type = 'brewery';

	/**
	 * Register the custom post brewerie
	 * 
	 * @return void
	 */

	public static function register_post_type()
	{
		$labels = [
			"name" => __("Breweries", "import-api-plugin"),
			"singular_name" => __("Brewery", "import-api-plugin"),
			"add_new" => __("Add new Brewery", "import-api-plugin"),
			"edit_item" => __("Edit Brewery", "import-api-plugin"),
			"new_item" => __("New Brewery", "import-api-plugin"),
			"view_item" => __("View Brewery", "import-api-plugin"),
			"all_item" => __("All Breweries", "import-api-plugin"),
			"search_items" => __("Search Breweries", "import-api-plugin"),
			"not_found" => __("No Breweries found", "import-api-plugin"),
			"not_found_in_trash" => __("No Breweries found", "import-api-plugin"),
		];

		$args = [
			"label" => __("Breweries", "import-api-plugin"),
			"labels" => $labels,
			"description" => "",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"has_archive" => true,
			"menu_icon" => "dashicons-beer",
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"delete_with_user" => false,
			"exclude_from_search" => false,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"query_var" => true,
			"supports" => ["title", "author", "editor", "custom-fields", "revisions",],
			"show_in_graphql" => true,
			'taxonomies' => array('category'),
		];

		register_post_type(self::post_type, $args);
	}


	/**
	 * Overwrite a Single Brewery Front Template
	 * 
	 * @return string
	 */
	public static function overwrite_brewery_template_single($template)
	{
		$object = get_queried_object();

		if ($object->post_type === self::post_type) {
			return IMPORTAPI_PLUGIN_DIR . "/single-$object->post_type.php";
		}

		return $template;
	}

	/**
	 * Create a submenu to import breweries from API
	 * 
	 * @return void
	 */
	public static function brewery_import_sub_menu_page()
	{
		add_submenu_page(
			'edit.php?post_type=' . self::post_type,
			__('Brewery Import', 'import-api-plugin'),
			__('Brewery Import', 'import-api-plugin'),
			'manage_options',
			'brewery-import-page',
			array(__CLASS__, 'brewery_import_sub_menu_page_callback')
		);
	}

	/**
	 * A callback function which displays the import option page
	 * 
	 * @return void
	 */
	public static function brewery_import_sub_menu_page_callback()
	{
		$breweries_imported = get_option('api_breweries_imported');
		$button_text = 'Import Breweries';
		if ($breweries_imported) {
			$button_text = 'Allow Import Breweries';
		}
		ob_start();
?>
		<div class="wrap">
			<h1><?php _e('Import Breweries', 'import-api-plugin'); ?></h1>
			<br>
			<?php if ($breweries_imported) : ?>
				<p>
					<?php
					_e(
						'An import has already been done previously. This action will not update or overwrite existing Breweries, and will duplicate content.',
						'import-api-plugin'
					);
					?>
				</p>
				<p>
					<?php
					_e(
						'Do you really want to perform this action?',
						'import-api-plugin'
					);
					?>
				</p>
			<?php endif; ?>
			<div class="button-gallery">
				<button class="button button-primary" id="import-breweries">
					<?php _e($button_text, 'import-api-plugin'); ?>
				</button>
			</div>
			<hr>
			<h2>
				<?php _e('The imported breweries will appear bellow', 'import-api-plugin'); ?>
			</h2>
			<ul id="imported-breweries-list">

			</ul>
		</div>
<?php
		echo ob_get_clean();
	}
}
