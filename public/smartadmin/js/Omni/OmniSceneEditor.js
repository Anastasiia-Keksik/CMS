let helper;
let layers = [];
function newImage(image, id){
    fabric.Image.fromURL(image, function (img) {
        img.nameid = id
        canvas.add(img)
        img.on('modified', function (){
            console.log(img)
        })
    })
}

$(document).ready(function (){
    $('.image-drag').draggable({
        helper: "clone",
        appendTo: 'body',
        start: function( event, ui ) {
            helper = ui.helper
            $(ui.helper).css('z-index','2')
            $(ui.helper).css('width','120px')
        }
    }).disableSelection()
    $('#shock-wave').droppable({
        drop: function (event, ui){
            helper.dateid = Date.now()
            layers.push(helper)
            console.log(helper.data('imagename'))


            newImage(helper.data('imagename'), helper.dateid)

            $("#layer-window").html('')
            let it1 = 1;
            layers.forEach(function (elm) {
                $("#layer-window").prepend('<li id="'+elm.dateid+'" contenteditable="true">Warstwa '+it1+'</li>')

                $('#'+elm.data('usertoobjconid')).bind("keypress", function(e) {
                    if($('#'+elm.data('usertoobjconid')).text().length > 64) {
                        //document.execCommand("undo");
                        e.preventDefault()
                    }

                    if (e.keyCode === 13){
                        e.preventDefault()
                    }
                        elm[0].name = $('#'+elm.data('usertoobjconid')).text()
                }).bind('paste', function (event){
                        if($('#'+elm.data('usertoobjconid')).text().length > 64) {
                            //document.execCommand("undo");
                            e.preventDefault()
                        }
                        event.preventDefault()

                    let paste = event.originalEvent.clipboardData.getData('text/plain').replace('/(?:\r\n|\r|\n)/g', ' ')
                    $('#'+elm.data('usertoobjconid')).text($('#'+elm.data('usertoobjconid')).text()+paste)

                })
                if(elm[0].name !== ""){
                    $('#'+elm.data('usertoobjconid')).text(elm[0].name)
                }
                it1++
            })
        }
    })

    // $('#layers').sortable({
    //     opacity: '0.4',
    //     revert: false,
    //     activate: function( event, ui ) {
    //         console.log(ui)
    //         $(ui.placeholder).width('100%')
    //         $(ui.helper).width('120px')
    //     },
    //     change: function( event, ui ) {
    //         $(ui.item).width('120')
    //          $(ui.placeholder).width('100%')
    //         console.log(ui.placeholder)
    //     },
    //     placeholder: '.sortable-placeholder',
    //     update: function( event, ui ) {
    //         $(ui.item).width('100%')
    //         $(ui.item).height('')
    //     },
    //     receive: function( event, ui ) {
    //         $(ui.item).width('100%')
    //         $(ui.item).height('')
    //
    //     }
    // })
    $('#shock-wrapper').resizable({
        resize: function (event, ui){
            canvas.setHeight(ui.size.height);
            canvas.setWidth(ui.size.width);
            canvas.renderAll();
            canvas.calcOffset();
        }
    })
})


function zoom(zoom){
    let zoomState = $('#shock-wrapper')
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
