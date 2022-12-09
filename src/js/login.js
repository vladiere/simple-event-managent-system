$('.btn-login').click(function(){
    if (checkLogin()) {
        doLoginRequest()
    } else {
        alert('Please enter your credentials')
    }
})

$('.btn-reg').click(function(){
    $(location).attr('href', './register.html')
})

checkLogin = () => {
    return ($('#username').val() != '' && $('#password').val() != '') ? true : false
}

doLoginRequest = () => {
    $.ajax({
        type: 'POST',
        url: 'src/php/router.php',
        data: {
            choice: 'login',
            username: $('#username').val(),
            password: $('#password').val()
        },
        success: (data) => {
            if (data === '200') {
                $('#password').val('')
                alert('Login Successfully')
                window.location.href = 'public/'
            } else {
                alert('Account not registered')
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(throwError);}
    })
}