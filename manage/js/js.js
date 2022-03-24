$(document).ready(function() {
    		$(".modalbox").fancybox({
            
             'afterClose': function() {
             		$("#contact_params").css("display","");
					$("#name_rider").val('');
					$("#phone").val('');
					$('.ok_text').remove();
					$('.error_text').remove();
					$("#name_rider").removeClass("error");
					$("#phone").removeClass("error");
					
             },

			'beforeShow': function() {
             	 var id = $(this.element).attr('rel');
             	 var act = $(this.element).attr('rel_act');
              	 $("#id_set").val(id);
              	 var zag_text = '';
              	 if (act == 'remove')
              	 {
              	 	zag_text = 'Удаление записи на ';
              	 	zag_text += $(this.element).parent().parent().attr('day_str') + ' в ' + $(this.element).parent().parent().attr('rel') + '. ';
              	 	zag_text += '<h2 style="font-size:0.6em;">Записан: ' + $(this.element).attr('rel_rider') + '</h2>';
              	 	$("#contact_params").css("display","none");
              	 	$("#remove_params").css("display","");
              	 }
              	 else
              	 if (act == 'add')
              	 {
              	 	zag_text = 'Запись на лебедку на ';
              	 	zag_text += $(this.element).parent().parent().attr('day_str') + ' в ' + $(this.element).parent().parent().attr('rel');
              	 	$("#contact_params").css("display","");
              	 	$("#remove_params").css("display","none");
              	 }
              	 
              	 $("#title_zap").html(zag_text);
             },        
                     
             'afterShow': function() {
             	 $("#name_rider").focus();
             },        
                       
             'afterLoad': function() {
              $("#phone").mask("+7 (999) 999-9999");
              
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
					url: 'managetable.php?action=confirm',
					data: $("#contact").serialize(),
					success: function(data) {
						if(data == "true") 
						{
							$("#contact_params").fadeOut("fast", function(){
								$(this).before("<p class='ok_text'><strong>Запись создана.</strong></p>");
								$('#img-'+$('#id_set').val()).attr("src","pic/Box_Red.png");
								var title = $('#name_rider').val()+', '+$('#phone').val();
								$('#img-'+$('#id_set').val()).attr("title",title);
								$('#img-'+$('#id_set').val()).parent().attr("rel_act",'remove');
								$('#img-'+$('#id_set').val()).parent().attr("rel_rider",title);
								setTimeout("$.fancybox.close()", 1000);
							});
						}
						else
						if (data == "false")
						{
								$(this).after("<p class='error_text' style='margin:0px;padding-bottom:5px;'><strong style='color:red;font-size:0.8em;'>Ошибка записи! :(</strong></p>");
						}
						
					}
				});
			}
		});
		
		$("#remove").on("click", function(){

				$.ajax({
					type: 'POST',
					url: 'managetable.php?action=remove',
					data: $("#contact").serialize(),
					success: function(data) {
						if(data == "true") 
						{
							$("#remove_params").fadeOut("fast", function(){
								$(this).before("<p class='ok_text'><strong>Запись удалена.</strong></p>");
								$('#img-'+$('#id_set').val()).attr("src","pic/Box_Green.png");
								$('#img-'+$('#id_set').val()).attr("title",'');
								$('#img-'+$('#id_set').val()).parent().attr("rel_act",'add');
								$('#img-'+$('#id_set').val()).parent().attr("rel_rider",'');
								setTimeout("$.fancybox.close()", 1000);
							});
						}
						else
						if (data == "false")
						{
								$(this).after("<p class='error_text' style='margin:0px;padding-bottom:5px;'><strong style='color:red;font-size:0.8em;'>Ошибка удаления! :(</strong></p>");
						}
						
					}
				});
			
		});
		
	});