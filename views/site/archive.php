<?php
use \lib\app\Maker;
?>

		<div class="main">
			<div class="row marg-center">
				<div class="cols-12 cols-6 inline-block">
					<h1 class="h1-title">Архив новостей</h1>
				</div>
			</div>
			<?php 
				$numberNews = count($newsList);
				foreach ($newsList as $oneNews):
			?>
			<div class="row marg-center bord-bottom" id="row-<?= $oneNews['news_archive_id'] ?>">
				<div class="cols-1 inline-block">
				    <img src="/images/bucket-not-hover.png" alt="Отправить в архив" class="bucket cursor-pointer" id="img-bucket-news-<?= $oneNews['news_archive_id'] ?>">
				</div>
				<div class="cols-9 cols-4 inline-block cursor-pointer" id="title-<?= $oneNews['news_archive_id'] ?>">
					<p class="news-title"><?= $oneNews['news_title'] ?></p>
				</div>
				<div class="cols-2 cols-1 inline-block cursor-pointer" id="date-<?= $oneNews['news_archive_id'] ?>">
					<p class="news-date"><?= date('d.m.Y H:i', $oneNews['publication_date']); ?></p>
				</div>
			</div>
			<div class="row marg-center bord-bottom not-display cursor-pointer" id="description-<?= $oneNews['news_archive_id'] ?>">
				<div class="cols-12 cols-6 inline-block">
					<?php 
						$paragraphSeparator = Maker::$app -> configData['separator'];
						$paragraphs = explode('|!|', $oneNews['news_description']); 
						foreach ($paragraphs as $oneParagraph):
					?>
					<p class="news-description"><?= htmlspecialchars_decode($oneParagraph) ?></p>
					<?php endforeach; ?>
					<a href="<?= $oneNews['news_link'] ?>" rel="nofollow" target="_blanck" class="all-news-text inline-block">Читать далее</a>
				</div>
			</div>
			<?php endforeach; ?>
		</div>