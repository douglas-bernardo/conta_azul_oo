$(function(){

    $('#menu-toggle').click(function(e){
        e.preventDefault();
        $('#wrapper').toggleClass("menuDisplayed");    
    });
    //redireciona em caixas de mensagem
    $("#message-button").on('click', function() {
        var url = $(this).attr('data-url');
        window.location = url;
    });

})

function confirm(param, url_return) {
    $('#ModalConfirm').find('.modal-body').html('<strong>Tem certeza que deseja excluir o registro?</strong>');
    if($('#ModalConfirm').find('#btn_yes').length == false){
        $('#ModalConfirm').find('.modal-footer').prepend('<button id="btn_yes" type="button" class="btn btn-primary" onclick="del(' + param + ', ' + "'" + url_return + "'" + ')">Sim</button>')
    }    
    $('#ModalConfirm').modal('show');
    
}

function del(id, url_return){

    $.ajax({
        url: url_return + 'Delete',
        type:'GET',
        data:{id:id},
        success:function () {
            $('#ModalConfirm').modal('hide');
            window.location.href = url_return + 'confirm&type=excluído';
        }
    });
}