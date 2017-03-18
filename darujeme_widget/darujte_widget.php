<?php
/**
 * Plugin name: Darujme widget
 * Plugin URI: 
 * widget_Description: WP widget for <a href="https://www.darujme.cz"> Darujme.cz</a>
 * Version: 0.1
 * Author: 2046
 * Author URI: http://2046.cz
 *
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'DarujteWidgetInit' );

/**
 * Register our widget.
 * 'Darujte_Widget_Widget' is the widget class used below.
 */
function DarujteWidgetInit() {
	register_widget( 'DarujteWidget' );
	// localization
	load_plugin_textdomain( 'DarujteWidgetLocalisation', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 
}

/**
 * Darujte_Widget Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 */
 class DarujteWidget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 
			'classname' => 'Darujte_Widget_class',
			'description' => __('Darovací Widget','DarujteWidgetLocalisation') 
		);	
		
		$this->frequency_types = array(
			'' => __('jednorázově','DarujteWidgetLocalisation'),
			'28' => __('měsíčně','DarujteWidgetLocalisation'),
			'365' => __('ročně','DarujteWidgetLocalisation')
		);

		/* Create the widget. */
		parent::__construct( 'DarujteWidget', __('Darovací widget', 'DarujteWidgetLocalisation'), $widget_ops);
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) { 
		
		/* Set up some default widget settings. */
		$defaults = array(
			'the_widget_title' => '', 
			'project_id' => '',
			'client_id' => '',
			'widget_description' => '',
			'amounts' => '200, 300, 500, 1000', 
			'default_amount' => '200', 
			'currency_title' => 'Kč', 
			'frequency' => '',
			'default_frequency' => '',
			'submit_text' => 'Daruj',
			'custom_widget_class' => '',
			'disable_css' => 0
		);

		

		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		echo '<div id="the_widget_id_'.$this->id.'" class="Darujte_Widget">';

			echo '
			<p class="the_widget_title">
				<strong>'.__('nadpis widgety','DarujteWidgetLocalisation').'</strong><br />
				<input id="in-widget-title"  type="text" name="'. $this->get_field_name( 'the_widget_title' ).'" value="'. $instance['the_widget_title'] .'"/>
				<br />
				<em>'.__('Nadpis widgety.', 'DarujteWidgetLocalisation').'</em>
			</p>

			<p class="client_id">
				<strong>'.__('ID klienta','DarujteWidgetLocalisation').'</strong><br />
				<input type="text" name="'. $this->get_field_name( 'client_id' ).'" value="'. $instance['client_id'] .'"/>
				<br />
				<em>'.__('ID klienta získáte na stránkách Darujte.cz', 'DarujteWidgetLocalisation').'</em>
			</p>

			<p class="project_id">
				<strong>'.__('ID projektu','DarujteWidgetLocalisation').'</strong><br />
				<input type="text" name="'. $this->get_field_name( 'project_id' ).'" value="'. $instance['project_id'] .'"/>
				<br />
				<em>'.__('ID projektu který chcete podpořit získáte na stránkách Darujte.cz', 'DarujteWidgetLocalisation').'</em>
			</p>
				
			<p class="widget_description">
				<strong>'.__('Popisek','DarujteWidgetLocalisation').'</strong><br />
				<textarea name="'. $this->get_field_name( 'widget_description' ).'">'. $instance['widget_description'] .'</textarea>
				<br />
				<em>'.__('popisek se zobrazi pod nadpisem widgety', 'DarujteWidgetLocalisation').'</em>
			</p>
		

			<p class="amounts">
				<strong>'.__('částky','DarujteWidgetLocalisation').'</strong><br />
				<input type="text" name="'. $this->get_field_name( 'amounts' ).'" value="'. $instance['amounts'] .'"/>
				<br />
				<em>'.__('částky oddělené čárkou', 'DarujteWidgetLocalisation').'</em>
			</p>

			<p class="default_amount">
				<strong>'.__('předvybraná částka','DarujteWidgetLocalisation').'</strong><br />
				<input type="text" name="'. $this->get_field_name( 'default_amount' ).'" value="'. $instance['default_amount'] .'"/>
				<br />
				<em>'.__('zadejte jednu z výše uvedených hodnot', 'DarujteWidgetLocalisation').'</em>
			</p>

			<p class="currency_title">
				<strong>'.__('text měny','DarujteWidgetLocalisation').'</strong><br />
				<input type="text" name="'. $this->get_field_name( 'currency_title' ).'" value="'. $instance['currency_title'] .'"/>
				<br>
				<em>'.__('Definuje pouze text, nikoly měnu samotnou. <br>Ta je vždy CZK.', 'DarujteWidgetLocalisation').'</em>
			</p>

			<p class="frequency">
				<strong>'.__('frekvence','DarujteWidgetLocalisation').'</strong>
					<fieldset class="frequency" id="frequency">';
						
						foreach ($this->frequency_types as $i => $type){
							echo '<input';
							if(is_array($instance['frequency'])){
								if(in_array($i,$instance['frequency'])){
									echo ' checked="checked"';
								}
							}
							echo ' type="checkbox" name="'.$this->get_field_name( 'frequency' ).'[]" value="'.$i.'" /> '.$type.'<br />';
							
						}
					echo '</fieldset>
			</p>
			
			<p class="frequency">
				<strong>'.__('předvybraná frekvence','DarujteWidgetLocalisation').'</strong><br>
				<select name="'. $this->get_field_name( 'default_frequency' ).'" class="default_frequency">';
					foreach ($this->frequency_types as $i => $type){
						$selection = ($instance['default_frequency'] == $i) ? ' selected="selected"' : '';
						echo '<option name="'. $this->get_field_name( 'default_frequency' ).'" value="'.$i.'" '.$selection.'> '.$type.'</option>';
					}
				echo '</select>
			</p>
			
			<p class="submit_text">
				<strong>'.__('Text tlačítka odeslat','DarujteWidgetLocalisation').'</strong><br />
				<input type="text" name="'. $this->get_field_name( 'submit_text' ).'" value="'. $instance['submit_text'] .'"/>
			</p>

			<hr>

			<p class="custom_widget_class">
				<strong>'.__('vlastní třída widgety (CSS)','DarujteWidgetLocalisation').'</strong><br />
				<input id="in-widget-title"  type="text" name="'. $this->get_field_name( 'custom_widget_class' ).'" value="'. $instance['custom_widget_class'] .'"/>
			</p>

			<p class="disable_css">
				<strong>'.__('nepoužívat defaultní styl (CSS)','DarujteWidgetLocalisation').'</strong><br />
				<input type="checkbox" name="'. $this->get_field_name( 'disable_css' ).'" value="1" '; if ($instance['disable_css'] == 1) {echo 'checked="checked"';} echo '>
				<br />
				<em>'.__('V případě že si chcete naskinovat widgetu kompletně sami', 'DarujteWidgetLocalisation').'</em>
			</p>

		</div>';	
	}
	
	/**
	 * Update the widget settings.
	 */
	function update($new_instance, $old_instance ) {
	
		$instance = $old_instance;

		$instance['the_widget_title'] = $new_instance['the_widget_title'];
		
		$instance['project_id'] = esc_attr($new_instance['project_id']); // this is actually not a integer, it can start with 0
		$instance['client_id'] = esc_attr($new_instance['client_id']); //
		$instance['widget_description'] = esc_textarea($new_instance['widget_description']); //
		$instance['amounts'] = esc_attr($new_instance['amounts']); 
		$instance['default_amount'] = intval($new_instance['default_amount']); 
		$instance['currency_title'] = esc_attr($new_instance['currency_title']); 
		$instance['frequency'] = esc_sql($new_instance['frequency']);
		$instance['default_frequency'] = intval($new_instance['default_frequency']);
		$instance['submit_text'] = esc_attr($new_instance['submit_text']);
		$instance['custom_widget_class'] = esc_attr($new_instance['custom_widget_class']);
		$instance['disable_css'] = intval($new_instance['disable_css']);
		return $instance;
	}
	
	/**
	 * How to display the widget on the front end
	 */
	function widget($args, $instance) {
		extract( $args );
		$the_widget_id_class = 'slider_id_'.$args['widget_id'];

		foreach ($instance as $key => $value) {
			${$key} = $value;
		}
		$widget_description_html = (!empty($widget_description)) ? '<div class="widget_description">'.$widget_description.'</div>' : '';
		
		echo '<div class="widget Darujte_Widget_class '.$custom_widget_class.'" id="'.$the_widget_id_class.'">
		'.$before_widget.'
		
			<h4 class="widget_title">'.$the_widget_title.'</h4>
			'.$widget_description_html.'
			<div class="stemps">';
			$numbers = explode(',',str_replace(' ','',$amounts));
			foreach ($numbers as $item) {	
				$cls = ($item == $default_amount) ? ' active': '';

				echo '
					<a class=" stemp daruj'.$cls.'" href="#" title="'.$item.'">
						<span>'.$item.' '.$currency_title.'</span>
					</a>
				';
				}
			echo '</div>';
			
			echo '<div class="frequences">';
				foreach ($frequency as $i) {	
					$cls = ($i == $default_frequency ) ? ' active': '';
					echo '<a class="frequency checkbox'.$cls.'" title="'.$i.'">'.$this->frequency_types[$i].'</a>';
				}
			echo '</div>';		

			echo '
				<div class="submit_field">
					<div class="amount_holder">'. __('nebo',  'DarujteWidgetLocalisation' ).' <input class="amount" type="text" name="amount" value="'.$default_amount.'"> '.$currency_title.'</div>
					<span class="submit_holder"><a class="send">'.$submit_text.'</a></span>
				</div>
			
		'.$after_widget.'
		</div>';
		

		echo '<script>
			jQuery(document).ready(function($) {
				var defVal = '.$default_amount.';
				var amount = defVal;
				var frequency = '.$default_frequency.';
				var project = "'.$project_id.'";
				var client = "'.$client_id.'";

				// define amount
				$(".daruj").click(function (e) {
					e.preventDefault();
					// change the color
					$(".daruj").removeClass("active");
					$(this).addClass("active")
					// set value
					amount = $(this).attr("title");
					// set the input field
					$("input.amount").val(amount);
				});

				// define frequency
				$(".frequency").click(function (e) {
					e.preventDefault();
					// change the color
					$(".frequency").removeClass("active")
					$(this).addClass("active");
					// set value
					frequency = ($(this).attr("title") != 0) ? $(this).attr("title") : "";
				});

				// manual amount
				$(".amount").on("input",function(e){
					 // 
					 $(".stemps .stemp").removeClass("active")
					 amount = $(this).val();
					 // if the field is empty set to default value
					 if($(this).val().length === 0){
					 	amount = defVal;
					 	$(".stemps a").removeClass("active")
					 	$(".stemps a").first().addClass("active")
					 }
					// mark active stemp with same value as given if any matches
					$(".stemps a[title=" + $(this).val() + "]").addClass("active");
					
				});
				// do the action
				$(".send").click(function (e) {
					e.preventDefault();
					// build the url
					if(frequency == 0){
						frequency = ""
					}
					url = "https://www.darujme.cz/dar/index.php?payment_data____frequency="+frequency+"&ammount="+amount+"&project="+project+"&client="+client+"&page=checkout&currency=CZK";
					// open the window
					window.open(url, "new_tab");
				});
			});
			</script>';

			// add default css style if desired
			if($disable_css == 0){
				wp_enqueue_style( 'widget_daruj_css');
			}
		
	}

} // END of class

// register frontpage css
add_action('wp_enqueue_scripts', 'widget_daruj_css_style');
function widget_daruj_css_style(){
	wp_register_style('widget_daruj_css', plugins_url( 'css/widget_daruj.css' , __FILE__ ),false,0.1,'all');
}


