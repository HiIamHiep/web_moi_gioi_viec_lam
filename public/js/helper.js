function convertDateToDateTime(value) {
    let d = new Date(value);
    let day = d.getDate();
    let month = d.getMonth() + 1;
    let year = d.getFullYear();
    let hour = d.getHours();
    let minutes = d.getMinutes();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    return day + "/" + month + "/" + year + " " + hour + ":" + minutes;
}

function getDateFormat(value) {
    let d = new Date(value),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;
    let date = new Date();
    date.toLocaleDateString();

    return [day, month, year].join('-');
}

function renderPagination(links, request = '') {
    links.forEach(function (each) {
        $("#pagination").append($('<li>').attr('class', `page-item ${each.active ? 'active' : ''}`)
            .append(`<a href="${each.url + request}" class="page-link">${each.label}</a>`)
        )
    })
}

function renderPostsData(each, table) {
    let id = each.id;
    let district = each.district ? each.district + ' - ' : ''
    let location =  district + each.city;
    let remotable = '';
    if(each.remotable == 1) {
        remotable = 'Office only';
    } else if (each.remotable == 2) {
        remotable = 'Remote only';
    } else {
        remotable = 'Hybird';
    }
    let is_parttime = each.can_parttime ? 'X' : '';
    console.log(is_parttime);
    let range_salary = (each.min_salary && each.max_salary) ? each.min_salary + ' - ' + each.max_salary : '';
    let range_date = (each.start_date && each.end_date) ? convertDateToDateTime(each.start_date) + ' - ' + convertDateToDateTime(each.end_date) : '';
    let is_pinned = each.pinned ? 'X' : '';
    let created_at = convertDateToDateTime(each.created_at);

    table.append($('<tr>')
        .append($('<td>').append(id))
        .append($('<td>').append(each.job_title))
        .append($('<td>').append(location))
        .append($('<td>').append($('<i>').attr({
            'class': 'badge text-success'
        }).text(remotable)))
        .append($('<td>').append($('<i>').attr({
            'class': 'badge text-success'
        }).text(is_parttime)))
        .append($('<td>').append(range_salary))
        .append($('<td>').append(range_date))
        .append($('<td>').append(each.status))
        .append($('<td>').append($('<i>').attr({
            'class': 'badge text-success'
        }).text(is_pinned)))
        .append($('<td>').append(created_at))
        .append($('<td>').attr('class', 'table-action').append($('<a>').attr({
            'href': 'posts/'+ id,
            'class': 'action-icon'
        }).append($('<i>').attr('class', 'dripicons-preview')))
            .append($('<a>').attr({
            'href': 'posts/'+ id + '/edit' ,
            'class': 'action-icon'
        }).append($('<i>').attr('class', 'mdi mdi-pencil')))
            .append(createDeleteFormIcon(id, 'posts')))
    );
}

function renderUsersData(each, table) {
        let id = each.id;
        let avatar = each.avatar;
        let name = each.name;
        let gender = each.gender ? 'Male' : 'Female';
        let email = each.email;
        let phoneNumber = each.phone;
        let roleName = getRolesWithValue(each.role);
        let city = each.city;
        let company = each.company ?? '';
        let Showroute = '{{ route("admin.users.show", ["user" => "id"]) }} ';

        table.append($('<tr>')
            .append($('<td>').append($('<a>').attr('href', Showroute.replace('id', id)).text(id)))
            .append($('<td>').append($('<img>').attr('src', avatar).attr('height', '100')))
            .append($('<td>').append(name + ' - ' + gender).append('<br>').append($('<a>').attr("href", "mailto:"+ email).text(email)).append('<br>').append($('<a>').attr("href", "tel:" + phoneNumber).text(phoneNumber)))
            .append($('<td>').text(roleName))
            .append($('<td>').text(city))
            .append($('<td>').text(company.name))
            .append($('<td>').append(createDeleteForm(id, 'users')))
        )
}

function createDeleteForm(userId, table) {
    let deleteRoute = '{{ route("admin.' + table + '.destroy", ["user" => "id"]) }} ';

    let routeName = deleteRoute.replace("id", userId).replace('table', table)

    let deleteForm = $('<form>').attr({
        'method': 'POST',
        'action': routeName
    });

    // Them csrf vao form
    deleteForm.append($('<input>').attr({
        'type': 'hidden',
        'name': '_token',
        'value': '{{ csrf_token() }}',
    }));

    //Them input chua method delete
    deleteForm.append($('<input>').attr({
        'type': 'hidden',
        'name': '_method',
        'value': 'DELETE'
    }));

    deleteForm.append($('<button>').attr({
        'class': 'btn btn-danger',
        'type': 'submit'
    }).text('Delete'));

    return deleteForm;
}

function createDeleteFormIcon(userId, table) {
    let routeName = table + '/' + userId;

    let deleteForm = $('<form>').attr({
        'method': 'POST',
        'action': routeName,
        'class': 'table-action',
        'id': 'delete-post',
    });

    // Them csrf vao form
    deleteForm.append($('<input>').attr({
        'type': 'hidden',
        'name': '_token',
        'value': '{{ csrf_token() }}',
    }));

    //Them input chua method delete
    deleteForm.append($('<input>').attr({
        'type': 'hidden',
        'name': '_method',
        'value': 'DELETE'
    }));

    deleteForm.append($('<button>').attr({
        'class': 'action-icon btn btn-error',
        'type': 'submit'
    }).append($('<i>').attr('class', 'mdi mdi-delete')));

    return deleteForm;
}

function notifySuccess(message = '') {
    $.toast({
        heading: 'Success',
        text: message,
        showHideTransition: 'slide',
        position: 'top-right',
        icon: 'success'
    })
}

function notifyError(message = '') {
    $.toast({
        heading: 'Error',
        text: message,
        showHideTransition: 'slide',
        position: 'top-right',
        icon: 'error'
    })
}

function getRolesWithValue(val) {
    let objRoles = {
        'SUPER_ADMIN': 0,
        'ADMIN': 1,
        'APPLICANT': 2,
        'HR': 3
    };

    const array = Object.entries(objRoles);

    let roleName = '';

    array.forEach(function ([key, value]) {
        if (value === val)
        {
            let str = key.toLowerCase().replace('_', ' ');
            roleName = str.charAt(0).toUpperCase() + str.slice(1);
        }
    })

    return roleName;

}

