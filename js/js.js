$(document).ready(function() {
    		$(".modalbox").fancybox({
            
             'afterClose': function() {
             		$("#contact_params").css("display","");
					$('#confirm_contact').css("display", "none");
					$("#name_rider").val('');
					$("#phone").val('');
					$("#code_confirm").val('');
					$('.ok_text').remove();
					$('.error_text').remove();
					$("#name_rider").removeClass("error");
					$("#phone").removeClass("error");
					$("#code_confirm").removeClass("error");
             
             },
                     
             'afterShow': function() {
             	 $("#name_rider").focus();
             },        
                       
             'afterLoad': function() {
              var id = $(this.element).attr('rel');
              $("#id_set").val(id);
              var zag_text = 'Запись на лебедку на ';
              zag_text += $(this.element).parent().parent().attr('day_str') + ' в ' + $(this.element).parent().parent().attr('rel');             
              $("#title_zap").text(zag_text);
              $("#phone").mask("+7 (999) 999-9999");
              $("#code_confirm").mask("99999");
              
              $('#code_confirm').keypress(function(event)
              {
        	  var keyCode = event.keyCode ? event.keyCode :
        	  event.charCode ? event.charCode :
        	  event.which ? event.which : void 0;
        	  if(keyCode == 13)
        	  {
               	$("#confirm_send").click();
               	return false;
       	 	  }
			});
			
			$('#name_rider').keypress(function(event)
              {
        	  var keyCode = event.keyCode ? event.keyCode :
        	  event.charCode ? event.charCode :
        	  event.which ? event.which : void 0;
        	  if(keyCode == 13)
        	  {
               	$("#send").click();
               	return false;
       	 	  }
			});
			
			$('#phone').keypress(function(event)
              {
        	  var keyCode = event.keyCode ? event.keyCode :
        	  event.charCode ? event.charCode :
        	  event.which ? event.which : void 0;
        	  if(keyCode == 13)
        	  {
               	$("#send").click();
               	return false;
       	 	  }
			});
              
           	}
		});
		$("#contact").submit(function() { return false; });		
		$("#send").on("click", function(){
			var nameval  = $("#name_rider").val();
			var phoneval    = $("#phone").val();
			var namelen    = nameval.length;
			var phonelen    = phoneval.length;
			
			if(namelen ==0) {
				$("#name_rider").addClass("error");
			}
			else if(namelen > 0){
				$("#name_rider").removeClass("error");
			}
			
			if(phonelen ==0) {
				$("#phone").addClass("error");
			}
			else if(phonelen > 0){
				$("#phone").removeClass("error");
			}
			
			if(namelen >0 && phonelen > 0) {
				$.ajax({
					type: 'POST',
					url: 'sendmessage.php?action=send',
					data: $("#contact").serialize(),
					success: function(data) {
						if(data == "true") {
							$("#contact_params").fadeOut("fast", function(){
								$('#confirm_contact').css("display", ""); 
								$("#code_confirm").focus();
							});
						}
						else
						if (data == "false")
						{
								$("#contact_params").fadeOut("fast", function(){
								$(this).before("<p><strong style='color:red;'>Не удалось отправить сообщение :(</strong></p>");
							});
						}
						
					}
				});
			}
		});
		
		$("#confirm_send").on("click", function(){
			var nameval  = $("#name_rider").val();
			var phoneval    = $("#phone").val();
			var code_confirm    = $("#code_confirm").val();
			var namelen    = nameval.length;
			var phonelen    = phoneval.length;
			var code_confirm    = code_confirm.length;
			
			$('.error_text').remove();
			
			if(code_confirm ==0) {
				$("#code_confirm").addClass("error");
			}
			else if(code_confirm > 0){
				$("#code_confirm").removeClass("error");
			}
			
			
			
			if(namelen >0 && phonelen > 0 && code_confirm > 0) {
				$.ajax({
					type: 'POST',
					url: 'sendmessage.php?action=confirm',
					data: $("#contact").serialize(),
					success: function(data) {
						if(data == "true") 
						{
							$(this).before(data);
							$("#confirm_contact").fadeOut("fast", function(){
								$(this).before("<p class='ok_text'><strong>Вы успешно записаны! :)</strong></p>");
								$('#img-'+$('#id_set').val()).attr("src","pic/Box_Red.png");
								$('#img-'+$('#id_set').val()).css('cursor', 'default'); 
								$('#img-'+$('#id_set').val()).parent().attr("href",null);
								$('#img-'+$('#id_set').val()).parent().attr("class",null);
								setTimeout("$.fancybox.close()", 1000);
							});
						}
						else
						if (data == "false")
						{
								$("#code_confirm").after("<p class='error_text' style='margin:0px;padding-bottom:5px;'><strong style='color:red;font-size:0.8em;'>Введенный код не совпадает, попробуйте еще раз</strong></p>");
								$("#code_confirm").addClass("error");
						}
						
					}
				});
			}
		});
		
	});