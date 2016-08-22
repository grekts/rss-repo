

		<div class="main">
			<div class="row marg-center">
				<div class="cols-12 cols-6 inline-block">
					<h1 class="h1-title">Список лент</h1>
				</div>
			</div>
			<?php 
				$numberNews = count($feedsList);
				foreach ($feedsList as $oneFeed):
			?>
			<div class="row marg-center bord-bottom">
				<div class="cols-1 inline-block">
				    <img src="/images/bucket-not-hover.png" alt="Отправить в архив" class="bucket cursor-pointer" id="img-bucket-feed-<?= $oneFeed['rss_url_list_id'] ?>">
				</div>
				<div class="cols-9 cols-4 inline-block">
					<p class="news-title"><?= $oneFeed['rss_url'] ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>