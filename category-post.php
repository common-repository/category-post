<?php
/*
Plugin Name: Category Post
Plugin URI: http://www.torounit.com/category-post/
Description: Category Post adds 'Add New' to admin menu with each category.
Author: Toro-Unit
Author URI: http://www.torounit.com
Version: 0.8

*/

/*  Copyright 2011 Toro_Unit (email : mail@torounit.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*Last Update 2011-09-21*/


class Category_Post {
	
	//actions
	function Category_Post(){
		add_action('init', array(&$this,'load_textdomain'));
		add_action('admin_menu', array(&$this,'custom_post_link'));
		add_action('admin_menu', array(&$this,'remove_meta_boxes'));
		add_action('admin_print_scripts', array(&$this,'add_current_class'));
		add_action('dbx_post_sidebar',array(&$this,'add_input_tag'));
		add_action('save_post', array(&$this,'set_object_terms'), 100, 2 );	
	}

	function load_textdomain(){
		load_plugin_textdomain('categorypost',false,'category-post');	
	}


	//Customize admin menu
	function custom_post_link() {
		remove_submenu_page( "edit.php", "edit-tags.php?taxonomy=category" );
		remove_submenu_page( "edit.php", "edit-tags.php?taxonomy=post_tag" );
		remove_submenu_page( "edit.php", "post-new.php" );

		//add links to admin menu
		$categories = get_terms("category", "get=all");
		foreach ($categories as $key => $category){
			$catName = $category->name;
			$printStr = sprintf(__('Add New in %s',"categorypost"), $catName);
			add_posts_page($printStr, $printStr, 'edit_themes', 'post-new.php?category_name='.urlencode($catName));
		}
		add_posts_page(__('Categories'),__('Categories'), 'edit_posts', "edit-tags.php?taxonomy=category");
		add_posts_page(__('Post Tags'),__('Post Tags'), 'edit_posts', "edit-tags.php?taxonomy=post_tag");
	}



	//remove categorydiv
	function remove_meta_boxes() {
		if(isset($_GET["category_name"])){
			remove_meta_box('categorydiv','post','normal');
		}
	}

	//add current class
	function add_current_class() {
		wp_enqueue_script('add_current_class',get_settings('site_url').'/wp-content/plugins/category-post/scripts.js', array('jquery'), '0.4');
	}



	//save
	//カテゴリー名をPOSTで渡す
	function add_input_tag() {
		echo '<input type="hidden" id="category_name" name="category_name" value="'.urlencode($_GET["category_name"]).'" />';
	}

	//set category
	function set_object_terms( $post_id, $post ) {
		if($_POST["category_name"]){
			$cat['category'] = array(urldecode(($_POST["category_name"])));
			wp_set_object_terms( $post_id, $cat['category'], 'category');
		}
	}

}
$category_post = new Category_Post();
