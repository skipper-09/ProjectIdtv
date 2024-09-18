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
            text: "Menghapus data ini bersifat permanen",
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
                        if (res.status === "success") {
                            swal("Deleted!", res.message, {
                                icon: "success",
                            });
                        } else {
                            swal("Error!", res.message, {
                                icon: "error",
                            });
                        }
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
    if (type == "print") {
        $("#showmodal").find(".modal-content").html(`
                  <div class="modal-header">
                <h5 class="modal-title">Print Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex px-3">
                    <button class="btn btn-sm btn-success mr-2" id="print-standart">Standart Printer</button>
                    <button class="btn btn-sm btn-primary" id="print-thermal">Thermal Printer</button>
                </div>
            </div>
            
            `);
        $("#showmodal").modal("show");
    }
});
