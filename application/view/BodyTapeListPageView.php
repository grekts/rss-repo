<?php

echo '    
        <div id="main">
            <div class="divBlockSendTape">
                <input type="text" class="inputSendNewTape" id="inputSendNewTape" value="Ссылка на RSS ленту">
                <button class="buttonSendNewTape">Добавить</bottun>
            </div>
            <div id="divBlockWithTapeList">';
                for($i = 0; $i < $tape->numberTape; $i++) {
                  echo '<div class="divOneTape">';
                  echo '<p class="pTapeUrl">'.$tape->tapeListArray[$i][0].'</p>';
                  echo '<p class="pDeleteTape" id="pDeleteTape-'.$tape->tapeListArray[$i][1].'">Удалить</p>';
                  echo '</div>'; 
                }
           echo '</div>
        </div>';

