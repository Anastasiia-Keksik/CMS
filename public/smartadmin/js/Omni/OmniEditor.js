let helper;
$(document).ready(function (){
    // let draggedImage;
    // $('.image-drag').draggable({
    //     helper: "clone",
    //     //connectToSortable: "#sortable",
    //     start: function( event, ui ) {
    //         console.log(ui.helper.currentSrc)
    //         draggedImage = ui.helper.currentSrc;
    //     }
    // })

    $('.image-drag').draggable({
        connectToSortable: "#shock-wave",
        helper: "clone",
        start: function( event, ui ) {
            helper = ui.helper
        },
        drop: function( event, ui ) {
            console.log($(this))
        }
    }).disableSelection()
    $('#shock-wave').sortable({
        opacity: '0.4',
        revert: false,
        activate: function( event, ui ) {
            console.log(ui)
            $(ui.placeholder).width('100%')
            $(ui.helper).width('120px')
        },
        change: function( event, ui ) {
            $(ui.item).width('120')
             $(ui.placeholder).width('100%')
            console.log(ui.placeholder)
        },
        placeholder: '.sortable-placeholder',
        update: function( event, ui ) {
            $(ui.item).width('100%')
            $(ui.item).height('')
        },
        receive: function( event, ui ) {
            $(ui.item).width('100%')
            $(ui.item).height('')

        }
    })
    $('#shock-wrapper').resizable()
})


function zoom(zoom){
    let zoomState = $('#shock-wave')
    if (zoom === "in"){

        if(zoomState.css('width') === '120px'){
            zoomState.css('width', '200px')
        }else if(zoomState.css('width') === '200px'){
            zoomState.css('width', '400px')
        }else if(zoomState.css('width') === '400px'){
            zoomState.css('width', '800px')
        }else if(zoomState.css('width') === '800px'){
            zoomState.css('width', '1600px')
        }else if(zoomState.css('width') === '1600px'){
            zoomState.css('width', '100vw')
        }
    }else {
        if (zoomState.css('width') === '100vw'){
            zoomState.css('width', '1600px')
        }else if(zoomState.css('width') === '1600px'){
            zoomState.css('width', '800px')
        }else if(zoomState.css('width') === '800px'){
            zoomState.css('width', '400px')
        }else if(zoomState.css('width') === '400px'){
            zoomState.css('width', '200px')
        }else if(zoomState.css('width') === '200px'){
            zoomState.css('width', '120px')
        }
    }
}
