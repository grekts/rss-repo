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
			    	<?= $menu; ?>
			    </div>
			    <div class="cols-5 cols-6 pos-relative inline-block">
			    	<?=  $senderUrl; ?> 
			    </div>
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