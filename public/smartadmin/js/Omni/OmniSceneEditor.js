let helper;
let activeImg;
let activeImgId;
let layers = [];
let images = []
let sumOfHeight = 0;
let previousObjHeight = 0;
let leftPos = [];
let topPos = [];
let rotateDeg = [];
let scaleNbr = [];
let playTime = 0;
let animationLength = parseInt($('#timeline-active-area').css('width'))-1
let maxRightValues = [];
let lastPoint;

$('#timeline').resizable({
    handles: "n",
    maxHeight: 783,
    minHeight: 200
})

function setPlayTimeToBeginning(){
    playTime = 0;
    seconds = playTime % 60
    minutes = Math.floor(playTime / 60)
    if (seconds < 10){
        seconds = '0'.concat(String(seconds))
    }
    if (minutes < 10) {
        minutes = '0'.concat(String(minutes))
    }
    $('#clock').text(minutes + ":" + seconds)
    $('#time-pointer').css('left', '0px')
}

function setPlayTimeToEnd(){
    playTime = animationLength;
    seconds = playTime % 60
    minutes = Math.floor(playTime / 60)
    if (seconds < 10){
        seconds = '0'.concat(String(seconds))
    }
    if (minutes < 10) {
        minutes = '0'.concat(String(minutes))
    }
    $('#clock').text(minutes + ":" + seconds)
    $('#time-pointer').css('left', animationLength)
}

$('.KO').draggable({
    axis: "x",
    containment: $('.JO'),
    drag: function( event, ui ) {
        console.log(ui.position)
    }
})

$('#timeline-area').draggable({
    axis: "x",
    stop: function (event, ui) {
        if (parseInt($('#timeline-area').css('left')) > 0){
            $('#timeline-area').css('left', '0px')
        }
    }
})

let minutes = 0;
let seconds = 0;
$('#time-pointer').draggable({
    axis: "x",
    drag: function (event, ui) {
        if (ui.position.left < 0){
            ui.position.left = 0
        }
        if (ui.position.left > animationLength){
            ui.position.left = animationLength
        }
        playTime = ui.position.left
        seconds = playTime % 60
        minutes = Math.floor(playTime / 60)
        if (seconds < 10){
            seconds = '0'.concat(String(seconds))
        }
        if (minutes < 10) {
            minutes = '0'.concat(String(minutes))
        }
        $('#clock').text(minutes + ":" + seconds)
    }
})

$('#add-to-timeline').sortable()

function findMax(values){
    let max = 0
    console.log(values)
    for (var index in values){
        if (maxRightValues[index] > max){
            max = maxRightValues[index]
        }
    }
    return max
}

let max = 10;
let minutes_all = 0;
let seconds_all = 0;
$('#timeline-active-area').resizable({
    handles: "e",
    minWidth: 101,
    resize: function (event, ui) {
        animationLength = parseInt($('#timeline-active-area').css('width')) - 1
        if (ui.size.width <= max){
            $('#timeline-active-area').css('width', max+1)
            animationLength = max
        }
        seconds = animationLength % 60
        minutes = Math.floor(animationLength / 60)
        if (seconds < 10){
            seconds = '0'.concat(String(seconds))
        }
        if (minutes < 10) {
            minutes = '0'.concat(String(minutes))
        }
        $('#clock-all').text(minutes + ":" + seconds)
        max = findMax(maxRightValues)
        console.log(max)
    },
})

$('#btn-check').on("click", function () {
    console.log('sht')

    var tl = gsap.timeline();


    //tl.to($(activeImg), {x: 100, duration: 1});
})



$('#over-window').on('mousedown', function(event) {
    if (!$(event.target).is('.realImg')) {
            $('.selectable').removeClass('border-blend')
            console.log('unselecting')
        activeImg = ''
        $('#formPosX').val('')
        $('#formPosY').val('')
    }
});

var imgWidth = []
var imgHeight = []
function newImage(image){
    var img = new Image()
    img.onload = function (){
        imgWidth[image.id] = img.naturalWidth
        console.log(img.naturalWidth)
        imgHeight[image.id] = img.naturalHeight
        console.log(img.naturalHeight)
    }
    img.src = image.url
        $('#canvas').append('' +

                '<div id="img-'+image.id+'" class="selectable" style="top: 0px; left: 0px; width: 0px; height: 0px; outline-offset: -2px; position: relative; z-index: '+image.zIndex+'">' +
                    '<img class="realImg" id="realImg-'+image.id+'" src="'+image.url+'" style="left: 0px; top: 0px; width: 100%; height: 100%; mix-blend-mode: unset;">' +// to ma .nameide i .speed
                '</div>'
        )
        console.log(image.id + " zIndex - " + image.zIndex)
        setTimeout(function(){
            $('#img-'+image.id).css('width', imgWidth[image.id])
            //$('#realImg-'+image.id).css('width', imgWidth[image.id])
            $('#img-'+image.id).css('height', imgHeight[image.id])
            sumOfHeight -= previousObjHeight
            previousObjHeight = imgHeight[image.id]
            $('#img-'+image.id).css('top', sumOfHeight)
            rotateDeg[image.id] = 0;
            scaleNbr[activeImgId] = 0;
            leftPos[activeImgId] = 0;
            topPos[activeImgId] = 0;
            //$('#realImg-'+image.id).css('height', imgHeight[image.id])
        }, 100)
            $('#img-'+image.id).draggable({
                create: function (event, ui ){
                },
                start: function( event, ui ) {
                    activeImg = "#img-"+image.id
                    activeImgId = image.id
                    images[activeImgId] = true;
                    $('.selectable').removeClass('border-blend')
                    console.log('reselect')
                    $('#img-'+activeImgId).addClass('border-blend')
                    console.log('selected')
                    console.log('aktywny '+activeImg)
                    $('#formPosX').val(parseInt($(activeImg).css('left')))
                    $('#formPosY').val(parseInt($(activeImg).css('top')))
                    $('.KV').css('background', 'unset')
                    $('#KV-'+activeImgId).css('background', 'rgba(137, 196, 244, 0.8)')
                }
            })
                $('.realImg').each(function (index) {
                    $(this).on('click', function (){
                        if ( $(this).is('.ui-draggable-dragging') ) {
                            return;
                        }
                        $('.selectable').removeClass('border-blend')
                        activeImgId = $(this).attr('id').split("-")[1]
                        activeImg = '#img-'+activeImgId
                        $(activeImg).addClass('border-blend')
                        console.log('selected')
                        console.log('aktywny '+activeImg)
                        $('#formPosX').val(parseInt($(activeImg).css('left')))
                        $('#formPosY').val(parseInt($(activeImg).css('top')))
                        $('.KV').css('background', 'unset')
                        $('#KV-'+activeImgId).css('background', 'rgba(137, 196, 244, 0.8)')
                    })

                })
}

$('#formPosX').on('change', function (){
    leftPos[activeImgId] = parseInt($('#formPosX').val())
    $(activeImg).css('left', )
})
$('#formPosY').on('change', function (){
    topPos[activeImgId] = parseInt($('#formPosY').val())
    $(activeImg).css('top', )
})
$('#formRot').on('change', function (){
    rotateDeg[activeImgId] = parseInt($('#formRot').val())
    $(activeImg).css('transform', 'scale('+scaleNbr[activeImgId]+') rotate('+rotateDeg[activeImgId]+'deg)')
})
$('#formOpacity').on('change', function (){
    $(activeImg).css('opacity', parseFloat($('#formOpacity').val()))
    var it0 = 0;
    layers.forEach(function (elm) {
        if (elm.id === activeImgId) {
            layers[it0].opacity = parseFloat($('#formOpacity').val())
        }
        it0++
    })
})
$('#formParallaxSpeed').on('change', function (){
    var it0 = 0;
    layers.forEach(function (elm) {
        if (elm.id === activeImgId) {
            layers[it0].prlxSpd = $('#formParallaxSpeed').val()
        }
        it0++
    })
})
$('#formSceneHeight').on('change', function (){
    $('#shock-wrapper').css('height', $('#formSceneHeight').val())
})

let startPost;
$(document).ready(function (){
    $('#layer-window').sortable({
        handle: ".handle",
        start: function (event, ui){
            startPost = layers.length - 1 - ui.item.index()
            console.log(startPost)
        },
        stop: function ( event, ui){
            let it0 = 0;
            newPos = layers.length - 1 - ui.item.index()
            console.log('ID check - '+ ui.item.attr('id') + ' of moved obj from: '+ startPost + ' to ' + newPos)
            if(newPos > startPost){
                $(layers).each(function (elm) {
                    if (layers[it0].zIndex <= newPos && layers[it0].id != ui.item.attr('id') && layers[it0].zIndex >= startPost){
                        console.log(layers[it0].id+" zIndex changed from "+parseInt(layers[it0].zIndex) + " to " + (parseInt(layers[it0].zIndex)-1))
                        layers[it0].zIndex = layers[it0].zIndex - 1
                        $('#img-'+layers[it0].id).css('zIndex', layers[it0].zIndex)
                    }

                    if (layers[it0].id == ui.item.attr('id')){
                        $("#img-"+ui.item.attr('id')).css('zIndex', newPos)
                        layers[it0].zIndex = newPos
                    }
                    it0++
                })
                console.log('new pos is bigger')
            }else if(newPos < startPost){
                $(layers).each(function (elm) {
                    console.log(layers[it0].id+" zIndex is "+layers[it0].zIndex)
                    if (layers[it0].zIndex >= newPos && layers[it0].id != ui.item.attr('id') && layers[it0].zIndex <= startPost ){
                        console.log(layers[it0].id+" zIndex changed from "+parseInt(layers[it0].zIndex) + " to " + (parseInt(layers[it0].zIndex)+1))
                        layers[it0].zIndex = layers[it0].zIndex + 1
                        $('#img-'+layers[it0].id).css('zIndex', layers[it0].zIndex)
                    }

                    if (layers[it0].id == ui.item.attr('id')){
                        $("#img-"+ui.item.attr('id')).css('zIndex', newPos)
                        layers[it0].zIndex = newPos
                    }
                    it0++
                })
                console.log('new pos is smaller')
            }
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
            console.log(ui)
            let tmp_layer = [];
            tmp_layer.id = Date.now()
            tmp_layer.name = ''
            tmp_layer.width = helper[0].width
            tmp_layer.height = helper[0].height
            tmp_layer.posx = 0
            tmp_layer.posy = 0
            tmp_layer.rot = 0
            tmp_layer.opacity = 1
            tmp_layer.prlxSpd = 0
            tmp_layer.objid = helper.data('objid')
            tmp_layer.url = helper.data('imagename')
            tmp_layer.thumbUrl = helper[0].src
            tmp_layer.objconid = ''
            tmp_layer.zIndex = layers.length
            layers.push(tmp_layer)
            newImage(tmp_layer)

            prependLayers(tmp_layer)
        }
    })

    $('#shock-wrapper').resizable({
        handles: 's',
        minHeight: 200,
        maxHeight: 1600,
        grid: [ 0, 1 ],
        resize: function (event, ui){
            $('#formSceneHeight').val(Math.floor(ui.size.height))
            $('#paralaksa-lookout-target').css('height', ui.size.height)
        },
        stop: function( event, ui ) {
            $('#shock-wrapper').css('height', Math.floor(ui.size.height))
        }
    })
})

function choseTimelineLayer(layerid){
    activeImg = "#img-"+layerid
    activeImgId = layerid
    $('.selectable').removeClass('border-blend')
    $('#img-'+layerid).addClass('border-blend')
    $('.KV').css('background', 'unset')
    $('#KV-'+layerid).css('background', 'rgba(137, 196, 244, 0.8)')
}

let timelineChevronStatus = [];
let leftPoint = 0;
function prependLayers(layer){
    $("#layer-window").html('')
    let it1 = 1;
    $('#add-to-timeline').append('<tr>\n' +
        '                                    <td align="left" style="vertical-align: top;">\n' +
        '                                        <div class="dragdrop-draggable">\n' +
        '                                            <div id="KV-'+layer.id+'" class="KV pl-2" style="height: 18px" onclick="choseTimelineLayer('+layer.id+')">\n' +
        '                                                <div class="HV h18">\n' +
        '                                                    <i id="timeLineLayerChevron-'+layer.id+'" class="fa fa-chevron-down" style="font-size: 10px"></i>\n' +
        '                                                </div>\n' +
        '                                                <div class="NV dragdrop-handle h18">&nbsp;&nbsp;TweenUI</div>\n' +
        '                                                <div class="OV mx-1 h18" style="opacity: 0.3;">\n' +
        '                                                    <i class="fa fa-unlock-alt"></i>\n' +
        '                                                </div>\n' +
        '                                                <div class="BW mx-1 h18" style="opacity: 0.85;">\n' +
        '                                                    <i class="fa fa-eye"></i>\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div id="timeLineLayer-'+layer.id+'" class="PV" style="height: 36px; box-sizing: unset;">\n' +
        '                                                <div class="AW" style="position: absolute; overflow: hidden;">\n' +
        '<div class="gwt-Label pl-5 small h18" style="width: 162px; cursor: default;">Motion</div>'+
        '<div class="gwt-Label pl-5 small h18" style="width: 162px; cursor: default;">Opacity</div>'+
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                        </div>\n' +
        '                                    </td>\n' +
        '                                </tr>')
    $('#timeline-active-area').append('' +
        '<div id="layerTimeDefinition-'+layer.id+'" class="rounded" style="height: 14px; width: 100px; background: lightblue; opacity: 0.8; position: relative; left: 0; top: 2px">' +
        '</div>' +
        '')
        $('#layerTimeDefinition-'+layer.id).draggable({
            axis: 'x',
        drag: function (event,ui) {

                if (ui.position.left < 0){
                    ui.position.left = 0
                }else if(ui.position.left > parseInt($('#timeline-active-area').css('width'))-parseInt($('#layerTimeDefinition-'+layer.id).css('width'))){
                    ui.position.left = parseInt($('#timeline-active-area').css('width'))-parseInt($('#layerTimeDefinition-'+layer.id).css('width'))
                    $('#layerTimeDefinition-'+layer.id).css('left', ui.position.left)
                }
                console.log(ui.position.left+parseInt($('#layerTimeDefinition-'+layer.id).css('width')))
            maxRightValues[layer.id] = ui.position.left+parseInt($('#layerTimeDefinition-'+layer.id).css('width'))
        }
        })
        $('#layerTimeDefinition-'+layer.id).resizable({
            handles: 'w, e',
            start: function (event, ui) {
                leftPoint = ui.position.left
                lastPoint = ui.position.left + ui.size.width
            },
            resize: function (event, ui) {
                if (ui.position.left < 0) {
                    ui.position.left = 0
                    ui.size.width = lastPos
                }else{
                    lastPos = ui.size.width
                }
                console.log(ui.position.left+ui.size.width)
                maxRightValues[layer.id] = ui.position.left+ui.size.width
            },
            stop: function (event, ui) {
                console.log(lastPoint)
                let it = parseInt($(this).css('width')) + 1;
                while (parseInt($(this).css('left')) + parseInt($(this).css('width')) < lastPoint && leftPoint !== ui.position.left) {
                    $(this).css('width', it)
                    it++
                }
            }
        })
    timelineChevronStatus[layer.id] = true
    $('#timeLineLayerChevron-'+layer.id).on('click', function () {
        if (timelineChevronStatus[$(this).attr('id').split('-')[1]] === true){
            $('#timeLineLayerChevron-'+layer.id).removeClass('fa-chevron-down')
            $('#timeLineLayerChevron-'+layer.id).addClass('fa-chevron-up')
            $('#timeLineLayer-'+layer.id).toggle(200)
            timelineChevronStatus[layer.id] = false
        }else{
            $('#timeLineLayerChevron-'+layer.id).removeClass('fa-chevron-up')
            $('#timeLineLayerChevron-'+layer.id).addClass('fa-chevron-down')
            $('#timeLineLayer-'+layer.id).toggle(200)
            timelineChevronStatus[layer.id] = true
        }
    })
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

        }).on('click', function () {
            activeImg = '#img-'+elm.id
            activeImgId = elm.id
            $('.selectable').removeClass('border-blend')
            $(activeImg).addClass('border-blend')
            $('.KV').css('background', 'unset')
            $('#KV-'+activeImgId).css('background', 'rgba(137, 196, 244, 0.8)')
        })
        if(elm.name !== ""){
            $('#edit_'+elm.id).text(elm.name)
        }
        it1++
    })
}

// function zoom(zoom){
//     let zoomState = $('#shock-wrapper')
//     if (zoom === "in"){
//
//         if(zoomState.css('width') === '120px'){
//             zoomState.css('width', '200px')
//         }else if(zoomState.css('width') === '200px'){
//             zoomState.css('width', '400px')
//         }else if(zoomState.css('width') === '400px'){
//             zoomState.css('width', '800px')
//         }else if(zoomState.css('width') === '800px'){
//             zoomState.css('width', '1600px')
//         }else if(zoomState.css('width') === '1600px'){
//             zoomState.css('width', '100vw')
//         }
//     }else {
//         if (zoomState.css('width') === '100vw'){
//             zoomState.css('width', '1600px')
//         }else if(zoomState.css('width') === '1600px'){
//             zoomState.css('width', '800px')
//         }else if(zoomState.css('width') === '800px'){
//             zoomState.css('width', '400px')
//         }else if(zoomState.css('width') === '400px'){
//             zoomState.css('width', '200px')
//         }else if(zoomState.css('width') === '200px'){
//             zoomState.css('width', '120px')
//         }
//     }
// }

$( function() {
    var handle = $( "#scale-slider-handle" );
    $( "#slider" ).slider({
        max: 4,
        step: 0.01,
        create: function() {
            $(this).slider('value', 1)
            handle.text( ($( this ).slider( "value" )) );
        },
        slide: function( event, ui ) {
            handle.text( ui.value );
            scaleNbr[activeImgId] = ui.value
            console.log(rotateDeg)
            $(activeImg).css('transform', 'scale('+scaleNbr[activeImgId]+') rotate('+rotateDeg[activeImgId]+'deg)')
        }
    });
} );