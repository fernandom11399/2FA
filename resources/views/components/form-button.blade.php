<div class="d-flex justify-content-center" style="margin-top: 0.5rem;">
    <button id="submitButton" type="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">
        <span id="buttonText">{{ $text }}</span> <!-- Aquí se coloca el texto -->
        <div id="spinner" class="spinner-border" role="status" style="display: none;">
            <span class="visually-hidden">Loading...</span>
        </div> 
    </button>

    <script>
        //It disables the submit button, hides the text, and shows a spinner when the user clicks the button.
        document.addEventListener('DOMContentLoaded', function () {
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const spinner = document.getElementById('spinner');

            submitButton.addEventListener('click', function () {
                submitButton.disabled = true;
                buttonText.style.display = 'none';
                spinner.style.display = 'inline-block';
                const form = submitButton.closest('form');
                form.submit();
            });
        });
    </script>
</div>
