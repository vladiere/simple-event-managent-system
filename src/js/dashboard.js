function searchFunction() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search");
    filter = input.value.toUpperCase();
    table = document.getElementById("mytable");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
         txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

const getDatePickerTitle = elem => {
    // From the label or the aria-label
    const label = elem.nextElementSibling;
    let titleText = '';
    if (label && label.tagName === 'LABEL') {
      titleText = label.textContent;
    } else {
      titleText = elem.getAttribute('aria-label') || '';
    }
    return titleText;
  }
  
  const elems = document.querySelectorAll('.datepicker_input');
  for (const elem of elems) {
    const datepicker = new Datepicker(elem, {
      'format': 'yyyy/mm/dd', // UK format
      title: getDatePickerTitle(elem)
    });
  }

$(document).ready(() => {
    displayAccDetails()
    displayEvents()

    $(document).on('click', '#signout', ()=>{
        logout()
    })

    $(document).on('click', '#saveset', ()=>{
        if (checkInputs()) {
            eventRequests()
        } else {
            alert('Please enter your following event details')
        }
    })

    $(document).on('click', '#close', ()=>{
        $('#eventTitle').val('')
        $('#eventStart').val('')
        $('#eventEnd').val('')
    })

    $(document).on('click', '.btn-close', ()=>{
        $('#eventTitle').val('')
        $('#eventStart').val('')
        $('#eventEnd').val('')
    })

    $(document).on('click', '.trash3', function () {
        var trashID = $(this).attr("id")
        if (trashID != '') {
            deleteEvent(trashID)
        } else {
            alert('Event not found')
        }
    })

    $(document).on('click', '#changepass', function () {
        $('.settings').attr('id', 'updatepass')
    })

    $(document).on('click', '#updatepass', function () {
        if ($('#settings').val() != '') {
            changePassword()
        } else {
            alert('Please enter a new password')
        }
    })

    $(document).on('click', '#changeuser', function () {
        $('.settings').attr('id', 'updateuser')
    })

    $(document).on('click', '#updateuser', function () {
        if ($('#settings').val() != '') {
            changeUsername()
        } else {
            alert('Please enter a username password')
        }
    })

    $(document).on('click', '#changename', function () {
        $('.settings').attr('id', 'updatename')
    })

    $(document).on('click', '#updatename', function () {
        console.log($('#settings').val());
        if ($('#settings').val() != '') {
            changeFullname()
        } else {
            alert('Please enter a fullname password')
        }
    })

})

changeUsername = () => {
    $.ajax({
        type: 'POST',
        url: '../src/php/router.php',
        data: {
            choice: 'changeusername',
            newuser: $('#settings').val()
        },
        success: (data) => {
            console.log(data);
            if (data === '200') {
                $('#settings').val('')
                alert('Username changed successfully')
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError)}
    })
}

changeFullname = () => {
    $.ajax({
        type: 'POST',
        url: '../src/php/router.php',
        data: {
            choice: 'changefullname',
            newname: $('#settings').val()
        },
        success: (data) => {
            console.log(data);
            if (data === '200') {
                $('#settings').val('')
                alert('Fullname changed successfully')
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError)}
    })
}

changePassword = () => {
    $.ajax({
        type: 'POST',
        url: '../src/php/router.php',
        data: {
            choice: 'changepassword',
            newpass: $('#settings').val()
        },
        success: (data) => {
            console.log(data);
            if (data === '200') {
                $('#settings').val('')
                alert('Password changed successfully')
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError)}
    })
}

deleteEvent = (eventID) => {
    $.ajax({
        type: 'POST',
        url: '../src/php/router.php',
        data: {
            choice: 'deleteevent',
            eventID: eventID
        },
        success: (data) => {
            if (data === '200') {
                alert('Event deleted successfully')
                $('#eventtable').load(location.href + ' #mytable')
                displayEvents()
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError);}
    })
}

eventRequests = () => {
    $.ajax({
        type: 'POST',
        url: '../src/php/router.php',
        data: {
            choice: 'addevent',
            eventname: $('#eventTitle').val(),
            eventstart: $('#eventStart').val(),
            eventend: $('#eventEnd').val()
        },
        success: (data) => {
            if (data === '200') {
                $('#eventTitle').val('')
                $('#eventStart').val('')
                $('#eventEnd').val('')
                alert('Event Added')
                $('#eventtable').load(location.href + ' #mytable')

                displayEvents()
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError)}
    })
}

displayEvents = () => {
    $.ajax({
        type: 'POST',
        url: '../src/php/router.php',
        data: {
            choice: 'displayevents'
        },
        success: (data) => {
            var json = JSON.parse(data)
            var str = '<table class="table table-striped table-responsive text-center" id="mytable">'+
                        '<thead>'+
                            '<tr>'+
                            '<th>#</th>'+
                            '<th>Event name</th>'+
                            '<th>Date Start</th>'+
                            '<th>Date End</th>'+
                            '<th>Action</th>'+
                            '</tr>'+
                            '</thead>'

            var ctr = 1
            json.forEach(element => {
                str += '<tr>'+
                        '<td>'+ctr+'</td>'+
                        '<td>'+element.event_name+'</td>'+
                        '<td>'+element.event_start+'</td>'+
                        '<td>'+element.event_end+'</td>'+
                        '<td><a role="button" class="trash3" id="'+element.id+'"><img src="../public/images/trash3.svg"></a></td>'+
                        '</tr>'
                ctr++
            })
            str += '</table>'

            $('#eventtable').append(str)
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError)}
    })
}

displayAccDetails = () => {
    $.ajax({
        type: 'POST',
        url: '../src/php/router.php',
        data: {
            choice: 'displayAccDetails'
        },
        success: (data) => {
            if (checkDataJson(data)) {
                var json = JSON.parse(data)
                var str = ''
                var accname = ''
                json.forEach(element => {
                    accname = element.fullname
                })
                $('#accountname').text(accname)

            } else {
                window.location.href = '../'
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError)}
    })
}

logout = () => {
    $.ajax({
        type: 'POST',
        url: '../src/php/router.php',
        data: {
            choice: 'logout',
        },
        success: (data) => {
            if (data === '200') {
                window.location.href = '../'
            }
        },
        error: (xhr, ajaxOptions, thrownError) => {console.log(thrownError);}
    })
}

checkDataJson = (data) => {
    try {
        JSON.parse(data)
    } catch (ex) {
        return false
    }
    return true
}

checkInputs = () => {
    return ($('#eventTitle').val() != '' && $('#eventStart').val() != '' && $('#eventEnd').val() != '') ? true : false
}