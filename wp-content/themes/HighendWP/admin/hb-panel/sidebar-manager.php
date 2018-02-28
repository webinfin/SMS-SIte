<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<script>
			function remove_sidebar_link(name,num){
				answer = confirm("Are you sure you want to remove " + name + "?\nThis will remove any widgets you have assigned to this sidebar.");
				if(answer){
					remove_sidebar(name,num);
				}else{
					return false;
				}
			}

			var counter = 0;
			function add_sidebar_link(){
				counter++;
				var sidebar_name = (prompt("Please enter sidebar name:","New Sidebar " + counter)).trim();
				if (sidebar_name.length > 0){
					add_sidebar(sidebar_name);
					jQuery('.nsd').hide(0);
				} else {
					alert("Sidebar name cannot be empty");
				}
			}
		</script>
		<div class="wrap hb-alt-wrap">
			<h2>Sidebar Manager</h2>
			<table class="widefat hb_diagnostics_table" id="sbg_table">
				<tr>
					<th><strong>Sidebar Name</strong></th>
					<th><strong>CSS class</strong></th>
					<th><strong>Remove</strong></th>
				</tr>
				<?php
				$sidebars = sidebar_generator::get_sidebars();
				if(is_array($sidebars) && !empty($sidebars)){
					$cnt=0;
					foreach($sidebars as $sidebar){
						$alt = ($cnt%2 == 0 ? 'alternate' : '');
				?>
				<tr class="<?php echo $alt?>">
					<td><?php echo $sidebar; ?></td>
					<td><?php echo sidebar_generator::name_to_class($sidebar); ?></td>
					<td><a href="javascript:void(0);" onclick="return remove_sidebar_link('<?php echo $sidebar; ?>',<?php echo $cnt+1; ?>);" title="Remove this sidebar">Remove</a></td>
				</tr>
				<?php
						$cnt++;
					}
				}else{
					?>
					<tr class="nsd">
						<td colspan="3">No Sidebars defined</td>
					</tr>
					<?php
				}
				?>
			</table>
				<p class="about-description"><a href="javascript:void(0);" onclick="return add_sidebar_link()" class="button button-hero button-primary"><?php _e('Add New Sidebar','hbthemes'); ?></a></p>
			
		</div>
