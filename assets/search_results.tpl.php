<?php
/**
 * search results template, uses GCS
 */
?>
<section id="content">

<h1><?php echo htmlentities($title); ?></h1>

<p class="error">Notice: The search results are not very reliable yet. To be fixed.</p>

<div id="cse" style="width: 100%;">Loading</div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript"> 
  function parseQueryFromUrl () {
    var queryParamName = "q";
    var search = window.location.search.substr(1);
    var parts = search.split('&');
    for (var i = 0; i < parts.length; i++) {
      var keyvaluepair = parts[i].split('=');
      if (decodeURIComponent(keyvaluepair[0]) == queryParamName) {
        return decodeURIComponent(keyvaluepair[1].replace(/\+/g, ' '));
      }
    }
    return '';
  }
  google.load('search', '1', {language : 'en', style : google.loader.themes.MINIMALIST});
  google.setOnLoadCallback(function() {
    var customSearchControl = new google.search.CustomSearchControl('017496113186101154358:dazncf5kgus');
    customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
    var options = new google.search.DrawOptions();
    options.setAutoComplete(true);
    options.enableSearchResultsOnly(); 
    customSearchControl.draw('cse', options);
    var queryFromUrl = parseQueryFromUrl();
    if (queryFromUrl) {
      customSearchControl.execute(queryFromUrl);
    }
  }, true);
</script>

<style type="text/css">
  .gs-webResult.gs-result a.gs-title:hover,
  .gs-webResult.gs-result a.gs-title:hover b,
  .gs-imageResult a.gs-title:hover,
  .gs-imageResult a.gs-title:hover b {
    color: #095EBA;
	border: none;
  }
  .gs-webResult div.gs-visibleUrl,
  .gs-imageResult div.gs-visibleUrl,
  .gs-webResult div.gs-visibleUrl-short {
    color: #777777;
  }
  .gs-webResult div.gs-visibleUrl-short {
    display: block;
  }
  .gs-webResult div.gs-visibleUrl-long {
    display: none;
  }
  #adBlock {
    font-size: 0.8em;
  }
</style> 

</section><!-- #content -->
