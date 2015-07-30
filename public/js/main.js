
/* ==========================================================================
 JQUERY EVENT ACTIONS
 ========================================================================== */

$(document).ready(function(){

    // START - Slide Effects for more-info and message bubbles
    $('.more_info_question').click(function(){
        $(this).parent().prev().slideToggle(250);
    });
    $('.more_info_close').click(function(){
        $(this).parent().slideUp(250);
    });
    $('#actionMessage').slideDown(250);
    $('.message_close').click(function(){
        $(this).parent().parent().slideUp(250);
    });

    // START - Actions to take when tab button is clicked
    $('.tabbutton').click(function(){
        $(this).parent().find('li').each(function(){
           $(this).removeClass('active');
        });
        $(this).addClass('active');
    });

    // START - Enable JQueryUI Tabs
    $( "#basketTabs" ).tabs();
});

/* ==========================================================================
 PAGE LOAD FUNCTIONS
 ========================================================================== */

// START - enable Drag and Drop for Role Permissions
$(function() {
    $( "#permissionsAppliedHolder, #permissionsAvailableHolder" ).sortable({
        connectWith: ".connectedSortable"
    }).disableSelection().on( "sortreceive", function( event, ui ) {

        var permissionId = ui.item.attr("name");
        var oldContainerHidden = $('#' + ui.sender.attr("id").replace('Holder', ''));
        var newContainerHidden = $('#' + $(this).attr("id").replace('Holder', ''));

        oldContainerHidden.val(oldContainerHidden.val().replace(':' + permissionId, ''));
        newContainerHidden.val(newContainerHidden.val() + ':' + permissionId);
    });
});
// START - Date picker range defaults at 1 month ago
$(function() {
    var dateFromSelector = $("#datepicker_from");
    var dateToSelector = $("#datepicker_to");
    var dateFrom = (dateFromSelector.val()) ? dateFromSelector.val() : "-1m";
    var dateTo = (dateToSelector.val()) ? dateToSelector.val() : "+0d";

    dateFromSelector.datepicker({ dateFormat: 'yy/mm/dd' });
    dateFromSelector.datepicker( "setDate", dateFrom);
    dateToSelector.datepicker({ dateFormat: 'yy/mm/dd'});
    dateToSelector.datepicker( "setDate", dateTo);
});

/* ==========================================================================
 STANDARD FUNCTIONS
 ========================================================================== */

// START - Clean GET query for table filters
function submitFilter() {
    $('.filter').each(function () {
        if (!($(this).val())) {
            $(this).attr('disabled', true);
        }
    });
}
