<div id="pageBottom">&copy; Dudu 2014</div>
<script src="js/jquery.timers-1.0.0.js"></script>
<script type="text/javascript">




$(document).ready(function(){
   var j = jQuery.noConflict();
	j(document).ready(function()
	{
		j("body").everyTime (1000000,function(i){
			
			j.ajax({
			  url: "#",
			  cache: false,
			  success: function(html){
				j("body").html(html);
				
			  }
			})
		})
	});
});

		


</script>
<!-- overlay ends here -->

</div>

<?php include_once("track.php"); ?> 