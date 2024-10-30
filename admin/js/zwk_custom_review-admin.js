(function( $ ) {
	'use strict';

	


	$(document).ready( function(){
		
		$('.js-example-basic-single').select2();
		$('.zwk_edit_review_product').select2();
		// console.log($('#aioConceptName').find(":selected").text());
		$(document).on('change', '.js-example-basic-single',function(){
		});
		
		$('#zwk_review_image_upload').click(function(e) {
			e.preventDefault();
			
			var custom_uploader = wp.media({
				title: 'Custom Image',
				button: {
					text: 'Upload Image'
				},
				multiple: false  // Set this to true to allow multiple files to be selected
			})
			.on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#zwk_review_image').show();
				$('#zwk_review_image').after('<br/>');
				$('#zwk_review_image').attr('src', attachment.url);
				$('#zwk_review_image_url').val(attachment.url);

			})
			.open();
		});

		$('#zwk_edit_review_image_upload').click(function(e) {
			e.preventDefault();
			
			var custom_uploader = wp.media({
				title: 'Custom Image',
				button: {
					text: 'Upload Image'
				},
				multiple: false  // Set this to true to allow multiple files to be selected
			})
			.on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#zwk_edit_review_image').show();
				$('#zwk_edit_review_image').after('<br/>');
				$('#zwk_edit_review_image').attr('src', attachment.url);
				$('#zwk_edit_review_image_url').val(attachment.url);

			})
			.open();
		});

		$('#stars li').each(function(){
			$(this).addClass('selected');
		})

		$('#stars li').on('mouseover', function(){
			var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
		   
			// Now highlight all the stars that's not after the current hovered star
			$(this).parent().children('li.star').each(function(e){
			  if (e < onStar) {
				$(this).addClass('hover');
			  }
			  else {
				$(this).removeClass('hover');
			  }
			});
			
		  }).on('mouseout', function(){
			$(this).parent().children('li.star').each(function(e){
			  $(this).removeClass('hover');
			});
		});
		  
		  
		  /* 2. Action to perform on click */
		$('#stars li').on('click', function(){
			var onStar = parseInt($(this).data('value'), 10); // The star currently selected
			var stars = $(this).parent().children('li.star');
			
			for (var i = 0; i < stars.length; i++) {
				$(stars[i]).removeClass('selected');
			}
			
			for (i = 0; i < onStar; i++) {
				$(stars[i]).addClass('selected');
			}
			
			// JUST RESPONSE (Not needed)
			var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
			console.log(ratingValue);
		
		});
		
		$(document).on('click', '#zwk_review_submit', function(){
			var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
			var image_url =	$('#zwk_review_image_url').val();
			var author_name = $('#zwk_review_aurthor_name').val();
			var time = $('#zwk_review_date').val();
			var productId = $('#zwk_review_product_id').val();
			var reviewText = $('#zwk_review_text').val();
			console.log(ratingValue,image_url,author_name,time,productId);
			
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: {
					action: 'zwk_save_review',
					ratingValue: ratingValue,
					image_url: image_url,
					author_name:author_name,
					time:time,
					productId:productId,
					reviewText:reviewText
				},
				success: function (output) {                      
					location.reload();
                }
            })
		})

		$('#zwk_edit_review_stars li').each(function(){
			$(this).addClass('selected');
		})

		$('#zwk_edit_review_stars li').on('mouseover', function(){
			var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
		   
			// Now highlight all the stars that's not after the current hovered star
			$(this).parent().children('li.star').each(function(e){
			  if (e < onStar) {
				$(this).addClass('hover');
			  }
			  else {
				$(this).removeClass('hover');
			  }
			});
			
		  }).on('mouseout', function(){
			$(this).parent().children('li.star').each(function(e){
			  $(this).removeClass('hover');
			});
		});
		  
		  
		  /* 2. Action to perform on click */
		$('#zwk_edit_review_stars li').on('click', function(){
			var onStar = parseInt($(this).data('value'), 10); // The star currently selected
			var stars = $(this).parent().children('li.star');
			
			for (var i = 0; i < stars.length; i++) {
				$(stars[i]).removeClass('selected');
			}
			
			for (i = 0; i < onStar; i++) {
				$(stars[i]).addClass('selected');
			}
			
			// JUST RESPONSE (Not needed)
			var ratingValue = parseInt($('#zwk_edit_review_stars li.selected').last().data('value'), 10);
			console.log(ratingValue);
		
		});
			
		$(document).on('click', '#zwk_edit_review_submit', function(){
			var ratingValue = parseInt($('#zwk_edit_review_stars li.selected').last().data('value'), 10);
			var image_url =	$('#zwk_edit_review_image_url').val();
			var author_name = $('#zwk_edit_review_aurthor_name').val();
			var time = $('#zwk_edit_review_date').val();
			var reviewText = $('#zwk_edit_review_text').val();
			var commentId = $('#zwk_edit_review_submit').attr('comment_id');
			var productId = $('#zwk_edit_review_product_id').val();
			console.log(ratingValue,image_url,author_name,time);
			
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: {
					action: 'zwk_save_edited_review',
					productId : productId,
					commentId:commentId,
					ratingValue: ratingValue,
					image_url: image_url,
					author_name:author_name,
					time:time,
					reviewText:reviewText
				},
				success: function (output) {                      
					location.reload();
                }
            })
		})
		  
	})

})( jQuery );
