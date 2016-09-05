<ul class="main-menu">
	<?php $numberMenuElements = count($content);
	for($i = 0; $i < $numberMenuElements; $i++): ?>
					<li class="element-main-menu">
	    					<a href="<?= $url[$i]; ?>" class="menu-text"><?= $content[$i]; ?></a>
						</li>
	<?php endfor; ?>
				</ul>
				<div class="main-menu-button not-display cursor-pointer">≡</div>
