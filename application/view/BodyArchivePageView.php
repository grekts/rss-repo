<?php

echo '    
        <div class="divMain">
            <h1 class="h1Element">'.$h1Title->h1TitleTextData.'</h1>
            <div id="divBlockWithNewsList">';
                
                    for($i = 0; $i<$newsList->numberNewsForShow; $i++)
                    {
                      echo '<div class="divHeadPartOneNews" id="divHeadPartOneNews-'.$newsList->newsIdArray[$i].'">';
                          echo '<div class="divBlockWithFlags">';
                              echo '<img class="imgDeleteFromArchive" src="/public/img/bucket-not-hover.png" title="Удалить" alt="Удалить" id="imgDeleteFromArchive-'.$newsList->newsIdArray[$i].'" />';
                          echo '</div>';
                          echo '<div class="divTitleAndDate" id="divTitleAndDate-'.$newsList->newsIdArray[$i].'">';
                              echo '<p class="pTitleOneNews" id="pTitleOneNews-'.$newsList->newsIdArray[$i].'">'.$newsList->newsTitleArray[$i].'</p>';
                              $date = date('d.m.Y H:i', $newsList->newsPublicationDateArray[$i]);
                              echo '<p class="pTimeOneNews" id="pTimeOneNews-'.$newsList->newsIdArray[$i].'">'.$date.'</p>';
                          echo '</div>';
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

