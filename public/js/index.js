$('.buttonSendNewTape').click(sendNewRssTapeToDb);
$('.inputSendNewTape').focus(setFocusOnInputWithNameNewRss);
$('.inputSendNewTape').blur(blurOutofInputWithNameNewRss);
$('.divTitleAndDate').click(openNewsDescriptionOrNewsText);
$('.divOneNews').click(openNewsDescriptionOrNewsText);
$('.imgDeleteTape').click(sendTapeNumberForDelete);
$('.imgDeleteFromArchive').click(deleteNewsFromArchive);
$('.imgSendToArchive').click(sendNewsToArchive);

function sendNewRssTapeToDb()
{
  nameNewTape = $('#inputSendNewTape').val();
  
  $.ajax({
    type: "POST",
    url: "new-tape",
    data: {nameNewTape:nameNewTape, getNews:1},
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
    $('.divOneNews').hide();
    newsForShowOrClose.show();
    $('.divHeadPartOneNews').css('border-bottom', '1px solid #adadad');
    $('#divHeadPartOneNews-' + newsNumber).css('border-bottom', '0px');
    $('#pTitleOneNews-' + newsNumber).css('color', '#a5a5a5');
    $('#pTimeOneNews-' + newsNumber).css('color', '#a5a5a5');
    
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
    $('.divHeadPartOneNews').css('border-bottom', '1px solid #adadad');
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

function sendNewsToArchive()
{
  newsId = this.id;
  splitNewsId = newsId.split('-');
  newsNumber = splitNewsId[1];
  $.ajax({
    type: "POST",
    url: "send-news-to-archive",
    data: {idReadNews:newsNumber, sendToArchive:1},
    dataType: "text",
    success: function(data) {
      dataSplit = data.split('|');
      if(dataSplit[0] === 'error') {
        alert(dataSplit[1]);
      } else {
        $('#imgSendToArchive-' + newsNumber).attr("src","/public/img/folder-hover-or-click.png");
      }
    }
  });
}

function deleteNewsFromArchive()
{
  newsId = this.id;
  splitNewsId = newsId.split('-');
  newsNumber = splitNewsId[1];
  confirmResult = confirm('Новость будет удалена. Продолжить?');
  if(confirmResult === true) {
    $.ajax({
      type: "POST",
      url: "delete-news-from-archive",
      data: {idReadNews:newsNumber, deleteFromArchive:1},
      dataType: "text",
      success: function(data) {
        dataSplit = data.split('|');
        if(dataSplit[0] === 'error') {
          alert(dataSplit[1]);
        } else {
          url = "archive";
          $(location).attr('href',url);
        }
      }
    });
  }
}
