let helper;
let layers = [];
function newImage(image, id){
    fabric.Image.fromURL(image, function (img) {
        img.nameid = id
        canvas.add(img)
        img.on('modified', function (){
            if (typeof img.angle != 'undefined'){
                img.angle = Math.round(img.angle)
            }

            console.log("Image: ")
            console.log(img)
            console.log("Layers: ")
            console.log(layers)
            let it0 = 0;
            layers.forEach(function (layer) {
                if(layer.id === img.nameid){
                    layers[it0].width = img.width * img.scaleX
                    layers[it0].height = img.height * img.scaleY
                    layers[it0].posx = img.left
                    layers[it0].posy = img.top
                    layers[it0].rot = img.angle
                    console.log(layers)
                }
                it0++
            })
        }).on('selected', function () {
            let it0 = 0;
            layers.forEach(function (layer) {
                if (layer.id === img.nameid){
                    let tmp_layer = layers[it0]
                    layers.splice(it0, 1)
                    layers.push(tmp_layer)
                    prependLayers()
                    console.log(layers)
                }
                it0++
            })
        })
    })
}

$(document).ready(function (){
    $('#layer-window').sortable({
        handle: ".handle"
    }).selectable({
        filter: "div", cancel: ".handle",
        selected: function( event, ui ) {
            console.log(ui)
        }
    })

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
        accept: ".draggable",
        drop: function (event, ui){
            let tmp_layer = [];
            tmp_layer.id = Date.now()
            tmp_layer.name = ''
            tmp_layer.width = ''
            tmp_layer.height = ''
            tmp_layer.posx = ''
            tmp_layer.posy = ''
            tmp_layer.rot = ''
            tmp_layer.opacity = ''
            tmp_layer.url = helper.data('imagename')
            tmp_layer.thumbUrl = helper[0].src
            layers.push(tmp_layer)
            newImage(tmp_layer.url, tmp_layer.id)

            prependLayers()
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

function prependLayers(){
    $("#layer-window").html('')
    let it1 = 1;
    layers.forEach(function (elm) {
        $("#layer-window").prepend('<div id="'+elm.id+'" class="ui-widget-content wers d-flex position-relative" style="padding: 10px">' +
            '<div class="handle" style="position: absolute; top: 0; left: 0; height: 42px; width: 42px; margin-left: 0; margin-right: 8px; background-image: url(\''+elm.thumbUrl+'\'); background-repeat: no-repeat; background-size: cover;"></div>'+
            '<span id="edit_'+elm.id+'"  style="min-width: 60px; color: black; margin-left: 48px" contenteditable="true" data-toggle="tooltip" data-placement="auto" title="Right click to name change">Warstwa '+it1+'</span>' +
            '</div>')

        $('#'+elm.id).bind("keypress", function(e) {
            if($('#'+elm.id).text().length > 64) {
                //document.execCommand("undo");
                e.preventDefault()
            }

            if (e.keyCode === 13){
                e.preventDefault()
            }
            elm.name = $('#edit_'+elm.id).text()
        }).bind('paste', function (event){
            if($('#edit_'+elm.id).text().length > 64) {
                //document.execCommand("undo");
                e.preventDefault()
            }
            event.preventDefault()

            let paste = event.originalEvent.clipboardData.getData('text/plain').replace('/(?:\r\n|\r|\n)/g', ' ')
            $('#edit_'+elm.id).text($('#edit_'+elm.id).text()+paste)

        })
        if(elm.name !== ""){
            $('#edit_'+elm.id).text(elm.name)
        }
        it1++
    })
}

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
