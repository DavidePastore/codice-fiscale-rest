import $ from 'jquery';

$('.ui.dropdown')
    .dropdown()
;

/**
 * Show or hide different fields based on the type.
 */
$("#type").change(function(){
    var type = $(this).val();
    $(".field").hide();
    $(".field." + type).show();
}).change();


$("#sendRequest").click(function(){
    var url = $("#type option:selected").data("url");
    console.log("url:", url);
    var field = $("#form .field." + $("#type").val());
    var dataToSend = {};
    $("input, select", field).each(function(index, element){
        var $element = $(element);
        var name = $element.attr("name");
        var value = $element.val();
        dataToSend[name] = value;
    });
    
    $.get(url, dataToSend, function(data){
        var response = $("#response");
        var jsonResponse = JSON.stringify(data, null, ' ');
        response.text(jsonResponse);
        hljs.highlightBlock(response.get(0));
    });
});
hljs.initHighlighting();