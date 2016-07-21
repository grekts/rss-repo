$('.buttonSendNewTape').click(sendNewRssTapeToDb);
$('.inputSendNewTape').focus(setFocusOnInputWithNameNewRss);
$('.inputSendNewTape').blur(blurOutofInputWithNameNewRss);
$('.divHeadPartOneNews').click(openNewsDescriptionOrNewsText);
$('.divOneNews').click(openNewsDescriptionOrNewsText);
$('.pDeleteTape').click(sendTapeNumberForDelete);

function sendNewRssTapeToDb()
{
  nameNewTape = $('#inputSendNewTape').val();
  
  $.ajax({
    type: "POST",
    url: "new-tape",
    data: {nameNewTape:nameNewTape},
    dataType: "text",
    success: function(data) {
      dataSplit = data.split('|');
      if(dataSplit[0] === 'error') {
        alert(dataSplit[1]);
      } else {
        alert(dataSplit[1]);
        url = "/";
        $(location).attr('href',url);
      }
    }
  });
}

function setFocusOnInputWithNameNewRss()
{
  if($('.inputSendNewTape').val() === 'Ссылка на RSS ленту') {
    $('.inputSendNewTape').val('');
    $('.inputSendNewTape').css("color", '#000000')
  }
}

function blurOutofInputWithNameNewRss()
{
  if($('.inputSendNewTape').val() === '') {
    $('.inputSendNewTape').val('Ссылка на RSS ленту');
    $('.inputSendNewTape').css("color", '#D8D8D8')
  }
}

function openNewsDescriptionOrNewsText(){
  newsId = this.id;
  splitNewsId = newsId.split('-');
  newsNumber = splitNewsId[1];
  newsForShowOrClose = $('#divOneNew-' + newsNumber);
  if(newsForShowOrClose.css('display') === 'none') {
    newsForShowOrClose.show();
    $('.pTitleOneNews').css('color', '#eaeaea');
    $('.pTimeOneNews').css('color', '#eaeaea');
    $('.divHeadPartOneNews').css('border-bottom', '0px');
    $('#pTitleOneNews-' + newsNumber).css('color', '#000000');
    $('#pTitleOneNews-' + newsNumber).css('font-weight', 'bold');
    
    $.ajax({
      type: "POST",
      url: "read",
      data: {idReadNews:newsNumber},
      dataType: "text",
      success: function(data) {
        dataSplit = data.split('|');
        if(dataSplit[0] === 'error') {
          alert(dataSplit[1]);
        }
      }
    });
  } else {
    newsForShowOrClose.hide();
    $('.divHeadPartOneNews').css('border-bottom', '1px solid #D8D8D8');
    $('.pTitleOneNews').css('color', '#000000');
    $('.pTimeOneNews').css('color', '#999');
    $('#pTitleOneNews-' + newsNumber).css('font-weight', 'normal');
    $('#divHeadPartOneNews-' + newsNumber).hide();
  }
}

function sendTapeNumberForDelete()
{
  tapeId = this.id;
  splitTapeId = tapeId.split('-');
  tapeNumber = splitTapeId[1];
  confirmResult = confirm('RSS лента будет удалена. Продолжить?');
  if(confirmResult === true) {
    $.ajax({
      type: "POST",
      url: "delete-tape",
      data: {idTapeForDelete:tapeNumber},
      dataType: "text",
      success: function(data) {
        dataSplit = data.split('|');
        if(dataSplit[0] === 'error') {
          alert(dataSplit[1]);
        } else {
          url = "tape-list";
          $(location).attr('href',url);
        }
      }
    });
  }
}
