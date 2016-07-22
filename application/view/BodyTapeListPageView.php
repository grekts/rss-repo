<?php

echo '    
        <div id="main">
            <h1>'.$h1Title->h1TitleTextData.'</h1>
            <div id="divBlockWithTapeList">';
                for($i = 0; $i < $tape->numberTape; $i++) {
                  echo '<div class="divOneTape">';
                  echo '<p class="pTapeUrl">'.$tape->tapeListArray[$i][0].'</p>';
                  echo '<p class="pDeleteTape" id="pDeleteTape-'.$tape->tapeListArray[$i][1].'">Удалить</p>';
                  echo '</div>'; 
                }
           echo '</div>
        </div>';

