<?php
/*
Plugin Name: User Series
Plugin URI: http://fictions4u.com/?page_id=53
Version: 0.1
Description: Extend Organize Series plugin by adding author information. Users can only edit or post to their own series. Requires Organize Series and Taxonomy Metadata.
Author: Edmund Lin
Author URI: http://fictions4u.com/?page_id=53
*/

add_action('created_series','saveSeriesAuthor',10,2);
add_action('delete_term_taxonomy','deleteSeries');
add_action('edit_terms','editSeriesTerm');
add_action('edit_term_taxonomy','editSeriesTaxonomy',10,2);
add_action('get_series','getSeriesAuthor');
add_action('wp_before_admin_bar_render' ,'adminMenuBar');

add_filter('get_series_list','filterSeriesByAuthor');
add_filter('manage_edit-series_columns', 'addAuthorColumn');
add_filter('manage_series_custom_column', 'showAuthorColumn',1,3);
add_filter('get_terms','filterManageSeriesByAuthor');

function adminMenuBar($menuBar)
{
	global $wp_admin_bar,$wp_the_query;
	if(!$wp_admin_bar->get_node('edit')) return;
	$current_object = $wp_the_query->get_queried_object();
	if(empty($current_object->term_taxonomy_id)) return;
	$current_user = wp_get_current_user();
	if($current_user->ID!=$current_object->author->ID && !current_user_can('manage_options'))
		$wp_admin_bar->remove_menu('edit');
}

function getSeriesAuthor($term)
{
	$term->author=get_user_by('id',get_term_meta($term->term_taxonomy_id,'author',true));
	return $term;
}

function addAuthorColumn($columns) {
	$columns['author'] = 'Author';
	return $columns;
}

function showAuthorColumn($content, $column_name, $id)
{
	$output=$content;
	if($column_name=='author')
	{
		$term=get_term($id,'series');
		$output=$term->author->display_name;
	}
	return $output;
}

function saveSeriesAuthor($term_id, $tt_id)
{
	$current_user = wp_get_current_user();
	add_term_meta($tt_id,'author',$current_user->ID,true);
}

function deleteSeries($tt_id)
{
	global $wpdb;
	$taxonomy=$wpdb->get_var( $wpdb->prepare( "SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = %d", $tt_id));
	if($taxonomy!='series') return;
	$current_user = wp_get_current_user();
	$author=get_term_meta($tt_id,'author',true);
	if($author!=$current_user->ID)
	{
		$_SESSION['myError']="You can only delete your own series.";
		wp_die("You can only delete your own series.");
	}
}

function editSeriesTerm($t_id)
{
	if(current_user_can('manage_options')) return;
	$term=get_term($t_id,'series');
	if(empty($term)) return;
	$current_user=wp_get_current_user();
	if($current_user->ID!=$term->author->ID)
	{
		wp_die("You can only edit your own series.");
	}
}

function editSeriesTaxonomy($tt_id,$taxonomy)
{
	if(current_user_can('manage_options')) return;
	if($taxonomy!='series') return;
	$current_user=wp_get_current_user();
	$author=get_term_meta($tt_id,'author',true);
	if($author!=$current_user->ID)
	{
		wp_die("You can only edit your own series.");
	}
}


function filterSeriesByAuthor($series)
{
	$current_user = wp_get_current_user();
	$result=array();
	foreach($series as $key=>$serial)
	{
		$author=get_term_meta($key,'author',true);
		if($current_user->ID==$author)
			$result[$key]=$serial;
	}
	return $result;
}

function filterManageSeriesByAuthor($terms)
{
	if(!is_admin()) return $terms;
	if(current_user_can('manage_options')) return $terms;
	$result=array();
	$current_user = wp_get_current_user();
	foreach($terms as $key=>$term)
	{
		if($term->taxonomy!='series')
		{
			$result[$key]=$term;
		}
		else
		{
			$author=get_term_meta($term->term_taxonomy_id,'author',true);
			if($author==$current_user->ID)
				$result[$key]=$term;
		}
	}
	return $result;
}