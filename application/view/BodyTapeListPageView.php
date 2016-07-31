<?php

echo '    
        <div class="divMain">
            <h1 class="h1Element">'.$h1Title->h1TitleTextData.'</h1>
            <div id="divBlockWithTapeList">';
                for($i = 0; $i < $tape->numberTape; $i++) {
                  echo '<div class="divOneTape">';
                      echo '<div class="divBlockWithFlags">';
                          echo '<img class="imgDeleteTape" src="/public/img/bucket-not-hover.png" title="Удалить" alt="Удалить" id="imgDeleteTape-'.$tape->tapeListArray[$i][1].'" />';
                      echo '</div>';
                      echo '<div class="divTapeUrl">';
                          echo '<p class="pTapeUrl">'.$tape->tapeListArray[$i][0].'</p>';
                      echo '</div>';
                  echo '</div>'; 
                }
           echo '</div>
        </div>';

