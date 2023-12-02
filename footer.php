<script>
window.addEventListener("beforeunload", function () {
    // Kirim permintaan ke server atau lakukan tindakan sesuai kebutuhan
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "logout.php", false);  // Ganti dengan URL atau tindakan yang sesuai
    xhr.send();
});
</script>
