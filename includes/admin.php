<?php
class CinemaMaster {
	static $sql;
	
	public function __construct() {
		global $wpdb;
		self::$sql = $wpdb;
	}
	
	static function admin_actions() { 
		if( count($_GET) > 1 AND isset($_GET['edit']) == TRUE ) {
			
			switch( $_GET['edit'] ) {
				case "load": 
					$page = "<div class='wrap'><h1>Автоматическое заполнение</h1><p>Для корректной работы автоматической загрузки неоходимо получить новый токен от <b>kinopoiskapiunofficial.tech</b> и указать его в настройках виджета. По-умолчанию загрузка происходит в автоматическом режиме. Что бы добавить информацию о видео-контенте, Вам необходимо в первом поле указать ID (по версии <b>kinopoisk.ru</b>) фильма или сериала. </p>
					<form id='your-profile' action='?page=cinema&edit=load' method='POST'><table class='form-table' role='presentation'>
						
						<tr class='user-user-login-wrap'>
							<th><label for='user_login'>Загрузить:</label></th>
							<td><fieldset> <p>
							<textarea name='_kinopoisk' rows='10' cols='50' id='_by_kinopoisk' class='large-text code'>685246,306084,571335,508161,719481</textarea>
							</p><p class='description'>Через запятую. Можете оставить данное поле пустым и продолжить заполнение в ручном режиме.</p>
							</fieldset> </p> </td> 
						</table>
						<p class='submit'><input type='submit' name='submit' id='submit' class='button button-primary' value='Загрузить по списку'  /></p>
						
						<h1>Ручное заполнение</h1><p>Для корректкного отображения видео-плеера на сайте, необходимо указать ID фильма в базе kinopoisk.</p>
						<table class='form-table' role='presentation'>
						
						<tr><th><label for='user_login'>Опубликовать:</label></th>
							<td><fieldset>
							<label><input type='radio' name='_public' value='1' /> <span class='date-time-text format-i18n'>Да</span></label>
							<label><input type='radio' name='_public' value='0' checked='checked' /> <span class='date-time-text format-i18n'>Нет</span></label>
							</fieldset> </p> </td>
						</tr>
							
						<tr>
							<th scope='row'><label for='_name_ru'>Название по-русски:</label></th>
							<td><input name='_name_ru' type='text' id='_name_ru' value='' class='regular-text' /></td>	</tr>
						
						<tr>
							<th scope='row'><label for='_name_en'>Название по-анлгийски:</label></th>
							<td><input name='_name_en' type='text' id='_name_en' value='' class='regular-text' /></td>	</tr>
							
						<tr>
							<th scope='row'><label for='_description'>Опсиание:</label></th>
							<td><textarea name='_description' rows='10' cols='50' id='_description' class='large-text code'></textarea></td>	</tr>
							
						<tr>
							<th scope='row'><label for='_poster_url'>Ссылка на файл обложки:</label></th>
							<td><input name='_poster_url' type='text' id='_poster_url' value='' class='regular-text' /></td>	</tr>
							
						<tr>
							<th scope='row'><label for='_country'>Страна происхождения:</label></th>
							<td><input name='_country' type='text' id='_country' value='' class='regular-text' /></td>	</tr>
						
						<tr>
							<th scope='row'><label for='_year'>Дата релиза:</label></th>
							<td><input name='_year' type='text' id='_year' value='' class='regular-text' /></td>	</tr>
						
						<tr>
							<th scope='row'><label for='_genres'>Жанр:</label></th>
							<td><textarea name='_genres' rows='10' cols='50' id='_description' class='large-text code'></textarea>
							<p class='description'>Через запятую.</p></td></tr>
							
						<tr>
							<th scope='row'><label for='_kinopoisk_id'>Kinopoisk ID:</label></th>
							<td><input name='_kinopoisk_id' type='text' id='_kinopoisk_id' value='' class='regular-text' /></td>	</tr>
						
						
						</table>
					<p class='submit'><input type='submit' name='submit' id='submit' class='button button-primary' value='Сохранить изменения'  /></p></form>";
					break;
				default: 
					$row = self::$sql->get_results(
							self::$sql->prepare("SELECT * FROM `". self::$sql->prefix. "cinema` WHERE `id` = '%s';", $_GET['edit']),
							ARRAY_A
					);
					$page = "<div class='wrap'><h1>Редактор: ". $row[0]['name_ru'] ." (#". $row[0]['ID'] .")</h1><p></p>
					<form method='post' action='admin.php?page=cinema&edit=". $row[0]['ID'] ."' novalidate='novalidate'>
						<table class='form-table' role='presentation'>
						<input type='hidden' name='_id' value='". $row[0]['ID'] ."'><input type='hidden' name='_action' value='update'>
						<tr>
							<th scope='row'><label for='_name_ru'>Опубликовано:</label></th>
							<td>";
							
					if( $row[0]['publication'] == "1") { 
						$page .= "<label><input type='radio' name='_public' value='1' checked='checked' /> <span class='date-time-text format-i18n'>Да</span></label>
							<label><input type='radio' name='_public' value='0' /> <span class='date-time-text format-i18n'>Нет</span></label>";
					} else { 
						$page .= "<label><input type='radio' name='_public' value='1'  /> <span class='date-time-text format-i18n'>Да</span></label>
							<label><input type='radio' name='_public' value='0' checked='checked' /> <span class='date-time-text format-i18n'>Нет</span></label>";
					}
					
					$page .= "</td></tr>
					
						
						<tr>
							<th scope='row'><label for='_name_ru'>Название по-русски:</label></th>
							<td><input name='_name_ru' type='text' id='_name_ru' value='". $row[0]['name_ru'] ."' class='regular-text' /></td>	</tr>
						
						<tr>
							<th scope='row'><label for='_name_en'>Название по-анлгийски:</label></th>
							<td><input name='_name_en' type='text' id='_name_en' value='". $row[0]['name_en'] ."' class='regular-text' /></td>	</tr>
							
						<tr>
							<th scope='row'><label for='_description'>Опсиание:</label></th>
							<td><textarea name='_description' rows='10' cols='50' id='_description' class='large-text code'>". $row[0]['description'] ."</textarea></td>	</tr>
							
						<tr>
							<th scope='row'><label for='_poster_url'>Ссылка на файл обложки:</label></th>
							<td><input name='_poster_url' type='text' id='_poster_url' value='". $row[0]['poster_url'] ."' class='regular-text' /></td>	</tr>
							
						<tr>
							<th scope='row'><label for='_country'>Страна происхождения:</label></th>
							<td><input name='_country' type='text' id='_country' value='". $row[0]['country'] ."' class='regular-text' /></td>	</tr>
						
						<tr>
							<th scope='row'><label for='_year'>Дата релиза:</label></th>
							<td><input name='_year' type='text' id='_year' value='". $row[0]['year'] ."' class='regular-text' /></td>	</tr>
						
						<tr>
							<th scope='row'><label for='_genres'>Жанр:</label></th>
							<td><input name='_genres' type='text' id='_genres' value='". $row[0]['genres'] ."' class='regular-text' /></td>	</tr>
							
						<tr>
							<th scope='row'><label for='_kinopoisk'>Kinopoisk ID:</label></th>
							<td><input name='_kinopoisk' type='text' id='_kinopoisk' value='". $row[0]['kinopoisk'] ."' class='regular-text' /></td>	</tr>
						
						
						</table>
					<p class='submit'><input type='submit' name='submit' id='submit' class='button button-primary' value='Сохранить изменения'  /></p></form>"; 
					break;
			}
			
			@print $page;
				
		}
		
		if( count($_GET) <= 2 AND $_GET['page'] == "cinema" AND isset($_GET['edit']) == FALSE) {
			$r = self::$sql->get_results("SELECT COUNT(*) as `count` FROM `". self::$sql->prefix ."cinema`;", ARRAY_A);
			$e = self::$sql->get_results("SELECT COUNT(*) as `count` FROM `". self::$sql->prefix ."cinema` WHERE `publication` = 0;", ARRAY_A);
			$s = self::$sql->get_results("SELECT COUNT(*) as `count` FROM `". self::$sql->prefix ."cinema` WHERE `publication` = 1;", ARRAY_A);
			
			print "<div class='wrap'><h1 class='wp-heading-inline'>Все сериалы</h1><a class='page-title-action' href='?page=cinema&edit=load'>Обновить каталог</a><p>Информация о загруженных фильмах и сериалах. Данная информация доступна для редактирования. Именить правила для отображения видеоконтента можно в настройках шаблона.</p> 
			<ul class='subsubsub'>
				<li class='all'><a href='?page=cinema&show=all'><strong>Все</strong> </a><span class=\"count\">(". $r[0]['count'] .")</span> |</li>
				<li class='publish'><a href=\"?page=cinema&show=draft\">Черновики <span class=\"count\">(". $e[0]['count'] .")</span></a> |</li>
				<li class='publish'><a href=\"?page=cinema&show=public\">Опубликованные <span class=\"count\">(". $s[0]['count'] .")</span></a></li>
			</ul>
			
			<div class='tablenav top'> <div class='alignleft actions bulkactions'><label class='screen-reader-text'>Действие</label>
			<form action='admin.php?page=cinema' method='POST'> <input type='hidden' name='_action' value='cheked'> 
			<input type='submit' id='doaction' name='_delete' class='button action' value='Удалить'> <input type='submit' id='doaction' class='button action' name='_publication' value='Опубликовать'></div>
				</div>
					<table class='wp-list-table widefat'>
						<thead>
							<tr><td id='cb' class='manage-column column-cb check-column'><label class='screen-reader-text' for='cb-select-all-1'>Выделить все</label><input id='cb-select-all-1' type='checkbox'></td>
								<th scope='col' id='' class='manage-column column-primary'></th>
								<th scope='col' id='' class='manage-column column-primary'>Обложка</th>
								<th scope='col' id='' class='manage-column column-primary'>Название</th>
								<th scope='col' id='' class='manage-column column-primary'>En</th>
								<th scope='col' id='' class='manage-column column-primary'>Описание</th>
								<th scope='col' id='' class='manage-column column-primary'>Страна</th>
								<th scope='col' id='' class='manage-column column-primary'>Год</th>
								<th scope='col' id='' class='manage-column column-primary'>Жанры</th>
								<th scope='col' id='' class='manage-column column-primary'>Kinopoisk</th>
								<th scope='col' id='' class='manage-column column-primary'>Опубликовано</th>
							</tr>
						</thead> <tbody id='the-lsit'>";
						
			if( isset($_GET['show']) == TRUE AND $_GET['show'] == "draft" ) { 
				$listing = self::$sql->get_results("SELECT * FROM `". self::$sql->prefix."cinema` WHERE `publication` = 0 ORDER BY `ID` DESC;", ARRAY_A); } 
			if( isset($_GET['show']) == FALSE OR $_GET['show'] == "public" ) {
				$listing = self::$sql->get_results("SELECT * FROM `". self::$sql->prefix."cinema` WHERE `publication` = 1 ORDER BY `ID` DESC;", ARRAY_A);
			}
			if( isset($_GET['show']) == FALSE OR $_GET['show'] == "all" ) {
				$listing = self::$sql->get_results("SELECT * FROM `". self::$sql->prefix."cinema` ORDER BY `ID` DESC;", ARRAY_A);
			}
			
			
			foreach( $listing as $item) {
				
				@print "<tr class='inactive'>
							
							<th scope='row' class='check-column'><label class='screen-reader-text'>Выбрать ...</label><input type='checkbox' name='_cheked[]' value='". $item['ID'] ."'></th>
							<td class='column-description desc'>#". $item['ID'] ."</td>
							<td class='plugin-title column-primary'><a href='?page=cinema&edit=". $item["ID"] ."'><img alt src='".$item['poster_url']."' width='100' class='avatar'></a></td>
							<td class='column-description desc'><a href='?page=cinema&edit=". $item["ID"] ."'>". $item['name_ru'] ."</a></td>
							<td class='column-description desc'><a href='?page=cinema&edit=". $item["ID"] ."'>". $item['name_en'] ."</a></td>";
				
				if( strlen($item['description']) < 1) { print "<td class='column-description desc'><пусто></td>"; } else {
					print "<td class='column-description desc'>". substr($item['description'], 0, 111) ."...</td>"; 
				}
				
				print "		<td class='column-description desc'>". $item['country'] ."</td>
							<td class='column-description desc'>". $item['year'] ."</td>
							<td class='column-description desc'>". $item['genres'] ."</td>
							<td class='column-description desc'>". $item['kinopoisk'] ."</td>";
				
				switch($item['publication']) { 
					case "0": print "<td class='column-description desc'>Нет</td>"; break; 
					case "1": print "<td class='column-description desc'>Да</td>"; break; 
				}
				
				print "		</tr>";
			}
			print "</tbody>
					</table>
					<div class='tablenav top'>
			<div class='alignleft actions bulkactions'><label class='screen-reader-text'>Действие</label>
			<input type='submit' id='doaction' class='button action' value='Удалить'> <input type='submit' id='doaction' class='button action' value='Опубликовать'></div>
			</div></form>";
		}
		
		
	}

	static function new_catalog( $array ) {
		$state = 0;
		
		foreach( $array as $key=>$value) { 
			if( strlen($value) > 0 AND $key == "_kinopoisk" ) { $state = 1; break; } 
			elseif( strlen($value) <= 0 AND $key == "_kinopoisk" ) { $state = 2; continue; }
			elseif( strlen($value) <= 0 AND $key != "_kinopoisk" ) { self::key_error(); break; }
		}
		
		switch($state) {
			case 0: $result = False; break;
			case 1: 
				foreach ( explode(",", $_POST['_kinopoisk']) as $id) {
					settype($id, "int"); $genres = "";
					$response = wp_remote_get("http://kinopoiskapiunofficial.tech/api/v2/films/". $id, 
									array("headers" => array(	"Accept: */*\r\n". 
																"Authorization: Token ". get_option("api_key") ."\r\n")
									) 
					);
					if( strstr($response['response']['code'], "404") == TRUE) { continue; }
					if( strstr($response['response']['code'], "401") == TRUE) { self::download_error(); break; }
					$item = json_decode($response['body'], 1);
					$query = self::$sql->prepare("INSERT INTO `". self::$sql->prefix ."cinema` SET 
										`publication`		 = '%d',
										`poster_url`		 = '". $item['posterUrl'] ."',
										`name_ru`			 = '". $item['nameRu'] ."',
										`name_en`			 = '". $item['nameEn'] ."',
										`year`				 = '". $item['year'] ."',
										`description`		 = '". $item['description'] ."',
										`country`			 = '". $item['premiereWorldCountry'] ."',
										`genres`			 = '". $item['genres'][0]['genre'] ."',
										`kinopoisk`			 = '". $item['filmId'] ."';",
										sanitize_text_field( $_POST['_public'] )
					);
										
					$result = self::$sql->query($query);
				}
				break;
			case 2:
				$query = self::$sql->prepare("INSERT INTO `". self::$sql->prefix ."cinema` SET 
										`publication`		 = '%s',
										`poster_url`		 = '%s',
										`name_ru`			 = '%s',
										`name_en`			 = '%s',
										`year`				 = '%s',
										`description`		 = '%s',
										`country`			 = '%s',
										`genres`			 = '%s',
										`kinopoisk`			 = '%s';",
										sanitize_text_field( $_POST['_public'] ), 
										sanitize_text_field( $_POST['_poster_url'] ), 
										sanitize_text_field( $_POST['_name_ru'] ), 
										sanitize_text_field( $_POST['_name_en'] ),
										sanitize_text_field( $_POST['_year'] ),  
										sanitize_text_field( $_POST['_description'] ), 
										sanitize_text_field( $_POST['_country'] ), 
										sanitize_text_field( $_POST['_genres'] ),
										sanitize_text_field( $_POST['_kinopoisk_id'] )
				);
				
				$result = self::$sql->query($query);
				break;
			case 3:
				$result = False;
				break;
		}
		return $result;
	}
	
	static function update_record( $array ) {
		$query = self::$sql->prepare("UPDATE `". self::$sql->prefix ."cinema` SET 
										`publication`		 = '%s',
										`poster_url`		 = '%s',
										`name_ru`			 = '%s',
										`name_en`			 = '%s',
										`year`				 = '%s',
										`description`		 = '%s',
										`country`			 = '%s',
										`genres`			 = '%s',
										`kinopoisk`			 = '%s'
									WHERE `ID` = '%d';",
									sanitize_text_field( $_POST['_public'] ),
									sanitize_text_field( $_POST['_poster_url'] ),
									sanitize_text_field( $_POST['_name_ru'] ),
									sanitize_text_field( $_POST['_name_en'] ),
									sanitize_text_field( $_POST['_year'] ),
									sanitize_text_field( $_POST['_description'] ),
									sanitize_text_field( $_POST['_country'] ),
									sanitize_text_field( $_POST['_genres'] ),
									sanitize_text_field( $_POST['_kinopoisk'] ),
									sanitize_text_field( $_POST['_id'] )
		);
		$result = self::$sql->query($query);
		return $result;
	}
	
	static function cheked_records( $array ) { 
		if( isset($array['_delete']) AND count($array['_cheked']) > 0 ) { 
			foreach($array['_cheked'] as $id) { 
				$result = self::$sql->query("DELETE FROM `". self::$sql->prefix ."cinema` WHERE `ID` = '". $id ."';");
			}
		} elseif( isset($array['_publication'])  AND count($array['_cheked']) > 0 ) { 
			foreach($array['_cheked'] as $id) { 
				$result = self::$sql->query("UPDATE `". self::$sql->prefix ."cinema` SET `publication` = 1 WHERE `ID` = '". $id ."';"); 
			}
		} else { $result = False; }
		
		return $result;
	}
	
	static function first_run() {
		print "<div id='message' class='notice notice-success is-dismissible'>
			<p>Добро пожаловать в видео-каталог. База данных проиницированна.</p>
		</div>";
	}
	
	static function load_notice() {
		print "<div id='message' class='notice notice-success is-dismissible'>
			<p>Данные загружены. <a href='admin.php?page=cinema&show=all'>Посмотреть</a></p>
		</div>";
	}
	
	static function download_error() {
		print "<div id='message' class='notice notice-error is-dismissible'>
				<p>Необходимо обновить API-token в настройках виджета. Получить новый token можно <a href='http://kinopoiskapiunofficial.tech/'>здесь</a>.</p>
			</div>";
	}
	
	static function key_error() {
		print "<div id='message' class='notice notice-error is-dismissible'>
					<p>Необходимо ввести хоть какие-то данные.</p>
				</div>";
	}
	
	static function update_error() {
		print "<div id='message' class='notice notice-error is-dismissible'>
				<p>Произошла ошибка! Либо некоторые галочки не проставлены, либо данные не были отредактированы.</p>
			</div>";
	}
	
	static function manage_catalog() { 
		add_menu_page( "Фильмы и сериалы", "Кинотеатр онлайн", "manage_options", 
			"cinema", array( __CLASS__, "admin_actions" ), "dashicons-playlist-video", 2 );
	}
	
	static function plugin_footer() {
		return "Работает \"Сinema catalog\" &copy; написано креведкой в 2020 году."; 
	}
	
}

new CinemaMaster;
?>
