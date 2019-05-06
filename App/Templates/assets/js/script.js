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

function confirm(param) {
    $('#ModalConfirm').find('.modal-body').html('<strong>Tem certeza que deseja excluir o usuário?</strong>');
    if($('#ModalConfirm').find('#btn_yes').length == false){
        $('#ModalConfirm').find('.modal-footer').prepend('<button id="btn_yes" type="button" class="btn btn-primary" onclick="del(' + param + ')">Sim</button>')
    }    
    $('#ModalConfirm').modal('show');
    
}

function del(id){

    $.ajax({
        url:'index.php?class=UsersList&method=Delete',
        type:'GET',
        data:{id:id},
        success:function () {
            $('#ModalConfirm').modal('hide');
            window.location.href = 'index.php?class=UsersList&method=confirm&type=excluído';
        }
    });
}