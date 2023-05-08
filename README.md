# Ultimate Member - Polylang Ajax
Extension to Ultimate Member for Members Directory translations of Users hit counter with and without Polylang

## Settings
None

## Updates
Version 1.0.0

## Installation
1. Install by downloading the plugin ZIP file and install as a new Plugin, which you upload in WordPress -> Plugins -> Add New -> Upload Plugin.
2. Activate the Plugin: Ultimate Member - Polylang Ajax

## Template
1. Customize the template `members-header.php`
2. Read UM Documentation https://docs.ultimatemember.com/article/1516-templates-map


```
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>


<script type="text/template" id="tmpl-um-members-header">
	<div class="um-members-intro">
		<div class="um-members-total">
			<# if ( data.pagination.total_users == 1 ) { #>
				{{{data.pagination.header_single}}}
			<# } else if ( data.pagination.total_users > 1 ) { #>
				{{{data.pagination.header}}}
			<# } else if ( data.pagination.total_users == 0 ) { #>
				{{{data.pagination.no_users}}}
			<# }#>
		</div>
	</div>

	<div class="um-clear"></div>
</script>
```
