<?php 

if( ! defined('ABSPATH') ) exit;

add_action( 'widgets_init', array("Cinema_Catalog", "register_cinema_listing") );

class Cinema_Catalog extends WP_Widget {
	static $sql;
	
	function __construct() {
		global $wpdb;
		self::$sql = $wpdb;
		parent::__construct(
			'cinema_widget',
			'Видео-каталог',
			array( 'description' => 'Вывод списка имеющегося видео-контента.', )
		);  
	}

	static function register_cinema_listing() {
		register_widget( 'Cinema_Catalog' );
	}
	
	/** Вывод виджета онлайн кинотеатра
	 *
	 *  @param array $args     аргументы виджета.
	 *  @param array $instance сохраненные данные из настроек
	 */
	public function widget( $args, $instance ) {
		global $wpdb;

		// Получим опции виджета
		$title = apply_filters( 'widget_title', $instance['title'] ); // Узнаем заголовок виджета
		$sort = apply_filters( 'widget_sort', $instance['sort'] ); // Узнаем порядок сортировки
		
		if( $sort == "1") {
			$listing = $wpdb->get_results("SELECT CHAR_LENGTH(`name_ru`) as `length`, `ID`, `name_ru` FROM `". $wpdb->prefix ."cinema` 
											WHERE `publication` = '1' ORDER BY `length`;", ARRAY_A); 
		} else {
			$listing = $wpdb->get_results("SELECT `ID`, `name_ru` FROM `". $wpdb->prefix ."cinema` 
											WHERE `publication` = '1' ORDER BY `name_ru` DESC;", ARRAY_A);
		}
		
		print "<section id=\"categories-3\" class=\"widget widget_categories\"> 
					<h2 class=\"widget-title\"> ". $title . "</h2>
			<ul>";
		
		
		foreach($listing as $item) { 
			if( strlen($item['name_ru']) < 1) { continue; }
			print "<li class=\"cat-item\"><a href=\"?cinema=". $item['ID'] ."\">". $item['name_ru'] ."</a></li>";
		}
		
		print "</ul> </section>";
	}

	/**
	 * Админ-часть виджета
	 *
	 * @param array $instance сохраненные данные из настроек
	 */
	public function form( $instance ) {
		$title = @ $instance['title'] ?: 'Сериалы';
		$sort_by_len = @ $instance['sort'] ?: '0';
		$api_key = @ $instance['api'] ?: 'xTpDqDkkuzR7RlzRj7pjs+gHtUPdwxwQT60EsH5gZlo=';
	?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Заголовок:' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html( $title ); ?>"> </p>
	
	<?php if($sort_by_len == 1) { 
		print "<p> <input class=\"widefat\" id=\"". $this->get_field_id( 'sort' ) ."\" name=\"". $this->get_field_name( 'sort' ) ."\" type=\"checkbox\" value=\"1\" checked=\"checked\"></label>"; } else { 
		print "<p> <input class=\"widefat\" id=\"". $this->get_field_id( 'sort' ) ."\" name=\"". $this->get_field_name( 'sort' ) ."\" type=\"checkbox\" value=\"1\"></label>"; } 
	?>
	<label for="<?php echo $this->get_field_id( 'sort' ); ?>"><?php _e( 'Сортировать по длине строки' ); ?></label> </p>
	
	<p><label for="<?php echo $this->get_field_id( 'api' ); ?>"><?php _e( 'API kinopoiskapiunofficial.tech:' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'api' ); ?>" name="<?php echo $this->get_field_name( 'api' ); ?>" type="text" value="<?php echo esc_html( $api_key ); ?>"> </p>
	<?php }

	/**
	 * Сохранение настроек виджета. Здесь данные должны быть очищены и возвращены для сохранения их в базу данных.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance новые настройки
	 * @param array $old_instance предыдущие настройки
	 *
	 * @return array данные которые будут сохранены
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? ( $new_instance['title'] ) : '';
		$instance['sort'] = ( ! empty( $new_instance['sort'] ) ) ? ( $new_instance['sort'] ) : '';
		$instance['api'] = ( ! empty( $new_instance['api'] ) ) ? ( $new_instance['api'] ) : '';
		
		update_option("api_key", $instance['api']);

		return $instance;
	}
	
	static function single_cinema_watching() {
		
		global $wpdb;
		$query = $wpdb->prepare("SELECT * FROM `". $wpdb->prefix ."cinema` WHERE `ID` = %d", $_GET['cinema']);
		$cinema = $wpdb->get_results($query, ARRAY_A);
		
		wp_register_script("yohoho", "//yohoho.cc/yo.js", false, false, true);
		wp_enqueue_script('yohoho');
		
		if( !is_page() and count($cinema) >= 1 ) {
			get_header();
			@print "<div class='wrap'>
			<div id='primary' class='content-area'>
					<main id='main' class='site-main' role='main'>

						
			<article id='post-1' class='post-1 post type-post status-publish format-standard hentry category-1'>
				<header class='entry-header'> <h3 class='entry-title'>
					". $cinema[0]['name_ru'] ." / ". $cinema[0]['year'] ." / ". $cinema[0]['country'] ."</h3> 
					<small>". $cinema[0]['name_en'] ." </small> </header><!-- .entry-header -->

				<div class='entry-content'><p>". $cinema[0]['description'] ."</p> 
				<div id='yohoho' data-kinopoisk='". $cinema[0]['kinopoisk'] ."'></div>
				
				</div><!-- .entry-content -->

				
			</article></main><!-- #main -->
				</div>";
			
			get_sidebar();  
			get_footer();
			
		} elseif( !is_page() and count($cinema) < 1 ) {
			print "Нету информации по Вашему запросу";
		}
	}
}

?>
