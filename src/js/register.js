$('.btn-login').click(function(){
    $(location).attr('href', './')
})

$('.btn-reg').click(function(){
    if (checkRegister()) {
        doRequest()
    } else {
        alert('Please input the following credentials')
    }
})

doRequest = () => {
    $.ajax({
        type: 'POST',
        url: 'src/php/router.php',
        data: {
            choice: 'register',
            fullname: $('#fullname').val(),
            username: $('#username').val(),
            password: $('#password').val()
        },
        success: (data) => {
            if (data === '200') {
                $('#fullname').val('')
                $('#username').val('')
                $('#password').val('')
                alert('Registration successful')
                $(location).attr('href', './')
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError)}
    })
}

checkRegister = () => {
    return ($('#fullname').val() != '' && $('#username').val() != '' && $('#password').val() != '') ? true : false
}