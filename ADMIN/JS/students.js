

    function openModal() {
        document.getElementById('addStudentModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('addStudentModal').style.display = 'none';
    }

    window.onclick = function(event) {
        var modal = document.getElementById('addStudentModal');
        if (event.target === modal) {
            closeModal();
        }
    }

    $(document).ready(function() {
        $('#addStudentForm').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: 'student.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Student added successfully!');
                    closeModal();
                    location.reload();
                },
                error: function() {
                    alert('Error adding student.');
                }
            });
        });
    });
