<?php

namespace application\models;

class HtmlTags
{
  public $stringWithDeleteSeveralHtmlTags;
  
  function deleteSeveralHtmlTagsFromNewsCode($descriptions)
  {
    $numberDescriptions = count($descriptions);
    for($i = 0; $i<$numberDescriptions; $i++) {
      $this->stringWithDeleteSeveralHtmlTags[$i] = preg_replace('/&lt;img.*&gt;|()|&lt;iframe.*\/iframe&gt;|&lt;b.*&gt;|&lt;\/b&gt;|&lt;p.*&gt;|&lt;\/p&gt;|\/&gt;|&lt;i.*&gt;|&lt;\/i&gt;|\/&gt;/isU', '', $descriptions[$i]);
    }
    
    unset($descriptions, $numberDescriptions);
  }
  
  //method rewrite url tags fot forming it in right view
  function rewriteUrlTagsInCode()
  {
    $urlTags = '';
    $numberDescriptions = count($this->stringWithDeleteSeveralHtmlTags);
    for($i = 0; $i<$numberDescriptions; $i++) {
      $numberFindedUrlTags = preg_match_all("/&lt;a.*\/a&gt;/isU", $this->stringWithDeleteSeveralHtmlTags[$i], $urlTags);
      if(($numberFindedUrlTags !== 0) && ($numberFindedUrlTags !== false)) {
        for($j = 0; $j<$numberFindedUrlTags; $j++) {
          $codeWithUrl = '';
          $urlDescription = '';
          $findCodeUrlInUrlTags = preg_match("/href.*(&quot;|&#039;).*(&quot;|&#039;)/isU", $urlTags[0][$j], $codeWithUrl);
          $findDescriptionUrlInUrlTags = preg_match("/&gt;.*&lt;/isU", $urlTags[0][$j], $urlDescription);
          if(($findCodeUrlInUrlTags !== 0) 
              && ($findDescriptionUrlInUrlTags !== 0)
              && ($findCodeUrlInUrlTags !== false) 
              && ($findDescriptionUrlInUrlTags !== false)) {
            $clearUrl = preg_replace("/href|=|&quot;|&#039;/isU", '', $codeWithUrl[0]);
            $clearDescription = preg_replace("/\/|&lt;|&gt;/isU", '', $urlDescription[0]);
            if(strpos($clearDescription, 'Читать') === false) {
              $newUrl = '&lt;a href=&quot;'.$clearUrl.'&quot; class=&quot;aExternalUrl&quot; target=&quot;_blanck&quot; rel=&quot;nofollow&quot;&gt;'.$clearDescription.'&lt;/a&gt;';
              $quoteUrlCode = preg_quote($urlTags[0][$j], '/');
              $this->stringWithDeleteSeveralHtmlTags[$i] = preg_replace("/$quoteUrlCode/isU", $newUrl, $this->stringWithDeleteSeveralHtmlTags[$i]);
            } else {
              $quoteUrlCode = preg_quote($urlTags[0][$j], '/');
              $this->stringWithDeleteSeveralHtmlTags[$i] = preg_replace("/$quoteUrlCode/isU", '', $this->stringWithDeleteSeveralHtmlTags[$i]);
            }
          }
        }
      }
    }
    
    unset($urlTags, $numberDescriptions, $codeWithUrl, $urlDescription, $findCodeUrlInUrlTags, $findDescriptionUrlInUrlTags);
    unset($clearUrl, $clearDescription, $newUrl, $quoteUrlCode);
  }
}

