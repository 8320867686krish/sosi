function successMsgWithRedirect(message, redirectURL) {
    swal({
        title: "Success",
        text: message,
        type: "success",
        timer: 5000
    }, function () {
        redirectPage(redirectURL);
    });
}

function errorMsgWithRedirect(message, redirectURL) {
    swal({
        title: "Error",
        text: message,
        type: "error",
        timer: 5000
    }, function () {
        redirectPage(redirectURL);
    });
}

function redirectPage(redirectURL) {
    window.location.href = redirectURL; // Replace with your target URL
}

function successMsg(message) {
    swal({
        title: "Success",
        text: message,
        type: "success",
        timer: 5000
    });
}

function errorMsg(message, title = "Error") {
    swal({
        title: title,
        text: message,
        type: "error",
        timer: 5000
    });
}

function confirmDelete(deleteUrl, confirmMsg, successCallback, errorCallback) {
    swal({
        title: "Are you sure?",
        text: confirmMsg,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: deleteUrl,
                method: 'GET',
                success: function (response) {
                    if (response.isStatus) {
                        swal("Deleted!", `${response.message}`, "success");
                        if (successCallback) {
                            successCallback(response);
                        }
                    } else {
                        swal("Unable to Delete!", `${response.message}`, "error");
                        if (errorCallback) {
                            errorCallback(response);
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error deleting record: " + error);
                    if (errorCallback) {
                        errorCallback({ message: error });
                    }
                }
            });
        }
    });
}

function confirmDeleteWithElseIf(deleteUrl, confirmMsg, successCallback, errorCallback) {
    swal({
        title: "Are you sure?",
        text: confirmMsg,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: deleteUrl,
                method: 'GET',
                success: function (response) {
                    if (response.isStatus) {
                        swal("Deleted!", `${response.message}`, "success");
                        if (successCallback) {
                            successCallback(response);
                        }
                    } else {
                        swal("Unable to Delete!", `${response.message}`, "error");
                        if (errorCallback) {
                            errorCallback(response);
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error deleting record: " + error);
                    if (errorCallback) {
                        errorCallback({ message: error });
                    }
                }
            });
        }
    });
}

// function confirmDeleteWithOptions(deleteUrl, options = {}) {
//     const defaults = {
//         confirmMsg: "Are you sure you want to delete this item?",
//         successCallback: function(response) {},
//         errorCallback: function(response) {}
//     };

//     const settings = { ...defaults, ...options };

//     swal({
//         title: "Are you sure?",
//         text: settings.confirmMsg,
//         type: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#DD6B55",
//         confirmButtonText: "Yes, delete it!",
//         cancelButtonText: "No, cancel!",
//         closeOnConfirm: false,
//         closeOnCancel: true
//     }, function(isConfirm) {
//         if (isConfirm) {
//             $.ajax({
//                 url: deleteUrl,
//                 method: 'GET',
//                 success: function(response) {
//                     if (response.status) {
//                         swal("Deleted!", response.message, "success");
//                         settings.successCallback(response);
//                     } else {
//                         swal("Error", response.message, "error");
//                         settings.errorCallback(response);
//                     }
//                 },
//                 error: function(xhr, status, error) {
//                     swal("Error", "An error occurred: " + error, "error");
//                     settings.errorCallback(xhr);
//                 }
//             });
//         } else {
//             swal("Cancelled", "Your item is safe :)", "error");
//         }
//     });
// }

