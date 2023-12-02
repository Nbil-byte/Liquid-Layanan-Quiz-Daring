// validasi_kuis.js
function validateForm() {
    // Cek apakah setiap pertanyaan memiliki jawaban yang dipilih
    var questions = document.querySelectorAll('[name^="soal_"]');
    for (var i = 0; i < questions.length; i++) {
        var questionId = questions[i].value;
        var selectedAnswer = document.querySelector('[name="'+ questionId +'"]:checked');
        if (!selectedAnswer) {
            alert('Mohon jawab semua pertanyaan sebelum mengirimkan kuis.');
            return false; // Form tidak akan dikirimkan
        }
    }
    return true; // Form akan dikirimkan jika semua pertanyaan dijawab
}
