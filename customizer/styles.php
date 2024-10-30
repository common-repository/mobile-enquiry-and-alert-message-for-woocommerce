<?php

/**
 * Implements styles set in the theme customizer
 *
 * @package Customizer Library WooCommerce Designer
 */
if ( !function_exists( 'mmwea_woo_enq_btn_customizer_style_build' ) && class_exists( 'mob_enq_btn_style_library_Styles' ) ) {
    /**
     * Process user options to generate CSS needed to implement the choices.
     *
     * @since  1.0.0.
     *
     * @return void
     */
    function mmwea_woo_enq_btn_customizer_style_build()
    {


        /** Customize Add to Cart Button CSS Start */
        // Button - Font Size

        $mmwea_btn_style_options = get_option('mmwea_desgin_elements_options');


        $setting = 'whatsapp_btn_font_size';
        
        if ( isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting]) ) {
            $mod = $mmwea_btn_style_options[$setting];
            $mmwea_btn_fsize = esc_attr( $mod );
            mob_enq_btn_style_library_Styles()->add( array(
                'selectors'    => array( '.mmwea-wa-button' ),
                'declarations' => array(
                'font-size' => $mmwea_btn_fsize . 'px !important',
            ),
            ) );
        }

        $setting = 'whatsapp_btn_font_weight';
        
        if ( isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting]) ) {
            $mod = $mmwea_btn_style_options[$setting];
            $mmwea_btn_fsize = esc_attr( $mod );
            mob_enq_btn_style_library_Styles()->add( array(
                'selectors'    => array( '.mmwea-wa-button' ),
                'declarations' => array(
                'font-weight' => $mmwea_btn_fsize . '!important',
            ),
            ) );
        }

        // Button - Color
        $setting = 'whatsapp_btn_color';
        
        if ( isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting]) ) {
            $mod = $mmwea_btn_style_options[$setting];
            $mmwea_btn_bgcolor = mmwea_sanitize_hex_color( $mod );
            mob_enq_btn_style_library_Styles()->add( array(
                'selectors'    => array( '.mmwea-wa-button' ),
                'declarations' => array(
                'background-color' => $mmwea_btn_bgcolor . ' !important',
                'color'            => mmwea_getContrastColor( $mmwea_btn_bgcolor ) . ' !important',
            ),
            ) );
        }
        
        // Button - Font Color
        $setting = 'whatsapp_btn_font_color';
        
        if ( isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting]) ) {
            $mod = $mmwea_btn_style_options[$setting];
            $mmwea_btn_fontcolor = mmwea_sanitize_hex_color( $mod );
            mob_enq_btn_style_library_Styles()->add( array(
                'selectors'    => array( '.mmwea-wa-button' ),
                'declarations' => array(
                'color' => $mmwea_btn_fontcolor . ' !important',
            ),
            ) );
        }
        
        // Button - Hover Color
        $setting = 'whatsapp_btn_hover_color';
        
        if ( isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting]) ) {
            $mod = $mmwea_btn_style_options[$setting];
            $mmwea_btn_hovercolor = mmwea_sanitize_hex_color( $mod );
            mob_enq_btn_style_library_Styles()->add( array(
                'selectors'    => array( '.mmwea-wa-button:hover' ),
                'declarations' => array(
                'background-color' => $mmwea_btn_hovercolor . ' !important',
                'color'            => mmwea_getContrastColor( $mmwea_btn_hovercolor ) . ' !important',
            ),
            ) );
        }
        
        // Button - Font Hover Color
        $setting = 'whatsapp_btn_font_hover_color';
        
        if ( isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting]) ) {
            $mod = $mmwea_btn_style_options[$setting];
            $mmwea_btn_hovercolor = mmwea_sanitize_hex_color( $mod );
            mob_enq_btn_style_library_Styles()->add( array(
                'selectors'    => array( '.mmwea-wa-button:hover' ),
                'declarations' => array(
                'color' => $mmwea_btn_hovercolor . ' !important',
            ),
            ) );
        }
        
        // Button - Border Radius
        $setting = 'whatsapp_btn_border_radius';
        
        if ( isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting]) ) {
           
            $select_style_val = array();
            if(isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting])){
                $select_style_val = explode(",",$mmwea_btn_style_options[$setting] );
            }
 
            $top_value = isset($select_style_val[0]) ? $select_style_val[0] : '0';
            $right_value = isset($select_style_val[1]) ? $select_style_val[1] : '0';
            $bottom_value = isset($select_style_val[2]) ? $select_style_val[2] : '0';
            $left_value = isset($select_style_val[3]) ? $select_style_val[3] : '0';

            if(!empty($top_value) && !empty($right_value) && !empty($bottom_value) && !empty($left_value)){
                $mmwea_btn_br = $top_value."px ".$right_value."px ".$bottom_value."px ".$left_value."px !important";

                $mmwea_btn_br = esc_attr( $mmwea_btn_br );
                mob_enq_btn_style_library_Styles()->add( array(
                    'selectors'    => array( '.mmwea-wa-button' ),
                    'declarations' => array(
                    'border-radius' => $mmwea_btn_br,
                ),
                ) );
            }
        }

                
        // Button - Padding
        $setting = 'whatsapp_btn_padding';
       
        
        if ( isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting]) ) {

            $select_style_val = array();
            if(isset($mmwea_btn_style_options[$setting]) && !empty($mmwea_btn_style_options[$setting])){
                $select_style_val = explode(",",$mmwea_btn_style_options[$setting] );
            }
 
            $top_value = isset($select_style_val[0]) ? $select_style_val[0] : '0';
            $right_value = isset($select_style_val[1]) ? $select_style_val[1] : '0';
            $bottom_value = isset($select_style_val[2]) ? $select_style_val[2] : '0';
            $left_value = isset($select_style_val[3]) ? $select_style_val[3] : '0';

            if(!empty($top_value) && !empty($right_value) && !empty($bottom_value) && !empty($left_value)){

                $mmwea_btn_br = $top_value."px ".$right_value."px ".$bottom_value."px ".$left_value."px !important";
    
                $mmwea_btn_pad = esc_attr( $mmwea_btn_br );
                mob_enq_btn_style_library_Styles()->add( array(
                    'selectors'    => array( '.mmwea-wa-button' ),
                    'declarations' => array(
                    'padding' => $mmwea_btn_pad,
                ),
                ) );
            }
        }
    
    
    }

}
add_action( 'customizer_library_styles', 'mmwea_woo_enq_btn_customizer_style_build' );
if ( !function_exists( 'mmwea_woocustomizer_customizer_library_styles' ) ) {
    /**
     * Generates the style tag and CSS needed for the theme options.
     *
     * By using the "mob_enq_btn_style_library_Styles" filter, different components can print CSS in the header.
     * It is organized this way to ensure there is only one "style" tag.
     *
     * @since  1.0.0.
     *
     * @return void
     */
    function mmwea_woocustomizer_customizer_library_styles()
    {
        do_action( 'customizer_library_styles' );
        // Echo the rules
        $css = mob_enq_btn_style_library_Styles()->build();
        
        if ( !empty($css) ) {
            wp_register_style( 'mmwea-customizer-custom-css', false );
            wp_enqueue_style( 'mmwea-customizer-custom-css' );
            wp_add_inline_style( 'mmwea-customizer-custom-css', $css );
        }
    
    }

}
add_action( 'wp_enqueue_scripts', 'mmwea_woocustomizer_customizer_library_styles', 11 );

function mmwea_getContrastColor( $hexColor )
{
    // hexColor RGB
    $R1 = hexdec( substr( $hexColor, 1, 2 ) );
    $G1 = hexdec( substr( $hexColor, 3, 2 ) );
    $B1 = hexdec( substr( $hexColor, 5, 2 ) );
    // Black RGB
    $blackColor = "#000000";
    $R2BlackColor = hexdec( substr( $blackColor, 1, 2 ) );
    $G2BlackColor = hexdec( substr( $blackColor, 3, 2 ) );
    $B2BlackColor = hexdec( substr( $blackColor, 5, 2 ) );
    // Calc contrast ratio
    $L1 = 0.2126 * pow( $R1 / 255, 2.2 ) + 0.7151999999999999 * pow( $G1 / 255, 2.2 ) + 0.0722 * pow( $B1 / 255, 2.2 );
    $L2 = 0.2126 * pow( $R2BlackColor / 255, 2.2 ) + 0.7151999999999999 * pow( $G2BlackColor / 255, 2.2 ) + 0.0722 * pow( $B2BlackColor / 255, 2.2 );
    $contrastRatio = 0;
    
    if ( $L1 > $L2 ) {
        $contrastRatio = (int) (($L1 + 0.05) / ($L2 + 0.05));
    } else {
        $contrastRatio = (int) (($L2 + 0.05) / ($L1 + 0.05));
    }
    
    // If contrast is more than 5, return black color
    
    if ( $contrastRatio > 5 ) {
        return '#000000';
    } else {
        // if not, return white color.
        return '#FFFFFF';
    }

}

function mmwea_sanitize_hex_color( $color ) {
	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return null;
}

// function woocustomizer_library_hex_to_rgb( $hex ) {

// 	// Remove "#" if it was added
// 	$color = trim( $hex, '#' );

// 	// Return empty array if invalid value was sent
// 	if ( ! ( 3 === strlen( $color ) ) && ! ( 6 === strlen( $color ) ) ) {
// 		return array();
// 	}

// 	// If the color is three characters, convert it to six.
// 	if ( 3 === strlen( $color ) ) {
// 		$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
// 	}

// 	// Get the red, green, and blue values
// 	$red   = hexdec( $color[0] . $color[1] );
// 	$green = hexdec( $color[2] . $color[3] );
// 	$blue  = hexdec( $color[4] . $color[5] );

// 	// Return the RGB colors as an array
// 	return array( 'r' => $red, 'g' => $green, 'b' => $blue );
// }

?>