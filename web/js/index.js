$('.send-tape-url').click(sendFeedToDb);
$('[id ^= title]').click(showDescription);
$('[id ^= description]').click(showDescription);
$('[id ^= title]').click(setReadNews);
$('[id ^= description]').click(setReadNews);
$('[id ^= date]').click(setReadNews);
$('.folder').click(setReadNews);
$('.field-tape-url').click(setFocusOnInput);
$('.field-tape-url').blur(blurOutofInput);
$('.folder').click(sendNewsToArchive);
$('[id ^= img-bucket-news]').click(deleteNewsFromArchive);
$('[id ^= img-bucket-feed]').click(deleteFeed);
$('[class ^= main-menu-button]').click(showMainMenu);



function showDescription() {
	titleId = this.id;
	splitTitleId = titleId.split('-');
	descriptionBlock = $('#description-' + splitTitleId[1]);
	if(descriptionBlock.css('display') === 'none') {
		descriptionBlock.show();
		$('#row-' + splitTitleId[1]).css('border-bottom', '0px');
		$('#row-' + splitTitleId[1] + ' p').css('color', '#a5a5a5');
	} else {
		descriptionBlock.hide();
		$('#row-' + splitTitleId[1]).css('border-bottom', '1px solid #999999');
	}
}

function setFocusOnInput() {
	$inputField = $('.field-tape-url');
	if($inputField.val() === 'Ссылка на RSS ленту') {
	    $inputField.val('');
	    $inputField.css("color", '#000000')
	}
}

function blurOutofInput() {
	$inputField = $('.field-tape-url');
	if($inputField.val() === '') {
	    $inputField.val('Ссылка на RSS ленту');
	    $inputField.css("color", '#bcbcbc')
	}
}

function sendFeedToDb() {
	feedUrl = $('.field-tape-url').val();
	$.ajax({
    	type: "POST",
    	url: "save-feed",
    	data: {feedUrl:feedUrl},
    	dataType: "text",
    	success: function(data) {
          if(data.indexOf('error|') === 0) {
              dataSplit = data.split('|');
              alert(dataSplit[1]);
          } else {
             alert(data);
             url = "/";
             $(location).attr('href',url);
          }
    	}
  	});
}

function setReadNews() {
	titleId = this.id;
	splitTitleId = titleId.split('-');
	//Считаем части строки id, т.к. несколько элементов, использующие функцию, имеют разную длиину id
	numberidPart = splitTitleId.length;
	if(numberidPart == 2) {
		numberFromTitleId = splitTitleId[1];
	} else {
		numberFromTitleId = splitTitleId[2];
	}
	$.ajax({
    	type: "POST",
      	url: "set-read",
      	data: {newsId:numberFromTitleId},
      	dataType: "text",
      	success: function(data) {
        	  if(data.indexOf('error|') === 0) {
                dataSplit = data.split('|');
                alert(dataSplit[1]);
            }
      	}
    });
}

function sendNewsToArchive() {
	newsId = this.id;
 	splitNewsId = newsId.split('-');
  	newsNumber = splitNewsId[2];
  	$.ajax({
    	type: "POST",
    	url: "news-to-archive",
    	data: {newsId:newsNumber},
    	dataType: "text",
    	success: function(data) {
          if(data.indexOf('error|') === 0) {
              dataSplit = data.split('|');
              alert(dataSplit[1]);
          } else {
              $('#img-folder-' + newsNumber).attr("src","/images/folder-active.png");
          }
    	}
  	});
}

function deleteNewsFromArchive()
{
	confirmResult = confirm('Новость будет удалена. Продолжить?');
  	if(confirmResult === true) {
	  	newsId = this.id;
	  	splitNewsId = newsId.split('-');
	  	newsNumber = splitNewsId[3];
    	$.ajax({
     		type: "POST",
      		url: "delete-from-archive",
      		data: {newsId:newsNumber},
      		dataType: "text",
      		success: function(data) {
              if(data.indexOf('error|') === 0) {
                  dataSplit = data.split('|');
                  alert(dataSplit[1]);
              } else {
                  url = "archive";
                  $(location).attr('href',url);
              }
      		}
    	});
  	}
}

function deleteFeed() {
	confirmResult = confirm('RSS лента будет удалена. Продолжить?');
  	if(confirmResult === true) {
		feedId = this.id;
	 	splitFeedId = feedId.split('-');
	  	feedNumber = splitFeedId[3];
	  	$.ajax({
	    	type: "POST",
	    	url: "delete-feed",
	    	data: {feedId:feedNumber},
	    	dataType: "text",
	    	success: function(data) {
            if(data.indexOf('error|') === 0) {
                  dataSplit = data.split('|');
                  alert(dataSplit[1]);
              } else {
                  url = "feed-list";
                  $(location).attr('href',url);
              }
	    	  }
	  	});
  	}
}

function showMainMenu() {
    mainMenu = $('ul[class ^= main-menu]');
    if(mainMenu.css('display') === 'none') {
        mainMenu.show();
        $('.field-tape-url').show();
        $('.send-tape-url').show();
        $('.header-height').css('height', '160px');
    } else {
        mainMenu.hide();
        $('.field-tape-url').hide();
        $('.send-tape-url').hide();
        $('.header-height').css('height', '80px');
    }
}
