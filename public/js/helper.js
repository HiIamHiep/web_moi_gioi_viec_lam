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

function renderPagination(links) {
    links.forEach(function (each) {
        $("#pagination").append($('<li>').attr('class', `page-item ${each.active ? 'active' : ''}`)
            .append(`<a class="page-link">
                ${each.label}
            </a>`)
        )
    })
}
