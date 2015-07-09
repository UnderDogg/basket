
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
 JQUERY FUNCTIONS
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