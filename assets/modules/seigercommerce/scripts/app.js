document.addEventListener('DOMContentLoaded', function() {
    /*------------------- URL for AJAX requests -------------------*/
    const ajaxRoot = document.getElementsByName('ajaxRoot')[0].content;
    /*------------------- URL for AJAX requests -------------------*/
    /*--------------------- Submitting a form ---------------------*/
    document.querySelector('.scommerce-ajax').addEventListener('submit', function (event) {
        let form = this;
        let method = form.getAttribute('method');
        let submit = form.querySelector('[type="submit"]');

        if (method === null) {
            method = 'post';
        }

        submit.disabled = true;

        fetch(ajaxRoot, {
            method: method,
            cache: "no-store",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body: new FormData(form)
        }).then((response) => {
            return response.json();
        }).then((data) => {
            submit.disabled = false;
            console.info('Form', form.querySelector('[name="ajax"]').value+'.', data.message);
        }).catch(function(error) {
            if (error == 'SyntaxError: Unexpected token < in JSON at position 0') {
                console.error('Request failed SyntaxError: The response must contain a JSON string.');
            } else {
                console.error('Request failed', error, '.');
            }
            submit.disabled = false;
        });
        event.preventDefault();
    });
    /*--------------------- Submitting a form ---------------------*/
});