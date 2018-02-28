<?php
/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
class hb_custom_walker extends Walker_Nav_Menu
{  
        
      var $columns = 0;
      var $max_columns = 0;
      var $rows = 1;
      var $rowsCounter = array();
      var $mega_active = 0;

      function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);

        $style = '';
            
        if($depth === 0 && $this->active_megamenu) 
        {        
          $style .= !empty($this->megamenu_background) ? ('background-image:url('.$this->megamenu_background.');') : '';
          $style .= !empty($this->megamenu_styles) ? $this->megamenu_styles : '';
        }

        if ( !empty($this->megamenu_background) ){
          $output .= "\n$indent<ul style=\"$style\" class=\"sub-menu-with-bg sub-menu {locate_class}\">\n";  
        } else {
          $output .= "\n$indent<ul style=\"$style\" class=\"sub-menu {locate_class}\">\n";
        }

      }

      function end_lvl(&$output, $depth = 0, $args = array()) {
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
            
            if($depth === 0) 
            {
                if($this->active_megamenu)
                {

                    $output = str_replace("{locate_class}", "mega_col_".$this->max_columns."", $output);
                    
                    foreach($this->rowsCounter as $row => $columns)
                    {
                        $output = str_replace("{current_row_".$row."}", "mega_col_".$columns, $output);
                    }
                    
                    $this->columns = 0;
                    $this->max_columns = 0;
                    $this->rowsCounter = array();
                    
                }
                else
                {
                    $output = str_replace("{locate_class}", "", $output);
                }
            }
        }    

      function start_el(&$output, $item, $depth = 0, $args = Array(), $id = 0)
      {
           global $wp_query;

           $item_output = $li_text_block_class = $column_class = "";

           $this->megamenu_widgetarea = get_post_meta( $item->ID, '_menu_item_megamenu_widgetarea', true);
           $this->megamenu_background = get_post_meta( $item->ID, '_menu_item_megamenu_background', true );
           $this->megamenu_styles = get_post_meta( $item->ID, '_menu_item_megamenu_styles', true );
           $this->megamenu_columns = get_post_meta( $item->ID, '_menu_item_megamenu_columns', true );
           $this->megamenu_captions = get_post_meta( $item->ID, '_menu_item_megamenu_captions', true);

           if($depth === 0)
           {   
                $this->active_megamenu = get_post_meta( $item->ID, '_menu_item_megamenu', true);

                if($this->active_megamenu) {
                    $column_class .= " megamenu " . $this->megamenu_columns;
                    if ( $this->megamenu_captions != "enabled" )
                      $column_class .= " no-caption";
                } else {
                    $column_class .= " no-megamenu";
                }

           }

           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
          // $class_names = ' class="'. esc_attr( $class_names ) . '"';
           $class_names = ' class="'.$li_text_block_class. esc_attr( $class_names ) . $column_class.'"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

           $description  = ! empty( $item->description ) ? '<span class="hb-menu-description">'.esc_attr( $item->description ).'</span>' : '';

           if($depth === 1 && $this->active_megamenu && is_active_sidebar( $this->megamenu_widgetarea ) )
           {
            
                $item_output .= '<div class="megamenu-widgets-container">';
                ob_start();
                dynamic_sidebar( $this->megamenu_widgetarea );

                $item_output .= ob_get_clean() . '</div>';
              
           }else {
             if($depth != 0)
             {
              $append = $prepend = "";
             }

              $item_output = $args->before;
              $item_output .= '<a'. $attributes .'>';
              if ( $item->subtitle != '' ){
                $item_output .= '<i class="' . $item->subtitle.'"></i>';
              }
              $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
              $item_output .= $description.$args->link_after;
              $item_output .= '</a>';
              $item_output .= $args->after;
            }

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
}