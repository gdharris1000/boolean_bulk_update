# Boolean Bulk Update Module

## About

Drupal module that lets the user set the value of a boolean field to be the same on every existing page of a chosen content type.

### Compatibility

Drupal 8 or 9

## Installation

1. Add the folder to the **modules/custom** folder of your site.
2. Enable the module in **/admin/extend** or using Drush `drush en boolean_bulk_update`

## Usage

1. You can access the tool using the link to **Boolean Bulk Update** in the **Configuration** menu under **Content authoring**. Alternatively you can access the tool by navigating to **admin/boolean_bulk_update**.
2. You will be presented with a drop-down menu of all **Content Types**.
3. When you select a **Content Types**, the **Field** drop-down menu will be automatically populated with all the boolean fields of the chosen **Content Type**
4. **Check** the **New Value** checkbox to set this field to **TRUE** for all existing pages of the chosen Content Type. Leave the checkbox **unchecked** to set this field to **FALSE** for all existing pages of the chosen Content Type.
5. Press **Submit** to apply the update.
