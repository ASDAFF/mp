<style type="text/css">
	.ui-widget-header{
		border:none;
		background: none;
		color:#f15824;
		font:700 30px arial;
	}
	
	.ui-widget-overlay{
		background:url(/src/images/bg-form.png);
		opacity:1;
	}
	
	.spinner{
		width:30px;
		outline: none;
	}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		// Корзина
		$('.cart').dialog({autoOpen: false, modal: true, width: 900, });
		$('.cart-success').dialog({autoOpen: false, modal: true, width: 500});
		function updateCart(res){
			$('.cart-items-handler').html('');
			var cartItem = _.template(
				'<div class="citem">'+
				'	<div class="citem-block2">'+		
				'		<div class="td2"><a href="/butik/<%= code %>/"><img src="<%= picture %>" alt=""></a></div>'+
				'		<div class="td3">'+
				'			<h5><%= name %></h5>'+
				'			<%= description %>'+
				'		</div>'+
				'		<div class="td4">'+
				'			<input type="text" value="<%= quantity %>" class="spinner" rel="<%= id %>" />'+
				'		</div>'+
				'		<div class="td5"><%= sum %></div>'+
				'		<div class="td1"><a class="cart-item-delete" href="#" rel="<%= id %>"><img src="/src/images/fl-14.jpg" alt=""></a></div>'+
				'	</div>'+		
				'</div>'
			);
			if(res.items.length > 0) for(i=0; i<res.items.length; i++) $('.cart-items-handler').append(cartItem(res.items[i]));			
			$('.iprice').html(res.sumFormat);		
			$('.spinner').spinner({
				min: 1,
				stop: function(){$.ajax({url: '/cart/?update_basket_item='+$(this).attr('rel')+'&basket_item_quantity='+$(this).val(), success: function(res){ updateCart(res);} });}
			});				
			$('.cart').dialog('open');
		}
		
		$('.buy-link').click(function(e){
			e.preventDefault();
			$.ajax({url: $(this).attr('href'), success: function(res){ updateCart(res);} });
			return false;
		});
		
		$('.show-cart').click(function(e){
			e.preventDefault();
			$.ajax({url: '/cart/', success: function(res){ updateCart(res);} });
			return false;
		});
		
		$('.cart-item-delete').live('click', function(e){
			e.preventDefault();
			$.ajax({url: '/cart/?delete_from_basket='+$(this).attr('rel'), success: function(res){ updateCart(res); } });
			return false;
		});		
		
		$('.return-cart').click(function(e){
			e.preventDefault();
			$('.cart').dialog('close');
			$('.cart-success').dialog('close');
			return false;
		});

		$('.order-basket').click(function(e) {
			e.preventDefault();
			$('.cart').dialog('close');
			$.ajax({
				url: '/cart/order.php?order_basket=Y', 
				success: function(res){ 
					debugger;
					console.log(res);
					$('.cart-success').dialog('open');
				} 
			});
			return false;
		});
		
		function setOffer(target){
			// $('.buy-link').attr('href', target.val());
			$('.buy-sku').attr('href', target.val());
		}
		
		setOffer($('.offers:eq(0)'));
		
		$('.offers').change(function(){
			setOffer($(this));
		});
		
		
		
	});
</script>

<div class="cart" title="Корзина" style="display: none;">
	<div class="cart-block">
		<div class="cart-items-handler"></div>
		<div class="itogo">
			<div class="iall">Всего</div>			
			<div class="iprice"></div>
		</div>
		
		<div class="more">
			<a href="#" class="return-cart">Продолжить покупки</a> <a class="order-basket" href="javascript:;">Оформить</a>
		</div>
		
		<div style="clear:both;"></div>
	</div>
</div>

<div class="cart-success" title="Заказ оформлен, спасибо." style="display: none;">
		<p>В течение нескольких минут мы свяжемся с вами для уточнения адреса, способа и времени доставки.</p>
		
		<div class="more" style="padding-left: 110px;">
			<a href="#" class="return-cart">Вернуться на сайт</a> 
		</div>
		
		<div style="clear:both;"></div>
	</div>
</div>
