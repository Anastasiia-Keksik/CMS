let helper;
let layers = [];
let activeImg;
function newImage(image, id){
    fabric.Image.fromURL(image, function (img) {
        img.nameid = id
        img.speed = 1;
        canvas.add(img)
        img.on('modified', function (){
            if (typeof img.angle != 'undefined'){
                img.angle = Math.round(img.angle)
            }
            activeImg = img

            img.top = Math.round(img.top)
            console.log(img)


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
                    layers[it0].opacity = img.opacity
                    console.log(layers)
                }
                it0++
            })

            $('#formWidth').val(img.width * img.scaleX)
            $('#formHeight').val(img.height * img.scaleY)
            $('#formPosX').val(img.left)
            $('#formPosY').val(img.top)
            $('#formRot').val(img.angle)
            $('#formOpacity').val(img.opacity)
        }).on('selected', function () {
            $('#formWidth').val(img.width * img.scaleX)
            $('#formHeight').val(img.height * img.scaleY)
            $('#formPosX').val(img.left)
            $('#formPosY').val(img.top)
            $('#formRot').val(img.angle)
            $('#formOpacity').val(img.opacity)
            console.log(layers)
            layers.forEach(function (layer) {
                if(layer.id === img.nameid){
                    $('#formParallaxSpeed').val(layer.prlxSpd)
                }
            })
            activeImg = img
            })
    })
}

$('#formWidth').on('change', function (){
    activeImg.scaleX = $('#formWidth').val() / activeImg.width
    canvas.renderAll()
})
$('#formHeight').on('change', function (){
    activeImg.scaleY = $('#formHeight').val() / activeImg.height
    canvas.renderAll()
})
$('#formPosX').on('change', function (){
    activeImg.left = parseInt($('#formPosX').val())
    canvas.renderAll()
})
$('#formPosY').on('change', function (){
    activeImg.top = parseInt($('#formPosY').val())
    canvas.renderAll()
})
$('#formRot').on('change', function (){
    activeImg.angle = parseInt($('#formRot').val())
    canvas.renderAll()
})
$('#formOpacity').on('change', function (){
    activeImg.opacity = parseFloat($('#formOpacity').val())
    var it0 = 0;
    layers.forEach(function (elm) {
        if (elm.id === activeImg.nameid) {
            layers[it0].opacity = activeImg.opacity
        }
        it0++
    })
    canvas.renderAll()
})
$('#formParallaxSpeed').on('change', function (){
    var it0 = 0;
    layers.forEach(function (elm) {
        if (elm.id === activeImg.nameid) {
            layers[it0].prlxSpd = $('#formParallaxSpeed').val()
        }
        it0++
    })
})
$('#formSceneHeight').on('change', function (){
    $('#shock-wrapper').css('height', $('#formSceneHeight').val())
})

$(document).ready(function (){
    $('#layer-window').sortable({
        handle: ".handle",
        stop: function ( event, ui){
            let it0 = 0;
            canvas.getObjects().forEach(function (elm) {
                if (elm.nameid == ui.item.attr('id')){
                    canvas.item(it0).moveTo($('#layer-window .wers').length - 1 - ui.item.index())
                    var element = layers[it0];
                    layers.splice(it0, 1);
                    layers.splice($('#layer-window .wers').length - 1 - ui.item.index(), 0, element);
                }
                it0++
            })
            canvas.renderAll();
        }
    })

    $('.image-drag').draggable({
        helper: "clone",
        appendTo: 'body',
        start: function( event, ui ) {
            helper = ui.helper
            $(ui.helper).css('z-index','2')
            $(ui.helper).css('width','240px')
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
            tmp_layer.posx = 0
            tmp_layer.posy = 0
            tmp_layer.rot = 0
            tmp_layer.opacity = 1
            tmp_layer.prlxSpd = 0
            tmp_layer.objid = helper.data('objid')
            tmp_layer.url = helper.data('imagename')
            tmp_layer.thumbUrl = helper[0].src
            tmp_layer.objconid = ''
            layers.push(tmp_layer)
            newImage(tmp_layer.url, tmp_layer.id)

            prependLayers()
        }
    })

    $('#shock-wrapper').resizable({
        resize: function (event, ui){
            canvas.setHeight(ui.size.height);
            canvas.setWidth(ui.size.width);
            ui.size.width = '800';
            canvas.renderAll();
            canvas.calcOffset();

            windowHeight = ui.size.height;

            $('#formSceneHeight').val(ui.size.height)
        }
    })
})

function prependLayers(){
    $("#layer-window").html('')
    let it1 = 1;
    layers.forEach(function (elm) {
        $("#layer-window").prepend('<div id="'+elm.id+'" class="ui-widget-content wers d-flex position-relative" style="padding: 10px">' +
            '<div class="handle" style="position: absolute; top: 0; left: 0; height: 40px; width: 40px; margin-left: 0; margin-right: 8px; background-image: url(\''+elm.thumbUrl+'\'); background-repeat: no-repeat; background-size: cover;"></div>'+
            '<span id="edit_'+elm.id+'"  style="min-width: 60px; color: black; margin-left: 48px" contenteditable="true" data-toggle="tooltip" data-placement="auto" title="Right click to name change">Warstwa '+it1+'</span>' +
            '<span class="position-absolute" style="top: 10px; right: 8px">rv</span>'+
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
