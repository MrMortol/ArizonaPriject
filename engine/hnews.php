<?
	// Подключение к базе
	require_once('core/consite.php');
	
	// Запрос количества новостей
	$mysqli->set_charset("utf8");
	
	$sql = "SELECT count(*) FROM `site_news` WHERE `status` = 1";
	$result = $mysqli->query($sql);
	$rows = $result->fetch_row();
	
	// Узнаем общее количество страниц
	$news_total = 4;
	$per_page = 10;
	$news_pages = ceil($news_total/$per_page);
	
	// Получаем номер страницы и значение для лимита
	$cur_page = 1;
	if (isset($_GET['page']) && $_GET['page'] > 0) 
	{
		$cur_page = $mysqli->real_escape_string(stripslashes(htmlspecialchars(trim($_GET['page']))));
	}
	$start = $news_total - ($cur_page * $per_page);
	if($start < 0)
	{
		$per_page = $per_page + $start;
		if($per_page < 0) $per_page = 0;
		$start = 0;
	}
	$amount_page = 0;
	
	// Запрос новостей
	$sql = "SELECT * FROM `site_news` WHERE `status` = 1 LIMIT {$start}, {$per_page}";
	$result = $mysqli->query($sql);
	$rows = $result->num_rows;
	if($rows != 0)
	{	
		for ($id = $rows; $id > 0; $id--)	
		{	
			$result->data_seek($id-1);
			$news = $result->fetch_assoc();
			$url_image = $news['image'];
			$content .= '
			                <div>
					<div class="n-preview">
						<a href="view?new='.$news['id'].'" class="more">Читать</a><img src="'.$url_image.'" alt=""></div>
					<div class="n-info">
						<p><strong>'.$news['title'].'</strong></p>
						<p class="date"><img src="public\images\calendar.svg" class="ico-date" alt=""> 
							'.$news['date'].'													</p>
					</div>
				</div>
			';
		};
		$result->close();
		
		$prev_page = $cur_page - 1;
		$next_page = $cur_page + 1;
		$next_page_2 = $cur_page + 2;
	}
	
	// Закрываем соединение с базой
	$mysqli->close();
?>	