document.addEventListener('DOMContentLoaded', function() {
    // JavaScript para arrastar e soltar
    const dropArea = document.getElementById('drop_area');

    dropArea.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropArea.classList.add('active');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('active');
    });

    dropArea.addEventListener('drop', (event) => {
        event.preventDefault();
        const files = event.dataTransfer.files;
        document.getElementById('image_upload').files = files;
    });
});
