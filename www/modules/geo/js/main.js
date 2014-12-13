/**
 * @author: niwi.cz
 * @date: 2.12.2014
 */

$(function () {
    $('#search-form').submit(searchSubmitted);

    // autofocus for search field
    $('#search-string').focus();
});


function searchSubmitted() {

    var targetUrl = "/vypis?andOr=OR&";
    var searchString = encodeURIComponent($("#search-string").val());
    var qs = "";    // query string

    $('#search-form input:checked').each(function () {
        var name = $(this).attr("name");
        qs += "&gsfo-"+ name +"[]=CT&gsfv-"+ name +"[]="+ searchString;
    });

    if (qs.length > 0)
        window.location.href = targetUrl + qs;

    return false;
}

var responseLoaded = function (data) {
    if (isError(data))
        return err('Chyba při odesílání odpovědi.', data);

    // vyřešit odhašování...
    //if (data['data']['result'] == false)
}