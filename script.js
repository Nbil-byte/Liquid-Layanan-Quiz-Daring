/* scripts.js */

document.addEventListener("DOMContentLoaded", function () {
    var deleteButtons = document.querySelectorAll('.hapus-soal');

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            var idSoal = button.getAttribute("data-idsoal");
            var konfirmasi = confirm("Anda yakin ingin menghapus soal ini?");

            if (konfirmasi) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "hapus_soal.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            alert("Soal berhasil dihapus.");
                            button.parentNode.remove();
                        } else {
                            alert("Gagal menghapus soal. Silakan coba lagi.");
                        }
                    }
                };
                xhr.send("id_soal=" + idSoal);
            }
        });
    });
});
