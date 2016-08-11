<?php 
	$fullWidth 					= $helper->get('full-width');
	$style							= $helper->get('acm-style');
	$count 							= $helper->getRows('book-page.page-text');
	$float 							= 0;
	
	$blockImg 					= $helper->get('block-bg');
	$blockImgBg  				= 'background-image: url("'.$blockImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';

	$bookCover 					= $helper->get('book-cover');
	$bookCoverBg  			= 'background-image: url("'.$bookCover.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';			

	$bookIntro					= $helper->get('book-intro');

	$doc = JFactory::getDocument();
	$doc->addScript (T3_TEMPLATE_URL.'/acm/book/js/modernizr.custom.js');

	$bookBgColor				= $helper->get('book-bg-color');
	$bookTextColor			= $helper->get('book-text-color');
	$bookLink						= $helper->get('book-link');
	 
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($blockImg): echo 'style="'.$blockImgBg.'"'; endif; ?>>	
	<?php if($module->showtitle || $helper->get('block-intro')): ?>
	<h3 class="section-title ">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
		<?php if($helper->get('block-intro')): ?>
			<p class="container-sm section-intro hidden-xs"><?php echo $helper->get('block-intro'); ?></p>
		<?php endif; ?>	
	</h3>
	<?php endif; ?>	
	<div id="uber-book-<?php echo $module->id; ?>" class="uber-book style-1 <?php if($gray): ?> img-grayscale <?php endif; ?> <?php echo $style; ?> <?php if($fullWidth): ?>full-width <?php endif; ?> <?php if($count > $columns): ?> multi-row <?php endif; ?>">
		<?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
		<ul id="bk-list" class="bk-list clearfix">
			<li>
				<div class="bk-book book-1 bk-bookdefault">
					<div class="bk-front">
						<div class="bk-cover" style="<?php if($bookCover): echo $bookCoverBg; endif; ?> <?php if($bookBgColor): echo 'background-color: '.$bookBgColor.';'; endif; ?> <?php if($bookTextColor): echo 'color: '.$bookTextColor; endif; ?>">
							
						</div>
						<div class="bk-cover-back" style="<?php if($bookBgColor): echo 'background-color: '.$bookBgColor.';'; endif; ?> <?php if($bookTextColor): echo 'color: '.$bookTextColor; endif; ?>"></div>
					</div>
					<div class="bk-page">
						<?php 
		 				for ($i=0; $i<$count; $i++) : 
		 					$pageText = $helper->get('book-page.page-text',$i);
		 				?>
						<div class="bk-content <?php if($i==0) : ?>bk-content-current <?php endif; ?>">
							<p><?php echo $pageText; ?></p>
							<span class="number"><?php echo $i+1; ?></span>
						</div>
						<?php endfor ?>
						<?php if($bookLink) : ?>
						<div class="bk-content">
							<a href="<?php echo $bookLink; ?>" class="btn btn-primary"><?php echo JText::_( 'ACM_BOOK_BUY_NOW' ); ?></a>
							<span class="number"><?php echo JText::_( 'ACM_BOOK_END' ); ?></span>
						</div>
						<?php endif; ?>
					</div>
					<div class="bk-back" style="<?php if($bookBgColor): echo 'background-color: '.$bookBgColor.';'; endif; ?> <?php if($bookTextColor): echo 'color: '.$bookTextColor; endif; ?>">
						<?php if($bookIntro): ?>
							<p><?php echo $bookIntro; ?></p>
						<?php endif; ?>
					</div>
					<div class="bk-right"></div>
					<div class="bk-left" style="<?php if($bookBgColor): echo 'background-color: '.$bookBgColor.';'; endif; ?> <?php if($bookTextColor): echo 'color: '.$bookTextColor; endif; ?>">
						<h2>
							<span><?php if($bookLink) : ?>Anthony Burghiss<?php endif; ?></span>
							<span>A Catwork Orange</span>
						</h2>
					</div>
					<div class="bk-top"></div>
					<div class="bk-bottom"></div>
				</div>
				<div class="bk-info">
					<button class="btn btn-primary bk-bookback"><?php echo JText::_( 'ACM_BOOK_FLIP' ); ?></button>
					<button class="btn btn-primary bk-bookview"><?php echo JText::_( 'ACM_BOOK_VIEW_INSIDE' ); ?></button>
				</div>
			</li>
		</ul>
		<?php if(!$fullWidth): ?></div><?php endif; ?>
	</div>
</div>
<script>
(function($){
	var Books = (function() {

		var $books = $( '#bk-list > li > div.bk-book' ), booksCount = $books.length;
		
		function init() {

			$books.each( function() {
				
				var $book = $( this ),
					$other = $books.not( $book ),
					$parent = $book.parent(),
					$page = $book.children( 'div.bk-page' ),
					$bookview = $parent.find( 'button.bk-bookview' ),
					$content = $page.children( 'div.bk-content' ), current = 0;

				$parent.find( 'button.bk-bookback' ).on( 'click', function() {				
					
					$bookview.removeClass( 'bk-active' );

					if( $book.data( 'flip' ) ) {
						
						$book.data( { opened : false, flip : false } ).removeClass( 'bk-viewback' ).addClass( 'bk-bookdefault' );

					}
					else {
						
						$book.data( { opened : false, flip : true } ).removeClass( 'bk-viewinside bk-bookdefault' ).addClass( 'bk-viewback' );

					}
						
				} );

				$bookview.on( 'click', function() {

					var $this = $( this );			
					
					$other.data( 'opened', false ).removeClass( 'bk-viewinside' ).parent().css( 'z-index', 0 ).find( 'button.bk-bookview' ).removeClass( 'bk-active' );
					if( !$other.hasClass( 'bk-viewback' ) ) {
						$other.addClass( 'bk-bookdefault' );
					}

					if( $book.data( 'opened' ) ) {
						$this.removeClass( 'bk-active' );
						$book.data( { opened : false, flip : false } ).removeClass( 'bk-viewinside' ).addClass( 'bk-bookdefault' );
					}
					else {
						$this.addClass( 'bk-active' );
						$book.data( { opened : true, flip : false } ).removeClass( 'bk-viewback bk-bookdefault' ).addClass( 'bk-viewinside' );
						$parent.css( 'z-index', booksCount );
						current = 0;
						$content.removeClass( 'bk-content-current' ).eq( current ).addClass( 'bk-content-current' );
					}

				} );

				if( $content.length > 1 ) {

					var $navPrev = $( '<span class="bk-page-prev">&lt;</span>' ),
						$navNext = $( '<span class="bk-page-next">&gt;</span>' );
					
					$page.append( $( '<nav></nav>' ).append( $navPrev, $navNext ) );

					$navPrev.on( 'click', function() {
						if( current > 0 ) {
							--current;
							$content.removeClass( 'bk-content-current' ).eq( current ).addClass( 'bk-content-current' );
						}
						return false;
					} );

					$navNext.on( 'click', function() {
						if( current < $content.length - 1 ) {
							++current;
							$content.removeClass( 'bk-content-current' ).eq( current ).addClass( 'bk-content-current' );
						}
						return false;
					} );

				}
				
			} );

		}

		return { init : init };

	})();

	$(document).ready(function() {

		Books.init();

	});
})(jQuery);
</script>