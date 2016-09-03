<ul class="main-menu inline-block">
	<?php $numberMenuElements = count($content);
	for($i = 0; $i < $numberMenuElements; $i++): ?>
					<li class="element-main-menu">
	    					<a href="<?= $url[$i]; ?>" class="menu-text"><?= $content[$i]; ?></a>
						</li>
	<?php endfor; ?>
				</ul>
