function showError(message = '') {
    if (message != '') {
        $("#alert_block").html('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert">×</button> ' + message + ' </div>');
    }
}

function showMessage(message = '') {
    if (message != '') {
        $("#alert_block").html('<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">×</button> ' + message + ' </div>');
    }
}

function checkAlerts(data) {
    if (data.error) {
        showError(data.error);
    }
    if (data.message) {
        showMessage(data.message);
    }
}