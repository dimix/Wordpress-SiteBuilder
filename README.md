# Wordpress-SiteBuilder
Create fast and simple Wordpress themes by setting up a JSON.

Wordpress SiteBuilder is a simple theme that allow you to configure in a fast way your custom posts, taxonomies and menus.

## Features
- A JSON to auto-configure Wordpress custom posts, taxonomies and menus.
- JSON contents are the same of Wordpress action arguments.
- Automatically generate Custom post meta boxes.
- Support for input types: Text, Number, Color Picker, Checkbox, Select, Date, DateTime, TextEditor, Upload Image, Multiple Upload Image.

## How to Use
1. Add sitebuilder folder in your wordpress theme.
2. Configure site.json file with custom posts, taxonomies and menus.
3. Add your HTML/CSS Theme.
4. Launch your website.

The JSON has this structure:

```json
{
	"id" : "sitebuilder_theme",
	"custom_posts" : [],
  "custom_taxonomies": [],
  "menus": []
}
```
Custom Post has this structure:

```json
{
			"id" : <POST_TYPE_ID>,
			"labels" : {
				"name" : "<POST_TYPE_NAME>",
				"singular_name" : "<POST_TYPE_NAME>",
				"description"  : "<POST_TYPE_DESCRIPTION>"
			},
			"public" : true,
			"hierarchical" : false,
			"rewrite" : {
				"slug" : "<POST_TYPE_SLUG>"
			},
			"supports" : ["title", "editor", "thumbnail", "page-attributes", "revisions"],
			"meta_boxes" : [
				{
					"id" : "<POST_TYPE_METABOX_ID>",
					"title" : "<POST_TYPE_METABOX_NAME>",
					"context" : "normal",
					"priority" : "high",
					"descriptors" : [<POST_TYPE_METABOX_DESCRIPTORS>]
				}
			],
			"custom_columns" : {
				"<POST_TYPE_FIELD_SLUG>": "<POST_TYPE_FIELD_NAME>"
			}
		}
```

"Post Type Metabox Descriptor" descibe the fields in a Meta box and has this structure:

```json
{"slug" : "<SLUG>", "label" : "<LABEL>", "type" : "<TYPE>", "default" : "<DEFAULT VALUE>", "values": [<'SELECT TYPE' VALEUS>]}
```

The possible types are:
* text
* number
* checkbox
* colorpicker
* select
* datetime
* time
* date
* texteditor
* imageupload
* multiple-imageupload
* productbox (not fully supported)

Custom Taxonomies has the same structure of the register_taxonomy() arguments:

```json
{
			"id" : "<TAXONOMY_ID>",
			"custom_posts" : ["<POST_TYPE_SLUG>"],
			"hierarchical" : true,
			"labels" : {<REGISTER_TAXONOMY_ARGUMENTS>},
			"show_ui" : true,
			"show_admin_column" : true,
			"query_var" : true,
			"rewrite" : { "slug" : "<TAXONOMY_SLUG>" }
		}
```

Menus are very simple:

```json
{
    "<MENU_ID>": "<MENU_NAME>"
}
```
