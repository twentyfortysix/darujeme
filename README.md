# darujeme

Wordpess widgeta formulare https://www.darujme.cz/

Instalace:
Stejne jako jakykoliv WP plugin. Hodit adresar do slozky plugins a aktivovat.

Po aktivaci najdete novou widgetu kterou muzete vlozit do sidebaru a nastavit si u toho svoje ID, ID projektu a dalsi veci, jako castky apod.

Pokud nevidite moznost pridavat widgetym nemate asi definovany zadny sidebar.
pridejte si do functions.php v aktualnim templejtu/sablone incializcai sidebaru:

```
// register sidebar
add_action( 'widgets_init', 'register_my_sidebars' );
function register_my_sidebars() {
	$sidebar_args = array(
		'name'          => __( 'Sidebar', 'naovoce' ),
		'id'            => 's1',
		'description'   => '',
	        'class'         => '',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>' 
	);
	register_sidebar( $sidebar_args );
}
```


