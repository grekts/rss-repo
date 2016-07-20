<?php

echo '    
        <div id="main">
            <div class="divBlockSendTape">
                <input type="text" class="inputSendNewTape" id="inputSendNewTape" value="Ссылка на RSS ленту">
                <button class="buttonSendNewTape">Добавить</bottun>
            </div>
            <div id="divBlockWithNewsList">';
                
                    for($i = 0; $i<$newsList->numberNewsForShow; $i++)
                    {
                      echo '<div class="divHeadPartOneNews" id="divHeadPartOneNews-'.$newsList->newsIdArray[$i].'">';
                      echo '<p class="pTitleOneNews" id="pTitleOneNews-'.$newsList->newsIdArray[$i].'">'.$newsList->newsTitleArray[$i].'</p>';
                      $date = date('d.m.Y H:i', $newsList->newsPublicationDateArray[$i]);
                      echo '<p class="pTimeOneNews">'.$date.'</p>';
                      echo '</div>';
                      echo '<div class="divOneNews" id="divOneNew-'.$newsList->newsIdArray[$i].'">';
                      $explodeDescriptionString = explode('|!|', $newsList->newsDescriptionArray[$i]);
                      foreach ($explodeDescriptionString as $partDescriptionString) {
                        $partDescriptionString = trim($partDescriptionString);
                        if($partDescriptionString !== '') {
                          echo "<p class='pDescriptionOneNews'>".$partDescriptionString."</p>";
                        }
                      }
                      echo '<a href="'.$newsList->newsLinkArray[$i].'" class="aReadAllNewsText" target="_blanck">Читать полностью</a>';
                      echo '</div>';
                    }
                
           echo '</div>
        </div>';

