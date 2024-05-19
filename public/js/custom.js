/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

//sweet alert delete button
$("#dataTable").on("click", ".action", function () {
    let data = $(this).data();
    let id = data.id;
    let type = data.type;
    var route = data.route;
    if (type == "delete") {
        swal({
            title: "Apakah Kamu Yakin?",
            text: "Menghapus data ini bersifat permanet ",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: route,
                    method: "DELETE",
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (res) {
                        //reload table
                        $("#dataTable").DataTable().ajax.reload();
                        // Do something with the result
                        swal("Deleted!", res.message, {
                            icon: "success",
                        });
                    },
                });
            }
        });
    }
    if (type == "show") {
        $.ajax({
            method: "GET",
            url: route,
            success: function (res) {
                $("#showmodal").find(".modal-content").html(res);
                $("#showmodal").modal("show");
            },
        });
    }
});
