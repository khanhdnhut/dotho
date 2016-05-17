/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function openAjaxLink (element) {
    var ajax_link = jQuery(element).attr("ajaxlink");
    var ajax_target = jQuery(element).attr("ajaxtarget");
    if (window.history.replaceState) {
        window.history.replaceState(null, null, ajax_link);
    } else if (window.history && window.history.pushState) {
        window.history.pushState({}, null, ajax_link);
    } else {
        location = ajax_link;
    }
    jQuery.ajax({
        url: ajax_link,
        type: "POST",
        data: {
            ajax: "ajax"
        },
        success: function (data, textStatus, jqXHR)
        {
            jQuery(ajax_target).html(data);
            //data: return data from server
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            //if fails      
        }
    });
}
