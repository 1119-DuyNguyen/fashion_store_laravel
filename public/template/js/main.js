function logResult(result) {
    console.log(result);
}

function logError(error) {
    console.log("Looks like there was a problem: \n", error);
}

function validateResponse(response) {
    if (!response.ok) {
        throw Error(response.statusText);
    }
    return response;
}

function readResponseAsJSON(response) {
    return response.json();
}
function removeRow(idSelector, id, url) {
    if (confirm("Xóa mà không khôi phục. Bạn có chắc ?")) {
        let options = {
            method: "DELETE",
            body: JSON.stringify({ id: id }),
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
            },
        };
        return fetch(url, options)
            .then(validateResponse) // 2
            .then(readResponseAsJSON) // 3
            .then((result) => {
                if (result["error"] === false) {
                    alert("Xóa thành công");
                    var row = document.getElementById(idSelector);
                    if (row) {
                        row.remove();
                    }
                } else {
                    alert("Xóa thất bại");
                }
            }) // 4
            .catch(logError);
    }
}
// LOAD MORE PRODUCT
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function loadMore() {
    var page = $("#page").val();
    console.error(Number.parseInt(page) + 1);

    $.ajax({
        type: "POST",
        dataType: "JSON",
        data: {},
        url: "/services/load-product",
        success: function (result) {
            if (result.html !== "") {
                $("#loadProduct").append(result.html);
                console.log(Number.parseInt(page) + 1);
                $("#page").val(Number.toString(Number.parseInt(page) + 1));
            } else {
                alert("Đã load xong Sản Phẩm");
                $("#button-loadMore").css("display", "none");
            }
        },
    });
}
