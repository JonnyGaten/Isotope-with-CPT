<?php // Template name: Vacancies - ISOTOPE
get_header(); ?>

<!--  ------------------------------------------------------------   Scripts   ------------------------------------------------------------  -->

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="//npmcdn.com/isotope-layout@3/dist/isotope.pkgd.js"></script>

<!--  ------------------------------------------------------------  End Scripts  ----------------------------------------------------------  -->






<!--  ------------------------------------------------------------  CSS  ------------------------------------------------------------  -->

<style type="text/css">
	
	/***** Element item style (Width etc) *****/
	.element-item{
		width: 48%;
		float: left;
		padding:20px;
	}
	@media(max-width: 768px){
		.element-item{
			width:100%;
		}
	}
	.element-item a {
    	color: #e10e49;
	}

	/**** Isotope Filtering ****/

	.grid{
		min-height: 50px;
    	margin-top: 26px;
    	border-top: 1px solid #cccccc;
	}

	.isotope-item {
		z-index: 2;
	}
	.isotope-hidden.isotope-item {
		pointer-events: none;
		z-index: 1;
	}


	/**** Isotope CSS3 transitions ****/
	.isotope,
	.isotope .isotope-item {
		-webkit-transition-duration: 0.8s;
		-moz-transition-duration: 0.8s;
		transition-duration: 0.8s;
	}
	.isotope {
		-webkit-transition-property: height, width;
		-moz-transition-property: height, width;
		transition-property: height, width;
	}
	.isotope .isotope-item {
		-webkit-transition-property: -webkit-transform, opacity;
		-moz-transition-property:    -moz-transform, opacity;
		transition-property:         transform, opacity;
	}


	/**** disabling Isotope CSS3 transitions ****/
	.isotope.no-transition,
	.isotope.no-transition .isotope-item,
	.isotope .isotope-item.no-transition {
		-webkit-transition-duration: 0s;
		-moz-transition-duration: 0s;
		transition-duration: 0s;
	}

</style>

<!--  ---------------------------------------------------------- End CSS ----------------------------------------------------------  -->






<!-- ------------------------------------------------------------  JS  ------------------------------------------------------------ -->

<script type="text/javascript">
	
$(function() {

// Set variables for all filters
var qsRegex;
var dropFilter;
var dropFilterDepartment;

// init Isotope
$grid = $('.grid').isotope({

	// the class used to make the item isotope
	itemSelector: '.element-item',

	layoutMode: 'fitRows',

	// Filtering system, this makes all the filters work together in harmony (The search, the country and the department)
	filter: function()
		{
		  	var $this = $(this);
		  	var searchResult 			= 		qsRegex 					? $this.text().match( qsRegex )	 			: true;
		  	var dropResult 				= 		dropFilter 					? $this.is( dropFilter ) 					: true;
		  	var dropResultDepartment 	= 		dropFilterDepartment 		? $this.is( dropFilterDepartment ) 			: true;
		
		  	return searchResult && dropResult && dropResultDepartment;
		}

	});

	// On the country dropdown select, when option is changed, sent this value to dropFilter, which uses dropResult
	$('.filters-select').on( 'change', function() {
	
	  // get filter value from option value
	  dropFilter = this.value;
	
	  // use Isotope if matches value
	  $grid.isotope();

	});


	// On the department dropdown select, when option is changed, sent this value to dropFilterDepartment, which uses dropResultDepartment
	$('.filters-select-department').on( 'change', function() {

	  // get filter value from option value
	  dropFilterDepartment = this.value;

	  // use Isotope if matches value
	  $grid.isotope();

	});



	// use value of search field to filter
	var $quicksearch = $('#quicksearch').keyup( debounce( function() {

	  qsRegex = new RegExp( $quicksearch.val(), 'gi' );

	  // use Isotope if matches value
	  $grid.isotope();

	}) );
  

	// debounce so filtering doesn't happen every millisecond
	function debounce( fn, threshold ) {

	  var timeout;

	  return function debounced() {

	    if ( timeout ) {

	      clearTimeout( timeout );

	    }

	    function delayed() {

	      fn();

	      timeout = null;

	    }

	    setTimeout( delayed, threshold || 100 );

	  };

	}

	});

</script>

<!-- ------------------------------------------------------------  End JS  ------------------------------------------------------------ -->




<!-- ------------------------------------------------------------ HTML/PHP ------------------------------------------------------------ -->


<div class="container">

	<div id="filters">

		<div class="row">
			
			<div class="col-sm-12">
				
				<h2>Filters available</h2>

			</div>

		</div>

		<div class="row">

			<div class="col-sm-4">

				<!-- Gets all the country taxonomy and echos into dropdown -->
				<?php 

    			$terms = get_terms( 'country' );

    			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>

    				<select class="filters-select form-control nopadding vacencies-country filter option-set clearfix">

    					<option value="*">View by country (All)</option>

    					<?php

    					foreach ( $terms as $term ) { ?>

    					<?php $the_slug = $term->slug;

    					$the_slug_class  = strtolower($the_slug);?>

    					<option value=".<?php echo $the_slug_class;?>"><?php echo $term->name;?></option>

    					<?php }?>

    				</select>

    			<?php } ?>

			</div>

			<div class="col-sm-4">

			<!-- Gets all the department taxonomy and echos into dropdown -->
				<?php 

    			$terms = get_terms( 'department' );

    			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>

    				<select class="filters-select-department form-control nopadding vacencies-country filter option-set clearfix">

    					<option value="*">View by department (All)</option>

    					<?php

    					foreach ( $terms as $term ) { ?>

    					<?php $the_slug = $term->slug;

    					$the_slug_class  = strtolower($the_slug);?>

    					<option value=".<?php echo $the_slug_class;?>"><?php echo $term->name;?></option>

    					<?php }?>

    				</select>

    			<?php } ?>

			</div>

			<div class="col-sm-4">

				<input placeholder="Search" type="text" id="quicksearch" class="form-control">

			</div>

		</div>

	</div>

	<div class="grid">

		<div class="row">

			<div class="col-md-12">

    			<?php
    			// Get all posts from 'Vacancies'
    			$query = new WP_Query( array(
    				'post_type' => 'vacancies',
    				'posts_per_page' => '-1'
    				) );
	
    				while ( $query->have_posts() ) : $query->the_post(); ?>
    				
    				<?php
	
    					// Get taxonomy country
    					$taxonomy_names_country = wp_get_post_terms( get_the_ID(), 'country' );

    					// Get taxonomy department
    				 	$taxonomy_names_department = wp_get_post_terms( get_the_ID(), 'department' );
    					

					// For every country, get the slug and name
    				foreach ($taxonomy_names_country as $tax) {

    					// Get the slug, used to generate the class
    					$taxnamecountry .= $tax->slug . ' ';

    					// Get the name, used to display on page
    					$taxnamecountrytitle .= $tax->name . ' ';
	
    				}

					// For every department, get the slug and name
    				foreach ($taxonomy_names_department as $tax) {

    					// Get the slug, used to generate the class
    					$taxnamedepartment .= $tax->slug . ' ';
    					
    					// Get the name, used to display on page
    					$taxnamedepartmenttitle .= $tax->name . ' ';
	
    				} ?>
	
						<div class="element-item <?php echo $taxnamecountry;?> <?php echo $taxnamedepartment;?>">
	
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

							<p class="vacencies-keyinfo"><?php the_field('job_role'); ?> - <?php echo $taxnamecountrytitle;?></p>

							<?php $taxnamecountrytitle = ''; ?>

							<p><?php the_field('job_excerpt'); ?></p>

							<p><a href="<?php the_permalink(); ?>">&gt; <?php the_field('more_details','option'); ?></a></p>

							<div class="sharer-section">

								<?php get_template_part('templates/share-bar'); ?>

							</div>
	
						</div>

					<!-- Reset country and department -->
    				<?php $taxnamecountry = ''; ?>

    				<?php $taxnamedepartment = ''; ?>
	
    			<?php endwhile; ?> 

			</div>

		</div>

	</div>

</div>

	<!-- ------------------------------------------------------------ End HTML/PHP ------------------------------------------------------------ -->


	<?php get_footer(); ?>