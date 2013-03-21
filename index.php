<?php
// Search Parse www.wobblemedia.com
    $DaftAPI = new SoapClient(
        "http://api.daft.ie/v2/wsdl.xml"
        , array('features' => SOAP_SINGLE_ELEMENT_ARRAYS)
    );
 
    $queryString = array(
        'api_key'   =>  "651cc73ee7d51dc8bbd5e0c40536ce66a6fdebe8"
        , 'query'   =>  array()
    );
 
    $response = $DaftAPI->search_sale($queryString);
    $results = $response->results;

?>



<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Super amazing search thingy...</title>
  
  <!-- Included CSS Files (Uncompressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.css">
  -->
  
  <!-- Included CSS Files (Compressed) -->
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
  <link rel="stylesheet" href="stylesheets/app.css">

  <script src="javascripts/modernizr.foundation.js"></script>

  <!-- IE Fix for HTML5 Tags -->
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

</head>
<body>

<div class="row">
	<div class="eight columns">
<?php
	    foreach($results->ads as $ad)
    {
		?><hr><h2 class="headertext"><?php
			printf(
				'<a href="%s">%s</a><br />'
				, $ad->daft_url
				, $ad->full_address
			);
			
		?></h2>	
		<p>
		<a href="<?php echo $ad->daft_url;?>"><img src="<?php echo $ad->small_thumbnail_url; ?>" align="left" class="textbuffer"></a>
		
		<?php echo $ad->description; ?> 
		</p>	
		<p>
			Listed on: <?php echo gmdate("d-m-Y", $ad->listing_date);?> in <a href="#"><?php echo $ad->property_type; ?></a><br>
			Phone: <?php echo $ad->phone1; ?> or Email: <a href="mailto:<?php echo $ad->main_email; ?>"><?php echo $ad->main_email; ?></a>
		</p>
		<?php	
    }
?>
			
	</div>

	
	<div class="four columns">
		<h2>Property Search</h2>
		<hr />
		
		<form method="POST" action="results.php">		
		<input type="text" name="txt" placeholder="eg. 3 bed terrace cabra">
		<input type="submit" class="button">
		</form>
		
	</div>
</div>	
	
</div>


<!-- Included JS Files (Compressed) -->
<script src="javascripts/jquery.js"></script>
<script src="javascripts/foundation.min.js"></script>

<!-- Initialize JS Plugins -->
<script src="javascripts/app.js"></script>
</body>
</html>
