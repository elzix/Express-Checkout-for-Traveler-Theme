jQuery(document).ready(function(){
    var country = jQuery("#field-st_country").val();
    jQuery('#field-st_country').parent().attr('id', 'select-st_country');
    jQuery("#field-st_country").remove();
    jQuery("#select-st_country").append('<select class="form-control" id="field-st_country" name="st_country"></select>');
    jQuery("#select-st_country").append('<input id="field-st_country_code" name="st_country_code" type="hidden">');
    jQuery("#field-st_country").on("change",function(){
        jQuery("#field-st_country_code").val(jQuery('option:selected',this).data('code'));
    });
    var json = (function() {
        var json = null;
        jQuery.ajax({
            'async': false,
            'global': false,
            'url': "/wp-content/plugins/traveler-mula/inc/country.json",
            'dataType': "json",
            'success': function (data) {
                json = data;
            }
        });
        return json;
    })();
    jQuery.each(json, function(key,value) {
      if(value.cname == country){
      	jQuery("#field-st_country").append('<option value="'+value.cname+'" data-code="'+value.ccode+'" selected="selected">'+value.cname+'</option>');
        jQuery("#field-st_country_code").val(value.ccode);
      }
      else
        jQuery("#field-st_country").append('<option value="'+value.cname+'" data-code="'+value.ccode+'">'+value.cname+'</option>');
    });
});