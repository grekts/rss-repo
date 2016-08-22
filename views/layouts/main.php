<?php
use \lib\app\Maker;
?>

<!DOCTYPE html>
<?= $htmlTag ?>
<head>
<?= $headTags ?>
</head>
<body>
	<div class="container">
		<header class="header">
		    <div class="row marg-center header-height">
			    <div class="cols-2 cols-6 pos-relative inline-block">
			    	<a href="/" class="logo">RSS</a>
			    </div>
			    <div class="cols-5 cols-6 inline-block">
			    	<ul class="main-menu inline-block">
			    		<li class="element-main-menu">
			    		    <a href="feed-list" class="menu-text">Список лент</a>
			    		</li>
			    		<li class="element-main-menu">
			    		    <a href="archive" class="menu-text">Архив</a>
			    		</li>
			    	</ul>
			    </div>
			    <div class="cols-5 cols-6 pos-relative inline-block">
			    	<input type="text" class="field-tape-url" value="Ссылка на RSS ленту">
			    	<button class="send-tape-url cursor-pointer">Добавить</button>
			    </div>
			    <div class="main-menu-button not-display cursor-pointer">≡</div>
			</div>
		</header>
		<?= $content ?>
		<footer class="footer">
			<div class="row marg-center">
				<div class="cols-12 cols-6 inline-block">
					<p class="copyright">&#169; <?= date('Y'); ?></p>
				</div>
			</div>
		</footer>
	</div>
</body>
<?= $scriptTags ?>
</html>