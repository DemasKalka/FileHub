$(function() {
    function handleAuth(formID, endpoint, resultID) {
        $(formID).submit(e => {
            e.preventDefault();
            $.post(endpoint, $(e.target).serialize(), response => {
                response.success ? window.location.href = 'dashboard.php' : $(resultID).text(response.message);
            }, 'json').fail(() => $(resultID).text('Network error. Please try again.'));
        });
    }
    handleAuth('#login-form', 'login.php', '#login-response');
    handleAuth('#register-form', 'register.php', '#register-response');
});