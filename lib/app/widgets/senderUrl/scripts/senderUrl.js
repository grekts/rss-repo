
$('.button-send-data').click(sendUrlToDb);
$('.field-write-text').click(setFocusOnInput);
$('.field-write-text').blur(blurOutofInput);

function setFocusOnInput() {
	$inputField = $('.field-write-text');
	if($inputField.val() === 'Ссылка') {
	    $inputField.val('');
	    $inputField.css("color", '#000000')
	}
}

function blurOutofInput() {
	$inputField = $('.field-write-text');
	if($inputField.val() === '') {
	    $inputField.val('Ссылка');
	    $inputField.css("color", '#bcbcbc')
	}
}

function sendUrlToDb() {
	data = $('.field-write-text').val();
  console.log(data );
	$.ajax({
    	type: "POST",
    	url: "sender-url/save-url",
    	data: {data:data},
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